<?php
/**
 * @copyright
 * @version 1.0.0
 * @link
 */

return [
    'Auth'         => [
        'LoginRequest'         => [
            'country_code' => ['required' => 'कृपया देश कोड चुनें'],
            'account'  => ['required' => 'कृपया लागइन खाता इनपुट करें', 'alpha_dash' => 'खाता फ़ॉर्मेट त्रुटि सिर्फ अक्षरों और संख्याओं को समर्थन करता है, तथा डैश और अंडरस्कोर', 'between' => 'खाता प्रारूप त्रुटि, 5-30 स्ट्रिंग लम्बाई'],
            'password' => ['required' => 'कृपया लॉगइन पासवर्ड प्रविष्ट करें', 'alpha_dash' => 'पासवर्ड फ़ॉर्मेट त्रुटि. सिर्फ अक्षर तथा संख्या, डैश तथा अंडरस्कोर समर्थित हैं', 'between' => 'पासवर्ड फ़ॉर्मेट त्रुटि, 6- 30 स्ट्रिंग लम्बाई']
        ],
        'PhoneLoginRequest'    => [
            'phone' => ['required' => 'कृपया अपना मोबाइल फोन संख्या भरें', 'regex' => 'मोबाइल फोन क्रमांक फ़ॉर्मेट त्रुटि'],
            'code'  => ['required' => 'कृपया 6- अंक SMS पुष्टिकरण कोड इनपुट करें', 'size' => 'कृपया 6- अंक SMS पुष्टिकरण कोड इनपुट करें']
        ],
        'RegisterRequest'      => [
            'country_id'      => ['required' => 'कृपया एक देश चुनें'],
            'country_code'    => ['required' => 'कृपया देश कोड चुनें'],
            'account'         => ['required' => 'कृपया खाता संख्या भरें', 'alpha_dash' => 'खाता फ़ॉर्मेट त्रुटि सिर्फ अक्षरों और संख्याओं को समर्थन करता है, तथा डैश और अंडरस्कोर', 'between' => 'खाता प्रारूप त्रुटि, 5-30 स्ट्रिंग लम्बाई'],
            'password'        => ['required' => 'कृपया पासवर्ड प्रविष्ट करें', 'alpha_dash' => 'पासवर्ड फ़ॉर्मेट त्रुटि. सिर्फ अक्षर तथा संख्या, डैश तथा अंडरस्कोर समर्थित हैं', 'between' => 'पासवर्ड फ़ॉर्मेट त्रुटि, 5- 30 स्ट्रिंग लम्बाई', 'confirmed' => 'दो पासवर्ड अक्षम हैं'],
            'phone'           => ['required' => 'कृपया अपना मोबाइल फोन संख्या भरें', 'regex' => 'मोबाइल फोन संख्या का गलत फॉर्मेट'],
            'code'            => ['required' => 'कृपया सत्यापन कोड भरें', 'digits' => 'कृपया 6- अंक सत्यापन कोड इनपुट करें'],
            'invitation_code' => ['required' => 'कृपया आमन्त्रणा कोड भरें']
        ],
        'ResetPasswordRequest' => [
            'country_code'    => ['required' => 'कृपया देश कोड चुनें'],
            'account'  => ['required' => 'कृपया खाता संख्या भरें', 'alpha_dash' => 'खाता फ़ॉर्मेट त्रुटि सिर्फ अक्षरों और संख्याओं को समर्थन करता है, तथा डैश और अंडरस्कोर', 'between' => 'खाता प्रारूप त्रुटि, 5-30 स्ट्रिंग लम्बाई'],
            'password' => ['required' => 'कृपया पासवर्ड प्रविष्ट करें', 'alpha_dash' => 'पासवर्ड फ़ॉर्मेट त्रुटि. सिर्फ अक्षर तथा संख्या, डैश तथा अंडरस्कोर समर्थित हैं', 'between' => 'पासवर्ड फ़ॉर्मेट त्रुटि, 6- 30 स्ट्रिंग लम्बाई', 'confirmed' => 'दो पासवर्ड अक्षम हैं'],
            'phone'    => ['required' => 'कृपया अपना मोबाइल फोन संख्या भरें', 'regex' => 'मोबाइल फोन संख्या का गलत फॉर्मेट'],
            'code'     => ['required' => 'कृपया सत्यापन कोड भरें', 'digits' => 'कृपया 6- अंक सत्यापन कोड इनपुट करें'],
        ]
    ],
    'Account'      => [
        'ChangeAvatarRequest'   => [
            'avatar' => ['required' => 'कृपया अपनी छवि अपलोड करें', 'max' => 'अवतार पता की लम्बाई :max अक्षरों से अधिक नहीं है']
        ],
        'ChangeNicknameRequest' => [
            'nickname' => ['required' => 'कृपया अपना उपनाम भरें', 'max' => 'उपनाम लंबाई :max अक्षरों से अधिक नहीं कर सकता']
        ],
        'ChangePhoneRequest'    => [
            'phone' => ['required' => 'कृपया अपना मोबाइल फोन संख्या भरें', 'numeric' => 'मोबाइल फोन क्रमांक फ़ॉर्मेट त्रुटि', 'digits' => 'मोबाइल फोन क्रमांक फ़ॉर्मेट त्रुटि: [: digits] length'],
            'code'  => ['required' => 'कृपया SMS सत्यापन कोड भरें', 'digits' => 'परीक्षण कोड फ़ॉर्मेट त्रुटि: [:digits अंक के लिए सही]']
        ],
        'ChangePasswordRequest' => [
            'old_password' => ['required' => 'कृपया पुराना पासवर्ड भरें', 'between' => 'पुराना पासवर्ड की लम्बाई 6- 30 स्ट्रिंग है'],
            'password'     => ['required' => 'कृपया एक नया पासवर्ड भरें', 'alpha_dash' => 'नया पासवर्ड फ़ॉर्मेट त्रुटि [सिर्फ अक्षरों और संख्याओं, तथा डैश और अंडरस्कोर समर्थित हैं]', 'between' => 'नया पासवर्ड फ़ॉर्मेट त्रुटि, 6- 30 स्ट्रिंग लम्बाई', 'confirmed' => 'दो पासवर्ड अक्षम हैं'],
            'trade_pass'   => ['alpha_dash' => 'नया पासवर्ड फ़ॉर्मेट त्रुटि [सिर्फ अक्षरों और संख्याओं, तथा डैश और अंडरस्कोर समर्थित हैं]', 'between' => 'नया पासवर्ड फ़ॉर्मेट त्रुटि, 6- 30 स्ट्रिंग लंबाई']
        ],
        'ChangeIdCardRequest'   => [
            'id_card' => ['required' => 'कृपया आईडी कार्ड संख्या', 'size' => 'ID कार्ड संख्या का गलत प्रारूप']
        ],
        'ChangeBankRequest'     => [
            'bank_name' => ['required' => 'कृपया रिट्रोकल प्रकार चुनें'],
            'bank_address' => ['required' => 'कृपया बैंक पता भरें'],  
            'name'      => ['required' => 'कृपया नाम भरें', 'between' => 'नाम की लम्बाई 2- 20 अक्षर है'],
            'account'   => ['required' => 'कृपया खाता संख्या भरें', 'max' => 'खाता की अधिकतम लम्बाई 20 अक्षर है'],
            'trade_pass'   => ['required' => 'कृपया पासवर्ड प्रविष्ट करें']
        ],
        'ChangeGenderRequest'   => [
            'gender' => ['required' => 'कृपया जेंडर चुनें', 'in' => 'गलत सेक्स चयन']
        ]
    ],
    'Task'         => [
        'SubmitRequest' => [
            'id'    => ['required' => 'कृपया कार्य चुनें'],
            'image' => ['required' => 'कृपया कार्य स्क्रीनशॉट अपलोड करें', 'max' => 'कार्य स्क्रीनशॉट पता की लम्बाई 255 अक्षरों से अधिक नहीं होती']
        ],
        'TaskRequest' => [
            'category_id' => ['required' => 'कृपया कार्य श्रेणी चुनें'],
            'level' => ['required' => 'कृपया सदस्यता स्तर चुनें'],
            'title' => ['required' => 'कृपया कार्य शीर्षक भरें', 'max' => 'कार्य शीर्षक :max अक्षरों से लंबा नहीं हो सकता'],
            'description' => ['required' => 'कृपया कार्य प्रोफाइल भरें'],
            'url' => ['required' => 'कृपया कार्य पता लिंक भरें'],
            'amount' => ['required' => 'कृपया कार्य मात्रा भरें'],
            'num' => ['required' => 'कृपया कार्यों की संख्या भरें', 'gt' => 'कार्यों की संख्या 0 से बड़ी होनी चाहिए']
        ]
    ],
    'UserRecharge' => [
        'ManualRequest' => [
            'level'    => ['required' => 'कृपया फिर चार्ज करने के लिए सदस्य स्तर चुनें', 'gt' => 'सदस्य स्तर चयन त्रुटि, कृपया फिर चुनें'],
            'trade_no' => ['required' => 'कृपया ट्रांसेक्शन सीरियल संख्या'],
            'image'    => ['required' => 'कृपया पैलिंग स्क्रीनशॉट अपलोड करें', 'max' => 'पैलिंग स्क्रीनशॉट पता की लम्बाई 255 अक्षरों से अधिक नहीं है']
        ],
        'OnlineRequest' => [
            'channel' => ['required' => 'कृपया फिर चैनल चुनें'],
            'level'   => ['required' => 'कृपया फिर चार्ज करने के लिए सदस्य स्तर चुनें', 'integer' => 'सदस्य स्तर चयन त्रुटि, कृपया फिर चुनें', 'gt' => 'सदस्य स्तर चयन त्रुटि, कृपया फिर चुनें']
        ],
        'BankRequest'   => [
            'country_id' => ['required' => 'कृपया पहले एक देश चुनें'],
            'bank_name'  => ['required' => 'कृपया रिमेटेंस बैंक का नाम भरें'],
            'name'       => ['required' => 'कृपया रिमाइटर का नाम भरें'],
            'bank'       => ['required' => 'कृपया रिमेटेंस कार्ड संख्या इनपुट करें'],
            'amount'     => ['required' => 'कृपया पुनरार्ज मात्रा भरें'],
            'remittance' => ['required' => 'कृपया रिमेटेंस मात्रा इनपुट करें']
        ],
        'LevelRequest' => [
            'level' => ['required' => 'कृपया फिर चार्ज स्तर चुनें']
        ]
    ]
];