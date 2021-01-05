<?php
/**
 * @copyright
 * @version   1.0.0
 * @link
 */

return [
    'Auth'         => [
        'LoginRequest'         => [
            'country_code' => ['required' => '请选择国家区号'],
            'account'      => ['required'   => '请输入登陆账号',
                               'alpha_dash' => '账号格式错误[仅支持字母和数字，以及破折号和下划线]',
                               'between'    => '账号格式错误,5-30个字符串长度'
            ],
            'password'     => ['required'   => '请输入登陆密码',
                               'alpha_dash' => '密码格式错误[仅支持字母和数字，以及破折号和下划线]',
                               'between'    => '密码格式错误,6-30个字符串长度'
            ]
        ],
        'PhoneLoginRequest'    => [
            'phone' => ['required' => '请输入手机号', 'regex' => '手机号格式错误'],
            'code'  => ['required' => '请输入6位短信验证码', 'size' => '请输入6位短信验证码']
        ],
        'RegisterRequest'      => [
            'country_id'      => ['required' => '请选择国家'],
            'country_code'    => ['required' => '请选择国家区号'],
            'account'         => ['required'   => '请输入账号',
                                  'alpha_dash' => '账号格式错误[仅支持字母和数字，以及破折号和下划线]',
                                  'between'    => '账号格式错误,5-30个字符串长度'
            ],
            'password'        => ['required'   => '请输入密码',
                                  'alpha_dash' => '密码格式错误[仅支持字母和数字，以及破折号和下划线]',
                                  'between'    => '密码格式错误,5-30个字符串长度',
                                  'confirmed'  => '两次输入密码不一致'
            ],
            'phone'           => ['required' => '请输入手机号', 'regex' => '手机号格式不正确'],
            'code'            => ['required' => '请输入验证码', 'digits' => '请输入6位数字验证码'],
            'invitation_code' => ['required' => '请输入邀请码']
        ],
        'ResetPasswordRequest' => [
            'country_code' => ['required' => '请选择国家区号'],
            'account'      => ['required'   => '请输入账号',
                               'alpha_dash' => '账号格式错误[仅支持字母和数字，以及破折号和下划线]',
                               'between'    => '账号格式错误,5-30个字符串长度'
            ],
            'password'     => ['required'   => '请输入密码',
                               'alpha_dash' => '密码格式错误[仅支持字母和数字，以及破折号和下划线]',
                               'between'    => '密码格式错误,6-30个字符串长度',
                               'confirmed'  => '两次输入密码不一致'
            ],
            'phone'        => ['required' => '请输入手机号', 'regex' => '手机号格式不正确'],
            'code'         => ['required' => '请输入验证码', 'digits' => '请输入6位数字验证码'],
        ]
    ],
    'Account'      => [
        'ChangeAvatarRequest'   => [
            'avatar' => ['required' => '请上传头像', 'max' => '头像地址长度不能超过:max个字符']
        ],
        'ChangeNicknameRequest' => [
            'nickname' => ['required' => '请输入昵称', 'max' => '昵称长度不能超过:max个字符']
        ],
        'ChangePhoneRequest'    => [
            'phone' => ['required' => '请输入手机号码', 'numeric' => '手机号码格式错误', 'digits' => '手机号码格式错误: [:digits]长度'],
            'code'  => ['required' => '请输入短信验证码', 'digits' => '验证码格式错误: [正确为:digits位数字]']
        ],
        'ChangePasswordRequest' => [
            'old_password' => ['required' => '请输入旧密码', 'between' => '旧密码长度为6~30个字符串长度'],
            'password'     => ['required'   => '请输入新密码',
                               'alpha_dash' => '新密码格式错误[仅支持字母和数字，以及破折号和下划线]',
                               'between'    => '新密码格式错误,6-30个字符串长度',
                               'confirmed'  => '两次输入密码不一致'
            ],
            'trade_pass'   => ['alpha_dash' => '新取款密码格式错误[仅支持字母和数字，以及破折号和下划线]', 'between' => '新取款密码格式错误,6-30个字符串长度'],
        ],
        'ChangeIdCardRequest'   => [
            'id_card' => ['required' => '请输入身份证号码', 'size' => '身份证号码格式不正确']
        ],
        'ChangeBankRequest'     => [
            'name'       => ['required' => '请输入姓名', 'between' => '姓名长度为2~20个字符'],
            'account'    => ['required' => '请输入账号', 'max' => '账号长度最高为20个字符'],
            'trade_pass' => ['required' => '请输入提现密码'],
            'phone'      => ['required' => '请输入手机号码'],
            'ifsc'       => ['required' => '请输入IFSC'],
            'bank_code'  => ['required' => '请选择银行']
        ],
        'ChangeGenderRequest'   => [
            'gender' => ['required' => '请选择性别', 'in' => '性别选择有误']
        ]
    ],
    'Task'         => [
        'SubmitRequest' => [
            'id'    => ['required' => '请选择任务'],
            'image' => ['required' => '请上传任务截图', 'max' => '任务截图地址长度不能超过255个字符']
        ],
        'TaskRequest'   => [
            'category_id' => ['required' => '请选择任务分类'],
            'level'       => ['required' => '请选择会员等级'],
            'title'       => ['required' => '请输入任务标题', 'max' => '任务标题长度不能超过:max个字符'],
            'description' => ['required' => '请输入任务简介'],
            'url'         => ['required' => '请输入任务地址链接'],
            'amount'      => ['required' => '请输入任务金额'],
            'num'         => ['required' => '请输入任务数量', 'gt' => '任务数量必须大于0']
        ]
    ],
    'UserRecharge' => [
        'ManualRequest' => [
            'level'    => ['required' => '请选择要充值的会员级别', 'gt' => '会员级别选择有误，请重新选择'],
            'trade_no' => ['required' => '请输入交易流水编号'],
            'image'    => ['required' => '请上传支付截图', 'max' => '支付截图地址长度不能超过255个字符']
        ],
        'OnlineRequest' => [
            'channel' => ['required' => '请选择充值渠道'],
            'level'   => ['required' => '请选择要充值的会员级别', 'integer' => '会员级别选择有误，请重新选择', 'gt' => '会员级别选择有误，请重新选择']
        ],
        'BankRequest'   => [
            'country_id' => ['required' => '请先选择国家'],
            'bank_name'  => ['required' => '请输入汇款银行名'],
            'name'       => ['required' => '请输入汇款人姓名'],
            'bank'       => ['required' => '请输入汇款卡号'],
            'amount'     => ['required' => '请输入充值金额'],
            'remittance' => ['required' => '请输入汇款金额'],
            'voucher'    => ['required' => '请上传转账凭证']
        ],
        'LevelRequest'  => [
            'level' => ['required' => '请选择充值等级']
        ]
    ]
];