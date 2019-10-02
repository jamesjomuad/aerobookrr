<?php namespace Bookrr\User\Components;

use Cms\Classes\ComponentBase;
use Cms\Classes\Page;
use BackendAuth;
use Response;
use View;
use Backend\Models\User;


class Login extends ComponentBase
{
    public function componentDetails()
    {
        return [
            'name'        => 'Authenticate Page',
            'description' => 'Login screen for backend user.'
        ];
    }

    public function defineProperties()
    {
        return [
            'isLoginPage' => [
                'title'     => 'Set page as Login?',
                'type'      => 'dropdown',
                'default'   => 'no',
                'options'   => ['no'=>'No', 'yes'=>'Yes']
            ],
            'loginPage' => [
                'title'     => 'Login page.',
                'type'      => 'dropdown',
                'default'   => null,
                'depends'   => ['isLoginPage']
            ],
            'redirectPage' => [
                'title'     => 'Redirect when Logged in.',
                'type'      => 'dropdown',
                'default'   => '/',
                'depends'   => ['isLoginPage']
            ],
        ];
    }

    public function getLoginPageOptions()
    {
        if(post('isLoginPage')=="yes")
        {
            return null;
        }
        
        $array = Page::sortBy('baseFileName')->lists('baseFileName', 'baseFileName');
        array_unshift($array, 'none');
        return $array;
    }

    public function getRedirectPageOptions()
    {   
        if(post('isLoginPage')=="yes")
        {
            return null;
        }
        $array = Page::sortBy('baseFileName')->lists('baseFileName', 'baseFileName');
        array_unshift($array, 'none');
        return $array;
    }

    public function getUrlLogoutOptions()
    {
        return Page::sortBy('baseFileName')->lists('baseFileName', 'baseFileName');
    }

    public function onRun()
    {
        if($this->property('isLoginPage')=='yes' AND !BackendAuth::check())
        {
            $this->prepareAssets();
            return;
        }
        else if($this->property('isLoginPage')=='yes' AND BackendAuth::check())
        {
            return redirect()->to(url($this->property('redirectPage')));
        }
        else if(BackendAuth::check())
        {
            return;
        }
        else if(!BackendAuth::check() AND $this->property('isLoginPage')=='no')
        {
            return redirect()->to(url($this->property('loginPage')));
        }
        else if($this->property('loginPage') == '/')
        {
            $this->prepareAssets();
            return;
        }
        
        return response()->make('Access denied!', 403);
    }

    public function prepareAssets()
    {
        $this->addCss('/plugins/bookrr/user/assets/css/login-comp.css');
        $this->addCss('//maxcdn.bootstrapcdn.com/bootstrap/4.0.0/css/bootstrap.min.css');
        $this->addJs('//netdna.bootstrapcdn.com/bootstrap/3.1.0/js/bootstrap.min.js');
    }

    public function onSignin()
    {
        $login = @User::where('email',post('username'))->first()->login ? : post('username');
        
        $user = BackendAuth::authenticate([
            'login' => $login,
            'password' => post('password')
        ]);

        if(BackendAuth::check())
        {
            return redirect()->to(url($this->property('redirectPage')));
        }
    }

    public function loginForm()
    {
        $content = $this->renderPartial('default.htm');
        return Response::make($content)->header('Content-Type', 'text/xml');
    }
}
