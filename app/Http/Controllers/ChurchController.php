<?php

namespace App\Http\Controllers;

use App\Models\Church;
use Illuminate\Http\Request;

class ChurchController extends Controller
{
    /**
     * Display a listing of active churches or default church homepage
     */
    public function index()
    {
        // Cek apakah ada gereja default
        $defaultChurch = Church::active()->default()->first();
        
        if ($defaultChurch) {
            // Jika ada gereja default, tampilkan halaman company profile gereja tersebut
            return $this->show($defaultChurch);
        }
        
        // Jika tidak ada gereja default, tampilkan daftar gereja
        $churches = Church::active()
            ->orderBy('name')
            ->get();

        return view('churches.index', compact('churches'));
    }

    /**
     * Display the specified church
     */
    public function show(Church $church)
    {
        if (!$church->is_active) {
            abort(404);
        }

        return view('churches.show', compact('church'));
    }

    /**
     * Display listing of all churches (for manual access)
     */
    public function list()
    {
        $churches = Church::active()
            ->orderBy('name')
            ->get();

        return view('churches.index', compact('churches'));
    }
}
