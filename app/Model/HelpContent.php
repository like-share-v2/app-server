<?php

declare (strict_types=1);
namespace App\Model;

/**
 * 帮助手册内容模型
 *
 * @property int $id 
 * @property int $help_id 
 * @property string $locale 
 * @property string $content 
 */
class HelpContent extends Model
{
    /**
     * The table associated with the model.
     *
     * @var string
     */
    protected $table = 'help_content';
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
    protected $casts = ['id' => 'integer', 'help_id' => 'integer'];
}