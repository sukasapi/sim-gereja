<?php

namespace App\Http\Controllers;

use App\Models\User;
use App\Models\Church;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\Auth;
use Illuminate\Support\Facades\Hash;

class AdminController extends Controller
{
    public function __construct()
    {
        $this->middleware('auth');
    }

    /**
     * Reset password user lain (hanya superadmin)
     */
    public function resetPassword(Request $request, User $user)
    {
        // Hanya superadmin yang dapat reset password
        if (!Auth::user()->isSuperAdmin()) {
            abort(403, 'Unauthorized action.');
        }

        $request->validate([
            'password' => 'required|string|min:8|confirmed',
        ]);

        $user->update([
            'password' => Hash::make($request->password),
        ]);

        return response()->json([
            'success' => true,
            'message' => 'Password berhasil direset!'
        ]);
    }

    /**
     * Edit data gereja (hanya admin gereja)
     */
    public function editChurch()
    {
        $user = Auth::user();
        
        // Hanya admin gereja yang dapat edit data gereja
        if (!$user->isAdminGereja()) {
            abort(403, 'Unauthorized action.');
        }

        $church = $user->church;
        return view('admin.edit-church', compact('church'));
    }

    /**
     * Update data gereja
     */
    public function updateChurch(Request $request)
    {
        $user = Auth::user();
        
        // Hanya admin gereja yang dapat edit data gereja
        if (!$user->isAdminGereja()) {
            abort(403, 'Unauthorized action.');
        }

        $church = $user->church;

        $request->validate([
            'name' => 'required|string|max:255',
            'address' => 'required|string|max:500',
            'city' => 'required|string|max:100',
            'province' => 'required|string|max:100',
            'postal_code' => 'required|string|max:10',
            'phone' => 'required|string|max:20',
            'email' => 'required|email|max:255',
            'website' => 'nullable|url|max:255',
            'logo' => 'nullable|image|mimes:jpeg,png,jpg,gif|max:2048',
        ]);

        $churchData = $request->only([
            'name', 'address', 'city', 'province', 'postal_code', 
            'phone', 'email', 'website'
        ]);

        // Handle logo upload
        if ($request->hasFile('logo')) {
            // Delete old logo
            if ($church->logo) {
                \Storage::delete('public/' . $church->logo);
            }
            
            $logo = $request->file('logo');
            $logoName = time() . '_' . $logo->getClientOriginalName();
            $logo->storeAs('public/churches', $logoName);
            $churchData['logo'] = 'churches/' . $logoName;
        }

        $church->update($churchData);

        return redirect()->route('admin.edit-church')
                        ->with('success', 'Data gereja berhasil diperbarui!');
    }

    /**
     * List semua user (hanya superadmin)
     */
    public function users()
    {
        if (!Auth::user()->isSuperAdmin()) {
            abort(403, 'Unauthorized action.');
        }

        $users = User::with('church')
                    ->orderBy('created_at', 'desc')
                    ->paginate(15);

        return view('admin.users', compact('users'));
    }

    /**
     * Show form reset password
     */
    public function showResetPasswordForm(User $user)
    {
        if (!Auth::user()->isSuperAdmin()) {
            abort(403, 'Unauthorized action.');
        }

        return view('admin.reset-password', compact('user'));
    }
}
