<?php namespace Aeroparks\Store\Controllers;

use BackendMenu;
use Backend\Classes\Controller;
use Aeroparks\Store\Models\Coupon as CouponModel;

/**
 * Coupon Back-end Controller
 */
class Coupon extends Controller
{
    public $implement = [
        'Backend.Behaviors.FormController',
        'Backend.Behaviors.ListController'
    ];

    public $formConfig = 'config_form.yaml';
    public $listConfig = 'config_list.yaml';

    public function __construct()
    {
        parent::__construct();

        BackendMenu::setContext('Aeroparks.Store', 'store', 'coupon');
    }

    public function getCoupon($code)
    {
        $result = CouponModel::where('code',$code)->first();

        return response()
            ->json($result);
    }

    public function getCoupons()
    {
        $result = CouponModel::all();

        return response()
            ->json($result);
    }

}
