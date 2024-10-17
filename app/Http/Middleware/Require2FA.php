<?php

namespace App\Http\Middleware;

use Closure;
use Illuminate\Http\Request;
use Symfony\Component\HttpFoundation\Response;
use App\Services\BankService;

class Require2FA
{
    /**
     * Handle an incoming request.
     *
     * @param  \Closure(\Illuminate\Http\Request): (\Symfony\Component\HttpFoundation\Response)  $next
     */
    public function handle(Request $request, Closure $next): Response
    {
        $user = request()->user();

        if ($user && $user->two_factor_enabled === 'enabled') {
            if (!$request->session()->has('2fa_verified')) {
                return redirect()->route('2fa.verify');
            }
        }
        return $next($request);
    }
}
