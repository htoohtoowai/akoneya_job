<?php

namespace App\Http\Middleware;

use Closure;
use Illuminate\Support\Facades\Http;

class AuthenticateToUaaService
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
        $accessToken = $request->bearerToken();

        if($accessToken)
        {
            $authenticateResponse = Http::withToken($accessToken)
                                    ->post(config('services.uaa_auth_service.url'));
            $authenticateResponseJson = $authenticateResponse->json();

            if($authenticateResponse->ok()){
                if($authenticateResponseJson["statusCode"] == 200) {
                    $request->merge([
                        "client_user_access_token" => $accessToken,
                        "authUserId" => $authenticateResponseJson["result"]["user"]["id"]
                    ]);
                    return $next($request);
                }
            }

            return response()->json($authenticateResponseJson,$authenticateResponse->status());
        }
        
        return response()->json([
            'result' => null,
            'statusCode' => 40101,
            'message' => trans('message.tokenInvalidOrExpire')
        ],401);
    }
}