<?php namespace Bookrr\Booking\Models;

use Model;
use \Carbon\Carbon;


class Movement extends Model
{

    public $table = 'bookrr_booking_movement';

    protected $fillable = ['*'];

    public $belongsTo = [
        'booking' => ['\Bookrr\Booking\Models\Parking','key' => 'booking_id'],
        'staff'   => [\Bookrr\User\Models\Staff::class,'key' => 'user_id']
    ];

    public $events = [
        1 => 'Key Given To Supplier',
        2 => 'Key Returned By Supplier',
        3 => 'Key Given to Staff Member',
        4 => 'Key Returned By Staff Member'
    ];


    public function getEventOptions()
    {
        return $this->events;
    }

    public function getEventAttribute($value)
    {
        if($value)
        return $this->events[$value];
    }

    public function getBayOptions($value = null)
    {
        dd($value);
        return 3;
    }

    public function listParkBays($value, $formData)
    {
        return (new \Bookrr\Booking\Models\Parking)->listParkBays();
    }

    public function getCreatedatAttribute($date)
    {
        return Carbon::createFromFormat('Y-m-d H:i:s', $date)->format('d/m/Y');
        // return Carbon::createFromFormat('Y-m-d H:i:s', $date)->format('d/m/Y g:i A');
    }

}