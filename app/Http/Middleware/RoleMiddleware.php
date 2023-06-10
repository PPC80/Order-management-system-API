<?php

namespace App\Http\Middleware;

use Closure;
use Illuminate\Http\Request;
use Symfony\Component\HttpFoundation\Response;
use Illuminate\Support\Facades\Auth;

class RoleMiddleware
{
    /**
     * Handle an incoming request.
     *
     * @param  \Closure(\Illuminate\Http\Request): (\Symfony\Component\HttpFoundation\Response)  $next
     */
    public function handle(Request $request, Closure $next, $role): Response
    {
        //Por alguna razon $role era string
        $idRole = (int) Auth::user()->idRole;
        $rolex = (int) $role;

        //Si el usuario no esta logeado o su rol no es el permitido lanza un error
        if (!Auth::check() || $idRole !== $rolex) {
            return response()->json(['message' => 'Unauthorized Role'], 401);
        }

        return $next($request);
    }
}
