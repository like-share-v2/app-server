<?php

declare (strict_types=1);
/**
 * @copyright 
 * @version 1.0.0
 * @link  
 */

namespace App\Request\Task;

use App\Request\RequestAbstract;

/**
 *
 * @package App\Request\Task
 */
class SubmitRequest extends RequestAbstract
{
    public function authorize()
    {
        return true;
    }

    public function rules()
    {
        return [
            'id'    => 'required',
            'image' => 'required|max:255'
        ];
    }
}