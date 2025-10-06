<?php

namespace App\Http\Responses;

use Filament\Http\Responses\Auth\LoginResponse as BaseLoginResponse;
use Illuminate\Http\RedirectResponse;
use Illuminate\Support\Facades\Redirect;

class FilamentLoginResponse extends BaseLoginResponse
{
    public function toResponse($request): RedirectResponse
    {
        return redirect()->intended(filament()->getHomeUrl());
    }
} 