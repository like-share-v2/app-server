<?php

declare (strict_types=1);
namespace App\Model;

use Hyperf\Database\Model\Relations\BelongsTo;

/**
 * 用户账单模型
 *
 * @property int $id 
 * @property int $user_id 
 * @property string $type 
 * @property float $balance 
 * @property float $before_balance 
 * @property float $after_balance 
 * @property string $remark 
 * @property \Carbon\Carbon $created_at 
 * @property \Carbon\Carbon $updated_at 
 */
class UserBill extends Model
{
    public $dateFormat = 'U';

    /**
     * The table associated with the model.
     *
     * @var string
     */
    protected $table = 'user_bill';
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
    protected $casts = ['id' => 'integer', 'user_id' => 'integer', 'balance' => 'float', 'before_balance' => 'float', 'after_balance' => 'float', 'created_at' => 'date:Y-m-d H:i:s', 'updated_at' => 'date:Y-m-d H:i:s'];

    public function user()
    {
        return $this->belongsTo(User::class, 'user_id', 'id');
    }

    public function getTypeAttribute($value)
    {
        return __('logic.USER_BILL_TYPE_'.$value);
    }

    /**
     * 关联下级
     *
     * @return BelongsTo
     */
    public function low()
    {
        return $this->belongsTo(User::class, 'low_id', 'id');
    }
}