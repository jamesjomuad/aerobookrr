<?php namespace Aeroparks\Store\Components;

use Cms\Classes\ComponentBase;
use Aeroparks\Store\Models\Product as Products;

class Product extends ComponentBase
{
    public $pageTitle = 'Products and Services';

    public function componentDetails()
    {
        return [
            'name'        => 'Products',
            'description' => 'Products and Services'
        ];
    }

    public function defineProperties()
    {
        return [];
    }

    public function onRun()
    {
        $this->name = 'Products and Services';
        // dd(Products::get()->toArray());
    }

    public function products()
    {
        return Products::get()->toArray();
    }
}