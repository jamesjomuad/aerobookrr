<?php namespace Bookrr\Report\Controllers;

use BackendMenu;
use Backend\Classes\Controller;
use Bookrr\Report\Controllers\BaseController;

/**
 * Customer Report Back-end Controller
 */
class Customer extends BaseController
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

        BackendMenu::setContext('Aeroparks.Report', 'report', 'customer');
        // dd($this->widget->list->model);
    }


    public function listExtendQuery($query)
    {
        // Extend Model
        $this->widget->list->model->extend(function($model) {
            
            // Count Customer Times Parked
            $model->addDynamicMethod('getCountParksAttribute', function() use ($model)
            {
                return $model->bookings->where('status','checkout')->count();
            });

        });

        return $query;
    }

}
