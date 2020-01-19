<?php namespace Bookrr\Bay\Models;

use Model;
use \Carbon\Carbon;


class Bay extends Model
{
    use \October\Rain\Database\Traits\Validation;
    
    public $table = 'bookrr_bay';

    protected $guarded = ['*'];

    protected $fillable = ['name','status'];

    protected $hidden = ['created_at','updated_at','deleted_at'];

    public $rules = [
        'name' => 'unique',
    ];


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

    public function getStatusColorAttribute()
    {
        if($this->status==NULL)
        {
            return "btn-default bg-p";
        }
        elseif($this->status=='reserve')
        {
            return "btn-primary";
        }
        else
        {
            return "br-a";
        }
    }

    public function getStatusAttribute($value)
    {
        if($value===NULL OR $value===0)
        {
            return false;
        }
        
        return $value;
    }

    public function getAvailabilityAttribute()
    {
        if($this->status==false)
        {
            return "Not Available";
        }
        elseif($this->status=='reserve')
        {
            return "Reserved";
        }
        elseif($this->status=='occupied')
        {
            return "Occupied";
        }
        elseif($this->status==1)
        {
            return "Available";
        }
    }


    #
    #  Scopes
    #
    public function scopeSetReserve()
    {
        $this->status = 'reserve';
        return $this->save();
    }

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
        $query->where('status',null)
        ->orWhere('status',1);
        return $query;
    }

    public function scopeGetAvailable($query)
    {
        $query->where('status',null)
        ->orWhere('status',1);
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