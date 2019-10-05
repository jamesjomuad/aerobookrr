<?php namespace Bookrr\General\ReportWidgets;

use Backend\Classes\ReportWidgetBase;
use Bookrr\Booking\Models\Parking;
use Bookrr\User\Models\Vehicle;
use Carbon\Carbon;
use Flash;


class Finder extends ReportWidgetBase
{

    protected $defaultAlias = 'bookrr_general_finder';

    public function render()
    {
        return $this->makePartial('widget');
    }
    
    public function defineProperties()
    {
        return [
            'title' => [
                'title'             => 'Widget title',
                'default'           => 'Finder',
                'type'              => 'string',
                'validationPattern' => '^.+$',
                'validationMessage' => 'The Widget Title is required.'
            ]
        ];
    }

    public function loadAssets()
    {
        $this->addCss('css/style.css', 'v2.5');
        $this->addJs('js/finder.js', 'v2.5');
    }

    /*
    *   EVENT HANDLER
    */
    public function onBarcodeForm()
    {
        $model = $this->getBookingModel();

        if($model->count()==false)
        {
            \Flash::info('Not Found!');
            return;
        }
        else if($model->count())
        {
            $this->vars['model'] = $model->first();

            $this->vars['formWidget'] = $this->createFormWidget([
                'alias'     => 'barcodeForm',
                'arrayName' => 'Barcode',
                'model'     => $this->vars['model'],
                'config'    => '$/bookrr/general/reportwidgets/finder/fields.yaml'
            ]);

            return $this->makePartial('barcode');
        }

        \Flash::eroor('Something went wrong!');
    }

    public function onBookingForm()
    {
        $model = $this->getBookingModel()->first();

        if($model==null)
        {
            $this->vars['message'] = 'Oops! Record not Found!';
            return $this->makePartial('404');
        }
        else if($model->customer==null)
        {
            $this->vars['message'] = 'Booking has no Customer relation!';
            $this->vars['class'] = 'info';
            return $this->makePartial('404');
        }

        $this->vars['model'] = $model;

        $this->vars['bookNum'] = preg_replace('/\s+/', '', post('booking-num'));

        $this->vars['time'] = Carbon::now()->format('Y-m-d H:i:s');

        $this->vars['formWidget'] = $this->createFormWidget([
            'alias'     => 'checkinForm',
            'arrayName' => 'CheckIn',
            'model'     => $this->vars['model'],
            'config'    => '$/bookrr/general/reportwidgets/finder/fields.yaml'
        ]);

        return $this->makePartial('booking');
    }

    public function onCheckInForm()
    {
        $model = $this->getBookingModel()->first();

        if($model==null)
        {
            $this->vars['message'] = 'Oops! Record not Found!';
            return $this->makePartial('404');
        }
        else if($model->customer==null)
        {
            $this->vars['message'] = 'Booking has no Customer relation!';
            $this->vars['class'] = 'info';
            return $this->makePartial('404');
        }
        else if(strtolower($model->status) == 'parked')
        {
            $this->vars['message'] = 'Already Parked!';
            $this->vars['class'] = 'info';
            return $this->makePartial('404');
        }
        
        $this->vars['model'] = $model;

        $this->vars['bookNum'] = preg_replace('/\s+/', '', post('booking-num'));

        $this->vars['time'] = Carbon::now()->format('Y-m-d H:i:s');

        $this->vars['formWidget'] = $this->createFormWidget([
            'alias'     => 'checkinForm',
            'arrayName' => 'CheckIn',
            'model'     => $this->vars['model'],
            'config'    => '$/bookrr/general/reportwidgets/finder/fields_checkin.yaml'
        ]);

        return $this->makePartial('checkin');
    }

    public function onPlateNoForm()
    {
        $model = Vehicle::where('plate','=',post('plate'))->first()->booking;

        if($model==null)
        {
            $this->vars['message'] = 'Oops! Record not Found!';
            return $this->makePartial('404');
        }
        else if($model->customer==null)
        {
            $this->vars['message'] = 'Booking has no Customer relation!';
            $this->vars['class'] = 'info';
            return $this->makePartial('404');
        }

        $this->vars['model'] = $model;

        $this->vars['bookNum'] = preg_replace('/\s+/', '', post('booking-num'));

        $this->vars['time'] = Carbon::now()->format('Y-m-d H:i:s');

        $this->vars['formWidget'] = $this->createFormWidget([
            'alias'     => 'checkinForm',
            'arrayName' => 'CheckIn',
            'model'     => $this->vars['model'],
            'config'    => '$/bookrr/general/reportwidgets/finder/fields.yaml'
        ]);

        return $this->makePartial('booking');
    }

