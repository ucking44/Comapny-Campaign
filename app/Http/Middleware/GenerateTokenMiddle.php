<?php

namespace App\Http\Middleware;

use Closure;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\DB;

class GenerateTokenMiddle
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
        $token = $request->header('token');
        $check_token = DB::table('generate_tokens')->where(['token' => $token])->first();

        if(is_null($check_token))
        {
            return response()->json([
                'message'=> 'Unauthorised Service Request',
            ], 401);
        }

        return $next($request);
    }
}

