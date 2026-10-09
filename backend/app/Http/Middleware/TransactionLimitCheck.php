<?php

namespace App\Http\Middleware;

use Closure;
use Illuminate\Http\Request;
use Symfony\Component\HttpFoundation\Response;

class TransactionLimitCheck
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

        $amount = (float) ($request->input('amount', 0));
        $dailyLimit = $user->transactionLimitForToday();

        if ($amount > $dailyLimit) {
            return response()->json([
                'message' => 'El monto excede el límite diario permitido.',
            ], 403);
        }

        return $next($request);
    }
}
