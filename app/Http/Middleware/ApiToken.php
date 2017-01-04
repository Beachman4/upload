<?php

namespace App\Http\Middleware;

use App\User;
use Closure;

class ApiToken
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
        if ($request->hasHeader('apiKey')) {
            dd(true);
            $key = $request->header('apiKey');
            if (User::where('apiKey', $key)->first()) {
                return $next($request);
            }
        }
        dd(false);
        return -1;
    }
}
