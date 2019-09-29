<?php namespace Bookrr\User\Traits;

use Illuminate\Support\Arr;
use Carbon\Carbon;

trait formatter{

    public function formatRegister()
    {
        return [
            'email'                 => post('email'),
            'first_name'            => post('mainFname'),
            'last_name'             => post('mainLname'),
            'login'                 => studly_case(post('fname')),
            'password_confirmation' => post('confirm_pass'),
            'password'              => post('password'),
            'phone'                 => post('mainPhone'),
            'title'                 => post('title'),
            'referer'               => post('referer'),
            'vehicles'              => post('othercar')=='no' ? [post('vehicle')[0]] : post('vehicle'),
            'contacts'              => [
                'primary'    => 1,
                'title'      => post('title'),
                'first_name' => post('mainFname'),
                'last_name'  => post('mainLname'),
                'phone'      => post('mainPhone'),
            ]
        ];
    }

    public function formatBooking()
    {
        return [
            'guest_out'            => post('guest_out'),
            'guest_in'             => post('guest_in'),
            'agent_reference'      => post('agentReference'),
            'date_in'              => $this->toTimestamp(post('dateIn') . ' ' . post('TimeIn')), 
            'date_out'             => $this->toTimestamp(post('dateOut') . ' ' . post('TimeOut')),
            'destination_in'       => post('destinationIn'),
            'destination_out'      => post('destinationOut'),
            'flight_number_arrive' => post('flight_arrivenum'),
            'flight_number_depart' => post('flight_depnum')
        ];
    }

    public function formatContact()
    {
        return [
            'primary'    => 1,
            'first_name' => post('first_name'),
            'last_name'  => post('last_name'),
            'phone'      => post('phone'),
            'email'      => post('email'),
        ];
    }

    private function toTimestamp($dateTime)
    {
        if(!$dateTime){
            return;
        }
        return Carbon::createFromFormat('d/m/Y g:i A', $dateTime)->format('Y/m/d H:i:s');
    }

}

