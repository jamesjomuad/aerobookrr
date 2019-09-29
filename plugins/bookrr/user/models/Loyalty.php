<?php namespace Bookrr\User\Models;

use Model;
use Backend\Models\User;

/**
 * Loyalty Model
 */
class Loyalty extends Model
{

    public $table = 'aeroparks_user_loyalty';

    public static $loginPoints = 5;

    protected $guarded = ['*'];

    protected $fillable = ['points'];

    public $hasOne = [];
    public $hasMany = [];
    public $belongsTo = [
        'backendUser' => User::class
    ];
    public $belongsToMany = [];
    public $morphTo = [];
    public $morphOne = [];
    public $morphMany = [];
    public $attachOne = [];
    public $attachMany = [];

    public function addPoints($points)
    {
        $this->points += $points;
        if($this->save())
        {
            return $this;
        }
        return null;
    }
}
