<?php

declare (strict_types=1);
namespace App\Model;

/**
 * 用户签到记录模型
 *
 * @property int $id 
 * @property int $user_id 
 * @property int $credit 
 * @property \Carbon\Carbon $created_at 
 */
class UserSignInRecord extends Model
{
    public $dateFormat = 'U';

    public const UPDATED_AT = null;

    /**
     * The table associated with the model.
     *
     * @var string
     */
    protected $table = 'user_sign_in_record';
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
    protected $casts = ['id' => 'integer', 'user_id' => 'integer', 'credit' => 'integer', 'created_at' => 'date:Y-m-d H:i:s'];
}