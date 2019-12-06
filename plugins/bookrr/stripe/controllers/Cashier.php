<?php namespace Bookrr\Stripe\Controllers;

use BackendMenu;
use Backend\Classes\Controller;
use Bookrr\Stripe\Models\Settings;
use Bookrr\Booking\Models\Parking;


class Cashier extends Controller
{

    public function __construct()
    {
        parent::__construct();

        BackendMenu::setContext('Bookrr.Stripe', 'stripe', 'cashier');
    }

    public function index()
    {
        return false;   
    }

    public function onStripe()
    {
        $this->addCss('/plugins/bookrr/stripe/assets/css/style.css','v1.3');
        $this->addJs('https://js.stripe.com/v3/','v1.1');
        $this->addJs('/plugins/bookrr/stripe/assets/js/charge.js','v1.8');

        $this->vars['config'] = self::config();

        return $this->makePartial('stripe');
    }

    public function onCreate()
    {
        $config = Cashier::config();

        Cashier::stripe()->setApiKey($config->key);

        $options = [
            'source'   => input('stripeToken'),
            'amount'   => input('amount'),
            'currency' => $config->currency,
            'receipt_email' => input('email'),
        ];

        if($config->receipt_email)
        {
            $options['receipt_email'] = input('email');
        }

        $result = \Stripe\Charge::create($options);

        if(!$config->isLive){
            trace_log($result);
        }

        $parking = Parking::find(input('id'));

        if($parking->cart)
        {
            $parking->cart->setPaid($result->id,input('amount'));
        }
        

        return true;
    }

    public static function stripe()
    {
        return new \Stripe\Stripe;
    }

    public static function config()
    {
        return Settings::getSettings();
    }
}
