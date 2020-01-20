<?php namespace Bookrr\Booking\Controllers;

use BackendMenu;
use Backend\Classes\Controller;

/**
 * Cart Back-end Controller
 */
class Cart extends \Bookrr\Store\Controllers\Cart
{

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

    public function relationExtendConfig($config, $field, $model)
    {

        if($model->isPaid)
        {
            $config->view['toolbarButtons'] = false;
            unset($config->view['list']['columns']['_action']);
        }

    }

    #
    #   Form Overider
    #
    // public function formExtendFields($form,$fields)
    // {
    //     // $fields['_park_rate']->value = "{$this->currency} ".$fields['_park_rate']->value;
    //     return $form;
    // }

}
