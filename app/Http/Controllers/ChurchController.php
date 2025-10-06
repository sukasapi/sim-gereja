<?php

namespace App\Http\Controllers;

use App\Models\Church;
use Illuminate\Http\Request;

class ChurchController extends Controller
{
    /**
     * Display a listing of active churches
     */
    public function index()
    {
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
}
