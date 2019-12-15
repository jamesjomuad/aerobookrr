<?php namespace Bookrr\User;

use Backend;
use System\Classes\PluginBase;
use Backend\Models\User as UserModel;
use Backend\Controllers\Users as UserController;
use BackendAuth;
use Event;


class Plugin extends PluginBase
{

    public function pluginDetails()
    {
        return [
            'name'        => 'Bookrr User',
            'description' => 'Bookrr extended user fields.',
            'author'      => 'bookrr',
            'icon'        => 'icon-leaf'
        ];
    }

    public function boot()
    {
        # Extend User
        UserModel::extend(function($model){
            # Extend Relations
            $model->hasOne['customer']  = [
                '\Bookrr\User\Models\Customers',
                'delete' => true
            ];
            $model->hasOne['staff']  = [
                '\Bookrr\User\Models\Staff',
                'delete' => true
            ];
            $model->hasMany['vehicles']  = [
                '\Bookrr\User\Models\Vehicle',
                'delete' => true
            ];

            # Extend Mehod
            $model->addDynamicMethod('isCustomer',function() use($model) {
                return $model->role->code=='customer' ? true : false;
            });

            return $model;
        });

        # Extend User fields
        UserController::extendFormFields(function($form, $model, $context){

            if(BackendAuth::getUser()->isCustomer())
            {
                $form->addTabFields([
                    'customer[phone]' => [
                        'label' => 'Phone Number',
                        'span'  => 'auto',
                        'tab'   => 'Profile'
                    ],
                    'customer[address]' => [
                        'label' => 'Company',
                        'span'  => 'auto',
                        'tab'   => 'Profile'
                    ],
                    'customer[gender]' => [
                        'label' => 'Gender',
                        'type'  => 'dropdown',
                        'span'  => 'auto',
                        'tab'   => 'Profile',
                        'emptyOption' => 'None',
                        'options'=> [
                            'male' => 'Male',
                            'female' => 'Female'
                        ]
                    ],
                    'customer[birth]' => [
                        'label' => 'Birthdate',
                        'type'  => 'datetimepicker',
                        'mode'  => 'date',
                        'span'  => 'auto',
                        'tab'   => 'Profile'
                    ]
                ]);
            }
            
        });
        
        # Event
        Event::listen('backend.page.beforeDisplay', function($controller, $action, $params) {

            if(BackendAuth::getUser()->isCustomer())
            {
                $controller->addCss('/plugins/bookrr/user/assets/css/customer.css');
            }

        });
    }

    public function registerComponents()
    {
        return [
            'Bookrr\User\Components\Register' => 'Register',
            'Bookrr\User\Components\Login'    => 'Login'
        ];
    }

    public function registerPermissions()
    {
        return []; // Remove this line to activate

        return [
            'bookrr.user.some_permission' => [
                'tab' => 'user',
                'label' => 'Some permission'
            ],
        ];
    }

    public function registerNavigation()
    {
        if(BackendAuth::getUser()->isCustomer())
        {
            return [
                'user' => [
                    'label' => 'Vehicles',
                    'url'   => Backend::url('bookrr/user/vehicle'),
                    'icon'  => 'fa fa-car',
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
                    ]
                ]
            ]
        ];
    }
}
