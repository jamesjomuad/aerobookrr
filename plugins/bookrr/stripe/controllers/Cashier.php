<?php namespace Bookrr\Stripe\Controllers;

use BackendMenu;
use Backend\Classes\Controller;

/**
 * Cashier Back-end Controller
 */
class Cashier extends Controller
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

        BackendMenu::setContext('Bookrr.Stripe', 'stripe', 'cashier');
    }

    public static function onPay()
    {
        \Stripe\Stripe::setApiKey('sk_test_pfyvRhOEAjBZuxpqn6CJ7OFx009cqGVxay');

        $this->vars['charge'] = \Stripe\Charge::create([
            'amount'    => input('amount'),
            'currency'  => 'usd',
            'source'    => input('stripeToken')
        ]);

        return $this->makePartial('stripe');
    }

}
