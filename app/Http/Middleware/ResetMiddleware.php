<?php
namespace App\Http\Middleware;

use Closure;
use App\User;
use Firebase\JWT\JWT;

class ResetMiddleware
{
    public function handle($request, Closure $next)
    {
        $token = $request->input('token');
        if ($token !== null) {
            $results = JWT::decode($token, env('JWT_SECRET'), ['HS256']);
            $user = User::find($results->sub);
            if ($user === null) {
                return response()->json([
                    'error' => "User does not exist"
                ], 400);
            } elseif ($results->ide !== 'mail') {
                return response()->json([
                    'error' => "UnAuthorized"
                ], 401);
            }
            return $next($request);
        } else {
            return response()->json([
                'error' => 'Need a token to authenticate'
            ], 401);
        }
    }
}
