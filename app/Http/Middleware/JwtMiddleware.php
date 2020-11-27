<?php

namespace App\Http\Middleware;

use Closure;
use JWTAuth;
use Exception;
use Tymon\JWTAuth\Http\Middleware\BaseMiddleware;

class JwtMiddleware
{
    /**
     * Handle an incoming request.
     *
     * @param  \Illuminate\Http\Request  $request
     * @param  \Closure  $next
     * @return mixed
     */
    public function handle($request, Closure $next)
    {   
        try {
            $user = JWTAuth::parseToken()->authenticate();
        } catch (Exception $e) {
                if ($e instanceof \Tymon\JWTAuth\Exceptions\TokenInvalidException){
                    return response()->json([
                        'result' => null,
                        'statusCode' => 40101,
                        'message' => trans('message.tokenInvalidOrExpire')
                    ],401);
                }else if ($e instanceof \Tymon\JWTAuth\Exceptions\TokenExpiredException){
                    return response()->json([
                        'result' => null,
                        'statusCode' => 40102,
                        'message' => trans('message.tokenInvalidOrExpire')
                    ],401);
                }else{
                    return response()->json([
                        'result' => null,
                        'statusCode' => 40101,
                        'message' => trans('message.tokenInvalidOrExpire')
                    ],401);
                }
        }
        
        $accessToken = $request->bearerToken();

        $request->merge(["client_user_access_token" => $accessToken,"auth_user_id" => auth()->user()->id]);
        return $next($request);
    }
}
