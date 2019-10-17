<?php namespace Bookrr\User\Models;

use Model;
use \Carbon\Carbon;



class Customer extends User
{
    use \October\Rain\Database\Traits\Validation;
    
    use \October\Rain\Database\Traits\SoftDelete;
    
    public $table = 'bookrr_user';

    protected $rules = [];

    protected $guarded = ['*'];

    protected $fillable = ['role_id'];

    public $belongsTo = [
        'user' => [
            \Backend\Models\User::class,
            'key'    => 'user_id',
            'delete' => true
        ],
        'role' => \Backend\Models\UserRole::class
    ];

    public $hasMany = [
        'bookings' => ['Bookrr\Booking\Models\Parking','key' => 'user_id','delete' => true],
        'vehicles' => ['Bookrr\User\Models\Vehicle','key' => 'user_id','delete' => true],
        'contacts' => ['Bookrr\User\Models\Contact','key' => 'user_id','delete' => true]
    ];
    

    #
    #   Set Default Query
    #
    public function newQuery($excludeDeleted = true)
    {
        $query = parent::newQuery($excludeDeleted);
        $query->isCustomer();
        return $query;
    }

    #
    #  Scopes
    #
    public function scopeIsCustomer($query)
    {
        return $query->with('user')
        ->whereHas('user.role',function($q){
            $q->where('code','customer');
        });
    }

    public function scopeRoleRelation()
    {
        return $this->profile->role();
    }

    public function scopeUserRelation()
    {
        return $this->profile();
    }

    public function scopeRoleID()
    {
        if($role = $this->role()->where('code','customer')->orWhere('name','customer')->first())
            return $role->id;
        else
            return false;
    }

    #
    #  ATTRIBUTES
    #
    public function getCreatedAtAttribute($value)
    {
        if($value)
            return (new Carbon($value))->diffForHumans();
        else
            return $value;
    }

    public function getFullNameAttribute()
    {
        return $this->profile->first_name.' '.$this->profile->last_name;
    }

    public function getIDFullNameAttribute()
    {
        return $this->profile->id.' - '.$this->profile->first_name.' '.$this->profile->last_name;
    }

}
