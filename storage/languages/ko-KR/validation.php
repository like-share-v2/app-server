<?php
/**
 * @copyright
 * @version 1.0.0
 * @link
 */

return [
    'Auth'         => [
        'LoginRequest'         => [
            'country_code' => ['required' => '국가 지역 번 호 를 선택 하 십시오.'],
            'account'  => ['required' => '로그 인 계 정 을 입력 하 세 요', 'alpha_dash' => '계 정 형식 오류 [알파벳 과 숫자 만 지원 하고 디 폴 트 와 밑줄 만 지원 합 니 다]', 'between' => '계 정 형식 오류, 5 - 30 개 문자열 길이'],
            'password' => ['required' => '로그 인 비밀번호 입력', 'alpha_dash' => '암호 형식 오 류 는 알파벳 과 숫자, 그리고 디 폴 트 와 밑줄 만 지원 합 니 다.', 'between' => '비밀번호 형식 오류, 6 - 30 개 문자열 길이']
        ],
        'PhoneLoginRequest'    => [
            'phone' => ['required' => '핸드폰 번 호 를 입력 하 세 요.', 'regex' => '핸드폰 번호 가 틀 렸 다.'],
            'code'  => ['required' => '6 비트 메시지 인증번호 입력', 'size' => '6 비트 메시지 인증번호 입력']
        ],
        'RegisterRequest'      => [
            'country_id'      => ['required' => '나 라 를 선택 하 세 요.'],
            'country_code'    => ['required' => '국가 지역 번 호 를 선택 하 십시오.'],
            'account'         => ['required' => '계 정 을 입력 하 세 요', 'alpha_dash' => '계 정 형식 오류 [알파벳 과 숫자 만 지원 하고 디 폴 트 와 밑줄 만 지원 합 니 다]', 'between' => '계 정 형식 오류, 5 - 30 개 문자열 길이'],
            'password'        => ['required' => '비밀 번 호 를 입력 하 세 요.', 'alpha_dash' => '암호 형식 오 류 는 알파벳 과 숫자, 그리고 디 폴 트 와 밑줄 만 지원 합 니 다.', 'between' => '비밀번호 형식 오류, 5 - 30 개 문자열 길이', 'confirmed' => '두 번 의 입력 비밀번호 가 일치 하지 않 습 니 다.'],
            'phone'           => ['required' => '핸드폰 번 호 를 입력 하 세 요.', 'regex' => '핸드폰 번호 의 양식 이 정확 하지 않다.'],
            'code'            => ['required' => '인증번호 입력', 'digits' => '6 자리 숫자 인증번호 입력'],
            'invitation_code' => ['required' => '요청 코드 를 입력 하 세 요']
        ],
        'ResetPasswordRequest' => [
            'country_code'    => ['required' => '국가 지역 번 호 를 선택 하 십시오.'],
            'account'  => ['required' => '계 정 을 입력 하 세 ', 'alpha_dash' => '계 정 형식 오류 [알파벳 과 숫자 만 지원 하고 디 폴 트 와 밑줄 만 지원 합 니 다]', 'between' => '계 정 형식 오류, 5 - 30 개 문자열 길이'],
            'password' => ['required' => '비밀 번 호 를 입력 하 세 요.', 'alpha_dash' => '암호 형식 이 틀 리 면 알파벳 과 숫자, 그리고 디 폴 트 와 밑줄 만 지원 합 니 다.', 'between' => '비밀번호 형식 오류, 6 - 30 개 문자열 길이', 'confirmed' => '두 번 의 입력 비밀번호 가 일치 하지 않 습 니 다.'],
            'phone'    => ['required' => '핸드폰 번 호 를 입력 하 세 요.', 'regex' => '핸드폰 번호 의 양식 이 정확 하지 않다.'],
            'code'     => ['required' => '인증번호 입력', 'digits' => '6 자리 숫자 인증번호 입력'],
        ]
    ],
    'Account'      => [
        'ChangeAvatarRequest'   => [
            'avatar' => ['required' => '프로필 사진 올 려 주세요.', 'max' => '프로필 사진 주소 길이 초과 불가: max 개 문자']
        ],
        'ChangeNicknameRequest' => [
            'nickname' => ['required' => '닉네임 을 입력 하 세 요', 'max' => '닉네임 길이 초과 불가: max 개 문자']
        ],
        'ChangePhoneRequest'    => [
            'phone' => ['required' => '핸드폰 번 호 를 입력 하 세 요.', 'numeric' => '핸드폰 번호 형식 오류', 'digits' => '핸드폰 번호 형식 오류: [: digits] 길이'],
            'code'  => ['required' => '문자 인증 번 호 를 입력 하 세 요.', 'digits' => '인증번호 형식 오류: [정확: digits 비트 숫자]']
        ],
        'ChangePasswordRequest' => [
            'old_password' => ['required' => '오래된 비밀 번 호 를 입력 하 세 요.', 'between' => '오래된 비밀번호 길 이 는 6 ~ 30 개의 문자열 길이 입 니 다.'],
            'password'     => ['required' => '새 암 호 를 입력 하 세 요', 'alpha_dash' => ' 새 암호 형식 오류 입 니 다. [알파벳 과 숫자 만 지원 하고, 디 폴 트 와 밑줄 만 지원 합 니 다.]', 'between' => '새 비밀번호 형식 오류, 6 - 30 개 문자열 길이', 'confirmed' => '두 번 의 입력 비밀번호 가 일치 하지 않 습 니 다.'],
            'trade_pass'   => ['alpha_dash' => '새 인출 비밀번호 의 형식 이 잘못 되 었 습 니 다. [알파벳 과 숫자 만 지원 하고, 디 폴 트 와 밑줄 만 지원 합 니 다.]', 'between' => '신규 인출 비밀번호 형식 오류, 6 - 30 개 문자열 길이']
        ],
        'ChangeIdCardRequest'   => [
            'id_card' => ['required' => '주민등록번호 입력 하 세 요', 'size' => '주민등록번호 의 격식 이 정확 하지 않다']
        ],
        'ChangeBankRequest'     => [
            'bank_name' => ['required' => '현금 인출 유형 을 선택 하 세 요'],
            'bank_address' => ['required' => '계좌 개설 은행 주 소 를 입력 하 세 요.'],  
            'name'      => ['required' => '이름 을 입력 하 세 요', 'between' => '이름 길이 가 2 ~ 20 글자 입 니 다.'],
            'account'   => ['required' => '계 정 을 입력 하 세 요', 'max' => '계 정 길이 최대 20 글자'],
            'trade_pass'   => ['required' => '현금 인출 비밀 번 호 를 입력 하 세 요.']
        ],
        'ChangeGenderRequest'   => [
            'gender' => ['required' => '성별 을 선택 하 세 요', 'in' => '성별 선택 에 착오 가 있다.']
        ]
    ],
    'Task'         => [
        'SubmitRequest' => [
            'id'    => ['required' => '퀘 스 트 를 선택 하 세 요'],
            'image' => ['required' => '미 션 캡 처 올 려 주세요.', 'max' => '미 션 캡 처 주소 길이 255 글자 넘 으 면 안 됩 니 다.']
        ],
        'TaskRequest' => [
            'category_id' => ['required' => '퀘 스 트 분 류 를 선택 하 세 요'],
            'level' => ['required' => '회원 레벨 을 선택 하 세 요'],
            'title' => ['required' => '퀘 스 트 제목 을 입력 하 세 요', 'max' => '퀘 스 트 제목 길이 초과 불가: max 개 문자'],
            'description' => ['required' => '퀘 스 트 프로필 을 입력 하 세 요'],
            'url' => ['required' => '퀘 스 트 주소 링크 입력'],
            'amount' => ['required' => '퀘 스 트 금액 을 입력 하 세 요'],
            'num' => ['required' => '퀘 스 트 수량 을 입력 하 세 요', 'gt' => '퀘 스 트 수량 이 0 이상 이 어야 합 니 다']
        ]
    ],
    'UserRecharge' => [
        'ManualRequest' => [
            'level'    => ['required' => '충전 할 회원 등급 을 선택 하 세 요', 'gt' => '회원 등급 의 선택 이 잘못 되 었 습 니 다. 다시 선택 하 십시오.'],
            'trade_no' => ['required' => '거래 흐름 번 호 를 입력 하 세 요'],
            'image'    => ['required' => '결제 캡 처 올 려 주세요.', 'max' => '지불 캡 처 주소 길 이 는 255 자 를 초과 할 수 없습니다.']
        ],
        'OnlineRequest' => [
            'channel' => ['required' => '충전 루트 를 선택 하 세 요'],
            'level'   => ['required' => '충전 할 회원 등급 을 선택 하 세 요', 'integer' => '회원 등급 의 선택 이 잘못 되 었 습 니 다. 다시 선택 하 십시오.', 'gt' => '회원 등급 의 선택 이 잘못 되 었 습 니 다. 다시 선택 하 십시오.']
        ],
        'BankRequest'   => [
            'country_id' => ['required' => '먼저 나 라 를 선택 하 세 요.'],
            'bank_name'  => ['required' => '입금 은행 이름 입력 해 주세요.'],
            'name'       => ['required' => '입금 자 이름 입력 해 주세요.'],
            'bank'       => ['required' => '입금 카드 번 호 를 입력 해 주세요.'],
            'amount'     => ['required' => '충전 금액 을 입력 하 세 요'],
            'remittance' => ['required' => '입금 금액 입력 해 주세요.']
        ],
        'LevelRequest' => [
            'level' => ['required' => '충전 레벨 을 선택 하 세 요']
        ]   
    ]
];