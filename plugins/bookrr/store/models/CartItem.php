<?php namespace Bookrr\Store\Models;

use Model;

/**
 * CartItem Model
 */
class CartItem extends Model
{
    /**
     * @var string The database table used by the model.
     */
    public $table = 'bookrr_cart_product';

    /**
     * @var array Guarded fields
     */
    protected $guarded = ['*'];

    /**
     * @var array Fillable fields
     */
    protected $fillable = [];

    /**
     * @var array Relations
     */
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
