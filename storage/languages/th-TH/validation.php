<?php
/**
 * @copyright
 * @version 1.0.0
 * @link
 */

return [
    'Auth'         => [
        'LoginRequest'         => [
            'country_code' => ['required' => 'กรุณาเลือกรหัสประเทศ'],
            'account'  => ['required' => 'กรุณาใส่หมายเลขบัญชีเข้าสู่ระบบ', 'alpha_dash' => 'ข้อผิดพลาดในรูปแบบบัญชีสนับสนุนเพียงตัวอักษรและตัวเลขรวมทั้งเส้นประและขีดเส้นใต้', 'between' => 'รูปแบบบัญชีผิดพลาด 5-30 ความยาวสตริง'],
            'password' => ['required' => 'กรุณาใส่รหัสผ่านเข้าสู่ระบบ', 'alpha_dash' => 'รหัสผ่านรูปแบบไม่ถูกต้องเท่านั้นสนับสนุนตัวอักษรและตัวเลขรวมทั้งเครื่องหมายเส้นประและขีดเส้นใต้”', 'between' => 'รหัสผ่านรูปแบบข้อผิดพลาด 6-30 ความยาวสตริง']
        ],
        'PhoneLoginRequest'    => [
            'phone' => ['required' => 'กรุณาใส่หมายเลขโทรศัพท์มือถือของคุณ', 'regex' => 'ข้อผิดพลาดรูปแบบหมายเลขโทรศัพท์มือถือ'],
            'code'  => ['required' => 'กรุณาใส่ 6-bit SMS ตรวจสอบรหัส', 'size' => 'กรุณาใส่ 6-bit SMS ตรวจสอบรหัส']
        ],
        'RegisterRequest'      => [
            'country_id'      => ['required' => 'กรุณาเลือกประเทศ'],
            'country_code'    => ['required' => 'กรุณาเลือกรหัสประเทศ'],
            'account'         => ['required' => 'กรุณาใส่หมายเลขบัญชี', 'alpha_dash' => 'ข้อผิดพลาดในรูปแบบบัญชีสนับสนุนเพียงตัวอักษรและตัวเลขรวมทั้งเส้นประและขีดเส้นใต้', 'between' => 'รูปแบบบัญชีผิดพลาด 5-30 ความยาวสตริง'],
            'password'        => ['required' => 'กรุณาใส่รหัสผ่าน', 'alpha_dash' => 'รหัสผ่านรูปแบบไม่ถูกต้องเท่านั้นสนับสนุนตัวอักษรและตัวเลขรวมทั้งเครื่องหมายเส้นประและขีดเส้นใต้”', 'between' => 'รหัสผ่านรูปแบบไม่ถูกต้อง 5-30 ความยาวสตริง', 'confirmed' => 'สองป้อนรหัสผ่านที่ไม่สอดคล้องกัน'],
            'phone'           => ['required' => 'กรุณาใส่หมายเลขโทรศัพท์มือถือของคุณ', 'regex' => 'รูปแบบหมายเลขโทรศัพท์มือถือไม่ถูกต้อง'],
            'code'            => ['required' => 'กรุณาใส่รหัสการตรวจสอบ', 'digits' => 'กรุณาใส่ 6-bit ดิจิตอลการตรวจสอบรหัส'],
            'invitation_code' => ['required' => 'กรุณาใส่รหัสเชิญ']
        ],
        'ResetPasswordRequest' => [
            'country_code'    => ['required' => 'กรุณาเลือกรหัสประเทศ'],
            'account'  => ['required' => 'กรุณาใส่หมายเลขบัญชี', 'alpha_dash' => 'ข้อผิดพลาดในรูปแบบบัญชีสนับสนุนเพียงตัวอักษรและตัวเลขรวมทั้งเส้นประและขีดเส้นใต้', 'between' => 'รูปแบบบัญชีผิดพลาด 5-30 ความยาวสตริง'],
            'password' => ['required' => 'กรุณาใส่รหัสผ่าน', 'alpha_dash' => 'รหัสผ่านรูปแบบไม่ถูกต้องเท่านั้นสนับสนุนตัวอักษรและตัวเลขรวมทั้งเครื่องหมายเส้นประและขีดเส้นใต้”', 'between' => 'รหัสผ่านรูปแบบข้อผิดพลาด 6-30 ความยาวสตริง', 'confirmed' => 'สองป้อนรหัสผ่านที่ไม่สอดคล้องกัน'],
            'phone'    => ['required' => 'กรุณาใส่หมายเลขโทรศัพท์มือถือของคุณ', 'regex' => 'รูปแบบหมายเลขโทรศัพท์มือถือไม่ถูกต้อง'],
            'code'     => ['required' => 'กรุณาใส่รหัสการตรวจสอบ', 'digits' => 'กรุณาใส่ 6-bit ดิจิตอลการตรวจสอบรหัส'],
        ]
    ],
    'Account'      => [
        'ChangeAvatarRequest'   => [
            'avatar' => ['required' => 'กรุณาอัปโหลดภาพ', 'max' => 'หัวที่อยู่ไม่เกิน :max อักขระ']
        ],
        'ChangeNicknameRequest' => [
            'nickname' => ['required' => 'กรุณาใส่ชื่อเล่น', 'max' => 'ชื่อเล่นความยาวไม่เกิน :max ตัวอักษร']
        ],
        'ChangePhoneRequest'    => [
            'phone' => ['required' => 'กรุณาใส่หมายเลขโทรศัพท์มือถือของคุณ', 'numeric' => 'รูปแบบหมายเลขโทรศัพท์มือถือผิด', 'digits' => 'รูปแบบหมายเลขโทรศัพท์ผิด :digits ความยาว'],
            'code'  => ['required' => 'กรุณาใส่รหัสการตรวจสอบข้อความ', 'digits' => 'รหัสการตรวจสอบรูปแบบข้อผิดพลาดที่ถูกต้องสำหรับ :digits บิตดิจิตอล']
        ],
        'ChangePasswordRequest' => [
            'old_password' => ['required' => 'กรุณาใส่รหัสผ่านเก่า', 'between' => 'ความยาวของรหัสผ่านเก่าเป็น 6-12630 สตริงความยาว'],
            'password'     => ['required' => 'กรุณาใส่รหัสผ่านใหม่', 'alpha_dash' => 'รูปแบบรหัสผ่านใหม่ไม่ถูกต้องสนับสนุนเพียงตัวอักษรและตัวเลขรวมทั้งเครื่องหมายเส้นประและขีดเส้นใต้’', 'between' => 'ข้อผิดพลาดรูปแบบรหัสผ่านใหม่ 6-30 ความยาวสตริง', 'confirmed' => 'สองป้อนรหัสผ่านที่ไม่สอดคล้องกัน'],
            'trade_pass'   => ['alpha_dash' => 'รหัสผ่านการถอนเงินใหม่รูปแบบข้อผิดพลาด 91s สนับสนุนเพียงตัวอักษรและตัวเลขรวมทั้งเครื่องหมายและขีดเส้นใต้ 93s', 'between' => 'รูปแบบรหัสผ่านการถอนเงินใหม่ไม่ถูกต้อง 6-30 ความยาวสตริง']
        ],
        'ChangeIdCardRequest'   => [
            'id_card' => ['required' => 'กรุณาใส่หมายเลขบัตรประชาชน', 'size' => 'หมายเลขบัตรประชาชนรูปแบบไม่ถูกต้อง']
        ],
        'ChangeBankRequest'     => [
            'bank_name' => ['required' => 'กรุณาเลือกประเภทเงินสด'],
            'bank_address' => ['required' => 'กรุณาระบุที่อยู่ของบัญชีธนาคาร'],  
            'name'      => ['required' => 'กรุณาใส่ชื่อ', 'between' => 'ความยาวของชื่อคือ'],
            'account'   => ['required' => 'กรุณาใส่หมายเลขบัญชี', 'max' => 'ความยาวบัญชีสูงสุดสำหรับ'],
            'trade_pass'   => ['required' => 'กรุณาใส่รหัสผ่าน']
        ],
        'ChangeGenderRequest'   => [
            'gender' => ['required' => 'กรุณาเลือกเพศ', 'in' => 'ความผิดพลาดในการเลือกเพศ']
        ]
    ],
    'Task'         => [
        'SubmitRequest' => [
            'id'    => ['required' => 'เลือกงาน'],
            'image' => ['required' => 'กรุณาอัปโหลดภาพหน้าจอของงาน', 'max' => 'ความยาวหน้าจอที่อยู่ไม่เกิน 255 อักขระ']
        ],
        'TaskRequest' => [
            'category_id' => ['required' => 'เลือกหมวดหมู่งาน'],
            'level' => ['required' => 'กรุณาเลือกระดับสมาชิก'],
            'title' => ['required' => 'ป้อนชื่องาน', 'max' => 'งานส่วนหัวไม่เกิน :max ตัวอักษร'],
            'description' => ['required' => 'โปรดระบุรายละเอียดงาน'],
            'url' => ['required' => 'ป้อนที่อยู่งานลิงค์'],
            'amount' => ['required' => 'กรุณาระบุจำนวนของงาน'],
            'num' => ['required' => 'ระบุหมายเลขของงาน', 'gt' => 'จำนวนงานต้องมากกว่า NU 0']
        ]
    ],
    'UserRecharge' => [
        'ManualRequest' => [
            'level'    => ['required' => 'เลือกระดับสมาชิกที่คุณต้องการเติมเงิน', 'gt' => 'ข้อผิดพลาดในการเลือกระดับสมาชิกกรุณาเลือกอีกครั้ง'],
            'trade_no' => ['required' => 'กรุณาใส่หมายเลขการไหลของธุรกรรม'],
            'image'    => ['required' => 'กรุณาอัปโหลดหน้าจอการชำระเงิน', 'max' => 'ความยาวหน้าจอที่อยู่ไม่เกิน 255 ตัวอักษร']
        ],
        'OnlineRequest' => [
            'channel' => ['required' => 'กรุณาเลือกช่องเติมเงิน'],
            'level'   => ['required' => 'เลือกระดับสมาชิกที่คุณต้องการเติมเงิน', 'integer' => 'ข้อผิดพลาดในการเลือกระดับสมาชิกกรุณาเลือกอีกครั้ง', 'gt' => 'ข้อผิดพลาดในการเลือกระดับสมาชิกกรุณาเลือกอีกครั้ง']
        ],
        'BankRequest'   => [
            'country_id' => ['required' => 'กรุณาเลือกประเทศก่อน'],
            'bank_name'  => ['required' => 'กรุณาใส่ชื่อธนาคาร'],
            'name'       => ['required' => 'กรุณาใส่ชื่อของผู้ส่ง'],
            'bank'       => ['required' => 'กรุณาใส่หมายเลขบัตร'],
            'amount'     => ['required' => 'กรุณาป้อนค่าใช้จ่าย'],
            'remittance' => ['required' => 'กรุณาระบุยอดเงินโอนเงิน']
        ],
        'LevelRequest' => [
            'level' => ['required' => 'เลือกระดับเติมเงิน']
        ]   
    ]
];