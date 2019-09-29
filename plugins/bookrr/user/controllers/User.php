<?php namespace Bookrr\User\Controllers;

use BackendMenu;
use Backend\Classes\Controller;
use Bookrr\User\Models\UserModel;


class User extends Controller
{
    public $requiredPermissions = [
        'aeroparks.user.*'
    ];

    public $implement = [
        'Backend.Behaviors.FormController',
        'Backend.Behaviors.ListController'
    ];

    public $formConfig = 'config_form.yaml';
    public $listConfig = 'config_list.yaml';

    public function __construct()
    {
        parent::__construct();

        BackendMenu::setContext('Aeroparks.User', 'user', 'user');
    }

    public function onCreate()
    {
        $data = post();
        
        $rules = [
            'type'          => 'required',
            'first_name'    => 'required',
            'last_name'     => 'required',
            'login'         => 'required',
            'email'         => 'required|email'
        ];

        $validation = Validator::make($data, $rules);

        if ($validation->fails()) {
            throw new ValidationException($validation);
        }


    }

}
