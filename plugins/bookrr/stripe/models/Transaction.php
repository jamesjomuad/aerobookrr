<?php namespace Bookrr\Stripe\Models;

use Model;

/**
 * Transactions Model
 */
class Transaction extends Model
{
    /**
     * @var string The database table used by the model.
     */
    public $table = 'bookrr_stripe_transactions';

    /**
     * @var array Guarded fields
     */
    protected $guarded = ['*'];

    /**
     * @var array Fillable fields
     */
    protected $fillable = [
        'amount',
        'email',
        'payment_method',
        'ref_id',
        'refunded',
        'amount_refunded',
        'receipt_url',
        'status',
        'response'
    ];

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
