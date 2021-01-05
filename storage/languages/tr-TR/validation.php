<?php
/**
 * @copyright
 * @version 1.0.0
 * @link  
 */

return [
    'Auth'         => [
        'LoginRequest'         => [
            'country_code' => ['required' => 'Lütfen ülke kodunu seçin'],
            'account'  => ['required' => 'Lütfen giriş hesabını girin', 'alpha_dash' => 'Hesap format ı hatası sadece mektupları ve numaraları, sıçanları ve altını destekliyor', 'between' => 'Hesap format ı hatası, 5-30 kate uzunluğu'],
            'password' => ['required' => 'Hesap format ı hatası, 5-30 kate uzunluğu', 'alpha_dash' => 'Yanlış parola format ı [sadece harfler ve numaralar, ve taşlar ve alt notlar]', 'between' => 'Parola format ı hatası, 6-30 kate uzunluğu']
        ],
        'PhoneLoginRequest'    => [
            'phone' => ['required' => 'Lütfen cep telefonunuzu girin', 'regex' => 'Cep telefonu numarası format ı hatası'],
            'code'  => ['required' => 'Lütfen 6 rakam SMS doğrulama kodu girin', 'size' => 'Lütfen 6 rakam SMS doğrulama kodu girin']
        ],
        'RegisterRequest'      => [
            'country_id'      => ['required' => 'Lütfen bir ülke seçin'],
            'country_code'    => ['required' => 'Lütfen ülke kodunu seçin'],
            'account'         => ['required' => 'Lütfen hesap numarasını girin', 'alpha_dash' => 'Hesap format ı hatası sadece mektupları ve numaraları, sıçanları ve altını destekliyor', 'between' => 'Hesap format ı hatası, 5-30 kate uzunluğu'],
            'password'        => ['required' => 'Lütfen bir parola girin', 'alpha_dash' => 'Yanlış parola format ı [sadece harfler ve sayılar, dashes ve alt notlar destekleniyor]', 'between' => 'Parola format ı hatası, 5-30 kate uzunluğu', 'confirmed' => 'İki parola uygunsuz.'],
            'phone'           => ['required' => 'Lütfen cep telefonunuzu girin', 'regex' => 'Cep telefonu numarasının yanlış biçimi'],
            'code'            => ['required' => 'Lütfen doğrulama kodunu girin', 'digits' => 'Lütfen 6 rakam doğrulama kodu girin'],
            'invitation_code' => ['required' => 'Lütfen davet kodunu girin']
        ],
        'ResetPasswordRequest' => [
            'country_code'    => ['required' => 'Lütfen ülke kodunu seçin'],
            'account'  => ['required' => 'Lütfen hesap numarasını girin', 'alpha_dash' => 'Hesap format ı hatası sadece mektupları ve numaraları, sıçanları ve altını destekliyor', 'between' => 'Hesap format ı hatası, 5-30 kate uzunluğu'],
            'password' => ['required' => 'Lütfen bir parola girin', 'alpha_dash' => 'Yanlış parola format ı [sadece harfler ve sayılar, dashes ve alt notlar destekleniyor]', 'between' => 'Parola format ı hatası, 6-30 kate uzunluğu', 'confirmed' => 'İki parola uygunsuz.'],
            'phone'    => ['required' => 'Lütfen cep telefonunuzu girin', 'regex' => 'Cep telefonu numarasının yanlış biçimi'],
            'code'     => ['required' => 'Lütfen doğrulama kodunu girin', 'digits' => 'Lütfen 6 rakam doğrulama kodu girin'],
        ]
    ],
    'Account'      => [
        'ChangeAvatarRequest'   => [
            'avatar' => ['required' => 'Lütfen resiminizi yükleyin', 'max' => 'Avatar adresinin uzunluğu aşamaz: max karakterler']
        ],
        'ChangeNicknameRequest' => [
            'nickname' => ['required' => 'Lütfen lağabınızı girin', 'max' => 'Lağap uzunluğu aşamaz: max karakterler']
        ],
        'ChangePhoneRequest'    => [
            'phone' => ['required' => 'Lütfen cep telefonunuzu girin', 'numeric' => 'Cep telefonu numarası format ı hatası', 'digits' => 'Mobil sayı format ı hatası: [: digits] uzunluğu'],
            'code'  => ['required' => 'Lütfen SMS doğrulama kodu girin', 'digits' => 'Verifik kodu format ı hatası: [doğru: digits rakam]']
        ],
        'ChangePasswordRequest' => [
            'old_password' => ['required' => 'Lütfen eski parolanı girin', 'between' => 'Eski parolanın uzunluğu 6-30 katedir'],
            'password'     => ['required' => 'Lütfen yeni bir parola girin', 'alpha_dash' => 'Yeni parola format ı hatası [sadece harfler ve sayılar, dashes ve alt notlar destekleniyor]', 'between' => 'Yeni parola format ı hatası, 6-30 kater uzunluğu', 'confirmed' => 'İki parola uygunsuz.'],
            'trade_pass'   => ['alpha_dash' => 'Yeni kaldırma şifre format ı hatası [sadece harfler ve numaralar, dashes ve altscores destekleniyor]', 'between' => 'Yeni çıkarma parola format ı hatası, 6-30 katel uzunluğu']
        ],
        'ChangeIdCardRequest'   => [
            'id_card' => ['required' => 'Lütfen kimlik kartı numarasını girin', 'size' => 'Kimlik kartı numarası yanlış format ı']
        ],
        'ChangeBankRequest'     => [
            'bank_name' => ['required' => 'Lütfen çekilme türünü seçin'],
            'bank_address' => ['required' => 'Lütfen banka adresini girin'],  
            'name'      => ['required' => 'Lütfen isim girin', 'between' => 'Adın uzunluğu 2-20 karakterdir.'],
            'account'   => ['required' => 'Lütfen hesap numarasını girin', 'max' => 'Hesabının maksimum uzunluğu 20 karakterdir.'],
            'trade_pass'   => ['required' => 'Lütfen çekilme parolanı girin']
        ],
        'ChangeGenderRequest'   => [
            'gender' => ['required' => 'Lütfen cinsel seçin', 'in' => 'Yanlış seks seçimi']
        ]
    ],
    'Task'         => [
        'SubmitRequest' => [
            'id'    => ['required' => 'Lütfen görevi seçin'],
            'image' => ['required' => 'Lütfen görev ekran fotoğrafını yükleyin', 'max' => 'Görev ekran görüntüsünün uzunluğu 255 karakterden fazla değil']
        ],
        'TaskRequest' => [
            'category_id' => ['required' => 'Lütfen görev kategorisini seçin'],
            'level' => ['required' => 'Lütfen üyelik seviyesini seçin'],
            'title' => ['required' => 'Lütfen görev başlığını girin', 'max' => 'Görev başlığının uzunluğu aşamaz: max karakterler'],
            'description' => ['required' => 'Lütfen görev profilini girin'],
            'url' => ['required' => 'Lütfen görev adresi bağlantısını girin'],
            'amount' => ['required' => 'Lütfen görev miktarını girin'],
            'num' => ['required' => 'Lütfen görevlerin sayısını girin', 'gt' => "Görevlerin sayısı 0'dan daha büyük olmalı."]
        ]
    ],
    'UserRecharge' => [
        'ManualRequest' => [
            'level'    => ['required' => 'Lütfen yeniden yüklemek için üye seviyesini seçin', 'gt' => 'Üye seviye seçim hatası, lütfen seçin'],
            'trade_no' => ['required' => 'Lütfen transaksyon seri numarasını girin'],
            'image'    => ['required' => 'Lütfen ödeme ekran fotoğrafını yükleyin', 'max' => 'Ödeme ekran görüntüsünün uzunluğu 255 karakterden fazla değil']
        ],
        'OnlineRequest' => [
            'channel' => ['required' => 'Lütfen yeniden yükleme kanalı seçin'],
            'level'   => ['required' => 'Lütfen yeniden yüklemek için üye seviyesini seçin', 'integer' => 'Üye seviye seçim hatası, lütfen seçin', 'gt' => 'Üye seviye seçim hatası, lütfen seçin']
        ],
        'BankRequest'   => [
            'country_id' => ['required' => 'Lütfen önce bir ülke seçin'],
            'bank_name'  => ['required' => 'Lütfen gönderme bankasının adını girin'],
            'name'       => ['required' => 'Lütfen hatırlatıcı adını girin'],
            'bank'       => ['required' => 'Lütfen gönderme kartı numarasını girin'],
            'amount'     => ['required' => 'Lütfen yeniden yükleme miktarını girin'],
            'remittance' => ['required' => 'Lütfen gönderme miktarını girin']
        ],
        'LevelRequest' => [
            'level' => ['required' => 'Lütfen yeniden yükleme seviyesini seçin']
        ]
    ]
];