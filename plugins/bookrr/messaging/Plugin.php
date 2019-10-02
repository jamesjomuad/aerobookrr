<?php namespace Bookrr\Messaging;

use Backend;
use System\Classes\PluginBase;



class Plugin extends PluginBase
{

    public function pluginDetails()
    {
        return [
            'name'        => 'messaging',
            'description' => 'No description provided yet...',
            'author'      => 'bookrr',
            'icon'        => 'icon-leaf'
        ];
    }

    public function registerComponents()
    {
        return []; // Remove this line to activate

        return [
            'Bookrr\Messaging\Components\MyComponent' => 'myComponent',
        ];
    }

    public function registerPermissions()
    {
        return [
            'bookrr.messaging.*' => [
                'tab' => 'Bookrr',
                'label' => 'Messaging'
            ],
        ];
    }

    public function registerNavigation()
    {
        return [
            'messaging' => [
                'label'       => 'Messaging',
                'url'         => Backend::url('bookrr/messaging/txt'),
                'icon'        => 'fa fa-comment-dots',
                'permissions' => ['bookrr.messaging.*'],
                'order'       => 925,

                'sideMenu' => [
                    'txt' => [
                        'label'       => 'Txt',
                        'url'         => Backend::url('bookrr/messaging/txt'),
                        'icon'        => 'fa fa-sms',
                        'permissions' => ['bookrr.messaging.*'],
                    ],
                    'mail' => [
                        'label'       => 'Mail',
                        'url'         => Backend::url('bookrr/messaging/mail'),
                        'icon'        => 'fa fa-envelope',
                        'permissions' => ['bookrr.messaging.*'],
                    ],
                    'contact' => [
                        'label'       => 'Contacts',
                        'url'         => Backend::url('bookrr/messaging/contact'),
                        'icon'        => 'fa fa-address-book',
                        'permissions' => ['bookrr.messaging.*'],
                    ],
                ]
            ],
        ];
    }

}
