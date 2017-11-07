<?php

namespace App\Http\Middleware;

use Closure;

class CORS
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

        // Author Later.C
        header('Access=Control-Allow-Origin: *');

        $headers = [
            'Access-Control-Allow-Methods'  =>  'POST, GET, OPTIONS, PUT, DELETE',
            'Access-Control-Allow-Header'   =>  'Content-Type, X-Auth-Token',
        ];
        if ($request->getMethod() == 'OPTIONS') {
            return Response::make('ok', 200, $headers);
        }

        $response = $next($request);
        foreach ($headers as $k  => $v ) {
               $response->header($k, $v);
        }
        return $response;

        // return $next($request)->withHeaders($headers);
    }
}
