<?php namespace Bookrr\Booking\Models;

use Model;

/**
 * Cart Model
 */
class Cart extends \Bookrr\Store\Models\Cart
{
    // public $currencySymbol = "$";

    public function getTotalFloatAttribute()
    {
        return "{$currencySymbol} ".number_format($this->total, 2);
    }

    // public function filterFields($fields, $context = null)
    // {
    //     trace_log('filterFields');

    //     return $this;
    // }


}
