<?php namespace Bookrr\User\Models;

use Model;
use Bookrr\User\Models\BaseUser;
use \Carbon\Carbon;


class User extends Model
{
    use \October\Rain\Database\Traits\Validation;
    
    use \October\Rain\Database\Traits\SoftDelete;

    public $table = 'aeroparks_user';

    protected $rules = [];

    protected $guarded = ['created_at'];

    protected $hidden = ['user_id','created_at','updated_at','deleted_at'];

    protected $fillable = ['type','phone','title','company','age','gender','birthdate','referer'];

    public $titles = ['Mr.','Mrs.','Ms.','Dr.','Eng.','Atty.'];

    /*
    *   Relation
    */
    public $belongsTo = [
        'backendUser' => ['Bookrr\User\Models\BaseUser','key' => 'user_id', 'delete' => true],  //enable relation delete
        'role' => UserRole::class
    ];

    public $hasMany = [
        'bookings' => ['Bookrr\Booking\Models\Parking','key' => 'user_id','delete' => true],
        'vehicles' => ['Bookrr\User\Models\Vehicle','key' => 'user_id','delete' => true],
        'contacts' => ['Bookrr\User\Models\Contact','key' => 'user_id','delete' => true]
    ];





    /*
    *   SCOPES
    */
    public function scopeGetBaseUser($query)
    {
        return $query->with('backendUser')->has('baseuser');
    }

    public function scopeGetFullName()
    {
        return $this->backendUser->first_name . ' ' . $this->backendUser->last_name;
    }

    public function scopeParkCount($query){
        return
            $query->with([
                'bookings' => function($query){
                    $query
                        ->where('status','checkout')
                        ->orWhere('status','parked');
                }
            ]);
    }

    /*
    *   OPTIONS
    */
    public function getTypeOptions()
    {
        return ['staff'=>'Staff','customer'=>'Customer'];
    }

    public function getTitleOptions()
    {
        return $this->titles;
    }

    /*
    *   ATTRIBUTES
    */
    public function getLastLoginAttribute($value)
    {
        return '#'.$value;
        return (new Carbon($value))->diffForHumans();
    }

    /*
    *   EVENTS
    */
    public function afterCreate()
    {
        
        $data = [
            'email' => $this->email,
            'title' => $this->title,
            'name'  => $this->first_name . ' ' . $this->last_name
        ];

        if($this->email)
        {
            \Mail::queue('backend::mail.welcome.customer', $data, function ($message) use($data) {
                $message->to($data['email']);
            });
        }
        
    }

}
