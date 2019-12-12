<?php namespace Bookrr\User\Models;

use Model;
use \Bookrr\User\Models\Customers;

/**
 * Vehicle Model
 */
class Vehicle extends Model
{

    public $table = 'bookrr_user_vehicles';

    protected $guarded = ['*'];

    public $belongsTo = [
        'customer' => [
            \Bookrr\User\Models\Customers::class,
            'key' => 'user_id',
            'delete' => true
        ]
    ];

    public $hasOne = [];
    public $hasMany = [];
    public $belongsToMany = [];
    public $morphTo = [];
    public $morphOne = [];
    public $morphMany = [];
    public $attachOne = [];
    public $attachMany = [];

    public function getPrimaryAttribute($value)
    {
        if(input('id') && Customers::find(input('id'))->vehicles->count()==0)
        {
            return 1;
        }

        return $value;
    }

    public function beforeSave()
    {
        if($this->primary==1)
        {
            $this->where('primary',1)->update(['primary' => 0]);
        }
    }

    public function scopeHasDefault($query)
    {
        return $query->where('primary',1)->get()->isNotEmpty();
    }
}
