<?php

declare (strict_types=1);
namespace App\Model;

use Hyperf\Database\Model\Relations\BelongsTo;
use Hyperf\Database\Model\Relations\HasMany;
use Hyperf\Database\Model\SoftDeletes;

/**
 * 任务模型
 *
 * @property int $id
 * @property int $user_id
 * @property int $category_id 
 * @property int $level 
 * @property string $title 
 * @property string $url 
 * @property float $amount 
 * @property int $num 
 * @property int $status 
 * @property \Carbon\Carbon $created_at 
 * @property \Carbon\Carbon $updated_at 
 * @property int $deleted_at 
 */
class Task extends Model
{
    use SoftDeletes;

    public $dateFormat = 'U';

    /**
     * The table associated with the model.
     *
     * @var string
     */
    protected $table = 'task';
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
    protected $casts = ['id' => 'integer', 'user_id' => 'integer', 'category_id' => 'integer', 'level' => 'integer', 'amount' => 'float', 'num' => 'integer', 'status' => 'integer', 'created_at' => 'date:Y-m-d H:i', 'updated_at' => 'date:Y-m-d H:i', 'deleted_at' => 'integer'];

    protected $hidden = [
        'sort',
        'deleted_at'
    ];

    /**
     * 关联分类
     *
     * @return BelongsTo
     */
    public function category()
    {
        return $this->belongsTo(TaskCategory::class, 'category_id', 'id');
    }

    /**
     * 关联等级
     *
     * @return BelongsTo
     */
    public function levelInfo()
    {
        return $this->belongsTo(UserLevel::class, 'level', 'level');
    }

    /**
     * 关联用户任务
     *
     * @return HasMany
     */
    public function userTask()
    {
        return $this->hasMany(UserTask::class, 'task_id', 'id');
    }
}