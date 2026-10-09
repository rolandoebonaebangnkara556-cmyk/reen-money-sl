<?php

namespace App\Http\Middleware;

use Closure;
use Illuminate\Http\Request;
use Symfony\Component\HttpFoundation\Response;

class DepartmentAccess
{
    public function handle(Request $request, Closure $next): Response
    {
        $user = $request->user();

        if (! $user) {
            return response()->json(['message' => 'Unauthenticated.'], 401);
        }

        if ($user->isAdmin()) {
            return $next($request);
        }

        $departmentId = $request->route('department_id') ?? $request->query('department_id');

        if ($departmentId && (int) $departmentId !== (int) $user->department_id) {
            return response()->json([
                'message' => 'No tienes acceso a este departamento.',
            ], 403);
        }

        return $next($request);
    }
}
