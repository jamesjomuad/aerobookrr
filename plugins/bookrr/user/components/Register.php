<?php namespace Bookrr\User\Components;

use Cms\Classes\ComponentBase;
use BackendAuth;
use Validator;
use ValidationException;
use Flash;
use Redirect;
use DB;
use Bookrr\Booking\Models\Parking;
use Bookrr\Booking\Models\Ticket;
use Bookrr\User\Models\Vehicle;
use Bookrr\User\Models\User;
use Bookrr\User\Models\Contact;
use Bookrr\User\Models\Customer;
use Bookrr\Booking\Controllers\Ticket as TicketController;



class Register extends ComponentBase
{
    use \Bookrr\User\Traits\formatter;

    public function componentDetails()
    {
        return [
            'name'        => 'Backend User Registration',
            'description' => 'User signup'
        ];
    }

    public function onRegister()
    {
        $finish = null;

        $data = post();

        $data['login'] = post('first_name').post('last_name');

        $validator = Validator::make($data, [
            'first_name'    => 'required',
            'last_name'     => 'required',
            'login'         => 'required',
            'email'         => 'required|email'
        ]);

        if ($validator->fails()) {
            throw new ValidationException($validator);
        }

        \Db::transaction(function() use($data,&$finish){
            // Create Backend user
            $backendUser = BackendAuth::register([
                'first_name'        => $data['first_name'],
                'last_name'         => $data['last_name'],
                'login'             => $data['login'],
                'email'             => $data['email'],
                'password'          => $data['password'],
                'password_confirmation' => $data['password_confirmation']
            ]);
            $backendUser->role_id = Customer::roleID();
            $backendUser->save();

            // Create Aeroparks user
            $user = User::create(array_add($data, 'type', 'customer'));

            // Create Booking
            $booking = new Parking;
            $booking
                ->clearRules()
                ->fill($this->formatBooking())
            ;

            // Create Vehicles
            $vehicle = Vehicle::create(array_add($data['vehicle'],'primary',1));

            // Create Contact
            $contact = new Contact($this->formatContact());

            $booking->vehicle()->associate($vehicle)->save();

            $backendUser->aeroUser()->save($user);

            $user->contacts()->save($contact);

            $user->vehicles()->save($vehicle);

            $user->bookings()->save($booking);

            $finish = [
                'number' => $booking->number,
                'email'  => $backendUser->email,
                'ticket' => TicketController::generate($booking)
            ];

        });
        /* end transaction */

        Flash::success('Account created successfully!');

        return ['booking' => $finish];
    }

}