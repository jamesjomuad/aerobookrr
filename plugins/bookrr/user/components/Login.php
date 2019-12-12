<?php namespace Bookrr\User\Components;

use Cms\Classes\ComponentBase;
use Flash;
use BackendAuth;
use Backend\Models\User;
use Redirect;
use Session;


class Login extends ComponentBase
{
    public $name;

    public function componentDetails()
    {
        return [
            'name'        => 'Login Component',
            'description' => 'Bookrr login form.'
        ];
    }

    public function defineProperties()
    {
        return [
            'loginLabel' => [
                'title'       => 'Login Label',
                'description' => 'Button label.',
                'default'     => 'Login',
                'type'        => 'string',
            ],
            'logoutLabel' => [
                'title'       => 'Logout Label',
                'description' => 'Button label.',
                'default'     => 'Logout',
                'type'        => 'string',
            ]
        ];
    }

    public function onRun()
    {
        $this->addJs('/plugins/bookrr/user/assets/js/ajaxUtils.js');
        $this->addJs('/plugins/bookrr/user/assets/js/ajaxPopup.js');

        $this->page['isLogin'] = BackendAuth::check();
    }

    public function onLoginForm()
    {
        if(BackendAuth::check())
        {
            Flash::success('Currently login.');
            return;
        }

        return [
            'popup' => $this->renderPartial('@modal-form.htm')
        ];
    }

    public function onLogin()
    {
        if(BackendAuth::check())
        {
            Flash::success('Currently login.');
            return;
        }

        // Authenticate user by credentials
        $user = BackendAuth::authenticate([
            'login'     => @User::where('email',post('login'))->first()->login ?? post('login'),
            'password'  => post('password')
        ]);

        // Sign in as a specific user
        BackendAuth::login($user);

        if(BackendAuth::check())
        {
            Flash::success('Login Successful!');
            return Redirect::to('/backend');;
        }

        Flash::error('Login Failed!');
    }

    public function onLogout()
    {
        \BackendAuth::logout();
        \Session::flush();
        return \Redirect::to('/');
    }
}