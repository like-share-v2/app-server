<?php

declare (strict_types=1);
namespace App\Model;

use Hyperf\Database\Model\Relations\BelongsTo;

/**
 * 用户任务模型
 *
 * @property int $id 
 * @property int $user_id 
 * @property int $task_id 
 * @property int $status 
 * @property string $image 
 * @property float $amount
 * @property int submit_time
 * @property int audit_time
 * @property int cancel_time
 * @property int $admin_id 
 * @property string $remark 
 * @property \Carbon\Carbon $created_at 
 * @property \Carbon\Carbon $updated_at 
 */
class UserTask extends Model
{
    public $dateFormat = 'U';

    /**
     * The table associated with the model.
     *
     * @var string
     */
    protected $table = 'user_task';
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
        'id' => 'integer',
        'user_id' => 'integer',
        'task_id' => 'integer',
        'status' => 'integer',
        'amount' => 'float',
        'submit_time' => 'date:Y-m-d H:i',
        'audit_time' => 'date:Y-m-d H:i',
        'cancel_time' => 'date:Y-m-d H:i',
        'admin_id' => 'integer',
        'created_at' => 'date:Y-m-d H:i:s',
        'updated_at' => 'date:Y-m-d H:i'
    ];

    protected $hidden = [
        'admin_id'
    ];

    /**
     * @return BelongsTo
     */
    public function task()
    {
        return $this->belongsTo(Task::class, 'task_id', 'id');
    }
}