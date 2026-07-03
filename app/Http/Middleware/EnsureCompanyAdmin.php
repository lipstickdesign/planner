<?php

namespace App\Http\Middleware;

use Closure;
use Illuminate\Http\Request;
use Symfony\Component\HttpFoundation\Response;

/**
 * Kun superadmin eller admin i det aktive selskapet får endre data.
 */
class EnsureCompanyAdmin
{
    public function handle(Request $request, Closure $next): Response
    {
        $user = $request->user();
        if (! $user || ! $user->isCompanyAdmin()) {
            return response()->json(['error' => 'Du har ikke rettigheter til dette.'], 403);
        }

        return $next($request);
    }
}
