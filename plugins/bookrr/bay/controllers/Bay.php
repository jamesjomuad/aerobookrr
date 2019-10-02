<?php namespace Bookrr\Bay\Controllers;

use BackendMenu;
use Backend\Classes\Controller;
use System\Classes\SettingsManager;
use Bookrr\Bay\Models\Bay as BayModel;



/**
 * Bay Back-end Controller
 */
class Bay extends Controller
{
    public $model;

    public $requiredPermissions = [
        'bookrr.bay'
    ];

    public $implement = [
        'Backend.Behaviors.FormController',
        'Backend.Behaviors.ListController'
    ];

    public $formConfig = 'config_form.yaml';
    public $listConfig = [
        'bay'   => 'config_bay_list.yaml',
        'zone'  => 'config_zone_list.yaml'
    ];

    public function __construct()
    {
        parent::__construct();

        BackendMenu::setContext('October.System', 'system', 'setting');
        SettingsManager::setContext('Bookrr.Bay', 'bookrr.bay');

        $this->model = new BayModel;
    }

    public function index()
    {   
        $this->asExtension('ListController')->index();
    }

    public function test()
    {
        dd(
            $this->model->where('status','occupied')->update(['status'=>null])
        );
    }
 
}
