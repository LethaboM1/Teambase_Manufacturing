<?php

namespace App\Http\Middleware;

use Closure;
use Illuminate\Http\Request;

class ProductsMiddleware
{
    /**
     * Handle an incoming request.
     *
     * @param  \Illuminate\Http\Request  $request
     * @param  \Closure(\Illuminate\Http\Request): (\Illuminate\Http\Response|\Illuminate\Http\RedirectResponse)  $next
     * @return \Illuminate\Http\Response|\Illuminate\Http\RedirectResponse
     */
    public function handle(Request $request, Closure $next)
    {
        if (auth()->check()) {
            if (auth()->user()->role == 'manager' || auth()->user()->role == 'recipe' || auth()->user()->role == 'job admin' ||auth()->user()->role == 'system') {
                return $next($request);
            }
        }
        return redirect('/');
    }
}
