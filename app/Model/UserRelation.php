<?php

declare (strict_types=1);
namespace App\Model;

use Hyperf\Database\Model\Relations\BelongsTo;
use Hyperf\Database\Model\Relations\HasMany;

/**
 * 用户关系表
 *
 * @property int $id 
 * @property int $user_id 
 * @property int $parent_id 
 * @property int $level 
 * @property \Carbon\Carbon $created_at 
 */
class UserRelation extends Model
{
    protected $dateFormat = 'U';

    /**
     * The table associated with the model.
     *
     * @var string
     */
    protected $table = 'user_relation';
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
    protected $casts = ['id' => 'integer', 'user_id' => 'integer', 'parent_id' => 'integer', 'level' => 'integer', 'created_at' => 'date:Y-m-d H:i:s'];

    /**
     * 关联用户
     *
     * @return BelongsTo
     */
    public function user()
    {
        return $this->belongsTo(User::class, 'user_id', 'id')->with(['userLevel:level,name']);
    }

    /**
     * 关联会员等级
     *
     * @return HasMany
     */
    public function userMember()
    {
        return $this->hasMany(UserMember::class, 'user_id', 'user_id');
    }
}