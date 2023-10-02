<?php

namespace App\Http\Middleware;

use Closure;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\Route;

class AccountMiddleware
{
    public function handle(Request $request, Closure $next)
    {
        $type = $request->route('type');
        if (!in_array($type, ['phone', 'card', 'email'])) {
            throw new \InvalidArgumentException('Wrong account parameters');
        }

        return $next($request);
    }
}
