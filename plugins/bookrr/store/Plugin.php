<?php namespace Bookrr\Store;

use Backend;
use System\Classes\PluginBase;
use BackendAuth;

class Plugin extends PluginBase
{
    public function pluginDetails()
    {
        return [
            'name'        => 'Bookrr Store',
            'description' => 'Bookrr online Store.',
            'author'      => 'bookrr',
            'icon'        => 'icon-leaf'
        ];
    }

    public function registerPermissions()
    {
        return [
            // Store Management
            'bookrr.store.*' => [
                'tab' => 'Store Management',
                'label' => 'Manage Store',
                'order' => 201,
            ],

            'bookrr.store.car-rent' => [
                'tab' => 'Store Management',
                'label' => 'View Car Rental',
                'order' => 202,
            ],

            'bookrr.store.invoice' => [
                'tab' => 'Store Management',
                'label' => 'Manage Invoice',
                'order' => 203,
            ],

            'bookrr.store.coupon' => [
                'tab' => 'Store Management',
                'label' => 'Manage Coupon',
                'order' => 204,
            ],

            /* Product Permision */
            'bookrr.product.create' => [
                'tab' => 'Store Management',
                'label' => 'Can Create product',
                'order' => 205,
            ],
            'bookrr.product.read' => [
                'tab' => 'Store Management',
                'label' => 'Can View product',
                'order' => 206,
            ],
            'bookrr.product.update' => [
                'tab' => 'Store Management',
                'label' => 'Can Update product',
                'order' => 207,
            ],
            'bookrr.product.delete' => [
                'tab' => 'Store Management',
                'label' => 'Can Remove product',
                'order' => 208,
            ],
            /* end Permision */

            /* Product Category Permision */
            'bookrr.productCategory.create' => [
                'tab' => 'Store Management',
                'label' => 'Can Create product category',
                'order' => 205,
            ],
            'bookrr.productCategory.read' => [
                'tab' => 'Store Management',
                'label' => 'Can View product category',
                'order' => 206,
            ],
            'bookrr.productCategory.update' => [
                'tab' => 'Store Management',
                'label' => 'Can Update product category',
                'order' => 207,
            ],
            'bookrr.productCategory.delete' => [
                'tab' => 'Store Management',
                'label' => 'Can Remove product category',
                'order' => 208,
            ],
            /* end Permision */
            
            /* Rule Permision */
            'bookrr.rule.create' => [
                'tab' => 'Store Management',
                'label' => 'Can Create rule',
                'order' => 209,
            ],
            'bookrr.rule.read' => [
                'tab' => 'Store Management',
                'label' => 'Can View rule',
                'order' => 210,
            ],
            'bookrr.rule.update' => [
                'tab' => 'Store Management',
                'label' => 'Can Update rule',
                'order' => 211,
            ],
            'bookrr.rule.delete' => [
                'tab' => 'Store Management',
                'label' => 'Can Remove rule',
                'order' => 212,
            ],
            /* end Permision */
        ];
    }

    public function registerNavigation()
    {
        if(BackendAuth::getUser()->isCustomer())
        {
            return [
                'car-rental' => [
                    'label'       => 'Car Rental',
                    'url'         => Backend::url('bookrr/store/carrent'),
                    'icon'        => 'icon-car',
                    'permissions' => ['bookrr.store.car-rent'],
                    'order'       => 910,
                ]
            ];
        }

        return [
            'store' => [
                'label'       => 'Store',
                'url'         => Backend::url('bookrr/store/carrent'),
                'icon'        => 'icon-cart-plus',
                'permissions' => ['bookrr.store.*'],
                'order'       => 910,

                'sideMenu' => [
                    'car-rental' => [
                        'label'       => 'Car Rental',
                        'url'         => Backend::url('bookrr/store/carrent'),
                        'icon'        => 'icon-car',
                        'permissions' => ['bookrr.store.car-rent'],
                    ],
                    'product' => [
                        'label'       => 'Product / Service',
                        'url'         => Backend::url('bookrr/store/product'),
                        'icon'        => 'icon-shopping-basket',
                        'permissions' => ['bookrr.product.read'],
                    ],
                    'cart' => [
                        'label'       => 'Carts',
                        'url'         => Backend::url('bookrr/store/cart'),
                        'icon'        => 'icon-cart-plus',
                        'permissions' => ['bookrr.product.read'],
                    ],
                    'invoice' => [
                        'label'       => 'Invoice',
                        'url'         => Backend::url('bookrr/store/invoice'),
                        'icon'        => 'icon-file-text-o',
                        'permissions' => ['bookrr.store.invoice'],
                    ],
                    'coupon' => [
                        'label'       => 'Coupon',
                        'url'         => Backend::url('bookrr/store/coupon'),
                        'icon'        => 'icon-certificate',
                        'permissions' => ['bookrr.store.coupon'],
                    ],
                    'category' => [
                        'label'       => 'Category',
                        'url'         => Backend::url('bookrr/store/category'),
                        'icon'        => 'icon-bookmark-o',
                        'permissions' => ['bookrr.productCategory.*'],
                    ],
                    'rules' => [
                        'label'       => 'Rule Set',
                        'url'         => Backend::url('bookrr/store/rule'),
                        'icon'        => 'icon-list',
                        'permissions' => ['bookrr.rule.*'],
                    ]
                ]
            ]
        ];
    }

}
