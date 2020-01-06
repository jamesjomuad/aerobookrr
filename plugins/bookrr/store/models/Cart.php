<?php namespace Bookrr\Store\Models;

use Model;



class Cart extends Model
{
    public $table = 'bookrr_carts';

    protected $guarded = ['*'];

    protected $fillable = ['name','status','amount','note'];

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

    public $hasMany = [
        'Transaction' => [
            \Bookrr\Stripe\Models\Transaction::class, 
            'key' => 'other_id'
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

    public function scopeIsPaid($query)
    {
        $collect = $this->Transaction()->where('status', 'succeeded')->get();
        
        if($collect->isNotEmpty())
        {
            return true;
        }
        
        return false;
    }

    public function scopeIsFail($query)
    {
        $collect = $this->Transaction()->where('status', 'fail')->get();
        
        if($collect->isNotEmpty())
        {
            return true;
        }
        
        return false;
    }

    public function scopeSetPaid($query,$stripe)
    {
        if($stripe)
        {
            $this->status     = "paid";
            $this->amount     = ($stripe['amount'])/100;
            $this->paymentId  = $stripe['id'];
            $this->receiptUrl = $stripe['receipt_url'];
            $this->save();
            return $this;
        }

        return null;
    }

    /*
    *   Attribute
    */
    public function getIsPaidAttribute($value)
    {
        return $this->isPaid();
    }

}