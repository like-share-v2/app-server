<?php

declare (strict_types=1);
namespace App\Model;

/**
 * 任务审核模型
 *
 * @property int $id 
 * @property int $user_id 
 * @property int $category_id 
 * @property int $level 
 * @property string $title 
 * @property string $description 
 * @property string $url 
 * @property float $amount 
 * @property int $num 
 * @property int $status 
 * @property \Carbon\Carbon $created_at 
 * @property \Carbon\Carbon $updated_at 
 * @property int $admin_id 
 * @property string $remark 
 */
class TaskAudit extends Model
{
    public $dateFormat = 'U';

    /**
     * The table associated with the model.
     *
     * @var string
     */
    protected $table = 'task_audit';
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
    protected $casts = ['id' => 'integer', 'user_id' => 'integer', 'category_id' => 'integer', 'level' => 'integer', 'amount' => 'float', 'num' => 'integer', 'status' => 'integer', 'created_at' => 'datetime', 'updated_at' => 'datetime', 'admin_id' => 'integer'];
}