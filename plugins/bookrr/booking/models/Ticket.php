<?php namespace Bookrr\Booking\Models;

use Model;

/**
 * Ticket Model
 */
class Ticket extends Model
{

    public $table = 'aeroparks_ticket';

    protected $guarded = ['*'];

    protected $hidden = ['id','created_at','updated_at','deleted_at'];

    protected $fillable = ['qrcode','barcode','status','amount'];

    public $hasOne = [
        'booking' => ['Bookrr\Booking\Models\Parking']
    ];
    public $hasMany = [];
    public $belongsTo = [];
    public $belongsToMany = [];
    public $morphTo = [];
    public $morphOne = [];
    public $morphMany = [];
    public $attachOne = [];
    public $attachMany = [];
}
