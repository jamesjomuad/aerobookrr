<?php namespace Bookrr\User\Components;

use Cms\Classes\ComponentBase;
use ValidationException;
use Validator;
use BackendAuth;
use Flash;
use Redirect;
use Backend\Models\User;
use Backend\Models\UserRole;
use Bookrr\User\Models\Customers;
use Bookrr\User\Models\Vehicle;
use Bookrr\Rates\Models\Rate;
use Bookrr\Booking\Models\Parking;
use Bookrr\Bay\Models\Bay;
use Bookrr\Stripe\Controllers\Cashier;



class Register extends ComponentBase
{
    public function componentDetails()
    {
        return [
            'name'        => 'Register Component',
            'description' => 'Customer registration.'
        ];
    }

    public function defineProperties()
    {
        return [
            'btnLabel' => [
                'title'       => 'Button Label',
                'description' => 'Button label.',
                'default'     => 'Register',
                'type'        => 'string',
            ]
        ];
    }

    public function onRun()
    {
        $this->addJs('/plugins/bookrr/user/assets/js/vue.min.js');
        $this->addJs('/plugins/bookrr/user/assets/js/ajaxUtils.js');
        $this->addJs('/plugins/bookrr/user/assets/js/ajaxPopup.js');
        $this->addJs('/plugins/bookrr/user/assets/js/bootstrap-autocomplete.min.js');
        $this->addJs('/plugins/bookrr/user/assets/js/comp.register.js');
        $this->addCss('/plugins/bookrr/user/assets/css/comp.register.css');

        $this->page['isLogin'] = BackendAuth::check();
    }

    public function onRegisterForm()
    {
        return [
            'popup' => $this->renderPartial('@register-form.htm')
        ];
    }

    public function onRegister()
    {
        $response = [];

        // Register user
        $user = BackendAuth::register([
            'email'      => input('email'),
            'login'      => input('login'),
            'first_name' => input('firstname'),
            'last_name'  => input('lastname'),
            'password'   => input('password'),
            'password_confirmation' => input('confirmpassword')
        ]);

        // Assign role as customer
        $user->role()->add(UserRole::where('code','customer')->first());

        // Add to bookrr user as customer
        $customer = new Customers();
        $customer->phone = input('phone');
        $customer->save();
        $user->customer()->save($customer);

        $vehicle = new Vehicle;
        $vehicle->plate = input('plate');
        $vehicle->brand = input('make');
        $vehicle->model = input('model');
        $vehicle->primary = 1;
        $vehicle->save();

        // Add attach vehicle to customer
        $user->vehicles()->save($vehicle);

        # Save all user data
        $user->save();

        // Create Booking when enabled
        if(input('date_in') AND input('date_out'))
        {
            $booking = new Parking;
            $booking->rules = [];
            $booking->date_in = (new \Carbon\Carbon())->parse(input('date_in'));
            $booking->date_out = (new \Carbon\Carbon())->parse(input('date_out'));

            // Add Vehicle to booking
            $booking->vehicle()->add($vehicle);

            // Attach parking bay
            // $booking->bay()->add(Bay::getAvailable()->first());

            // Attach booking to customer
            $customer->parkings()->save($booking);

            $booking->save();

            $response["booking_number"] = $booking->number;
        }

        return [
            '@thank-you' => $this->renderPartial('@thank-you.htm',[
                "name" => $user->first_name." ".$user->last_name,
                "login" => $user->login,
                "email" => $user->email,
                "number" => @$response["booking_number"] ? : false
            ])
        ];
    }

    public function onStepOne()
    {
        // Validate
        $validator = Validator::make(input(), [
            'login'     => 'required|between:4,20|unique:backend_users',
            'email'     => 'required|email|unique:backend_users',
            'phone'     => 'required|regex:/^([0-9\s\-\+\(\)]*)$/|min:7',
            'firstname' => 'required',
            'lastname'  => 'required',
            'password'  => 'required|between:4,255',
            'confirmpassword'  => 'required|same:password'
        ],
        // Custom Message
        [
            'login.unique' => 'Username is already taken!',
            'confirmpassword.required' => 'Password Confirmation is required!',
            'confirmpassword.same' => 'Password Confirmation should match the Password',
        ]);
        
        // Check point
        if ($validator->fails()) {
            foreach ($validator->messages()->all() as $message) {
                Flash::error($message);
            }
            throw new ValidationException($validator);
        }
    }

    public function onStepTwo()
    {
        // Validate
        $validator = Validator::make(input(), [
            'plate' => 'required',
            'make'  => 'required',
            'model' => 'required',
        ]);
        
        // Check point
        if ($validator->fails()) {
            foreach ($validator->messages()->all() as $message) {
                Flash::error($message);
            }
            throw new ValidationException($validator);
        }
    }

    public function onBookingForm()
    {
        return [
            'currency' => Cashier::config()->symbol,
            'rate' => Rate::amount()
        ];
    }

    public function onTest()
    {
        $rate = Rate::compute(input('dateIn'),input('dateOut'));

        // Sign in as a specific user
        BackendAuth::login($user);

        if(BackendAuth::check())
        {
            Flash::success('Login Successful!');
            Redirect::to('/backend');
            return;
        }

        Flash::error('Login Failed!');
    }

}