<?php

namespace App\Http\Middleware;

use Closure;
use Illuminate\Support\Facades\Log;

class MergeJSON
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
        if($request->has('questions'))
            $request->merge(['questions' => json_decode($request->input('questions'), true)]);
        if($request->has('responses'))
            $request->merge(['responses' => json_decode($request->input('responses'), true)]);
        return $next($request);
    }
}
