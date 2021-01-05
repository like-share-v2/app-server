<?php
/**
 * @copyright
 * @version   1.0.0
 * @link
 */

return [
    'Auth'         => [
        'LoginRequest'         => [
            'country_code' => ['required' => 'Please select the country code'],
            'account'      => ['required'   => 'Please input login account',
                               'alpha_dash' => 'Wrong account format [only letters and numbers, dashes and underscores are supported]',
                               'between'    => 'Account format error, 5-30 string length '
            ],
            'password'     => ['required'   => 'Please input the login password',
                               'alpha_dash' => 'Wrong password format [only letters and numbers, as well as dashes and underscores are supported]',
                               'between'    => 'Password format error, 6-30 string length'
            ]
        ],
        'PhoneLoginRequest'    => [
            'phone' => ['required' => 'Please enter your mobile phone number',
                        'regex'    => 'Mobile phone number format error'
            ],
            'code'  => ['required' => 'Please input 6-digit SMS verification code',
                        'size'     => 'Please input 6-digit SMS verification code'
            ]
        ],
        'RegisterRequest'      => [
            'country_id'      => ['required' => 'Please select a country'],
            'country_code'    => ['required' => 'Please select the country code'],
            'account'         => ['required'   => 'Please enter the account number',
                                  'alpha_dash' => 'Wrong account format [only letters and numbers, dashes and underscores are supported]',
                                  'between'    => 'Account format error, 5-30 string length'
            ],
            'password'        => ['required'   => 'Please input a password',
                                  'alpha_dash' => 'Wrong password format [only letters and numbers, as well as dashes and underscores are supported]',
                                  'between'    => 'Password format error, 5-30 string length',
                                  'confirmed'  => 'The two passwords are inconsistent'
            ],
            'phone'           => ['required' => 'Please enter your mobile phone number',
                                  'regex'    => 'Incorrect format of mobile phone number'
            ],
            'code'            => ['required' => 'Please enter the verification code',
                                  'digits'   => 'Please input 6-digit verification code'
            ],
            'invitation_code' => ['required' => 'Please enter the invitation code']
        ],
        'ResetPasswordRequest' => [
            'country_code' => ['required' => 'Please select the country code'],
            'account'      => ['required'   => 'Please enter the account number',
                               'alpha_dash' => 'Wrong account format [only letters and numbers, dashes and underscores are supported]',
                               'between'    => 'Account format error, 5-30 string length'
            ],
            'password'     => ['required'   => 'Please input a password',
                               'alpha_dash' => 'Wrong password format [only letters and numbers, as well as dashes and underscores are supported]',
                               'between'    => 'Password format error, 6-30 string length',
                               'confirmed'  => 'The two passwords are inconsistent'
            ],
            'phone'        => ['required' => 'Please enter your mobile phone number',
                               'regex'    => 'Incorrect format of mobile phone number'
            ],
            'code'         => ['required' => 'Please enter the verification code',
                               'digits'   => 'Please input 6-digit verification code'
            ],
        ]
    ],
    'Account'      => [
        'ChangeAvatarRequest'   => [
            'avatar' => ['required' => 'Please upload your picture',
                         'max'      => 'The length of the avatar address cannot exceed :max characters'
            ]
        ],
        'ChangeNicknameRequest' => [
            'nickname' => ['required' => 'Please enter your nickname',
                           'max'      => 'Nickname length cannot exceed:max characters'
            ]
        ],
        'ChangePhoneRequest'    => [
            'phone' => ['required' => 'Please enter your mobile phone number',
                        'numeric'  => 'Mobile phone number format error',
                        'digits'   => 'Mobile phone number format error: [: digits] length'
            ],
            'code'  => ['required' => 'Please input SMS verification code',
                        'digits'   => 'Verification code format error: [correct: digits digit]'
            ]
        ],
        'ChangePasswordRequest' => [
            'old_password' => ['required' => 'Please enter the old password',
                               'between'  => 'The length of the old password is 6-30 strings'
            ],
            'password'     => ['required'   => 'Please enter a new password',
                               'alpha_dash' => 'New password format error [only letters and numbers, as well as dashes and underscores are supported]',
                               'between'    => 'New password format error, 6-30 string length ',
                               'confirmed'  => 'two input passwords are inconsistent'
            ],
            'trade_pass'   => ['alpha_dash' => 'New withdrawal password format error [only letters and numbers, as well as dashes and underscores are supported]',
                               'between'    => 'New withdrawal password format error, 6-30 string length'
            ]
        ],
        'ChangeIdCardRequest'   => [
            'id_card' => ['required' => 'Please input ID card number', 'size' => 'Incorrect format of ID card number']
        ],
        'ChangeBankRequest'     => [
            'name'       => ['required' => 'Please type in your name', 'between' => 'The length of the name is 2~20 characters'],
            'account'    => ['required' => 'Please enter the bank card number', 'max' => 'The maximum account length is 20 characters'],
            'trade_pass' => ['required' => 'Please enter the withdrawal password'],
            'phone'      => ['required' => 'Please enter the phone number'],
            'ifsc'       => ['required' => 'Please enter IFSC'],
            'bank_code' => ['required' => 'Please select bank']
        ],
        'ChangeGenderRequest'   => [
            'gender' => ['required' => 'Please select gender', 'in' => 'Wrong gender choice']
        ]
    ],
    'Task'         => [
        'SubmitRequest' => [
            'id'    => ['required' => 'Please select a task'],
            'image' => ['required' => 'Please upload the task screenshot',
                        'max'      => 'The length of the task screenshot address cannot exceed 255 characters'
            ]
        ],
        'TaskRequest'   => [
            'category_id' => ['required' => 'Please select task category'],
            'level'       => ['required' => 'Please select membership level'],
            'title'       => ['required' => 'Please enter task title',
                              'max'      => 'The length of task Title cannot exceed: Max characters'
            ],
            'description' => ['required' => 'Please enter task profile'],
            'url'         => ['required' => 'Please enter the task address link'],
            'amount'      => ['required' => 'Please enter task amount'],
            'num'         => ['required' => 'Please enter the number of tasks',
                              'gt'       => 'The number of tasks must be greater than 0'
            ]
        ]
    ],
    'UserRecharge' => [
        'ManualRequest' => [
            'level'    => ['required' => 'Please select the member level to recharge',
                           'gt'       => 'Member level selection error, please re select'
            ],
            'trade_no' => ['required' => 'Please input transaction serial number'],
            'image'    => ['required' => 'Please upload the payment screenshot',
                           'max'      => 'The length of the payment screenshot address cannot exceed 255 characters'
            ]
        ],
        'OnlineRequest' => [
            'channel' => ['required' => 'Please select recharge channel'],
            'level'   => ['required' => 'Please select the member level to recharge',
                          'integer'  => 'Member level selection error, please re select',
                          'gt'       => 'Member level selection error, please re select'
            ]
        ],
        'BankRequest'   => [
            'country_id' => ['required' => 'Please select a country first'],
            'bank_name'  => ['required' => 'Please enter the name of remittance bank'],
            'name'       => ['required' => 'Please enter the name of remitter'],
            'bank'       => ['required' => 'Please input the remittance card number'],
            'amount'     => ['required' => 'Please enter the recharge amount'],
            'remittance' => ['required' => 'Please input the remittance amount'],
            'voucher'    => ['required' => 'Please upload proof of payment']
        ],
        'LevelRequest'  => [
            'level' => ['required' => 'Please select recharge level']
        ]
    ]
];