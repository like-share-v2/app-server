<?php
/**
 * @copyright
 * @version 1.0.0
 * @link  
 */

return [
    'Auth'         => [
        'LoginRequest'         => [
            'country_code' => ['required' => 'Hãy chọn quốc gia'],
            'account'  => ['required' => 'Nhập tài khoản đăng nhập', 'alpha_dash' => 'Sai định dạng tài khoản (chỉ có các chữ cái, số, gạch gạch và ô gạch được hỗ trợ)', 'between' => 'Lỗi định dạng tài khoản, dài 5-30'],
            'password' => ['required' => 'Nhập mật khẩu đăng nhập', 'alpha_dash' => 'Định dạng mật khẩu sai (chỉ có chữ cái và số, cũng như đường gạch và ô gạch được hỗ trợ)', 'between' => 'Lỗi định dạng mật khẩu, dài 6-30']
        ],
        'PhoneLoginRequest'    => [
            'phone' => ['required' => 'Hãy nhập số điện thoại di động', 'regex' => 'Lỗi định dạng số điện thoại di động'],
            'code'  => ['required' => 'Nhập mã số kiểm tra tin nhắn', 'size' => 'Nhập mã số kiểm tra tin nhắn']
        ],
        'RegisterRequest'      => [
            'country_id'      => ['required' => 'Hãy chọn một quốc gia'],
            'country_code'    => ['required' => 'Hãy chọn quốc gia'],
            'account'         => ['required' => 'Xin hãy nhập số tài khoản.', 'alpha_dash' => 'Sai định dạng tài khoản (chỉ có các chữ cái, số, gạch gạch và ô gạch được hỗ trợ)', 'between' => 'Lỗi định dạng tài khoản, dài 5-30'],
            'password'        => ['required' => 'Nhập mật khẩu đi.', 'alpha_dash' => 'Định dạng mật khẩu sai (chỉ có chữ cái và số, cũng như đường gạch và ô gạch được hỗ trợ)', 'between' => 'Lỗi định dạng mật khẩu, dài 5-30', 'confirmed' => 'Hai mật khẩu không khớp với nhau.'],
            'phone'           => ['required' => 'Hãy nhập số điện thoại di động', 'regex' => 'Định dạng sai của số điện thoại di động'],
            'code'            => ['required' => 'Hãy nhập mật mã kiểm tra.', 'digits' => 'Nhập mã số 6-số kiểm tra'],
            'invitation_code' => ['required' => 'Hãy nhập mã thư mời.']
        ],
        'ResetPasswordRequest' => [
            'country_code'    => ['required' => 'Hãy chọn quốc gia'],
            'account'  => ['required' => 'Xin hãy nhập số tài khoản.', 'alpha_dash' => 'Sai định dạng tài khoản (chỉ có các chữ cái, số, gạch gạch và ô gạch được hỗ trợ)', 'between' => 'Lỗi định dạng tài khoản, dài 5-30'],
            'password' => ['required' => 'Nhập mật khẩu đi.', 'alpha_dash' => 'Định dạng mật khẩu sai (chỉ có chữ cái và số, cũng như đường gạch và ô gạch được hỗ trợ)', 'between' => 'Lỗi định dạng mật khẩu, dài 6-30', 'confirmed' => 'Hai mật khẩu không khớp với nhau.'],
            'phone'    => ['required' => 'Hãy nhập số điện thoại di động', 'regex' => 'Định dạng sai của số điện thoại di động'],
            'code'     => ['required' => 'Hãy nhập mật mã kiểm tra.', 'digits' => 'Nhập mã số 6-số kiểm tra'],
        ]
    ],
    'Account'      => [
        'ChangeAvatarRequest'   => [
            'avatar' => ['required' => 'Vui lòng tải ảnh lên', 'max' => 'Độ dài của địa chỉ Avatar không thể vượt qua kí tự ：max.']
        ],
        'ChangeNicknameRequest' => [
            'nickname' => ['required' => 'Hãy nhập nickname của em', 'max' => 'Không thể đè lên độ dài ：max']
        ],
        'ChangePhoneRequest'    => [
            'phone' => ['required' => 'Hãy nhập số điện thoại di động', 'numeric' => 'Lỗi định dạng số điện thoại di động'],
            'code'  => ['required' => 'Nhập mật mã kiểm tra SMS.', 'digits' => 'Lỗi định dạng chuỗi']
        ],
        'ChangePasswordRequest' => [
            'old_password' => ['required' => 'Hãy nhập mật khẩu cũ.', 'between' => 'Chiều dài của mật khẩu cũ là dây 6-30'],
            'password'     => ['required' => 'Hãy nhập mật khẩu mới.', 'alpha_dash' => 'Lỗi định dạng mật khẩu mới (chỉ có chữ cái và số, cũng như đường gạch và ô gạch được hỗ trợ)', 'between' => 'Lỗi định dạng mật khẩu mới, dài 6-30', 'confirmed' => 'Hai mật khẩu không khớp với nhau.'],
            'trade_pass'   => ['alpha_dash' => 'Lỗi định dạng mật khẩu mới của rút lui (chỉ có các chữ cái và số, cũng như đường gạch và ô gạch được hỗ trợ)', 'between' => 'Lỗi định dạng mật khẩu mới, dài 6-30']
        ],
        'ChangeIdCardRequest'   => [
            'id_card' => ['required' => 'Xin nhập số thẻ ID', 'size' => 'Định dạng sai của số thẻ ID']
        ],
        'ChangeBankRequest'     => [
            'bank_name' => ['required' => 'Hãy chọn kiểu rút lui'],
            'bank_address' => ['required' => 'Hãy nhập địa chỉ ngân hàng.'],  
            'name'      => ['required' => 'Hãy nhập tên', 'between' => 'The length of the name is 2-20 characters'],
            'account'   => ['required' => 'Xin hãy nhập số tài khoản.', 'max' => 'Tính cách tối đa của tài khoản là 20'],
            'trade_pass'   => ['required' => 'Nhập mật khẩu rút lui']
        ],
        'ChangeGenderRequest'   => [
            'gender' => ['required' => 'Hãy chọn giới tính', 'in' => 'Chọn sai giới tính']
        ]
    ],
    'Task'         => [
        'SubmitRequest' => [
            'id'    => ['required' => 'Hãy chọn tác vụ'],
            'image' => ['required' => 'Tải ảnh chụp lại', 'max' => 'Độ dài của địa chỉ chụp ảnh không thể vượt qua kí tự 255']
        ],
        'TaskRequest' => [
            'category_id' => ['required' => 'Hãy chọn phân loại tác vụ'],
            'level' => ['required' => 'Hãy chọn cấp thành viên'],
            'title' => ['required' => 'Hãy nhập tên tác vụ', 'max' => 'Tên tác vụ không thể dài hơn cả :max'],
            'description' => ['required' => 'Hãy nhập hồ sơ tác vụ'],
            'url' => ['required' => 'Hãy nhập vào đường dẫn địa chỉ nhiệm vụ'],
            'amount' => ['required' => 'Hãy nhập số lượng nhiệm vụ'],
            'num' => ['required' => 'Hãy nhập số các công việc', 'gt' => 'Số phận phải lớn hơn 0']
        ]
    ],
    'UserRecharge' => [
        'ManualRequest' => [
            'level'    => ['required' => 'Hãy chọn cấp thành viên cần nạp lại', 'gt' => 'Lỗi chọn cấp thành viên, xin chọn lại'],
            'trade_no' => ['required' => 'Nhập số hàng loạt giao dịch'],
            'image'    => ['required' => 'Vui lòng tải màn hình thanh toán', 'max' => 'Độ dài của địa chỉ màn hình thanh toán không thể vượt qua kí tự 255']
        ],
        'OnlineRequest' => [
            'channel' => ['required' => 'Hãy chọn kênh phục hồi'],
            'level'   => ['required' => 'Hãy chọn cấp thành viên cần nạp lại', 'integer' => 'Lỗi chọn cấp thành viên, xin chọn lại', 'gt' => 'Lỗi chọn cấp thành viên, xin chọn lại']
        ],
        'BankRequest'   => [
            'country_id' => ['required' => 'Hãy chọn một quốc gia trước'],
            'bank_name'  => ['required' => 'Hãy nhập tên của ngân hàng chuyển tiền.'],
            'name'       => ['required' => 'Hãy nhập vào tên của bộ nhớ'],
            'bank'       => ['required' => 'Xin hãy nhập số thẻ tín dụng.'],
            'amount'     => ['required' => 'Hãy nhập số lượng nạp lại.'],
            'remittance' => ['required' => 'Xin hãy nhập số tiền chuyển.']
        ],
        'LevelRequest' => [
            'level' => ['required' => 'Hãy chọn cấp nạp năng lượng']
        ] 
    ]
];