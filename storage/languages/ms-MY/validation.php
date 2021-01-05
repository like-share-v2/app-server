<?php
/**
 * @copyright
 * @version 1.0.0
 * @link  
 */

return [
    'Auth'         => [
        'LoginRequest'         => [
            'country_code' => ['required' => 'Sila pilih kod negara'],
            'account'  => ['required' => 'Sila masukkan akaun log masuk', 'alpha_dash' => 'Format akaun salah [hanya huruf dan nombor, dash dan bawah sorotan disokong]', 'between' => 'Ralat format akaun, panjang rentetan 5-30'],
            'password' => ['required' => 'Sila masukkan kata laluan log masuk', 'alpha_dash' => 'Format kata laluan salah [hanya huruf dan nombor, serta dashes dan underscores disokong]', 'between' => 'Ralat format kata laluan, panjang rentetan 6-30']
        ],
        'PhoneLoginRequest'    => [
            'phone' => ['required' => 'Sila masukkan nombor telefon bimbit anda', 'regex' => 'Ralat format nombor telefon bimbit'],
            'code'  => ['required' => 'Sila masukkan kod pengesahan SMS 6-digit', 'size' => 'Sila masukkan kod pengesahan SMS 6-digit']
        ],
        'RegisterRequest'      => [
            'country_id'      => ['required' => 'Sila pilih negara'],
            'country_code'    => ['required' => 'Sila pilih kod negara'],
            'account'         => ['required' => 'Sila masukkan nombor akaun', 'alpha_dash' => 'Format akaun salah [hanya huruf dan nombor, dash dan bawah sorotan disokong]', 'between' => 'Ralat format akaun, panjang rentetan 5-30'],
            'password'        => ['required' => 'Sila masukkan kata laluan', 'alpha_dash' => 'Format kata laluan salah [hanya huruf dan nombor, serta dashes dan underscores disokong]', 'between' => 'Ralat format kata laluan, panjang rentetan 5-30', 'confirmed' => 'Dua kata laluan tidak konsisten'],
            'phone'           => ['required' => 'Sila masukkan nombor telefon bimbit anda', 'regex' => 'Format nombor telefon bimbit tidak betul'],
            'code'            => ['required' => 'Sila masukkan kod pengesahan', 'digits' => 'Sila masukkan kod pengesahan 6-digit'],
            'invitation_code' => ['required' => 'Sila masukkan kod undangan']
        ],
        'ResetPasswordRequest' => [
            'country_code'    => ['required' => 'Sila pilih kod negara'],
            'account'  => ['required' => 'Sila masukkan nombor akaun', 'alpha_dash' => 'Format akaun salah [hanya huruf dan nombor, dash dan bawah sorotan disokong]', 'between' => 'Ralat format akaun, panjang rentetan 5-30'],
            'password' => ['required' => 'Sila masukkan kata laluan', 'alpha_dash' => 'Format kata laluan salah [hanya huruf dan nombor, serta dashes dan underscores disokong]', 'between' => 'Ralat format kata laluan, panjang rentetan 6-30', 'confirmed' => 'Dua kata laluan tidak konsisten'],
            'phone'    => ['required' => 'Sila masukkan nombor telefon bimbit anda', 'regex' => 'Format nombor telefon bimbit tidak betul'],
            'code'     => ['required' => 'Sila masukkan kod pengesahan', 'digits' => 'Sila masukkan kod pengesahan 6-digit'],
        ]
    ],
    'Account'      => [
        'ChangeAvatarRequest'   => [
            'avatar' => ['required' => 'Sila muat naik gambar anda', 'max' => 'Panjang alamat avatar tidak boleh melebihi :max aksara']
        ],
        'ChangeNicknameRequest' => [
            'nickname' => ['required' => 'Sila masukkan gelaran anda', 'max' => 'Panjang gelaran tidak dapat melebihi :max aksara']
        ],
        'ChangePhoneRequest'    => [
            'phone' => ['required' => 'Sila masukkan nombor telefon bimbit anda', 'numeric' => 'Ralat format nombor telefon bimbit', 'digits' => 'Ralat format nombor telefon bimbit: [: digits] panjang'],
            'code'  => ['required' => 'Sila masukkan kod pengesahan SMS', 'digits' => 'Ralat format kod pengesahihan: [betul untuk :digits digit]']
        ],
        'ChangePasswordRequest' => [
            'old_password' => ['required' => 'Sila masukkan kata laluan lama', 'between' => 'Panjang kata laluan lama adalah 6-30 rentetan'],
            'password'     => ['required' => 'Sila masukkan kata laluan baru', 'alpha_dash' => 'Ralat format kata laluan baru [hanya huruf dan nombor, serta dashes dan underscores disokong]', 'between' => 'Ralat format kata laluan baru, panjang rentetan 6-30', 'confirmed' => 'Dua kata laluan tidak konsisten'],
            'trade_pass'   => ['alpha_dash' => 'Ralat format kata laluan tarik baru [hanya huruf dan nombor, serta dashes dan underscores disokong]', 'between' => 'Ralat format kata laluan tarik baru, panjang rentetan 6-30']
        ],
        'ChangeIdCardRequest'   => [
            'id_card' => ['required' => 'Sila masukkan nombor kad ID', 'size' => 'Format nombor kad ID tidak betul']
        ],
        'ChangeBankRequest'     => [
            'bank_name' => ['required' => 'Sila pilih jenis penarikan'],
            'bank_address' => ['required' => 'Sila masukkan alamat bank'],  
            'name'      => ['required' => 'Sila masukkan nama', 'between' => 'Panjang nama adalah 2-20 aksara'],
            'account'   => ['required' => 'Sila masukkan nombor akaun', 'max' => 'Panjang maksimum akaun adalah 20 aksara'],
            'trade_pass'   => ['required' => 'Sila masukkan kata laluan penarikan']
        ],
        'ChangeGenderRequest'   => [
            'gender' => ['required' => 'Sila pilih jenis', 'in' => 'Pilihan seks salah']
        ]
    ],
    'Task'         => [
        'SubmitRequest' => [
            'id'    => ['required' => 'Sila pilih tugas'],
            'image' => ['required' => 'Sila muat naik skrin tugas', 'max' => 'Panjang alamat cekupan skrin tugas tidak boleh melebihi 255 aksara']
        ],
        'TaskRequest' => [
            'category_id' => ['required' => 'Sila pilih kategori tugas'],
            'level' => ['required' => 'Sila pilih aras ahli'],
            'title' => ['required' => 'Sila masukkan tajuk tugas', 'max' => 'Tajuk tugas tidak boleh lebih lama daripada :nmax aksara'],
            'description' => ['required' => 'Sila masukkan profil tugas'],
            'url' => ['required' => 'Sila masukkan pautan alamat tugas'],
            'amount' => ['required' => 'Sila masukkan jumlah tugas'],
            'num' => ['required' => 'Sila masukkan bilangan tugas', 'gt' => 'Bilangan tugas mesti lebih besar dari 0']
        ]
    ],
    'UserRecharge' => [
        'ManualRequest' => [
            'level'    => ['required' => 'Sila pilih aras ahli untuk dimuatkan semula', 'gt' => 'Ralat pemilihan aras ahli, sila pilih semula'],
            'trade_no' => ['required' => 'Sila masukkan nombor siri transaksi'],
            'image'    => ['required' => 'Sila muat naik skrin pembayaran', 'max' => 'Panjang alamat gambar skrin pembayaran tidak boleh melebihi 255 aksara']
        ],
        'OnlineRequest' => [
            'channel' => ['required' => 'Sila pilih saluran muat semula'],
            'level'   => ['required' => 'Sila pilih aras ahli untuk dimuatkan semula', 'integer' => 'Ralat pemilihan aras ahli, sila pilih semula', 'gt' => 'Ralat pemilihan aras ahli, sila pilih semula']
        ],
        'BankRequest'   => [
            'country_id' => ['required' => 'Sila pilih negara dahulu'],
            'bank_name'  => ['required' => 'Sila masukkan nama bank penghantaran'],
            'name'       => ['required' => 'Sila masukkan nama penghantar'],
            'bank'       => ['required' => 'Sila masukkan nombor kad penghantaran'],
            'amount'     => ['required' => 'Sila masukkan jumlah muatan semula'],
            'remittance' => ['required' => 'Sila masukkan jumlah penghantaran']
        ],
        'LevelRequest' => [
            'level' => ['required' => 'Sila pilih aras muat semula']
        ]
    ]
];