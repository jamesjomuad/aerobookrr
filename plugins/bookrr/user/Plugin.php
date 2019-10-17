<?php namespace Bookrr\User;

use Backend;
use Event;
use System\Classes\PluginBase;
use Backend\Models\User as UserModel;
use Backend\Controllers\Users as UserController;
use BackendAuth;
use Bookrr\User\Models\User;
use Bookrr\User\Models\Loyalty;



class Plugin extends PluginBase
{
    use \Bookrr\General\Traits\Tool;

    public $elevated = true;

    public function pluginDetails()
    {
        return [
            'name'        => 'Aero User',
            'description' => 'Manage Aeroparks Users',
            'author'      => 'Jomuad',
            'icon'        => 'icon-car'
        ];
    }

    public function boot()
    {
        
        # Middleware
        $this->app['Illuminate\Contracts\Http\Kernel']
        ->pushMiddleware('Bookrr\User\Middleware\FrontendMiddleware');


        # Overide backend user model
        UserModel::extend(function($model){
            // Relation
            $model->hasOne = [
                'profile'  => ['Bookrr\User\Models\User'],
                'points'    => ['Bookrr\User\Models\Loyalty']
            ];

            // isCustomer method
            $model->addDynamicMethod('isCustomer',function() use($model) {
                $user = $model->profile;
                return ($model->profile && strtolower($user->type)=='customer') ? true : false;
            });

            // isActive method
            $model->addDynamicMethod('isActive',function() use($model) {
                return (@$model->profile->is_active) ? true : false;
            });
        });

        UserController::extendFormFields(function($form, $model, $context){

            if(!$model->profile){
                return;
            }

            $form->addTabFields([
                'profile[title]' => [
                    'label' => 'Title',
                    'span'  => 'auto',
                    'tab'   => 'Profile'
                ],
                'profile[phone]' => [
                    'label' => 'Phone Number',
                    'span'  => 'auto',
                    'tab'   => 'Profile'
                ],
                'profile[company]' => [
                    'label' => 'Company',
                    'span'  => 'auto',
                    'tab'   => 'Profile'
                ],
                'profile[age]' => [
                    'label' => 'Age',
                    'type'  => 'number',
                    'span'  => 'auto',
                    'tab'   => 'Profile'
                ],
                'profile[gender]' => [
                    'label' => 'Gender',
                    'span'  => 'auto',
                    'tab'   => 'Profile'
                ],
                'profile[birthdate]' => [
                    'label' => 'Birthdate',
                    'type'  => 'datetimepicker',
                    'mode'  => 'date',
                    'span'  => 'auto',
                    'tab'   => 'Profile'
                ]
            ]);
        });

        # Create/Update Loyalty Reward for customer
        Event::listen('backend.user.login', function ($user) {
            if(BackendAuth::getUser()->isCustomer())
            {
                if($user->points)
                {
                    $plusPoints = Loyalty::$loginPoints;
                    $user->points->points += $plusPoints;
                    $user->points->save();
                }
                else
                {
                    $loyalty = Loyalty::create(['points' => Loyalty::$loginPoints]);
                    $user->points()->save($loyalty);
                }
            }
        });

        # Set User as Active
        Event::listen('backend.user.login', function ($user) {
            if($user->profile)
            {
                $user->profile->is_active = true;
                $user->profile->save();
            }
        });

        #Set User as not Active
        Event::listen('backend.user.logout', function ($user) {
            if($user->profile)
            {
                $user->profile->is_active = false;
                $user->profile->save();
            }
        });
        
        Event::listen('backend.page.beforeDisplay', function ($controller, $action, $params) {
            if(BackendAuth::getUser() && BackendAuth::getUser()->isCustomer())
            {
                $controller->addCss('/plugins/bookrr/user/assets/css/user.css');  
            }
        });
    }

    public function registerPermissions()
    {
        return [
            'bookrr.users.customer' => [
                'tab' => 'Bookrr Users',
                'label' => 'Can Manage Customer.'
            ],
            'bookrr.users.staff' => [
                'tab' => 'Bookrr Users',
                'label' => 'Can Manage Staff.'
            ],
        ];
    }

    public function registerNavigation()
    {
        $navs = [
            'user' => [
                'label'       => 'Users',
                'url'         => Backend::url('bookrr/user/customer'),
                'icon'        => 'icon-users',
                'permissions' => ['bookrr.users.*'],
                'order'       => 920,

                'sideMenu' => [
                    'customer' => [
                        'label'       => 'Customers',
                        'url'         => Backend::url('bookrr/user/customer'),
                        'icon'        => 'icon-user-circle-o',
                        'permissions' => ['bookrr.users.customer'],
                    ],
                    'staff' => [
                        'label'       => 'Staff',
                        'url'         => Backend::url('bookrr/user/staff'),
                        'icon'        => 'icon-user-secret',
                        'permissions' => ['bookrr.users.staff'],
                    ],
                    // 'agent' => [
                    //     'label'       => 'Agents',
                    //     'url'         => Backend::url('bookrr/user/agent'),
                    //     'icon'        => 'icon-handshake-o',
                    //     'permissions' => ['bookrr.user.*'],
                    // ],
                    // 'affiliate' => [
                    //     'label'       => 'Affiliate',
                    //     'url'         => Backend::url('bookrr/user/affiliate'),
                    //     'icon'        => 'icon-user-plus',
                    //     'permissions' => ['bookrr.user.*'],
                    // ]
                ]
            ]
        ];

        return $this->setDefaultNav($navs,'bookrr.users');
    }
}
