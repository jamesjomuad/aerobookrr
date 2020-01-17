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
    //     if($fields->total_float)
    //     {
    //         $fields->total_float->value = "$currencySymbol ".number_format( $this->total(), 2);
    //     }

    //     return $this;
    // }


}
