<?php namespace Aeroparks\User\Models;

use Model;
use Backend\Models\User;
use Backend\Models\UserRole;
use \Carbon\Carbon;



class Staff extends Model
{
    use \October\Rain\Database\Traits\Validation;
    
    use \October\Rain\Database\Traits\SoftDelete;

    public $table = 'aeroparks_user';

    protected $rules=[];

    protected $guarded = ['*'];

    protected $fillable = ['role_id'];

    public $belongsTo = [
        // 'backendUser' => User::class,
        'backendUser' => ['Aeroparks\User\Models\BaseUser','key' => 'user_id'],
        'role' => UserRole::class
    ];

    public $titles = ['Mr.','Mrs.','Ms.','Dr.','Eng.','Atty.'];

    /*
    *   Set Default Query
    */
    public function newQuery($excludeDeleted = true)
    {
        return parent::newQuery($excludeDeleted)
            ->where('type', '=', 'staff');
    }


    /*
    *   SCOPE
    */
    public function scopeRoleID()
    {
        if($role = $this->role()->where('code','staff')->orWhere('name','Staff')->first())
            return $role->id;
        else
            return false;
    }


    /*
    *   ATTRIBUTES
    */
    public function getCreatedAtAttribute($value)
    {
        if($value)
            return (new Carbon($value))->diffForHumans();
        else
            return $value;
    }

    public function getNameAttribute($value)
    {
        return $this->backendUser->first_name . ' ' . $this->backendUser->last_name;
    }


    public function getTitleOptions()
    {
        return $this->titles;
    }


}

