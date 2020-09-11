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
        $request->merge(['questions' => json_decode($request->input('questions'), true)]);
        Log::info($request);
        return $next($request);
    }
}
