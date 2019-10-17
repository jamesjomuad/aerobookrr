<?php namespace Bookrr\User\Models;

use Model;
use \Carbon\Carbon;



class Staff extends Model
{
    use \October\Rain\Database\Traits\Validation;
    
    use \October\Rain\Database\Traits\SoftDelete;

    public $table = 'bookrr_user';

    protected $rules=[];

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

    public $titles = ['Mr.','Mrs.','Ms.','Dr.','Eng.','Atty.'];

    #
    #   Set Default Query
    #
    public function newQuery($excludeDeleted = true)
    {
        $query = parent::newQuery($excludeDeleted);
        $query->isStaff();
        return $query;
    }


    #
    #  SCOPE
    #
    public function scopeRoleID()
    {
        if($role = $this->role()->where('code','staff')->orWhere('name','Staff')->first())
            return $role->id;
        else
            return false;
    }

    public function scopeIsStaff($query)
    {
        return $query->with('user')
        ->whereHas('user.role',function($q){
            $q->where('code','staff');
        });
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

    public function getNameAttribute($value)
    {
        return $this->profile->first_name . ' ' . $this->profile->last_name;
    }


    public function getTitleOptions()
    {
        return $this->titles;
    }


}

