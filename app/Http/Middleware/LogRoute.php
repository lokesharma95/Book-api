<?php

namespace App\Http\Middleware;

use Carbon\Carbon;
use Closure;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\Log;

class LogRoute
{
    /**
     * Handle an incoming request.
     *
     * @param  \Illuminate\Http\Request  $request
     * @param  \Closure  $next
     * @return mixed
     */
    public function handle(Request $request, Closure $next)
    {
        $response = $next($request);
        if(app()->environment('local')){
            $log=[
                'URL' => $request->getUri(),
                'METHOD' => $request->getMethod(),
                'BODY' => $request->all(),
                'RESPONSE' => $response->getContent(),
                'TIMESTAMP' => Carbon::now()->toDateTimeString(),
            ];
            Log::channel('logRequest')->info(json_encode($log));
        }
        return $response;
    }
}
