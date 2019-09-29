<?php namespace Aeroparks\User\Models;

use Model;
use Backend\Models\User;


class Vehicle extends Model
{
    use \October\Rain\Database\Traits\Validation;
    
    use \October\Rain\Database\Traits\SoftDelete;

    public $table = 'aeroparks_vehicle';

    protected $dates = ['deleted_at'];

    protected $fillable = ['primary','name', 'plate', 'brand', 'model', 'color', 'size'];

    public $rules = [];

    public $hasOne = [
        'parking' => ['Aeroparks\Booking\Models\Parking']
    ];

    public $belongsTo = [
        'customer'  => ['Aeroparks\User\Models\Customer','key' => 'user_id'],
        'user'      => ['Aeroparks\User\Models\User','key' => 'user_id']
    ];


    /*
    *   OPTIONS
    */
    public function getBrandOptions()
    {
        $options = Vehicle::select('brand')
            ->get()
            ->unique('brand')
            ->pluck('brand')
            ->filter()
            ->toArray();

        $tmp = [];

        foreach($options as $option)
        {
            $tmp[$option] = $option;
        }

        return $tmp;
    }

    public function getModelOptions()
    {
        $options = Vehicle::select('model')
            ->get()
            ->unique('model')
            ->pluck('model')
            ->filter()
            ->toArray();

        $tmp = [];

        foreach($options as $option)
        {
            $tmp[$option] = $option;
        }

        return $tmp;
    }

    public function getSizeOptions()
    {
        $options = Vehicle::select('size')
            ->get()
            ->unique('size')
            ->pluck('size')
            ->filter()
            ->toArray();

        $tmp = [];

        foreach($options as $option)
        {
            $tmp[$option] = $option;
        }

        return $tmp;
    }


    /*
    *   SCOPES
    */
    public function scopeApplyBrandFilter($query, $filtered)
    {
        return $query->whereHas('roles', function($q) use ($filtered) {
            $q->whereIn('id', $filtered);
        });
    }

    /*
    *   EVENTS
    */
    public function beforeCreate()
    {
        
    }


}