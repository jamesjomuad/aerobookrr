<?php namespace Aeroparks\Store\Controllers;

use BackendMenu;
use Backend\Classes\Controller;
use Aeroparks\Store\Models\Product as ProductModel;


/**
 * Product Back-end Controller
 */
class Product extends Controller
{
    public $requiredPermissions = [
        'aeroparks.product.read'
    ];

    public $implement = [
        'Backend.Behaviors.FormController',
        'Backend.Behaviors.ListController',
        'Backend.Behaviors.ReorderController'
    ];

    public $formConfig = 'config_form.yaml';
    public $listConfig = 'config_list.yaml';
    public $reorderConfig = 'config_reorder.yaml';
    public $currency = [
        'symbol' => '$',
        'code'  => 'NZD'
    ];

    public function __construct()
    {
        parent::__construct();

        BackendMenu::setContext('Aeroparks.Store', 'store', 'product');
    }

    public function getProduct($id)
    {
        $result = ProductModel::find($id);

        return response()
            ->json($result);
    }

    public function getProducts()
    {
        $result = ProductModel::all();

        return response()
            ->json($result);
    }
}