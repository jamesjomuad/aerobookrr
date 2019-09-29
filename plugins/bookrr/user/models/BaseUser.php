<?php namespace Bookrr\User\Models;

use Model;
use Backend\Models\UserRole;
use \Carbon\Carbon;
use BackendAuth;
use Hash;


class BaseUser extends Model
{
    use \October\Rain\Database\Traits\Validation;

    use \October\Rain\Database\Traits\SoftDelete;

    public $table = 'backend_users';

    /**
     * Validation rules
     */
    public $rules = [
        'email' => 'required|between:6,255|email|unique:backend_users',
        'login' => 'required|between:2,255|unique:backend_users',
        'password' => 'required:create|between:4,255|confirmed',
        'password_confirmation' => 'required_with:password|between:4,255'
    ];
    
    protected $hidden = ['created_at', 'updated_at', 'deleted_at', 'permissions', 'is_activated', 'role_id', 'activated_at', 'reset_password_code', 'last_login', 'is_superuser', 'password', 'activation_code', 'persist_code'];

    protected $fillable = ['first_name', 'last_name', 'login', 'email', 'password',  'role_id'];

    protected $guarded = ['password_confirmation'];

    public $hasOne = [
        'user' => ['Bookrr\User\Models\User','key' => 'user_id']
    ];

    public $hasMany = [
        'vehicles' => ['Bookrr\User\Models\Vehicle','key' => 'user_id']
    ];
    
    public $belongsTo = [
        'role' => UserRole::class
    ];


    /*
    *   ATTRIBUTES
    */
    public function getLastLoginAttribute($value)
    {
        if($value)
            return (new Carbon($value))->diffForHumans();
        else
            return $value;
    }


    /*
    *   SCOPES
    */
    public function scopeAuth()
    {
        if(BackendAuth::getUser() ? true : false)
        {
           return $this->with('user')->find(BackendAuth::getUser()->id) ? : false; 
        }
        return false;
    }

    public function scopeIsCustomer($query)
    {
        if(BackendAuth::getUser())
        {
            return strtolower($this->role->name)=='customer';
        }
        return null;
    }

    public function scopeGetCustomer()
    {
        if($this->isCustomer())
            return $this->authUser()->user;

        return null;
    }

    public function scopeGetCustomerID()
    {
        if($this->isCustomer())
            return $this->getCustomer()->id;

        return null;
    }

    public function scopeIsStaff()
    {
        return strtolower($this->role->name)=='staff';
    }


    /*
    *   EVENTS
    */
    public function beforeSave()
    {
        $this->password = Hash::make($this->password);
        unset($this->password_confirmation);
    }
}
