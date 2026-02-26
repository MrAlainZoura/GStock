<?php

namespace App\Http\Middleware;

use Closure;
use Exception;
use Tymon\JWTAuth\Facades\JWTAuth;
use Tymon\JWTAuth\Exceptions\TokenExpiredException;
use Tymon\JWTAuth\Exceptions\TokenInvalidException;

class JwtMiddleware
{
    public function handle($request, Closure $next)
    {
        try {
            // Vérifie et authentifie l'utilisateur via le token
            $user = JWTAuth::parseToken()->authenticate();
        } catch (Exception $e) {
            if ($e instanceof TokenInvalidException) {
                return response()->json(["success"=>false, 'error' => 'Token invalide'], 401);
            } else if ($e instanceof TokenExpiredException) {
                return response()->json(["success"=>false, 'error' => 'Token expiré'], 401);
            } else {
                return response()->json(["success"=>false, 'error' => 'Token absent'], 401);
            }
        }

        return $next($request);
    }
}