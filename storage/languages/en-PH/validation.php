<?php
/**
 * @copyright
 * @version 1.0.0
 * @link  
 */

return [
    'Auth'         => [
        'LoginRequest'         => [
            'country_code' => ['required' => 'Mangyaring piliin ang code ng bansa'],
            'account'  => ['required' => 'Paki-input ang login account', 'alpha_dash' => 'Maling format ng account [ang mga titik at numero lamang, mga dashes at underscores ay suportado]', 'between' => 'May pagkakamali sa format ng account, 5-30 string length'],
            'password' => ['required' => 'Paki-input ang login account', 'alpha_dash' => 'Maling format ng password [suportado lamang ang mga titik at numero, pati na rin ang mga dashes at underscores]', 'between' => 'May pagkakamali sa format ng kontrasenyas, haba ng 6-30 string']
        ],
        'PhoneLoginRequest'    => [
            'phone' => ['required' => 'Pakiusap ipasok ang iyong numero ng mobile phone', 'regex' => 'May pagkakamali sa format ng cell phone number'],
            'code'  => ['required' => 'Paki-input ang 6-digit SMS verification code', 'size' => 'Paki-input ang 6-digit SMS verification code']
        ],
        'RegisterRequest'      => [
            'country_id'      => ['required' => 'Mangyaring pumili ng bansa'],
            'country_code'    => ['required' => 'Mangyaring piliin ang code ng bansa'],
            'account'         => ['required' => 'Paki-enter ang numero ng account', 'alpha_dash' => 'Maling format ng account [ang mga titik at numero lamang, mga dashes at underscores ay suportado]', 'between' => 'May pagkakamali sa format ng account, 5-30 string length'],
            'password'        => ['required' => 'Pakiusap ipasok ang password', 'alpha_dash' => 'Maling format ng password [suportado lamang ang mga titik at numero, pati na rin ang mga dashes at underscores]', 'between' => 'May pagkakamali sa format ng kontrasenyas, 5-30 na haba ng string', 'confirmed' => 'Ang dalawang kontrasenyas ay hindi konsistente'],
            'phone'           => ['required' => 'Pakiusap ipasok ang iyong numero ng mobile phone', 'regex' => 'Maling format ng numero ng mobile phone'],
            'code'            => ['required' => 'Pakipasok ang verification code', 'digits' => 'Paki-input ang 6-digit verification code'],
            'invitation_code' => ['required' => 'Paki-pasok ang invitation code']
        ],
        'ResetPasswordRequest' => [
            'country_code'    => ['required' => 'Mangyaring piliin ang code ng bansa'],
            'account'  => ['required' => 'Paki-enter ang numero ng account', 'alpha_dash' => 'Maling format ng account [ang mga titik at numero lamang, mga dashes at underscores ay suportado]', 'between' => 'Maling format ng account, 5-30 string length '],
            'password' => ['required' => 'Pakiusap ipasok ang password', 'alpha_dash' => 'Maling format ng password [suportado lamang ang mga titik at numero, pati na rin ang mga dashes at underscores]', 'between' => 'May pagkakamali sa format ng kontrasenyas, haba ng 6-30 string ','confirmed' => 'dalawang input password ay hindi konsistent'],
            'phone'    => ['required' => 'Pakiusap ipasok ang iyong numero ng mobile phone', 'regex' => 'Maling format ng numero ng mobile phone'],
            'code'     => ['required' => 'Pakipasok ang verification code', 'digits' => 'Paki-input ang 6-digit verification code'],
        ]
    ],
    'Account'      => [
        'ChangeAvatarRequest'   => [
            'avatar' => ['required' => 'Pakiusap i-upload ang iyong larawan', 'max' => 'Ang haba ng avatar address ay hindi maaaring lumalabas: max character']
        ],
        'ChangeNicknameRequest' => [
            'nickname' => ['required' => 'Pakiusap ipasok ang iyong pangalan', 'max' => 'Ang haba ng pangalan ay hindi maaaring lampas: max characters']
        ],
        'ChangePhoneRequest'    => [
            'phone' => ['required' => 'Pakiusap ipasok ang iyong numero ng mobile phone', 'numeric' => 'May pagkakamali sa format ng cell phone number', 'digits' => 'May pagkakamali sa format ng numero ng mobile phone: [: digits] haba'],
            'code'  => ['required' => 'Paki-input ang SMS verification code', 'digits' => 'May error sa format ng verification code: [tama: digit]']
        ],
        'ChangePasswordRequest' => [
            'old_password' => ['required' => 'Pakiusap ipasok ang lumang password', 'between' => 'Ang haba ng lumang password ay 6-30 string'],
            'password'     => ['required' => 'Pakiusap ipasok ang bagong password', 'alpha_dash' => 'Bagong pagkakamali sa format ng password [suportado lamang ang mga titik at numero, pati na rin ang mga dashes at underscores]', 'between' => 'Bagong pagkakamali sa format ng password, 6-30 string length', 'confirmed' => 'Ang dalawang kontrasenyas ay hindi konsistente'],
            'trade_pass'   => ['alpha_dash' => 'Bagong pagkakamali sa format ng kontrasenyas ng withdrawal [suportado lamang ang mga titik at numero, pati na rin ang mga dashes at underscores]', 'between' => 'New withdrawal password format error, 6-30 string length']
        ],
        'ChangeIdCardRequest'   => [
            'id_card' => ['required' => 'Paki-input ang numero ng ID card', 'size' => 'Maling format ng ID card number']
        ],
        'ChangeBankRequest'     => [
            'bank_name' => ['required' => 'Mangyaring piliin ang uri ng withdrawal'],
            'bank_address' => ['required' => 'Paki-enter ang address ng bank'],
            'name'      => ['required' => 'Pakiusap ipasok ang pangalan', 'between' => 'Ang haba ng pangalan ay 2-20 na character'],
            'account'   => ['required' => 'Paki-enter ang numero ng account', 'max' => 'Ang pinakamalaking haba ng account ay 20 character'],
            'trade_pass'   => ['required' => 'Pakiusap na ipasok ang password ng withdrawal']
        ],
        'ChangeGenderRequest'   => [
            'gender' => ['required' => 'Mangyaring piliin ang kasarian', 'in' => 'Maling pagpili ng sekso']
        ]
    ],
    'Task'         => [
        'SubmitRequest' => [
            'id'    => ['required' => 'Mangyaring piliin ang gawain'],
            'image' => ['required' => 'Pakiusap i-upload ang screenshot ng gawain', 'max' => 'Ang haba ng task screenshot address ay hindi maaaring lumalabas sa 255 character']
        ],
        'TaskRequest' => [
            'category_id' => ['required' => 'Mangyaring piliin ang kategorya ng gawain'],
            'level' => ['required' => 'Mangyaring piliin ang antas ng kasamahan'],
            'title' => ['required' => 'Pakiusap ipasok ang pamagat ng gawain', 'max' => 'The length of task Title cannot exceed: max characters'],
            'description' => ['required' => 'Paki-pasok ang task profile'],
            'url' => ['required' => 'Mangyaring ipasok ang link ng task address'],
            'amount' => ['required' => 'Pakipasok ang halaga ng gawain'],
            'num' => ['required' => 'Paki-enter ang bilang ng mga gawain', 'gt' => 'Ang bilang ng mga gawain ay dapat ay higit sa 0']
        ]
    ],
    'UserRecharge' => [
        'ManualRequest' => [
            'level'    => ['required' => 'Mangyaring piliin ang antas ng miyembro upang i-load muli', 'gt' => 'May pagkakamali sa pagpili ng antas ng miyembro, mangyaring piliin muli'],
            'trade_no' => ['required' => 'Paki-input ang serial number ng transaksyon'],
            'image'    => ['required' => 'Paki-upload ang payment screenshot', 'max' => 'Ang haba ng pagbabayad na screenshot address ay hindi maaaring lumalabas sa 255 na karakter']
        ],
        'OnlineRequest' => [
            'channel' => ['required' => 'Mangyaring piliin ang recharge channel'],
            'level'   => ['required' => 'Mangyaring piliin ang antas ng miyembro upang i-load muli', 'integer' => 'May pagkakamali sa pagpili ng antas ng miyembro, mangyaring piliin muli', 'gt' => 'May pagkakamali sa pagpili ng antas ng miyembro, mangyaring piliin muli']
        ],
        'BankRequest'   => [
            'country_id' => ['required' => 'Mangyaring pumili muna ang isang bansa'],
            'bank_name'  => ['required' => 'Paki-enter ang pangalan ng remittance bank'],
            'name'       => ['required' => 'Paki-enter ang pangalan ng remitter'],
            'bank'       => ['required' => 'Paki-input ang numero ng remittance card'],
            'amount'     => ['required' => 'Paki-enter ang halaga ng recharge'],
            'remittance' => ['required' => 'Paki-input ang halaga ng remittance']
        ],
        'LevelRequest' => [
            'level' => ['required' => 'Mangyaring piliin ang level ng recharge']
        ]
    ]
];