<?php namespace Aeroparks\User\Controllers;


use BackendMenu;
use Backend;
use Backend\Classes\Controller;
use Backend\Models\User;
use Flash;
use BackendAuth;
use Validator;
use ValidationException;
use Backend\Models\AccessLog;
use System\Classes\UpdateManager;


/**
 * Auth Back-end Controller
 */
class Auth extends Controller
{
    public function onLogin()
    {
        $login = @User::where('email',post('username'))->first()->login ? : post('username');

        $user = \BackendAuth::authenticate([
            'login'     => $login,
            'password'  => post('password')
        ]);

        \BackendAuth::login($user);

        if(\BackendAuth::check())
        {
            return [
                'isLogin'   => \BackendAuth::check(),
                'user'      => $user
            ];
        }

        return false;
    }

    public function signin_onSubmit()
    {
        $validation = Validator::make(post(), [
            'login'    => 'required|between:2,255',
            'password' => 'required|between:4,255'
        ]);

        if ($validation->fails()) {
            // throw new ValidationException($validation);

            $messages = $validation->messages();
            
            foreach ($messages->all() as $message) {
                Flash::error($message);
            }

            return Backend::redirect('backend/auth/signin');
        }

        if (($remember = config('cms.backendForceRemember', true)) === null) {
            $remember = (bool) post('remember');
        }

        
        // Authenticate user
        try {
            $login = @User::where('email',post('login'))->first()->login ? : post('login');
            
            $user = \BackendAuth::authenticate([
                'login' => $login,
                'password' => post('password')
            ], $remember);

        } catch (\October\Rain\Auth\AuthException $ex) {
            Flash::error($ex->getMessage());
            return Backend::redirect('backend/auth/signin');
        }
        

        try {
            // Load version updates
            UpdateManager::instance()->update();
        }
        catch (Exception $ex) {
            Flash::error($ex->getMessage());
        }

        // Log the sign in event
        AccessLog::add($user);

        // Redirect to the intended page after successful sign in
        return Backend::redirectIntended('backend');
    }

    public function signout()
    {
        \Event::fire('backend.user.logout',BackendAuth::getUser());
        \BackendAuth::logout();
        \Session::flush();
        return \Redirect::to('/');
    }
}
