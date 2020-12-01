<?php

namespace App\Http\Middleware;

use Closure;

class Cors
{
    /**
     * Handle an incoming request.
     *
     * @param \Illuminate\Http\Request $request
     * @param \Closure $next
     * @return mixed
     */
    public function handle($request, Closure $next)
    {
        // $headers = [
        //     'Access-Control-Allow-Origin' => '*',
        //     'Access-Control-Allow-Methods' => 'POST, GET, OPTIONS, PUT, DELETE',
        //     'Access-Control-Allow-Credentials' => 'true',
        //     'Access-Control-Max-Age' => '86400',
        //     'Access-Control-Allow-Headers' => 'Content-Range, charset, boundary, Content-Length, Content-Disposition, Content-Description, X-Auth-Token, Origin, Content-Type, Accept, Authorization, X-Requested-With'
        // ];

        // if ($request->isMethod('OPTIONS')) {
        //     return response()->json('{"method":"OPTIONS"}', 200, $headers);
        // }

        // $response = $next($request);
        // foreach ($headers as $key => $value) {
        //     $response->header($key, $value);
        // }

        // return $response;

        if ($request->ajax()) {
            header("Access-Control-Allow-Origin: *");
            $headers = [
                'Access-Control-Allow-Methods' => 'POST, GET, OPTIONS, PUT, PATCH, DELETE',
                'Access-Control-Allow-Headers' => 'Content-Type, Accept, Authorization, X-Requested-With, Application',
            ];
            if ($request->getMethod() == "OPTIONS") {
                return response()->make('OK', 200, $headers);
            }
            $response = $next($request);
            foreach ($headers as $key => $value) {
                $response->header($key, $value);
            }
            return $response;
        } else {
            $response = $next($request);
            $response->headers->set('Access-Control-Allow-Origin', '*');
            $response->headers->set('Access-Control-Allow-Methods', 'POST, GET, OPTIONS, PATCH, PUT, DELETE');
            $response->headers->set('Access-Control-Allow-Headers', 'Content-Type, Accept, Authorization, X-Requested-With, Application');
            return $response;
        }
    }
}
