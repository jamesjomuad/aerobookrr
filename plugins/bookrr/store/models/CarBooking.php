<?php namespace Bookrr\Store\Models;

use Model;
use Carbon\Carbon;

/**
 * CarBooking Model
 */
class CarBooking extends Model
{
    use \October\Rain\Database\Traits\SoftDelete;

    use \October\Rain\Database\Traits\Validation;
    
    public $table = 'bookrr_carrental_booking';

    public $rules = [
        'passenger' => 'required',
        'phone' => 'required|numeric|min:6',
    ];

    protected $guarded = ['*'];

    protected $fillable = ['number','date_in', 'date_out'];

    public $hasMany = [];
    public $belongsTo = [
        'car' => ['Bookrr\Store\Models\CarRent'],
        'user' => ['Bookrr\User\Models\User']
    ];
    public $belongsToMany = [];
    public $morphTo = [];
    public $morphOne = [];
    public $morphMany = [];
    public $attachOne = [];
    public $attachMany = [];

    public function getDateInAttribute($value)
    {
        return Carbon::parse($value)->format('d/m/y (h:i A)');
    }

    public function getDateOutAttribute($value)
    {
        return Carbon::parse($value)->format('d/m/y (h:i A)');
    }

}
