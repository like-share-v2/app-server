<?php

declare (strict_types=1);

namespace App\Model;

use Hyperf\Database\Model\Relations\BelongsTo;

/**
 * 用户信息模型
 *
 * @property int    $user_id
 * @property string $id_card
 * @property string $bank_name
 * @property string $name
 * @property string $account
 * @property string $bank_code
 * @property string $email
 * @property string $phone
 * @property string $upi
 * @property string $ifsc
 */
class UserInfo extends Model
{
    public const CREATED_AT = null;

    public const UPDATED_AT = null;

    /**
     * The table associated with the model.
     *
     * @var string
     */
    protected $table = 'user_info';
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
    protected $casts = ['user_id' => 'integer'];

    /**
     * 关联用户
     *
     * @return BelongsTo
     */
    public function user()
    {
        return $this->belongsTo(User::class, 'user_id', 'id');
    }
}