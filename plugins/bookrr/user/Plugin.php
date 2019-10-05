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
                'aeroUser'  => ['Bookrr\User\Models\User'],
                'points'    => ['Bookrr\User\Models\Loyalty']
            ];

            // isCustomer method
            $model->addDynamicMethod('isCustomer',function() use($model) {
                $user = $model->aeroUser;
                return ($model->aeroUser && strtolower($user->type)=='customer') ? true : false;
            });

            // isActive method
            $model->addDynamicMethod('isActive',function() use($model) {
                return (@$model->aeroUser->is_active) ? true : false;
            });
        });

        UserController::extendFormFields(function($form, $model, $context){

            if(!$model->aeroUser){
                return;
            }

            $form->addTabFields([
                'aeroUser[title]' => [
                    'label' => 'Title',
                    'span'  => 'auto',
                    'tab'   => 'Profile'
                ],
                'aeroUser[phone]' => [
                    'label' => 'Phone Number',
                    'span'  => 'auto',
                    'tab'   => 'Profile'
                ],
                'aeroUser[company]' => [
                    'label' => 'Company',
                    'span'  => 'auto',
                    'tab'   => 'Profile'
                ],
                'aeroUser[age]' => [
                    'label' => 'Age',
                    'type'  => 'number',
                    'span'  => 'auto',
                    'tab'   => 'Profile'
                ],
                'aeroUser[gender]' => [
                    'label' => 'Gender',
                    'span'  => 'auto',
                    'tab'   => 'Profile'
                ],
                'aeroUser[birthdate]' => [
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
            if($user->aeroUser)
            {
                $user->aeroUser->is_active = true;
                $user->aeroUser->save();
            }
        });

        #Set User as not Active
        Event::listen('backend.user.logout', function ($user) {
            if($user->aeroUser)
            {
                $user->aeroUser->is_active = false;
                $user->aeroUser->save();
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
            'bookrr.user.*' => [
                'tab' => 'Bookrr',
                'label' => 'Manage Aeropark Users.'
            ],
        ];
    }

    public function registerNavigation()
    {
        if(BackendAuth::getUser()->isCustomer())
        {
            return [
                'loyalty' => [
                    'label' => 'Rewards',
                    'url'   => Backend::url('bookrr/user/loyalty'),
                    'icon'  => 'icon-star',
                    'order' => 1000
                ]
            ];
        }


        return [
            'user' => [
                'label'       => 'Users',
                'url'         => Backend::url('bookrr/user/customer'),
                'icon'        => 'icon-users',
                'permissions' => ['bookrr.user.*'],
                'order'       => 920,

                'sideMenu' => [
                    'customer' => [
                        'label'       => 'Customers',
                        'url'         => Backend::url('bookrr/user/customer'),
                        'icon'        => 'icon-user-circle-o',
                        'permissions' => ['bookrr.user.*'],
                    ],
                    'staff' => [
                        'label'       => 'Staff',
                        'url'         => Backend::url('bookrr/user/staff'),
                        'icon'        => 'icon-user-secret',
                        'permissions' => ['bookrr.user.*'],
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
                    // ],
                    // 'loyalty' => [
                    //     'label'       => 'Loyalty',
                    //     'url'         => Backend::url('bookrr/user/loyalty'),
                    //     'icon'        => 'icon-star',
                    //     'permissions' => ['bookrr.user.*'],
                    // ]
                ]
            ]
        ];
    }
}
