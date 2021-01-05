<?php

declare (strict_types=1);

namespace App\Model;

/**
 * @property int            $id
 * @property int            $user_id
 * @property int            $level
 * @property int            $num
 * @property \Carbon\Carbon $created_at
 * @property \Carbon\Carbon $updated_at
 */
class UserLevelBuyNum extends Model
{
    /**
     * @var string
     */
    protected $dateFormat = 'U';
    /**
     * The table associated with the model.
     *
     * @var string
     */
    protected $table = 'user_level_buy_num';
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
    protected $casts = [
        'id'         => 'integer',
        'user_id'    => 'integer',
        'level'      => 'integer',
        'num'        => 'integer',
        'created_at' => 'datetime',
        'updated_at' => 'datetime'
    ];
}