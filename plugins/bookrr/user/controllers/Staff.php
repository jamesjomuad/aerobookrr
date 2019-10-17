<?php namespace Bookrr\User\Controllers;

use BackendMenu;
use Backend\Models\UserRole;
use Backend\Classes\Controller;
use Bookrr\User\Models\BaseUser;
use Bookrr\User\Models\User;
use Bookrr\User\Models\Staff as StaffModel;
use Validator;
use ValidationException;
use Carbon\Carbon;
use BackendAuth;




class Staff extends Controller
{
    public $requiredPermissions = ['bookrr.users.staff'];

    public $implement = [
        'Backend.Behaviors.FormController',
        'Backend.Behaviors.ListController'
    ];

    public $formConfig = 'config_form.yaml';
    public $listConfig = 'config_list.yaml';

    public function __construct()
    {
        parent::__construct();

        BackendMenu::setContext('Bookrr.User', 'user', 'staff');
    }

    public function create()
    {
        $this->pageTitle = 'Create Staff';

        $config = $this->makeConfig('$/bookrr/user/models/staff/fields.yaml');

        $config->model = new StaffModel;

        $widget = $this->makeWidget('Backend\Widgets\Form',$config);

        $this->vars['widget'] = $widget;
    }

    public function onCreate()
    {
        $data = post();

        $data['birthdate'] = Carbon::parse($data['birthdate']);

        $data['backendUser']['role_id'] = StaffModel::roleID();

        $data['backendUser']['login'] = post('login');

        $validation = Validator::make($data, [
            'backendUser.first_name'    => 'required',
            'backendUser.last_name'     => 'required',
            'backendUser.login'         => 'required',
            'backendUser.email'         => 'required|email'
        ]);

        if ($validation->fails())
        {
            throw new ValidationException($validation);
        }   

        $backendUser = BackendAuth::register($data['backendUser']);

        $backendUser->role_id = $this->getRoleId();

        $backendUser->save();

        $backendUser->profile()->save(User::create($data));

        if(post('close'))
        return \Redirect::to('/backend/bookrr/user/staff');

        \Flash::success('User Successfully Created!');
    }

    public function onAssignCodeForm($id)
    {
        $this->vars['model'] = StaffModel::find($id);
        
        return $this->makePartial('code_form');
    }

    public function onSaveCode($id)
    {
        $model = StaffModel::find($id);

        $model->code = post('code');

        if($model->save())
        {
            \Flash::success('Code saved!');
        }
    }

    public function getRoleId()
    {
        if($role = UserRole::where('code','staff')->first())
        {
            return $role->id;
        }
        return null;
    }

}