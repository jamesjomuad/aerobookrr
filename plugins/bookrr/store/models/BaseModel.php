<?php namespace Bookrr\Store\Models;

use Model;
use BackendAuth;
use Html;

/**
 * BaseModel Model
 */
class BaseModel extends Model
{
    
    public function hasAccess($permission)
    {
        return BackendAuth::getUser()->hasAccess($permission);
    }
    
}
