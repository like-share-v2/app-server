<?php

declare (strict_types=1);
namespace App\Model;

/**
 * @property int $id 
 * @property string $ip 
 * @property \Carbon\Carbon $created_at 
 * @property \Carbon\Carbon $updated_at 
 */
class IpBlackList extends Model
{
    /**
     * The table associated with the model.
     *
     * @var string
     */
    protected $table = 'ip_black_list';
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
    protected $casts = ['id' => 'integer', 'created_at' => 'datetime', 'updated_at' => 'datetime'];
}