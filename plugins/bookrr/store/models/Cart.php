<?php namespace Bookrr\Store\Models;

use Model;



class Cart extends Model
{
    public $table = 'bookrr_carts';

    protected $guarded = ['*'];

    protected $fillable = ['name','status','subtotal','note'];

    public $belongsTo = [
        'parking' => ['Bookrr\Booking\Models\Parking','key'=>'book_id']
    ];

    public $belongsToMany = [
        'products' => [ 
            'Bookrr\Store\Models\Product',
            'table' => 'bookrr_cart_product',
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

    /*
    *   Scopes
    */
    public function scopeBasket($query)
    {
        return $this->products->map(function ($item, $key) {
            return [
                "name"          => $item['name'],
                "description"   => $item['description'],
                "quantity"      => $item['pivot']['quantity'],
                "price"         => $item['price'],
                "total"         => round($item['pivot']['quantity']*$item['price'],2)
            ];
        });
    }

    public function scopeIsPaid()
    {
        if($this->status=='paid' AND $this->paymentId)
        {
           return true;
        }
        
        return false;
    }

    public function scopeSetPaid($query,$stripe)
    {
        if($stripe)
        {
            $this->status       = "paid";
            $this->subtotal     = ($stripe->amount)/100;
            $this->paymentId    = $stripe->id;
            $this->receiptUrl   = $stripe->receipt_url;
            $this->save();
            return $this;
        }

        return null;
    }

}
