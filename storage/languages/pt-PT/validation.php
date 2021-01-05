<?php
/**
 * @copyright
 * @version 1.0.0
 * @link  
 */

return [
    'Auth'         => [
        'LoginRequest'         => [
            'country_code' => ['required' => 'Por favor, seleccione o código do país'],
            'account'  => ['required' => 'Por favor, conta de login de Entrada', 'alpha_dash' => 'Formato errado Da conta [apenas Letras e números, Tabletes e underscores são suportados]', 'between' => 'Erro de formato Da conta, comprimento de cadeia 5-30'],
            'password' => ['required' => 'Por favor, insira a senha de login', 'alpha_dash' => 'Formato de senha errado [apenas Letras e números, BEM Como dashes e underscores são suportados]', 'between' => 'Formato de senha errado, comprimentos de cadeia 6-30']
        ],
        'PhoneLoginRequest'    => [
            'phone' => ['required' => 'Por favor, Digite o SEU número de telefone celular', 'regex' => 'Erro de formato do número de telefone celular'],
            'code'  => ['required' => 'Digite o código de verificação SMS de SEIS dígitos', 'size' => 'Digite o código de verificação SMS de SEIS dígitos']
        ],
        'RegisterRequest'      => [
            'country_id'      => ['required' => 'Por favor, selecione um país'],
            'country_code'    => ['required' => 'Por favor, seleccione o código do país'],
            'account'         => ['required' => 'Por favor, Digite o número Da conta', 'alpha_dash' => 'Formato errado Da conta [apenas Letras e números, Tabletes e underscores são suportados]', 'between' => 'Erro de formato Da conta, comprimento de cadeia 5-30'],
            'password'        => ['required' => 'Por favor insira UMA senha', 'alpha_dash' => 'Formato de senha errado [apenas Letras e números, BEM Como dashes e underscores são suportados]', 'between' => 'Erro de formato de senhas, comprimento de cadeia 5-30', 'confirmed' => 'As duas senhas são inconsistentes'],
            'phone'           => ['required' => 'Por favor, Digite o SEU número de telefone celular', 'regex' => 'Formato incorreto do número de telefone móvel'],
            'code'            => ['required' => 'Digite o código de verificação', 'digits' => 'Introduzir código de verificação de SEIS dígitos'],
            'invitation_code' => ['required' => 'Por favor, insira o código de convite']
        ],
        'ResetPasswordRequest' => [
            'country_code'    => ['required' => 'Por favor, seleccione o código do país'],
            'account'  => ['required' => 'Por favor, Digite o número Da conta', 'alpha_dash' => 'Formato errado Da conta [apenas Letras e números, Tabletes e underscores são suportados]', 'between' => 'Erro de formato Da conta, comprimento de cadeia 5-30'],
            'password' => ['required' => 'Por favor insira UMA senha', 'alpha_dash' => 'Formato de senha errado [apenas Letras e números, BEM Como dashes e underscores são suportados]', 'between' => 'Erro de formato de senhas, comprimento de cadeia 6-30', 'confirmed' => 'As duas senhas são inconsistentes'],
            'phone'    => ['required' => 'Por favor, Digite o SEU número de telefone celular', 'regex' => 'Formato incorreto do número de telefone móvel'],
            'code'     => ['required' => 'Digite o código de verificação', 'digits' => 'Introduzir código de verificação de SEIS dígitos'],
        ]
    ],
    'Account'      => [
        'ChangeAvatarRequest'   => [
            'avatar' => ['required' => 'Por favor, carregue SUA foto.', 'max' => 'O comprimento do endereço do Avatar não Pode exceder: caracteres max']
        ],
        'ChangeNicknameRequest' => [
            'nickname' => ['required' => 'Por favor, Digite SEU apelido', 'max' => 'O comprimento do apelido não Pode exceder OS caracteres ：max']
        ],
        'ChangePhoneRequest'    => [
            'phone' => ['required' => 'Por favor, Digite o SEU número de telefone celular', 'numeric' => 'Erro de formato do número de telefone celular', 'digits' => 'Erro no formato do número móvel: [：digits] comprimento'],
            'code'  => ['required' => 'Por favor introduza o código de verificação SMS', 'digits' => 'Erro no formato do código de verificação: [Correto para OS dígitos ：digits]']
        ],
        'ChangePasswordRequest' => [
            'old_password' => ['required' => 'Por favor, Digite a senha Antiga', 'between' => 'O comprimento Da Antiga senha são cordas 6-30'],
            'password'     => ['required' => 'Por favor, insira UMA Nova senha', 'alpha_dash' => 'Novo erro de formato de senha [apenas Letras e números, BEM Como dashes e underscores são suportados]', 'between' => 'Novo erro de formato de senha, tamanho de cadeia 6-30', 'confirmed' => 'As duas senhas são inconsistentes']
            'trade_pass'   => ['alpha_dash' => 'Erro de novo formato de senha de retirada [apenas Letras e números, BEM Como dashes e underscores são suportados]', 'between' => 'Novo formato de senha de retirada, 6-30 comprimento de string']
        ],
        'ChangeIdCardRequest'   => [
            'id_card' => ['required' => 'Digite o número do cartão de identificação', 'size' => 'Formato incorreto do número do cartão de identificação']
        ],
        'ChangeBankRequest'     => [
            'bank_name' => ['required' => 'Selecione por favor o Tipo de retirada'],
            'bank_address' => ['required' => 'Por favor, Digite o endereço do banco.'],  
            'name'      => ['required' => 'Digite o nome', 'between' => 'O comprimento do Nome são caracteres 2-20'],
            'account'   => ['required' => 'Por favor, Digite o número Da conta', 'max' => 'O comprimento máximo Da conta são vinte caracteres']
            'trade_pass'   => ['required' => 'Digite a senha de retirada']
        ],
        'ChangeGenderRequest'   => [
            'gender' => ['required' => 'Seleccione por favor o género', 'in' => 'Selecção sexual errada']
        ]
    ],
    'Task'         => [
        'SubmitRequest' => [
            'id'    => ['required' => 'Por favor selecione a tarefa'],
            'image' => ['required' => 'Por favor, carregue a Imagem Da tarefa', 'max' => 'A duração do endereço de Imagem Da tarefa não Pode exceder 255 caracteres']
        ],
        'TaskRequest' => [
            'category_id' => ['required' => 'Seleccione por favor a Categoria de tarefas'],
            'level' => ['required' => 'Seleccione o nível de filiação'],
            'title' => ['required' => 'Digite o título Da tarefa', 'max' => 'O título Da tarefa não Pode ser Mais Longo do que OS caracteres ：max'],
            'description' => ['required' => 'Digite o PERFIL Da tarefa'],
            'url' => ['required' => 'Por favor, insira o endereço Da tarefa'],
            'amount' => ['required' => 'Digite o montante Da tarefa'],
            'num' => ['required' => 'Por favor, Digite o número de tarefas', 'gt' => 'O número de tarefas deve ser superior a 0']
        ]
    ],
    'UserRecharge' => [
        'ManualRequest' => [
            'level'    => ['required' => 'Seleccione o nível de membro para recarregar', 'gt' => 'Erro de seleção do nível de Membro, por favor, re- selecione'],
            'trade_no' => ['required' => 'Por favor introduza o número de série Da transação'],
            'image'    => ['required' => 'Por favor, carregue a Imagem de pagamento', 'max' => 'A duração do endereço de tela de pagamento não Pode exceder 255 caracteres']
        ],
        'OnlineRequest' => [
            'channel' => ['required' => 'Seleccione por favor o Canal de recarga'],
            'level'   => ['required' => 'Seleccione o nível de membro para recarregar', 'integer' => 'Erro de seleção do nível de Membro, por favor, re- selecione', 'gt' => 'Erro de seleção do nível de Membro, por favor, re- selecione']
        ],
        'BankRequest'   => [
            'country_id' => ['required' => 'Por favor, selecione um país primeiro.'],
            'bank_name'  => ['required' => 'Por favor, Digite o Nome do Banco de remessas'],
            'name'       => ['required' => 'Por favor, Digite o Nome do remetente'],
            'bank'       => ['required' => 'Digite o número do cartão de remetente'],
            'amount'     => ['required' => 'Por favor, Digite a quantia de recarga'],
            'remittance' => ['required' => 'Por favor, introduza o montante de remessas']
        ],
        'LevelRequest' => [
            'level' => ['required' => 'Seleccione por favor o nível de recarga']
        ]
    ]
];