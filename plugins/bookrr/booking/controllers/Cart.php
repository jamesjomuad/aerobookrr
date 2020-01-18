<?php namespace Bookrr\Booking\Controllers;

use BackendMenu;
use Backend\Classes\Controller;

/**
 * Cart Back-end Controller
 */
class Cart extends \Bookrr\Store\Controllers\Cart
{

    // public $formConfig      = '$/bookrr/booking/controllers/cart/config_form.yaml';
    // public $listConfig      = '$/bookrr/booking/controllers/cart/config_list.yaml';
    // public $relationConfig  = '$/bookrr/booking/controllers/cart/config_relation.yaml';


    public function __construct()
    {
        parent::__construct();

        BackendMenu::setContext('Bookrr.Booking', 'booking', 'parking');
    }

    public function index()
    {
        $this->pageTitle = 'Cart Bookings';

        return false;
    }

}
