<?php namespace Bookrr\Stripe\Models;

use Model;


/**
 * Settings Model
 */
class Settings extends Model
{
    public $implement = ['System.Behaviors.SettingsModel'];

    // A unique code
    public $settingsCode = 'stripe';

    // Reference to field configuration
    public $settingsFields = 'fields.yaml';

}
