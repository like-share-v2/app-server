<?php
/**
 * @copyright
 * @version   1.0.0
 * @link       
 */

return [
    'Auth'         => [
        'LoginRequest'         => [
            'country_code' => ['required' => 'Silakan pilih kode negara'],
            'account'      => ['required'   => 'Silakan masukkan akun daftar masuk',
                               'alpha_dash' => 'Format akun salah [hanya huruf dan angka, dash dan underscores didukung]',
                               'between'    => 'Format akun salah, panjang 5-30 karakter'
            ],
            'password'     => ['required'   => 'Silakan masukkan sandi daftar masuk',
                               'alpha_dash' => 'Format kata sandi salah [hanya huruf dan nomor, serta dashes dan underscores didukung]',
                               'between'    => 'Format sandi salah, panjang 6-30 karakter'
            ]
        ],
        'PhoneLoginRequest'    => [
            'phone' => ['required' => 'Silahkan masukkan nomor ponsel Anda', 'regex' => 'Format nomor ponsel salah'],
            'code'  => ['required' => 'Silahkan masukkan kode verifikasi SMS 6 digit',
                        'size'     => 'Silahkan masukkan kode verifikasi SMS 6 digit'
            ]
        ],
        'RegisterRequest'      => [
            'country_id'      => ['required' => 'Silahkan pilih negara'],
            'country_code'    => ['required' => 'Silahkan pilih kode negara'],
            'account'         => ['required'   => 'Silahkan masukkan akun',
                                  'alpha_dash' => 'Format akun salah [hanya huruf dan angka, dash dan underscores didukung]',
                                  'between'    => 'Galat format akun, panjang string 5-30'
            ],
            'password'        => ['required'   => 'Silahkan masukkan sandi',
                                  'alpha_dash' => 'Format kata sandi salah [hanya huruf dan nomor, serta dashes dan underscores didukung]',
                                  'between'    => 'Ganti format sandi, panjang 5-30 karakter',
                                  'confirmed'  => 'Dua kata sandi tidak konsisten'
            ],
            'phone'           => ['required' => 'Silahkan masukkan nomor ponsel Anda',
                                  'regex'    => 'Format nomor ponsel salah'
            ],
            'code'            => ['required' => 'Silahkan masukkan kode verifikasi',
                                  'digits'   => 'Silahkan masukkan kode verifikasi 6 digit'
            ],
            'invitation_code' => ['required' => 'Silahkan masukkan kode undangan']
        ],
        'ResetPasswordRequest' => [
            'country_code' => ['required' => 'Silahkan pilih kode negara'],
            'account'      => ['required'   => 'Silahkan masukkan nomor rekening',
                               'alpha_dash' => 'Format rekening salah [hanya huruf dan angka, dash dan underscores didukung]',
                               'between'    => 'Format akun salah, panjang 5-30 karakter'
            ],
            'password'     => ['required'   => 'Silahkan masukkan sandi',
                               'alpha_dash' => 'Format kata sandi salah [hanya huruf dan nomor, serta dashes dan underscores didukung]',
                               'between'    => 'Format sandi salah, panjang 6-30 karakter',
                               'confirmed'  => 'Dua kata sandi tidak konsisten'
            ],
            'phone'        => ['required' => 'Silahkan masukkan nomor ponsel Anda',
                               'regex'    => 'Format nomor ponsel yang salah'
            ],
            'code'         => ['required' => 'Silahkan masukkan kode verifikasi',
                               'digits'   => 'Silahkan masukkan kode verifikasi 6 digit'
            ],
        ]
    ],
    'Account'      => [
        'ChangeAvatarRequest'   => [
            'avatar' => ['required' => 'Silahkan mengunggah foto Anda',
                         'max'      => 'Panjang alamat avatar tidak dapat melebihi :max karakter'
            ]
        ],
        'ChangeNicknameRequest' => [
            'nickname' => ['required' => 'Silahkan masukkan panggilan Anda',
                           'max'      => 'Panjang nama panggilan tidak dapat melebihi :max karakter'
            ]
        ],
        'ChangePhoneRequest'    => [
            'phone' => ['required' => 'Silahkan masukkan nomor ponsel Anda',
                        'numeric'  => 'Format nomor ponsel salah',
                        'digits'   => 'Format nomor ponsel salah: [: digits] panjang'
            ],
            'code'  => ['required' => 'Silahkan masukkan kode verifikasi SMS',
                        'digits'   => 'Format kode verifikasi salah: [benar untuk :digits digit]'
            ]
        ],
        'ChangePasswordRequest' => [
            'old_password' => ['required' => 'Silahkan masukkan sandi lama',
                               'between'  => 'Panjang kata sandi lama adalah 6-30 karakter'
            ],
            'password'     => ['required'   => 'Silahkan masukkan kata sandi baru',
                               'alpha_dash' => 'Format sandi baru salah [hanya huruf dan nomor, serta dashes dan underscores didukung]',
                               'between'    => 'Format sandi baru salah, panjang 6-30 karakter',
                               'confirmed'  => 'Dua kata sandi tidak konsisten'
            ],
            'trade_pass'   => ['alpha_dash' => 'Format sandi penarikan baru salah [hanya huruf dan nomor, serta dashes dan underscores didukung]',
                               'between'    => 'Format sandi tarik baru salah, panjang 6-30 karakter'
            ]
        ],
        'ChangeIdCardRequest'   => [
            'id_card' => ['required' => 'Silahkan masukkan nomor identitas',
                          'size'     => 'Format nomor kartu identitas salah'
            ]
        ],
        'ChangeBankRequest'     => [
            'bank_name'    => ['required' => 'Silahkan pilih tipe penarikan'],
            'bank_address' => ['required' => 'Silahkan masukkan bank tujuan'],
            'name'         => ['required' => 'Silahkan masukkan nama',
                               'between'  => 'Panjang nama adalah 2-20 karakter'
            ],
            'account'      => ['required' => 'Silahkan masukkan nomor rekening',
                               'max'      => 'Panjang maksimum akun adalah 20 karakter'
            ],
            'trade_pass'   => ['required' => 'Silahkan masukkan sandi penarikan']
        ],
        'ChangeGenderRequest'   => [
            'gender' => ['required' => 'Silahkan pilih jenis kelamin', 'in' => 'Pemilihan jenis kelamin gagal']
        ]
    ],
    'Task'         => [
        'SubmitRequest' => [
            'id'    => ['required' => 'Silahkan pilih tugas'],
            'image' => ['required' => 'Silahkan mengunggah gambar tangkapan layar tugas',
                        'max'      => 'Panjang tangkapan layar tugas tidak dapat melebihi 255 karakter'
            ]
        ],
        'TaskRequest'   => [
            'category_id' => ['required' => 'Silahkan pilih kategori tugas'],
            'level'       => ['required' => 'Silahkan pilih tingkat anggota'],
            'title'       => ['required' => 'Silahkan masukkan judul tugas',
                              'max'      => 'Judul tugas tidak dapat melebihi dari :max karakter'
            ],
            'description' => ['required' => 'Silahkan masukkan penjelasan singkat tugas'],
            'url'         => ['required' => 'Silahkan masukkan link alamat tugas'],
            'amount'      => ['required' => 'Silahkan masukkan pendapatan tugas'],
            'num'         => ['required' => 'Silahkan masukkan jumlah tugas',
                              'gt'       => 'Jumlah tugas harus lebih besar dari 0'
            ]
        ]
    ],
    'UserRecharge' => [
        'ManualRequest' => [
            'level'    => ['required' => 'Silahkan pilih tingkat anggota yang ingin diisi ulang',
                           'gt'       => 'Pemilihan tingkat anggota gagal, silakan pilih kembali'
            ],
            'trade_no' => ['required' => 'Silahkan masukkan nomor seri transaksi'],
            'image'    => ['required' => 'Silahkan mengunggah gambar tangkapan layar pembayaran',
                           'max'      => 'Panjang alamat foto layar pembayaran tidak dapat melebihi 255 karakter'
            ]
        ],
        'OnlineRequest' => [
            'channel' => ['required' => 'Silahkan pilih saluran isi ulang'],
            'level'   => ['required' => 'Silahkan pilih tingkat anggota untuk memuat ulang',
                          'integer'  => 'Pemilihan tingkat anggota gagal, silahkan pilih kembali',
                          'gt'       => 'Pemilihan tingkat anggota gagal, silahkan pilih kembali'
            ]
        ],
        'BankRequest'   => [
            'country_id' => ['required' => 'Silahkan pilih negara'],
            'bank_name'  => ['required' => 'Silahkan masukkan nama bank tujuan'],
            'name'       => ['required' => 'Silahkan masukkan nama penerima'],
            'bank'       => ['required' => 'Silahkan masukkan nomor kartu tujuan'],
            'amount'     => ['required' => 'Silahkan masukkan jumlah saldo isi ulang'],
            'remittance' => ['required' => 'Silahkan masukkan jumlah saldo penerimaan']
        ],
        'LevelRequest'  => [
            'level' => ['required' => 'Silahkan pilih tingkat isi ulang']
        ]
    ]
];