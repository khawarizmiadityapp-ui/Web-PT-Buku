<?php

namespace App\Http\Middleware;

use Closure;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\Auth;
use Symfony\Component\HttpFoundation\Response;

class RoleMiddleware
{
    /**
     * Handle an incoming request.
     *
     * @param  \Closure(\Illuminate\Http\Request): (\Symfony\Component\HttpFoundation\Response)  $next
     * @param  string  ...$roles
     */
    public function handle(Request $request, Closure $next, ...$roles): Response
    {
        if (!Auth::check()) {
            return redirect()->route('login');
        }

        $user = Auth::user();

        // If no specific roles required, continue
        if (empty($roles)) {
            return $next($request);
        }

        // Support comma-separated roles if passed as a single string (e.g. 'role:System Admin,Manager')
        $allowedRoles = [];
        foreach ($roles as $role) {
            $split = explode(',', $role);
            foreach ($split as $r) {
                $trimmed = trim($r);
                if ($trimmed !== '') {
                    $allowedRoles[] = $trimmed;
                }
            }
        }

        // Check if user has one of the allowed roles
        if (in_array($user->role, $allowedRoles, true)) {
            return $next($request);
        }

        // System Admin always has full access
        if ($user->role === 'System Admin' || $user->role === 'Admin') {
            return $next($request);
        }

        // If request expects JSON / AJAX, return 403 JSON
        if ($request->expectsJson() || $request->is('api/*')) {
            return response()->json([
                'success' => false,
                'message' => 'Akses ditolak: Anda tidak memiliki izin untuk mengakses sumber daya ini.'
            ], 403);
        }

        // Abort 403 with user friendly message
        abort(403, 'Akses Ditolak: Role (' . ($user->role ?? 'User') . ') tidak memiliki izin untuk mengakses halaman ini.');
    }
}
