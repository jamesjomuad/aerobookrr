<?php namespace Bookrr\Store\Models;

use Model;
use Bookrr\Store\Models\BaseModel;

class Rule extends BaseModel
{

    public $table = 'bookrr_product_rule';

    protected $guarded = ['*'];

    protected $fillable = [];

    public $hasOne = [];
    public $hasMany = [];
    public $belongsTo = [];
    public $belongsToMany = [];
    public $morphTo = [];
    public $morphOne = [];
    public $morphMany = [];
    public $attachOne = [];
    public $attachMany = [];


    public function formBeforeSave()
    {

    }

    public function getRefundPercentAttribute($value)
    {
        if(!request()->is('*/create'))
        return $value.'%';
    }

    public function getRefundFeeAttribute($value)
    {
        if(!request()->is('*/create'))
        return '$'.$value;
    }

    public function getRateAttribute($value)
    {
        if(!request()->is('*/create'))
        return '$'.$value;
    }

    public function getTypeAttribute($value)
    {
        if(request()->is('*/rule') && $value)
        {
            $option = [
                null,
                'Default',
                'Special Rate Pool'
            ];
            return $option[$value];
        }
    }

}
