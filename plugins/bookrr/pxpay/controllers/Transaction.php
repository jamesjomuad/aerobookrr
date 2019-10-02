<?php namespace Bookrr\Pxpay\Controllers;

use BackendMenu;
use \Response;
use Backend\Classes\Controller;
use Jomuad\Pxpay\Models\Transaction as Model;



class Transaction extends Controller
{
    public $implement = [
        'Backend.Behaviors.FormController',
        'Backend.Behaviors.ListController'
    ];

    public $formConfig = 'config_form.yaml';
    public $listConfig = 'config_list.yaml';

    public function __construct()
    {
        parent::__construct();

        BackendMenu::setContext('Jomuad.Pxpay', 'pxpay', 'transaction');
    }

    public function success()
    {
        $params = $this->getParams();

        Model::create([
            'amount' => $params['amount'],
            'reference' => $params['refnum'],
            'status' => 'paid',
            'result' => input('result')
        ]);

        // trace_log(input());

        return Response::view('jomuad.pxpay::success');
    }

    public function fail()
    {
        $params = $this->getParams();

        Model::create([
            'amount' => $params['amount'],
            'reference' => $params['refnum'],
            'status' => 'fail',
            'result' => input('result')
        ]);

        // trace_log(input());

        return Response::view('jomuad.pxpay::fail');
    }

    public function getParams()
    {
        return collect(explode(',',input('param')))
        ->mapWithKeys(function($v,$k){
            $arr = explode('::',$v);
            return [$arr[0] => $arr[1]];
        })->toArray();
    }

}
