<?php

declare(strict_types=1);

namespace App\Request\Task;

use App\Request\RequestAbstract;

/**
 * 任务验证器
 *
 * @package App\Request\Task
 */
class TaskRequest extends RequestAbstract
{
    public function authorize()
    {
        return true;
    }

    public function rules()
    {
        return [
            'category_id' => 'required',
            'level'  => 'required',
            'title' => 'required|max:100',
            'description' => 'required',
            'url' => 'required',
            'amount' => 'required',
            'num' => 'required|gt:0',
        ];
    }
}