    public function onSetCheckIn()
    {
        $model = $this->getBookingModel()->first();

        $this->vars['model'] = $model;

        if($model AND strtolower($model->status)!='parked')
        {
            $model->status = 'parked';

            $model->park_in = Carbon::now()->format('Y-m-d H:i:s');

            if($model->save())
            {
                \Flash::success('Successfully Check In!');
                return [
                    '.modal-dialog .modal-content' => $this->onBookingForm()
                ];
            }
        }
        else if(strtolower($model->status)=='parked')
        {
            \Flash::info('Already Parked!');
        }
        else
        {
            \Flash::error('Oops! Something went wrong!');
        }
    }

    public function onCheckOutForm()
    {
        $model = $this->getBookingModel()->first();

        if($model==null)
        {
            $this->vars['message'] = 'Oops! Record not Found!';
            return $this->makePartial('404');
        }
        else if(strtolower($model->status) == 'pending')
        {
            $this->vars['message'] = 'Need to check in first!';
            $this->vars['class'] = 'info';
            return $this->makePartial('404');
        }
        else if(!$model->user_id)
        {
            $this->vars['message'] = 'Booking has no Customer relation!';
            $this->vars['class'] = 'info';
            return $this->makePartial('404');
        }

        if($model)
        {
            $this->vars['model'] = $model;

            $this->vars['formWidget'] = $this->createFormWidget([
                'alias'     => 'checkoutForm',
                'arrayName' => 'CheckOut',
                'model'     => $this->vars['model'],
                'config'    => '$/bookrr/general/reportwidgets/finder/fields.yaml'
            ]);

            return $this->makePartial('checkout');
        }

        \Flash::error('Oops! Something went wrong!');
    }

    public function onSetCheckOut()
    {
        $model = $this->getBookingModel()->first();

        if($model AND strtolower($model->status)=='parked')
        {
            $model->status = 'Parked';

            $model->park_out = Carbon::now()->format('Y-m-d H:i:s');

            if($model->save())
            {
                \Flash::success('Successfully Check Out!');
                return;
            }
        }
        
        \Flash::error('Oops! Something went wrong!');
    }

    public function onAddTag()
    {
        $this->vars['value'] = $this;
        return $this->makePartial('tagger');
    }

    public function onSaveTag()
    {
        $model = Parking::where('number',post('booking.number'))->first();

        $model->barcode = post('booking.barcode');

        if($model->save())
        {
            \Flash::success('Tag saved!');
            return;
        }

        \Flash::error('Oops, something went wrong!');
    }

    /*
    *   PROTECTED METHODS
    */
    protected function createFormWidget($options)
    {
        $config = is_string($options['config']) ? $this->makeConfig($options['config']) : $options['config'];

        $config->model = $options['model'];

        $config->alias = $options['alias'];

        $config->arrayName = $options['arrayName'];

        $widget = $this->makeWidget('Backend\Widgets\Form', $config);

        $widget->bindToController();

        return $widget;
    }

    protected function getBookingModel()
    {
        
        if($number = post('booking.number_in') ? : post('booking.number_out'))
        {
            if($model = Parking::where('number',$number))
            {
                return $model;
            }
        } 
        else if($code = post('booking.barcode'))
        {
            if($model = Parking::where('barcode',$code))
            {
                return $model;
            }
        }
        else if($number = post('booking.number'))
        {
            if($model = Parking::where('number',$number))
            {
                return $model;
            }
        }
        
        return null;
    }

    protected function getPendingModel()
    {
        $number = post('booking-num') ? : post('bookNum');

        $model = Parking::has('customer')
            ->where('number',$number)
            ->where('status','pending')
            ->first()
        ;

        return $model;
    }

    protected function debug($v)
    {
        $this->vars['value'] = $v;
        return $this->makePartial('debug');
    }

}
