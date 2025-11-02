<?php

namespace App\Http\Middleware;

use App\Traits\ApiResponseTrait;
use Closure;

use Illuminate\Http\Request;
use Illuminate\Support\Facades\Auth;
use Symfony\Component\HttpFoundation\Response;

class RoleMiddleware
{
    use ApiResponseTrait;
    /**
     * Handle an incoming request.
     *
     * @param  \Closure(\Illuminate\Http\Request): (\Symfony\Component\HttpFoundation\Response)  $next
     */
    public function handle(Request $request, Closure $next,...$role): Response
    {   
        if(!in_array('api',$role)) {
            if(!Auth::check()) {   
                abort(401,'not authintication');
            }
        }
        if(!in_array(Auth::user()->role,$role)) {
            if(!in_array('api',$role)) {
                abort(403,'not authorieze');
            }
            else {
                return $this->errorResponse('not authorieze',403);
            }
        }

        return $next($request);
    }
}
