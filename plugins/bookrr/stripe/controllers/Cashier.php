<?php namespace Bookrr\Stripe\Controllers;

use BackendMenu;
use Backend\Classes\Controller;
use Bookrr\Stripe\Models\Settings;
use Bookrr\Stripe\Models\Transaction;


class Cashier extends Controller
{

    public function __construct()
    {
        parent::__construct();

        BackendMenu::setContext('Bookrr.Stripe', 'stripe', 'cashier');
    }

    public function index()
    {

        $cart = Parking::find(27)->cart;

        $product = (new \Bookrr\Store\Models\Product([
            'name' => 'Parking',
            'description' => '120/Hrs',
            'price' => 10.99 
        ]));

        $cart->products->add($product);

        dd(
            $cart->products
        );

        return false;   
    }

    public function onStripe()
    {
        // css
        $this->addCss('/plugins/bookrr/stripe/assets/css/style.css','v1.3');

        // js
        $this->addJs('https://js.stripe.com/v3/','v1.1');
        $this->addJs('/plugins/bookrr/stripe/assets/js/charge.js','v1.9');
        

        $this->vars['config'] = self::config();

        return $this->makePartial('stripe');
    }

    public function onCash()
    {
        $this->addCss('/plugins/bookrr/stripe/assets/css/bootstrap-grid.css');
        $this->addJs('/plugins/bookrr/stripe/assets/js/vue.min.js');
        $this->addJs('/plugins/bookrr/stripe/assets/js/cash.js');
        
        return $this->makePartial('cash');
    }

    public function onCreate()
    {
        $config = Cashier::config();

        Cashier::stripe()->setApiKey($config->key);

        // Create options for Stripe
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

        $transaction = Transaction::create([
            'amount'         => $result->amount,
            'email'          => $config->receipt_email ? input('email') : $result->billing_details->email,
            'payment_method' => $result->payment_method,
            'ref_id'         => $result->id,
            'refunded'       => $result->refunded,
            'amount_refunded'=> $result->amount_refunded,
            'receipt_url'    => $result->receipt_url,
            'status'         => $result->status,
            'response'       => $result
        ]);

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
