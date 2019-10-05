<?php namespace Bookrr\User\Middleware;

use Closure;
use Config;
use Illuminate\Http\Request;
use Illuminate\Http\Response;
use App;
use BackendAuth;
use Bookrr\User\Models\BaseUser;

class FrontendMiddleware
{
    public function handle($request, Closure $next)
    {
        $response = $next($request);

        if(!request()->is('backend/*'))
        {
            if(BackendAuth::check() && strtolower(BackendAuth::getUser()->role->name)=="frontend")
            {
                return response()->make(view()->make('cms::error'), 403);
            }
        }
        
        return $response;
    }
}