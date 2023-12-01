<?php

namespace App\Http\Middleware;

use Closure;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\DB;

class AdminCheck
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
        $flag = $request->header('admin');

        if(!isset($flag)){

            return response()->json([
                'message' => 'Access Denied'
            ]);

            //return $this->sendError('Access denied');
        }elseif(isset($flag) && $flag != 1){

            return response()->json([
                'message' => 'Failed To Grant Access '
            ]);

            //return $this->sendError('Access denied');
        }

        return $next($request);

        // $token = $request->header(['token' => 12345]);
        // dd($token);
        // if (is_null($token))
        // {
        //     return response()->json([
        //         'message' => 'Unauthorised Service Request'
        //     ], 401);
        // }
        // return $next($request);
    }

    // $token = $request->header('token');
    //     $check_token = DB::table('services')->where(['token'=>$token, 'status'=> 1])->first();

    //     if(is_null($check_token)){
    //         return response()->json([
    //             'message'=> 'Unauthorised service request',
    //         ], 401);
    //     }

    //     return $next($request);
}
