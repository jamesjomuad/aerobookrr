<?php namespace Bookrr\Store\Models;

use Model;


class CarRent extends Model
{

    public $table = 'bookrr_carrental';

    protected $guarded = ['*'];

    protected $fillable = [];

    public $attachMany = [
        'images' => 'System\Models\File'
    ];

    public $hasOne = [
        'carbooking' => ['Bookrr\Store\Models\CarBooking','key' => 'car_id']
    ];


    /*
    *   Attribute
    */
    public function getThumbAttribute($value)
    {
        if($img = $this->images()->first())
        {
            try {
                return $img->getThumb(100, 100, 'crop');
            } catch (\Throwable $th) {
                //throw $th;
            }
        }
    }

    public function getCustomerThumbAttribute($value)
    {
        if($img = $this->images()->first())
        {
            try {
                return $img->getThumb(300, 250, 'crop');
            } catch (\Throwable $th) {
                //throw $th;
            }
        }
    }

    public function getGearAttribute($value)
    {
        $gear = [
            'manual'    => 'M',
            'automatic' => 'A'
        ];
        
        return $gear[$this->transmission];
    }

    public function getImageGalleryAttribute($value)
    {
        if($images = $this->images()->get())
        {
            return $images->map(function ($model) {
                return collect($model->toArray())->pull('path');
            })->toArray();
        }
    }

    /*
    *   OPTIONS
    */
    public function getBrandOptions()
    {
        $options = $this->select('brand')
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
        $options = $this->select('model')
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

    public function getMpgOptions()
    {
        $options = $this->select('mpg')
            ->get()
            ->unique('mpg')
            ->pluck('mpg')
            ->filter()
            ->toArray();

        $tmp = [];

        foreach($options as $option)
        {
            $tmp[$option] = $option;
        }

        return $tmp;
    }

    public function getStatusOptions()
    {
        return [
            'available' => 'Available',
            'not_available' => 'Not Available',
            'trouble' => 'Trouble'
        ];
    }


}
