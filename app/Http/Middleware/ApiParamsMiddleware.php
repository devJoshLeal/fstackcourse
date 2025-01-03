<?php

namespace App\Http\Middleware;

use Closure;
use Illuminate\Http\Request;

class ApiParamsMiddleware
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
        $json = $request->input('json', null);
        $params_array = json_decode($json, true);
        if(empty($params_array)){
            return response()->json(['error' => 'No se enviaron parametros'], 400);
        }
        return $next($request);
    }
}
