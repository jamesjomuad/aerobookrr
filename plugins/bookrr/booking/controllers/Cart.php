<?php namespace Bookrr\Booking\Controllers;

use BackendMenu;
use Backend\Classes\Controller;

/**
 * Cart Back-end Controller
 */
class Cart extends \Bookrr\Store\Controllers\Cart
{

    public $formConfig      = '$/bookrr/booking/controllers/cart/config_form.yaml';
    public $listConfig      = '$/bookrr/booking/controllers/cart/config_list.yaml';
    public $relationConfig  = '$/bookrr/booking/controllers/cart/config_relation.yaml';


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

    public function onRelationManagePivotCreate() {
        $result = $this->asExtension('RelationController')->onRelationManagePivotCreate();

        $result['#Form-field-Cart-total-group'] = '<label for="Form-field-Cart-total">Total</label> <!-- Text --> <input type="text" name="Cart[total]" id="Form-field-Cart-total" value="'.$this->formGetModel()->total().'" placeholder="" class="form-control" autocomplete="off" maxlength="255"/>';

        return $result;
    }

    // public function listExtendColumns($list)
    // {
    //     dd($list);
    // }

    // public function formExtendRefreshResults($form)
    // {
    //     dd(
    //         $form
    //     );
    // }

}
