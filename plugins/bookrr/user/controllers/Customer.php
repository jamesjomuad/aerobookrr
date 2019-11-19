<?php namespace Bookrr\User\Controllers;

use BackendMenu;
use Backend\Classes\Controller;
use Backend\Models\User;
use Backend\Models\UserRole;
use \Carbon\Carbon;
use October\Rain\Exception\ApplicationException;



class Customer extends Controller
{
    public $implement = [
        'Backend.Behaviors.FormController',
        'Backend.Behaviors.ListController',
        'Backend.Behaviors.RelationController'
    ];

    public $formConfig = 'config_form.yaml';
    public $listConfig = 'config_list.yaml';
    public $relationConfig = 'config_relation.yaml';

    public function __construct()
    {
        parent::__construct();

        BackendMenu::setContext('Bookrr.User', 'user', 'customer');
    }

    public function formExtendModel($model)
    {
        if(UserRole::where('code','customer')->first()==NULL)
        {
            throw new ApplicationException('Customer Role not found! Please Create it!');
        }

        # Init proxy field model if we are creating the model
        if ($this->action == 'create') {
            $model->user = new User;
            $model->user->role()->add(UserRole::where('code','customer')->first());
        }
        return $model;
    }

    public function listFormatDate($value)
    {   
        $dateTime   = (new Carbon())->parse($value)->format('d/m/y (h:i A)');
        $diffHuman  = (new Carbon($value))->diffForHumans();
        $class      = str_contains($diffHuman,'ago') ? 'default' : 'primary';
        return [
            $dateTime,
            $diffHuman,
            $class
        ];
    }
}