<?php namespace Aeroparks\Report;

use Backend;
use System\Classes\PluginBase;


class Plugin extends PluginBase
{

    public function pluginDetails()
    {
        return [
            'name'        => 'Aero Reports',
            'description' => 'Generate customizable reports.',
            'author'      => 'Jomuad',
            'icon'        => 'icon-leaf'
        ];
    }
    
    public function registerPermissions()
    {
        return [
            'aeroparks.report.*' => [
                'tab' => 'Aeropark Reports',
                'label' => 'Manage and process Reports'
            ],
            'aeroparks.report.read' => [
                'tab' => 'Aeropark Reports',
                'label' => 'Can View reports',
                'order' => 202,
            ]
        ];
    }

    public function registerNavigation()
    {
        return [
            'report' => [
                'label'       => 'Reports',
                'url'         => Backend::url('aeroparks/report/bay'),
                'icon'        => 'icon-area-chart',
                'permissions' => ['aeroparks.report.*'],
                'order'       => 915,

                'sideMenu' => [
                    'bay' => [
                        'label'       => 'Bay',
                        'url'         => Backend::url('aeroparks/report/bay'),
                        'icon'        => 'fa fa-chart-area',
                        'permissions' => ['aeroparks.report.*'],
                    ],
                    'insight' => [
                        'label'       => 'Insights',
                        'url'         => Backend::url('aeroparks/report/insight'),
                        'icon'        => 'fa fa-chart-pie',
                        'permissions' => ['aeroparks.report.*'],
                    ],
                    'revenue' => [
                        'label'       => 'Revenue',
                        'url'         => Backend::url('aeroparks/report/revenue'),
                        'icon'        => 'fa fa-chart-bar',
                        'permissions' => ['aeroparks.report.*'],
                    ],
                    'driver' => [
                        'label'       => "Driver's Manifest",
                        'url'         => Backend::url('aeroparks/report/driver'),
                        'icon'        => 'icon-bar-chart',
                        'permissions' => ['aeroparks.report.*'],
                    ],
                    'booking' => [
                        'label'       => 'Bookings',
                        'url'         => Backend::url('aeroparks/report/booking'),
                        'icon'        => 'fa fa-chart-line',
                        'permissions' => ['aeroparks.report.*'],
                    ],
                    'customer' => [
                        'label'       => 'Customers',
                        'url'         => Backend::url('aeroparks/report/customer'),
                        'icon'        => 'fa fa-chart-bar',
                        'permissions' => ['aeroparks.report.*'],
                    ],
                    'vehicle' => [
                        'label'       => 'Vehicles',
                        'url'         => Backend::url('aeroparks/report/vehicle'),
                        'icon'        => 'fa fa-chart-area',
                        'permissions' => ['aeroparks.report.*'],
                    ],
                    'contact' => [
                        'label'       => 'Contacts',
                        'url'         => Backend::url('aeroparks/report/contact'),
                        'icon'        => 'icon-bar-chart',
                        'permissions' => ['aeroparks.report.*'],
                    ],
                ]
            ],
        ];
    }
}
