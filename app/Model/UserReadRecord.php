<?php

declare (strict_types=1);
namespace App\Model;

/**
 * 用户阅读记录
 *
 * @property int $user_id 
 * @property int $notify_id 
 */
class UserReadRecord extends Model
{
    public const CREATED_AT = null;

    public const UPDATED_AT = null;

    /**
     * The table associated with the model.
     *
     * @var string
     */
    protected $table = 'user_read_record';
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
    protected $casts = ['user_id' => 'integer', 'notify_id' => 'integer'];
}