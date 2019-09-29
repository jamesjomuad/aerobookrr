<?php namespace Bookrr\Bay\Models;

use Model;
use \Carbon\Carbon;

/**
 * Zone Model
 */
class Zone extends Model
{
    /**
     * @var string The database table used by the model.
     */
    public $table = 'aeroparks_bay_zone';

    /**
     * @var array Guarded fields
     */
    protected $guarded = ['*'];

    /**
     * @var array Fillable fields
     */
    protected $fillable = ['name','building','floor','description','slug'];

    /**
     * @var array Relations
     */
    public $hasOne = [];
    public $hasMany = [
        'bay' => \Bookrr\Bay\Models\Bay::class
    ];
    public $belongsTo = [];
    public $belongsToMany = [];
    public $morphTo = [];
    public $morphOne = [];
    public $morphMany = [];
    public $attachOne = [];
    public $attachMany = [];


    public function getCreatedatAttribute($date)
    {
        return Carbon::createFromFormat('Y-m-d H:i:s', $date)->format('d/m/Y');
    }

    public function floors()
    {
        return range(0,100);
    }

    public function filterFields($fields, $context = null)
    {
        if (!empty($this->name))
        {
            $fields->slug->value = str_slug($this->name);
        }
        
    }

}
