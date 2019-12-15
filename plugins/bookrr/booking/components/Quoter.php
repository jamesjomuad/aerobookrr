<?php namespace Bookrr\Booking\Components;

use Cms\Classes\ComponentBase;
use Flash;
use Redirect;
use Session;
use Validator;
use ValidationException;
use Bookrr\Rates\Models\Rate;
use Bookrr\Stripe\Controllers\Cashier;



class Quoter extends ComponentBase
{
    public function componentDetails()
    {
        return [
            'name'        => 'Quoter Component',
            'description' => 'Get a computation.'
        ];
    }

    public function defineProperties()
    {
        return [
            'departLabel' => [
                'title'       => 'Departure Label',
                'description' => 'Label',
                'default'     => 'Flight Departure Date',
                'type'        => 'string',
            ],
            'arrivalLabel' => [
                'title'       => 'Arrival Label',
                'description' => 'Label',
                'default'     => 'Flight Arrival Date',
                'type'        => 'string',
            ],
        ];
    }

    public function onRun()
    {
        $this->addCss('/plugins/bookrr/booking/assets/css/datetimepicker.css','v1.0');
        $this->addJs('/plugins/bookrr/booking/assets/js/datetimepicker.js','v1.0');
        $this->addJs('/plugins/bookrr/booking/assets/js/comp.quoter.js','v1.1');

        $this->page['rate'] = Cashier::config()->symbol.Rate::amount();
    }

    public function onGetQuote()
    {
        // Validate
        $validator = Validator::make(input(), [
            'dateIn'  => 'required',
            'dateOut' => 'required',
        ],[
            'dateIn.required' => 'Required Start date and time!',
            'dateOut.required' => 'Required End date and time!'
        ]);
        
        // Check point
        if ($validator->fails()) {
            foreach ($validator->messages()->all() as $message) {
                Flash::error($message);
            }
            throw new ValidationException($validator);
        }

        $start = (new \Carbon\Carbon())->parse(input('dateIn'));
        $end   = (new \Carbon\Carbon())->parse(input('dateOut'));
        $diff  = $start->diffInHours($end);
        $symbol = Cashier::config()->symbol;
        $amount  = $symbol.number_format($diff*Rate::amount(),2);
        

        $this->page['amount'] = $amount;

        return [
            '#qresult' => $this->renderPartial('@result')
        ];
    }
}
