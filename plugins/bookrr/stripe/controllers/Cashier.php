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

        $cart = Parking::find(input('id'))->cart;

        Cashier::stripe()->setApiKey($config->key);

        $options = [
            'source'        => input('stripeToken'),
            'amount'        => input('amount')*100,
            'currency'      => $config->currency,
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

        if($cart)
        {
            $cart->setPaid($result);
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

    public function test()
    {
        try {
            $charge = Stripe_Charge::create([
                "amount" => $clientPriceStripe,
                "currency" => "usd",
                "customer" => $customer->id,
                "description" => $description
            ]);
            $paymentProcessor = "Credit card (www.stripe.com)";
        } catch (Stripe_CardError $e) {
            $error1 = $e ->getMessage();
        } catch (Stripe_InvalidRequestError $e) {
            // Invalid parameters were supplied to Stripe's API
            $error2 = $e ->getMessage();
        } catch (Stripe_AuthenticationError $e) {
            // Authentication with Stripe's API failed
            $error3 = $e ->getMessage();
        } catch (Stripe_ApiConnectionError $e) {
            // Network communication with Stripe failed
            $error4 = $e ->getMessage();
        } catch (Stripe_Error $e) {
            // Display a very generic error to the user, and maybe send
            // yourself an email
            $error5 = $e ->getMessage();
        } catch (Exception $e) {
            // Something else happened, completely unrelated to Stripe
            $error6 = $e ->getMessage();
        }
    }
}
