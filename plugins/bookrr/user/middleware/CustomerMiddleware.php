<?php namespace Aeroparks\User\Middleware;

use Closure;
use Config;
use Illuminate\Http\Request;
use Illuminate\Http\Response;
use App;
use BackendAuth;
use Aeroparks\User\Models\BaseUser;

class CustomerMiddleware
{
    public function handle($request, Closure $next)
    {
        $response = $next($request);
        
        if(BackendAuth::check() && strtolower(BackendAuth::getUser()->role->name)=="customer")
        {
            // Check if user is an Aeropark
            if(BaseUser::auth()==false)
            {
                abort(403, 'Access denied');
            }
        }

        return $response;
    }
}