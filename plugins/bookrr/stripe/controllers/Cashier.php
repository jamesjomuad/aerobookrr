<?php namespace Bookrr\Stripe\Controllers;

use BackendMenu;
use Backend\Classes\Controller;
use Bookrr\Stripe\Models\Settings;


class Cashier extends Controller
{

    public function __construct()
    {
        parent::__construct();

        BackendMenu::setContext('Bookrr.Stripe', 'stripe', 'cashier');
    }

    public function onStripeElements()
    {
        $this->addCss('/plugins/bookrr/stripe/assets/css/style.css','v1.3');
        $this->addJs('https://js.stripe.com/v3/','v1.1');
        $this->addJs('/plugins/bookrr/stripe/assets/js/charge.js','v1.7');
    }

    public static function config()
    {
        return Settings::getSettings();
    }
}
