<?php namespace Bookrr\User\Models;

use Model;
use \Carbon\Carbon;
use Bookrr\User\Models\User;
use Backend\Models\User as BackendUser;
use Backend\Models\UserRole;

class Customer extends User
{
    use \October\Rain\Database\Traits\Validation;
    
    use \October\Rain\Database\Traits\SoftDelete;
    
    public $table = 'aeroparks_user';

    protected $rules = [];

    protected $guarded = ['*'];

    protected $fillable = ['role_id'];

    public $belongsTo = [
        'backendUser' => ['Bookrr\User\Models\BaseUser','key' => 'user_id'],
        'role' => UserRole::class
    ];

    public $hasMany = [
        'bookings' => ['Bookrr\Booking\Models\Parking','key' => 'user_id','delete' => true],
        'vehicles' => ['Bookrr\User\Models\Vehicle','key' => 'user_id','delete' => true],
        'contacts' => ['Bookrr\User\Models\Contact','key' => 'user_id','delete' => true]
    ];
    

    /*
    *   Set Default Query
    */
    public function newQuery($excludeDeleted = true)
    {
        return parent::newQuery($excludeDeleted)
            ->where('type', '=', 'customer');
    }

    /*
    *   Scopes
    */
    public function scopeRoleRelation()
    {
        return $this->backendUser->role();
    }

    public function scopeUserRelation()
    {
        return $this->backendUser();
    }

    public function scopeRoleID()
    {
        if($role = $this->role()->where('code','customer')->orWhere('name','customer')->first())
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

    public function getFullNameAttribute()
    {
        return $this->backendUser->first_name.' '.$this->backendUser->last_name;
    }

    public function getIDFullNameAttribute()
    {
        return $this->backendUser->id.' - '.$this->backendUser->first_name.' '.$this->backendUser->last_name;
    }

}
