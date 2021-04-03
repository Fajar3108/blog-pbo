<?php

namespace App\Http\Middleware;

use Closure;
use Illuminate\Http\Request;

class Admin
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
        if(auth()->user()){
            if(strtoupper(auth()->user()->role->name) == "ADMIN"){
                $respon = [
                    'status' => 'error',
                    'msg' => 'You are not an Admin',
                    'errors' => "Unauthorized action.",
                    'content' => [
                        'status_code' => 403
                    ]
                ];
                return response()->json($respon, 403);
            }
        }
        return $next($request);
    }
}
