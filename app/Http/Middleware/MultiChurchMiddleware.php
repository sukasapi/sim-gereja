<?php

namespace App\Http\Middleware;

use App\Models\Church;
use Closure;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\Auth;
use Symfony\Component\HttpFoundation\Response;

class MultiChurchMiddleware
{
    /**
     * Handle an incoming request.
     *
     * @param  \Closure(\Illuminate\Http\Request): (\Symfony\Component\HttpFoundation\Response)  $next
     */
    public function handle(Request $request, Closure $next): Response
    {
        $user = Auth::user();
        
        if ($user) {
            // Jika user adalah superadmin, tidak perlu filter
            if ($user->isSuperAdmin()) {
                return $next($request);
            }
            
            // Jika user terikat ke gereja tertentu, set church context
            if ($user->church_id) {
                $church = Church::find($user->church_id);
                if ($church) {
                    // Set church context untuk digunakan di seluruh aplikasi
                    app()->instance('current_church', $church);
                    $request->attributes->set('current_church', $church);
                }
            }
        }
        
        return $next($request);
    }
}
