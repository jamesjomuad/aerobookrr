<?php namespace Bookrr\Stripe\Models;

use Model;

/**
 * Transactions Model
 */
class Transaction extends Model
{
    public $table = 'bookrr_stripe_transactions';

    protected $guarded = ['*'];

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

    public $hasOne = [
        'customer' => ['Bookrr\User\Models\Customers','key'=>'id']
    ];


}
