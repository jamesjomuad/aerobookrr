<?php namespace Bookrr\User\Models;

use Model;
use \Bookrr\User\Models\Customers;
use Config;

/**
 * Vehicle Model
 */
class Vehicle extends Model
{

    public $table = 'bookrr_user_vehicles';

    protected $guarded = ['*'];
    protected $fillable = [
        'plate',
        'brand',
        'model',
        'color',
        'size',
        'primary'
    ];

    public $belongsTo = [
        'customer' => [
            \Bookrr\User\Models\Customers::class,
            'key' => 'user_id',
            'delete' => true
        ]
    ];

    public function getPrimaryAttribute($value)
    {
        if(input('id') && Customers::find(input('id'))->vehicles->count()==0)
        {
            return 1;
        }

        return $value;
    }

    public function getSizeNameAttribute()
    {
        if(!$this->size){ return; }
        return $this->getSizeOptions()[$this->size];
    }

    public function beforeSave()
    {
        if($this->primary==1 AND $this->customer)
        {
            $this->where('primary',1)->update(['primary' => 0]);
        }
    }

    #
    #   Scopes
    #
    public function scopeHasDefault($query)
    {
        return $query->where('primary',1)->get()->isNotEmpty();
    }

    #
    #   Options
    #
    public function getBrandOptions()
    {
        return Config::get('bookrr.user::make-v1');
    }

    public function getSizeOptions()
    {
        return [
            0 => 'Mini Cars',
            1 => 'Small Car',
            2 => 'Mid-Sized',
            3 => 'Full-Sized',
            4 => 'Small SUV',
            5 => 'Large SUV',
            6 => 'Small Pickup',
            7 => 'Large Pickup',
            8 => 'Bus',
            9 => 'Trailer Trucks',
            10 => 'Unclassified Vehicle'
        ];
    }
}
