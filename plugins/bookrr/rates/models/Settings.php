<?php namespace Bookrr\Rates\Models;

use Model;

/**
 * Settings Model
 */
class Settings extends Model
{
    public $implement = ['System.Behaviors.SettingsModel'];

    // A unique code
    public $settingsCode = 'rates';

    // Reference to field configuration
    public $settingsFields = 'fields.yaml';
}
