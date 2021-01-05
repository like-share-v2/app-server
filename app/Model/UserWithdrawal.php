<?php

declare (strict_types=1);

namespace App\Model;

/**
 * 用户提现模型
 *
 * @property int            $id
 * @property int            $country_id
 * @property int            $user_id
 * @property float          $amount
 * @property float          $service_charge
 * @property string         $bank_name
 * @property string         $name
 * @property string         $account
 * @property string         $bank_address
 * @property int            $status
 * @property int            $admin_id
 * @property string         $remark
 * @property \Carbon\Carbon $created_at
 * @property \Carbon\Carbon $updated_at
 * @property User           $user
 * @property int            $integral
 */
class UserWithdrawal extends Model
{
    public $dateFormat = 'U';

    /**
     * The table associated with the model.
     *
     * @var string
     */
    protected $table = 'user_withdrawal';
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
    protected $casts = [
        'id'             => 'integer',
        'country_id'     => 'integer',
        'user_id'        => 'integer',
        'amount'         => 'float',
        'service_charge' => 'float',
        'status'         => 'integer',
        'admin_id'       => 'integer',
        'created_at'     => 'date:Y-m-d H:i:s',
        'updated_at'     => 'date:Y-m-d H:i:s',
        'integral'       => 'integer'
    ];

    public function user()
    {
        return $this->belongsTo(User::class, 'user_id');
    }
}