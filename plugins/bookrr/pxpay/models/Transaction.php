<?php namespace Bookrr\Pxpay\Models;

use Model;

/**
 * Transaction Model
 */
class Transaction extends Model
{
    /**
     * @var string The database table used by the model.
     */
    public $table = 'jomuad_pxpay_transactions';

    /**
     * @var array Guarded fields
     */
    protected $guarded = ['*'];

    /**
     * @var array Fillable fields
     */
    protected $fillable = ['user_id','amount','status','reference','result'];

    /**
     * @var array Relations
     */
    public $hasOne = [];
    public $hasMany = [];
    public $belongsTo = [];
    public $belongsToMany = [];
    public $morphTo = [];
    public $morphOne = [];
    public $morphMany = [];
    public $attachOne = [];
    public $attachMany = [];


    public static function isPaid($ref)
    {
        if($model = self::where('reference',$ref)->first())
        {
            return $model->status == 'paid';
        }

        return false;
    }

    public static function isFail($ref)
    {
        if($model = self::where('reference',$ref)->first())
        {
            return $model->status == 'fail';
        }

        return null;
    }

}
