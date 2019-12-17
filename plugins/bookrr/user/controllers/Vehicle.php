<?php namespace Bookrr\User\Controllers;

use BackendMenu;
use Backend\Classes\Controller;
use Config;
use \GuzzleHttp\Client;

/**
 * Vehicle Back-end Controller
 */
class Vehicle extends Controller
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

        BackendMenu::setContext('Bookrr.User', 'user', 'vehicle');
    }

    public function create()
    {
        // $client = new Client();
        // $client->get('https://test.carjam.co.nz/a/vehicle:abcd?key=CE8130C5D3C82C035B493852F37BD96E6EA1E4EA&plate=asd');
        // dd($client);

        return $this->asExtension('FormController')->create();
    }


    # Overiders
    public function listExtendQuery($query)
    {
        if($this->user->isCustomer())
        {
            $query->where('user_id',$this->user->id);
        }

        return $query;
    }

    public function formAfterCreate($model)
    {
        # Vehicle should belong to Customer
        if($this->user->isCustomer())
        {
            $model->user_id = $this->user->id;

            if(!$this->user->vehicles()->hasDefault())
            {
                $model->primary = 1;
            }

            $model->save();
        }
    }

}
