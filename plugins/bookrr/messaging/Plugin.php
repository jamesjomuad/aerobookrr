<?php namespace Aeroparks\Messaging;

use Backend;
use System\Classes\PluginBase;



class Plugin extends PluginBase
{

    public function pluginDetails()
    {
        return [
            'name'        => 'messaging',
            'description' => 'No description provided yet...',
            'author'      => 'aeroparks',
            'icon'        => 'icon-leaf'
        ];
    }

    public function registerComponents()
    {
        return []; // Remove this line to activate

        return [
            'Aeroparks\Messaging\Components\MyComponent' => 'myComponent',
        ];
    }

    public function registerPermissions()
    {
        return [
            'aeroparks.messaging.*' => [
                'tab' => 'Aeroparks',
                'label' => 'Messaging'
            ],
        ];
    }

    public function registerNavigation()
    {
        return [
            'messaging' => [
                'label'       => 'Messaging',
                'url'         => Backend::url('aeroparks/messaging/txt'),
                'icon'        => 'fa fa-comment-dots',
                'permissions' => ['aeroparks.messaging.*'],
                'order'       => 925,

                'sideMenu' => [
                    'txt' => [
                        'label'       => 'Txt',
                        'url'         => Backend::url('aeroparks/messaging/txt'),
                        'icon'        => 'fa fa-sms',
                        'permissions' => ['aeroparks.messaging.*'],
                    ],
                    'mail' => [
                        'label'       => 'Mail',
                        'url'         => Backend::url('aeroparks/messaging/mail'),
                        'icon'        => 'fa fa-envelope',
                        'permissions' => ['aeroparks.messaging.*'],
                    ],
                    'contact' => [
                        'label'       => 'Contacts',
                        'url'         => Backend::url('aeroparks/messaging/contact'),
                        'icon'        => 'fa fa-address-book',
                        'permissions' => ['aeroparks.messaging.*'],
                    ],
                ]
            ],
        ];
    }

}
