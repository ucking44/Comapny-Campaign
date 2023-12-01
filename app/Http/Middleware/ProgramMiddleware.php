<?php

namespace App\Http\Middleware;

use Closure;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\DB;

class ProgramMiddleware
{
    /**
     * Handle an incoming request.
     *
     * @param  \Illuminate\Http\Request  $request
     * @param  \Closure(\Illuminate\Http\Request): (\Illuminate\Http\Response|\Illuminate\Http\RedirectResponse)  $next
     * @return \Illuminate\Http\Response|\Illuminate\Http\RedirectResponse
     */
    public function handle($request, Closure $next)
    {
        //dd($request->program_slug);
        $slug = $request->program_slug;
        $check = DB::table('programs')->where(['slug' => $slug, 'status'=> 1])->first();
        //dd($check);
        if(is_null($check)){
            return response()->json([
                'status' => 0,
                'message' => 'Invaild Program Identifier Passed',
                'status_code' => 400
            ], 400);
        }

        return $next($request);
    }
}
