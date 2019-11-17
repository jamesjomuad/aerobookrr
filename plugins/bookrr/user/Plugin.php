<?php namespace Bookrr\User;

use Backend;
use System\Classes\PluginBase;
use Backend\Models\User as UserModel;
use Backend\Controllers\Users as UserController;
use BackendAuth;


class Plugin extends PluginBase
{

    public function pluginDetails()
    {
        return [
            'name'        => 'user',
            'description' => 'No description provided yet...',
            'author'      => 'bookrr',
            'icon'        => 'icon-leaf'
        ];
    }

    public function register()
    {

    }

    public function boot()
    {
        # Extend User
        UserModel::extend(function($model){
            # Extend Relations
            $model->hasOne['customer']  = [
                'Bookrr\User\Models\Customer',
                'delete' => true
            ];
            $model->hasOne['staff']  = [
                'Bookrr\Keeprr\Models\Staff',
                'delete' => true
            ];

            # Extend Mehod
            $model->addDynamicMethod('isCustomer',function() use($model) {
                $user = $model->aeroUser;
                return ($model->aeroUser && strtolower($user->type)=='customer') ? true : false;
            });
        });
    }

    public function registerComponents()
    {
        return []; // Remove this line to activate

        return [
            'Bookrr\User\Components\MyComponent' => 'myComponent',
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

    // public function registerNavigation()
    // {
    //     return []; // Remove this line to activate

    //     return [
    //         'user' => [
    //             'label'       => 'user',
    //             'url'         => Backend::url('bookrr/user/mycontroller'),
    //             'icon'        => 'icon-leaf',
    //             'permissions' => ['bookrr.user.*'],
    //             'order'       => 500,
    //         ],
    //     ];
    // }

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
                    ]
                ]
            ]
        ];
    }
}
