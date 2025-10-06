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
        $filePath = $data['csv_file'];
        $filename = basename($filePath);
        
        // Check if file exists using Storage
        if (!\Illuminate\Support\Facades\Storage::disk('local')->exists($filePath)) {
            throw new \Exception('File CSV tidak ditemukan: ' . $filePath);
        }
        
        $fileSize = \Illuminate\Support\Facades\Storage::disk('local')->size($filePath);

        // Parse CSV to get total rows
        $csvContent = \Illuminate\Support\Facades\Storage::disk('local')->get($filePath);
        $csvData = array_map('str_getcsv', explode("\n", $csvContent));
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
        $filePath = $import->file_path;

        // Check if file exists using Storage
        if (!\Illuminate\Support\Facades\Storage::disk('local')->exists($filePath)) {
            $import->update([
                'status' => 'failed',
                'error_rows' => 1,
                'errors' => ['File CSV tidak ditemukan: ' . $filePath],
                'completed_at' => now(),
            ]);
            
            \Filament\Notifications\Notification::make()
                ->title('Import CSV Gagal')
                ->body('File CSV tidak ditemukan: ' . $filePath)
                ->danger()
                ->send();
            return;
        }

        try {
            // Update status to processing
            $import->update(['status' => 'processing']);

            $csvContent = \Illuminate\Support\Facades\Storage::disk('local')->get($filePath);
            $csvData = array_map('str_getcsv', explode("\n", $csvContent));
            $header = array_shift($csvData);
            
            // Validate header
            $expectedColumns = [
                'nama', 'tanggal_lahir', 'jenis_kelamin', 'alamat', 'telepon', 'email',
                'wilayah', 'ayah', 'ibu', 'tanggal_gabung', 'tanggal_baptis', 'tanggal_sidi',
                'pendidikan', 'pekerjaan', 'catatan_pelayanan', 'catatan_umum'
            ];
            
            if (count($header) !== count($expectedColumns)) {
                throw new \Exception('Format CSV tidak sesuai. Diharapkan ' . count($expectedColumns) . ' kolom, ditemukan ' . count($header) . ' kolom.');
            }
            
            // Check if all required columns exist
            $missingColumns = array_diff($expectedColumns, $header);
            if (!empty($missingColumns)) {
                throw new \Exception('Kolom yang hilang: ' . implode(', ', $missingColumns));
            }

            $imported = 0;
            $skipped = 0;
            $errors = [];

            foreach ($csvData as $index => $row) {
                try {
                    // Skip empty rows
                    if (empty(array_filter($row))) {
                        continue;
                    }
                    
                    // Validate column count
                    if (count($header) !== count($row)) {
                        $errors[] = "Baris " . ($index + 2) . ": Jumlah kolom tidak sesuai. Header: " . count($header) . ", Data: " . count($row);
                        continue;
                    }
                    
                    $rowData = array_combine($header, $row);
                    
                    // Validate required fields
                    if (empty($rowData['nama'])) {
                        $errors[] = "Baris " . ($index + 2) . ": Nama tidak boleh kosong";
                        continue;
                    }
                    
                    if (empty($rowData['jenis_kelamin']) || !in_array($rowData['jenis_kelamin'], ['L', 'P'])) {
                        $errors[] = "Baris " . ($index + 2) . ": Jenis kelamin harus L atau P";
                        continue;
                    }

                    // Skip if duplikat
                    if ($import->skip_duplicates ?? true) {
                        $birthDate = null;
                        if (!empty($rowData['tanggal_lahir'])) {
                            $birthDate = \Carbon\Carbon::createFromFormat('d-m-Y', $rowData['tanggal_lahir']);
                        }
                        
                        $existing = Member::where('church_id', $import->church_id)
                            ->where('name', $rowData['nama'])
                            ->where('birth_date', $birthDate)
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

                    // Parse tanggal dengan validasi
                    $birthDate = null;
                    if (!empty($rowData['tanggal_lahir'])) {
                        try {
                            $birthDate = \Carbon\Carbon::createFromFormat('d-m-Y', $rowData['tanggal_lahir']);
                        } catch (\Exception $e) {
                            $errors[] = "Baris " . ($index + 2) . ": Format tanggal lahir salah. Gunakan dd-mm-yyyy";
                            continue;
                        }
                    }
                    
                    $joinDate = null;
                    if (!empty($rowData['tanggal_gabung'])) {
                        try {
                            $joinDate = \Carbon\Carbon::createFromFormat('d-m-Y', $rowData['tanggal_gabung']);
                        } catch (\Exception $e) {
                            $errors[] = "Baris " . ($index + 2) . ": Format tanggal gabung salah. Gunakan dd-mm-yyyy";
                            continue;
                        }
                    }
                    
                    $baptismDate = null;
                    if (!empty($rowData['tanggal_baptis'])) {
                        try {
                            $baptismDate = \Carbon\Carbon::createFromFormat('d-m-Y', $rowData['tanggal_baptis']);
                        } catch (\Exception $e) {
                            $errors[] = "Baris " . ($index + 2) . ": Format tanggal baptis salah. Gunakan dd-mm-yyyy";
                            continue;
                        }
                    }
                    
                    $sidiDate = null;
                    if (!empty($rowData['tanggal_sidi'])) {
                        try {
                            $sidiDate = \Carbon\Carbon::createFromFormat('d-m-Y', $rowData['tanggal_sidi']);
                        } catch (\Exception $e) {
                            $errors[] = "Baris " . ($index + 2) . ": Format tanggal sidi salah. Gunakan dd-mm-yyyy";
                            continue;
                        }
                    }

                    // Buat member baru
                    Member::create([
                        'church_id' => $import->church_id,
                        'name' => $rowData['nama'],
                        'birth_date' => $birthDate,
                        'gender' => $rowData['jenis_kelamin'] === 'L' ? 'L' : 'P',
                        'address' => $rowData['alamat'] ?? null,
                        'phone' => $rowData['telepon'] ?? null,
                        'email' => $rowData['email'] ?? null,
                        'join_date' => $joinDate,
                        'baptism_date' => $baptismDate,
                        'sidi_date' => $sidiDate,
                        'education' => $rowData['pendidikan'] ?? null,
                        'occupation' => $rowData['pekerjaan'] ?? null,
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
