<?php namespace Aeroparks\Bay\Models;

use Model;
use \Carbon\Carbon;

/**
 * Bay Model
 */
class Bay extends Model
{
    public $table = 'aeroparks_bay';

    protected $guarded = ['*'];

    protected $fillable = ['name','status'];

    protected $hidden = ['created_at','updated_at','deleted_at'];

    /**
     * @var array Relations
     */
    public $hasOne = [];
    public $hasMany = [
        'parking' => \Aeroparks\Booking\Models\Parking::class
    ];
    public $belongsTo = [
        'zone' => \Aeroparks\Bay\Models\Zone::class
    ];
    public $morphTo = [];
    public $morphOne = [];
    public $morphMany = [];
    public $attachOne = [];
    public $attachMany = [];
    public $belongsToMany = [];


    /*
    *   Attributes
    */
    public function getCreatedatAttribute($date)
    {
        return Carbon::createFromFormat('Y-m-d H:i:s', $date)->format('d/m/Y');
    }

    public function getStatusAttribute($value)
    {
        if(empty($value))
        return 'Available';

        return ucfirst($value);
    }


    /*
    *   Scopes
    */
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


    /*
    *   Helpers
    */
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