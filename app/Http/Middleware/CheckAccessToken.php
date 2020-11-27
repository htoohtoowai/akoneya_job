<?php

namespace App\Http\Middleware;

use Closure;
use Illuminate\Support\Str;

class CheckAccessToken
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
        $accessTokenHeader = $request->header('Authorization', '');

        if(!$accessTokenHeader)
        {
            return response()->json(["message" => 'The access token string as issued by the authorization server.', "code" =>  config('http_status.unauthorized')],config('http_status.unauthorized'));
        }

        if (!Str::startsWith($accessTokenHeader, 'Bearer ')) {
            return response()->json(["message" => 'The type of token this is, typically just the string “bearer”.', "code" =>  config('http_status.unauthorized')],config('http_status.unauthorized'));
        }

        $accessToken = $request->bearerToken();

        $request->merge(["client_user_access_token" => $accessToken,"auth_user_id" => auth()->user()->id]);

        return $next($request);
    }
}
