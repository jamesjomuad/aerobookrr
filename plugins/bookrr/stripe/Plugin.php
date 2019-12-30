<?php namespace Bookrr\Stripe;

use Backend;
use System\Classes\PluginBase;
use Bookrr\Booking\Controllers\Parking;

/**
 * stripe Plugin Information File
 */
class Plugin extends PluginBase
{

    public function pluginDetails()
    {
        return [
            'name'        => 'Stripe',
            'description' => 'No description provided yet...',
            'author'      => 'bookrr',
            'icon'        => 'icon-leaf'
        ];
    }

    public function registerPermissions()
    {
        return []; // Remove this line to activate

        return [
            'bookrr.stripe.some_permission' => [
                'tab' => 'stripe',
                'label' => 'Some permission'
            ],
        ];
    }

    public function registerNavigation()
    {
        return [
            'stripe' => [
                'label'       => 'Transactions',
                'url'         => Backend::url('bookrr/stripe/transactions'),
                'icon'        => 'icon-table',
                'permissions' => ['bookrr.stripe.*'],
                'order'       => 914,
            ],
        ];
    }

    public function registerSettings()
    {
        return [
            'stripe.settings' => [
                'label'       => 'Stripe',
                'description' => 'Stripe settings for Payment gateway.',
                'category'    => 'Bookrr',
                'icon'        => 'icon-usd',
                'url'         => Backend::url('bookrr/stripe/settings'),
                'order'       => 900,
                'keywords'    => 'aeropark setting payment gateway',
                'permissions' => ['stripe.*']
            ]
        ];
    }
}
