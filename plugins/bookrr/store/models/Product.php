<?php namespace Bookrr\Store\Models;

use Bookrr\Store\Models\BaseModel;
use Html;
use Request;

class Product extends BaseModel
{

    public $table = 'bookrr_product';

    protected $guarded = ['*'];

    protected $hidden = ['status','hash','created_at','updated_at','deleted_at'];

    protected $fillable = [];

    public $hasOne = [];
    public $hasMany = [];
    public $belongsTo = [];
    public $morphTo = [];
    public $morphOne = [];
    public $morphMany = [];
    public $attachOne = [];
    public $attachMany = [
        'images' => 'System\Models\File'
    ];
    public $belongsToMany = [
        'category' => [
            'Bookrr\Store\Models\Category',
            'table' => 'bookrr_product_pivot',
            'order' => 'name'
        ],
        'cart' => [
            'Bookrr\Store\Models\Cart',
            'table' => 'bookrr_cart_product'
        ]
    ];


    public function getCategoryOptions($value)
    {
        return \Bookrr\Store\Models\Category::all()->pluck('name')->toArray();
    }

    public function getDescriptionAttribute($value)
    {
        if(empty($value))
        {
            return null;
        }

        return str_limit(Html::strip($value).'...', 100);
    }

    public function getThumbAttribute($value)
    {
        if($img = $this->images()->first())
        return $img->getThumb(100, 100, 'crop');
    }

    public function getTestAttribute()
    {
        return str_limit(Html::strip($value).'...', 100);
    }

    public function scopeQuantity($query)
    {
        return $this->pivot->quantity;
    }

    public function scopeTotalwithQty($query)
    {
        return number_format((float)$this->quantity() * $this->price, 2, '.', '');
    }
}
