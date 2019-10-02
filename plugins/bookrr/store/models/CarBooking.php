<?php namespace Bookrr\Store\Models;

use Model;
use Carbon\Carbon;

/**
 * CarBooking Model
 */
class CarBooking extends Model
{
    use \October\Rain\Database\Traits\SoftDelete;
    
    public $table = 'bookrr_carrental_booking';

    /**
     * @var array Guarded fields
     */
    protected $guarded = ['*'];

    /**
     * @var array Fillable fields
     */
    protected $fillable = ['number','date_in', 'date_out'];

    /**
     * @var array Relations
     */
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
