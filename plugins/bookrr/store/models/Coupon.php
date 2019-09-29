<?php namespace Aeroparks\Store\Models;

use Model;

/**
 * Coupon Model
 */
class Coupon extends Model
{
    /**
     * @var string The database table used by the model.
     */
    public $table = 'aeroparks_product_coupon';

    protected $guarded = ['*'];

    protected $hidden = ['updated_at','created_at'];

    protected $fillable = [];

    public $hasOne = [];
    public $hasMany = [];
    public $belongsTo = [];
    public $belongsToMany = [];
    public $morphTo = [];
    public $morphOne = [];
    public $morphMany = [];
    public $attachOne = [];
    public $attachMany = [];
}
