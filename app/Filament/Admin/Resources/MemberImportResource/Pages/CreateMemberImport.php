<?php

namespace App\Filament\Admin\Resources\MemberImportResource\Pages;

use App\Filament\Admin\Resources\MemberImportResource;
use App\Models\MemberImport;
use App\Models\Member;
use App\Models\Region;
use Filament\Actions;
use Filament\Resources\Pages\CreateRecord;
use Filament\Notifications\Notification;
use Illuminate\Support\Facades\Storage;

class CreateMemberImport extends CreateRecord
{
    protected static string $resource = MemberImportResource::class;

    protected function mutateFormDataBeforeCreate(array $data): array
    {
        $user = auth()->user();
        
        // Validate church access for non-superadmin
        if (!$user->isSuperAdmin() && $data['church_id'] != $user->church_id) {
            throw new \Exception('Anda tidak memiliki akses untuk mengimpor data ke gereja ini.');
        }

        // Get file info
        $filePath = storage_path('app/' . $data['csv_file']);
        $filename = basename($filePath);
        $fileSize = filesize($filePath);

        // Parse CSV to get total rows
        $csvData = array_map('str_getcsv', file($filePath));
        $totalRows = count($csvData) - 1; // Exclude header

        // Create import record
        $data['filename'] = $filename;
        $data['file_path'] = $data['csv_file'];
        $data['file_size'] = $fileSize;
        $data['total_rows'] = $totalRows;
        $data['imported_by'] = $user->id;
        $data['status'] = 'pending';
        $data['started_at'] = now();

        return $data;
    }

    protected function afterCreate(): void
    {
        // Process the import
        $this->processImport();
    }

    protected function processImport(): void
    {
        $import = $this->record;
        $filePath = storage_path('app/' . $import->file_path);

        try {
            // Update status to processing
            $import->update(['status' => 'processing']);

            $csvData = array_map('str_getcsv', file($filePath));
            $header = array_shift($csvData);

            $imported = 0;
            $skipped = 0;
            $errors = [];

            foreach ($csvData as $index => $row) {
                try {
                    $rowData = array_combine($header, $row);

                    // Skip if duplikat
                    if ($import->skip_duplicates ?? true) {
                        $existing = Member::where('church_id', $import->church_id)
                            ->where('name', $rowData['nama'])
                            ->where('birth_date', $rowData['tanggal_lahir'] ? \Carbon\Carbon::createFromFormat('d/m/Y', $rowData['tanggal_lahir']) : null)
                            ->first();

                        if ($existing) {
                            $skipped++;
                            continue;
                        }
                    }

                    // Cari wilayah
                    $region = null;
                    if (!empty($rowData['wilayah'])) {
                        $region = Region::where('church_id', $import->church_id)
                            ->where('name', 'like', '%' . $rowData['wilayah'] . '%')
                            ->first();
                    }

                    // Cari ayah
                    $father = null;
                    if (!empty($rowData['ayah'])) {
                        $father = Member::where('church_id', $import->church_id)
                            ->where('name', 'like', '%' . $rowData['ayah'] . '%')
                            ->where('gender', 'L')
                            ->first();
                    }

                    // Cari ibu
                    $mother = null;
                    if (!empty($rowData['ibu'])) {
                        $mother = Member::where('church_id', $import->church_id)
                            ->where('name', 'like', '%' . $rowData['ibu'] . '%')
                            ->where('gender', 'P')
                            ->first();
                    }

                    // Buat member baru
                    Member::create([
                        'church_id' => $import->church_id,
                        'name' => $rowData['nama'],
                        'birth_date' => $rowData['tanggal_lahir'] ? \Carbon\Carbon::createFromFormat('d/m/Y', $rowData['tanggal_lahir']) : null,
                        'gender' => $rowData['jenis_kelamin'] === 'L' ? 'L' : 'P',
                        'address' => $rowData['alamat'] ?? null,
                        'phone' => $rowData['telepon'] ?? null,
                        'email' => $rowData['email'] ?? null,
                        'join_date' => $rowData['tanggal_gabung'] ? \Carbon\Carbon::createFromFormat('d/m/Y', $rowData['tanggal_gabung']) : null,
                        'is_baptized' => $rowData['status_baptis'] === 'Ya' || $rowData['status_baptis'] === '1',
                        'is_sidi' => $rowData['status_sidi'] === 'Ya' || $rowData['status_sidi'] === '1',
                        'region_id' => $region?->id,
                        'father_id' => $father?->id,
                        'mother_id' => $mother?->id,
                        'is_active' => true,
                    ]);

                    $imported++;

                } catch (\Exception $e) {
                    $errors[] = "Baris " . ($index + 2) . ": " . $e->getMessage();
                }
            }

            // Update import record
            $import->update([
                'status' => 'completed',
                'imported_rows' => $imported,
                'skipped_rows' => $skipped,
                'error_rows' => count($errors),
                'errors' => $errors,
                'completed_at' => now(),
            ]);

            // Delete file after import
            Storage::delete($import->file_path);

            // Show success notification
            $message = "Import selesai! Berhasil: {$imported}, Dilewati: {$skipped}";
            if (!empty($errors)) {
                $message .= ", Error: " . count($errors);
            }

            Notification::make()
                ->title('Import CSV Berhasil')
                ->body($message)
                ->success()
                ->send();

        } catch (\Exception $e) {
            // Update import record with error
            $import->update([
                'status' => 'failed',
                'error_rows' => 1,
                'errors' => [$e->getMessage()],
                'completed_at' => now(),
            ]);

            Notification::make()
                ->title('Import CSV Gagal')
                ->body('Terjadi kesalahan: ' . $e->getMessage())
                ->danger()
                ->send();
        }
    }
}
