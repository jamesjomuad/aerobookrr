<?php namespace Bookrr\Rates\Controllers;

use BackendMenu;
use Backend\Classes\Controller;
use System\Classes\SettingsManager;
use Bookrr\Rates\Models\Settings as dbSettings;
use Bookrr\Rates\Models\Rate as RateModel;
use Carbon\Carbon;

/**
 * Rates Back-end Controller
 */
class Rates extends Controller
{
    use \Bookrr\General\Traits\Widgets;

    public $model;

    public $settings;

    public $pxpay;

    public $implement = [
        'Backend.Behaviors.FormController',
        'Backend.Behaviors.ListController'
    ];

    public $formConfig = 'config_form.yaml';

    public $listConfig = 'config_list.yaml';

    public function __construct()
    {
        parent::__construct();

        BackendMenu::setContext('October.System', 'system', 'settings');
        SettingsManager::setContext('Aeroparks.Rates', 'rates');

        $this->settings = dbSettings::instance();

        $this->model = new RateModel;

        $this->pxpay = \PxPay\PxPay::getSettings();
    }

    public function test()
    {
        return response()->make(view()->make('cms::error'), 403);
    }

    public function index()
    {
        $this->pageTitle = "Rates Settings";

        $this->addJs("/plugins/bookrr/rates/assets/js/fullcalendar.min.js");

        $this->addJs("/plugins/bookrr/rates/assets/js/script.js");
        
        $this->addCss("/plugins/bookrr/rates/assets/css/fullcalendar.min.css");

        $this->vars['settings'] = $this->FormWidget([
            'alias'     => 'RateSetting',
            'arrayName' => 'Rates',
            'model'     => $this->settings,
            'config'    => '$/bookrr/rates/models/settings/fields.yaml'
        ]);

        $this->vars['rateToday'] = $this->pxpay->symbol.$this->model->amount();

        $this->asExtension('ListController')->index();
    }

    public function events()
    {
        $timestamp = Carbon::createFromTimestamp(input('time'));

        $result = $this->model->thisMonth($timestamp)->get();

        $map = $result->map(function($item){
            return [
                'id' => $item->id,
                'title' => $item->name,
                'start' => Carbon::parse($item->date_start)->format('Y-m-d'), //->format('Y-m-d\TH:i'),
                'end'   => Carbon::parse($item->date_end)->addDay()->format('Y-m-d'),
                'url' => '/backend/bookrr/rates/rates/update/'.$item->id,
                'backgroundColor' => "#f1a36a",
                'borderColor' => '#ffffff'
            ];
        });

        return response()->json($map->toArray());
    }

    public function onSave()
    {
        $this->settings::set(post('Rates'));

        $this->vars['rateToday'] = $this->pxpay->symbol.$this->model->amount();
    }

    public function onCreate()
    {
        $from = Carbon::createFromTimestamp(input('start'))->format('Y-m-d');
        $to   = Carbon::createFromTimestamp(input('end'))->format('Y-m-d');

        $query = $this->model
        ->whereBetween('date_start', [$from,$to])
        ->orWhere(function($nest) use($from,$to) {
            $nest->whereBetween('date_end',[$from,$to]);
        });

        if($query->get()->count())
        {
            $name = $query->first()->name;
            \Flash::error('Date Range overlapped with '.$name.'!');
        }

        return redirect()
        ->to(url('/backend/bookrr/rates/rates/create'))
        ->with(['start'=>input('start'),'end'=>input('end')]);
    }

    public function listOverrideColumnValue($record,$columnName)
    {
        if($columnName=="rate")
        {
            return $this->pxpay->symbol . $record->rate;
        }
    }

    public function listInjectRowClass($record, $definition = null)
    {
        if($record->isCurrent())
        {
            return 'active positive';
        }
    }

    // return $this->messageBag('No range selected.','','warning');
    private function messageBag($message=null,$content=null,$type='info')
    {
        $this->vars['type'] = $type;

        $this->vars['message'] = $message;

        $this->vars['content'] = $content;

        return [
            '#message-bag' => $this->makePartial('dateband/message-bag',$this->vars)
        ];
    }

}
