<?php namespace Bookrr\Bay\Models;

use Model;
use \Carbon\Carbon;


class Bay extends Model
{
    public $table = 'bookrr_bay';

    protected $guarded = ['*'];

    protected $fillable = ['name','status'];

    protected $hidden = ['created_at','updated_at','deleted_at'];


    public $hasOne = [];
    public $hasMany = [
        'parking' => \Bookrr\Booking\Models\Parking::class
    ];
    public $belongsTo = [
        'zone' => \Bookrr\Bay\Models\Zone::class
    ];


    #
    #  Attributes
    #
    public function getCreatedatAttribute($date)
    {
        return Carbon::createFromFormat('Y-m-d H:i:s', $date)->format('d/m/Y');
    }

    public function getStatusAttribute($value)
    {
        if($value=='0')
        return false;

        if(empty($value) OR $value)
        return true;
    }

    public function getAvailabilityAttribute()
    {
        if($this->status)
            return "Available";
        else
            return "Not Available";
    }


    #
    #  Scopes
    #
    public function scopeSetOccupied()
    {
        $this->status = 'occupied';
        return $this->save();
    }

    public function scopeSetAvailable()
    {
        $this->status = null;
        return $this->save();
    }

    public function scopeIsAvailable($query)
    {
        $query->where('status',1);
        
        return $query;
    }


    #
    #  Helpers
    #
    public static function getBay($id)
    {
        return self::with('parking')->where('id',$id)->first()->toArray();
    }

    public static function countAvailable()
    {
        return self::whereNull('status')->get()->count();
    }

    public static function countOccupied()
    {
        return self::where('status','occupied')->get()->count();
    }

}