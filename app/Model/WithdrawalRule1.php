<?php

declare (strict_types=1);

namespace App\Model;

/**
 * @property int    $id
 * @property string $name
 * @property int    $active_sub
 * @property int    $withdrawal_count
 * @property int    $is_enable
 */
class WithdrawalRule1 extends Model
{
    /**
     * The table associated with the model.
     *
     * @var string
     */
    protected $table = 'withdrawal_rule_1';
    /**
     * The attributes that are mass assignable.
     *
     * @var array
     */
    protected $fillable = [];
    /**
     * The attributes that should be cast to native types.
     *
     * @var array
     */
    protected $casts = [
        'id'               => 'integer',
        'active_sub'       => 'integer',
        'withdrawal_count' => 'integer',
        'is_enable'        => 'boolean'
    ];
}