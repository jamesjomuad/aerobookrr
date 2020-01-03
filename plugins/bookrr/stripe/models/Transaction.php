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

    protected $jsonable = ['response'];
}
