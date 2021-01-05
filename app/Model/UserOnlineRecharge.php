<?php

declare (strict_types=1);
namespace App\Model;

/**
 * 在线充值模型
 *
 * @property int $id 
 * @property int $country_id 
 * @property int $user_id 
 * @property int $payment_id 
 * @property float $amount
 * @property string $channel 
 * @property int $status 
 * @property \Carbon\Carbon $created_at 
 * @property \Carbon\Carbon $updated_at 
 */
class UserOnlineRecharge extends Model
{
    public $dateFormat = 'U';

    /**
     * The table associated with the model.
     *
     * @var string
     */
    protected $table = 'user_online_recharge';
    /**
     * The attributes that are mass assignable.
     *
     * @var array
     */
    protected $guarded = [];
    /**
     * The attributes that should be cast to native types.
     *
     * @var array
     */
    protected $casts = ['id' => 'integer', 'country_id' => 'integer', 'user_id' => 'integer', 'payment_id' => 'integer', 'amount' => 'float', 'status' => 'integer', 'created_at' => 'date:Y-m-d H:i:s', 'updated_at' => 'date:Y-m-d H:i:s'];
}