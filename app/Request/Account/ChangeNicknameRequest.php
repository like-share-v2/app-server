<?php

declare (strict_types=1);
/**
 * @copyright 
 * @version 1.0.0
 * @link  
 */

namespace App\Request\Account;

use App\Request\RequestAbstract;

/**
 * ChangeNicknameRequest
 *
 *
 * @package App\Request\Account
 */
class ChangeNicknameRequest extends RequestAbstract
{
    /**
     * Determine if the user is authorized to make this request.
     */
    public function authorize(): bool
    {
        return true;
    }

    /**
     * Get the validation rules that apply to the request.
     */
    public function rules(): array
    {
        return [
            'nickname' => 'required|max:50'
        ];
    }
}