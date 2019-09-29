<?php namespace Bookrr\Store\Models;

use Model;

/**
 * Cart Model
 */
class Cart extends Model
{
    public $table = 'aeroparks_carts';

    protected $guarded = ['*'];

    public $hasMany = [
        'items_count' => ['Bookrr\Store\Models\CartItem', 'count' => true]
    ];
    public $belongsTo = [
        'parking' => 'Bookrr\Booking\Models\Parking'
    ];
    public $belongsToMany = [
        'products' => [
            'Bookrr\Store\Models\Product',
            'table' => 'aeroparks_cart_product',
            'pivot' => ['quantity']
        ]
    ];

    public function getTotalPrice()
    {
        return number_format((float)$this->products->sum(function($product) {
            return $product->quantity() * $product->price;
        }), 2, '.', '');
    }

    public function getTotalQuantity()
    {
        $this->products->sum('pivot.quantity');
    }

}
