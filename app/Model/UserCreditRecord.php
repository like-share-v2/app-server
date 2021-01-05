<?php

declare (strict_types=1);
namespace App\Model;

/**
 * 用户信用分记录模型
 *
 * @property int $id 
 * @property int $user_id 
 * @property string $type 
 * @property float $credit 
 * @property string $remark 
 * @property \Carbon\Carbon $created_at 
 */
class UserCreditRecord extends Model
{
    public const UPDATED_AT = null;

    public $dateFormat = 'U';

    /**
     * The table associated with the model.
     *
     * @var string
     */
    protected $table = 'user_credit_record';
    /**
     * The attributes that are mass assignable.
     *
     * @var array
     */
    protected $guarded = ['id'];
    /**
     * The attributes that should be cast to native types.
     *
     * @var array
     */
    protected $casts = ['id' => 'integer', 'user_id' => 'integer', 'credit' => 'float', 'created_at' => 'date:Y-m-d H:i:s'];

    public function getTypeAttribute($value)
    {
        return __('logic.CREDIT_RECORD_TYPE_'.$value);
    }
}