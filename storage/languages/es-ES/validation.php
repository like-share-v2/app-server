<?php
/**
 * @copyright
 * @version 1.0.0
 * @link
 */

return [
    'Auth'         => [
        'LoginRequest'         => [
            'country_code' => ['required' => 'Seleccione un código nacional.'],
            'account'  => ['required' => 'Ingrese la cuenta de desembarco.', 'alpha_dash' => 'Error en el formato de la cuenta [sólo las letras y los números, así como los números rotos y subrayados]', 'between' => 'Error de formato de la cuenta, 5 a 30 longitudes de cadena'],
            'password' => ['required' => 'Introduzca la contraseña.', 'alpha_dash' => 'Error en el formato de la contraseña [sólo soporta las letras y los números, así como el guión y el subrayado]', 'between' => 'Error en el formato de la contraseña, 6 a 30 longitudes de cadena']
        ],
        'PhoneLoginRequest'    => [
            'phone' => ['required' => 'Introduzca el número de teléfono.', 'regex' => 'Número de teléfono equivocado'],
            'code'  => ['required' => 'Por favor, introduzca el Código de seis mensajes.', 'size' => 'Por favor, introduzca el Código de seis mensajes.']
        ],
        'RegisterRequest'      => [
            'country_id'      => ['required' => 'Seleccione país'],
            'country_code'    => ['required' => 'Seleccione un código nacional.'],
            'account'         => ['required' => 'Ingrese la cuenta.', 'alpha_dash' => 'Error en el formato de la cuenta [sólo las letras y los números, así como los números rotos y subrayados]', 'between' => 'Error de formato de la cuenta, 5 a 30 longitudes de cadena'],
            'password'        => ['required' => 'Introduzca la contraseña.', 'alpha_dash' => 'Error en el formato de la contraseña [sólo soporta las letras y los números, así como el guión y el subrayado]', 'between' => 'Error de formato de contraseña, 5 a 30 longitudes de cadena', 'confirmed' => 'Dos veces la contraseña no coincide.'],
            'phone'           => ['required' => 'Introduzca el número de teléfono.', 'regex' => 'Número de teléfono incorrecto.'],
            'code'            => ['required' => 'Introduzca el Código de verificación.', 'digits' => 'Introduzca el Código de verificación digital de seis dígitos.'],
            'invitation_code' => ['required' => 'Introduzca el Código de invitación.']
        ],
        'ResetPasswordRequest' => [
            'country_code'    => ['required' => 'Seleccione un código nacional.'],
            'account'  => ['required' => 'Ingrese la cuenta.', 'alpha_dash' => 'Error en el formato de la cuenta [sólo las letras y los números, así como los números rotos y subrayados]', 'between' => 'Error de formato de la cuenta, 5 a 30 longitudes de cadena'],
            'password' => ['required' => 'Introduzca la contraseña.', 'alpha_dash' => 'Error en el formato de la contraseña [sólo soporta las letras y los números, así como el guión y el subrayado]', 'between' => 'Error en el formato de la contraseña, 6 a 30 longitudes de cadena', 'confirmed' => 'Dos veces la contraseña no coincide.'],
            'phone'    => ['required' => 'Introduzca el número de teléfono.', 'regex' => 'Número de teléfono incorrecto.'],
            'code'     => ['required' => 'Introduzca el Código de verificación.', 'digits' => 'Introduzca el Código de verificación digital de seis dígitos.'],
        ]
    ],
    'Account'      => [
        'ChangeAvatarRequest'   => [
            'avatar' => ['required' => 'Sube el retrato.', 'max' => 'La longitud de la dirección de cabecera no puede superar: max']
        ],
        'ChangeNicknameRequest' => [
            'nickname' => ['required' => 'Introduzca el apodo', 'max' => 'No puede tener más de :max caracteres.']
        ],
        'ChangePhoneRequest'    => [
            'phone' => ['required' => 'Introduzca el número de teléfono.', 'numeric' => 'Número de teléfono equivocado', 'digits' => 'Error de formato: longitud de digits'],
            'code'  => ['required' => 'Introduzca el Código de verificación de mensajes.', 'digits' => 'Error de formato del Código de autenticación: [correcto: número de bits digits]']
        ],
        'ChangePasswordRequest' => [
            'old_password' => ['required' => 'Introduzca la contraseña antigua.', 'between' => 'La antigua contraseña tiene una longitud de 6 a 30 cadenas.'],
            'password'     => ['required' => 'Introduzca una nueva contraseña.', 'alpha_dash' => 'Error en el formato de la nueva contraseña [sólo soporta las letras y los números, así como los números rotos y subrayados]', 'between' => 'Error de formato de la nueva contraseña, 6 a 30 longitudes de cadena', 'confirmed' => 'Dos veces la contraseña no coincide.'],
            'trade_pass'   => ['alpha_dash' => 'Error en el formato de la nueva contraseña de transferencia [sólo para letras y números, así como para marcar y tachar]', 'between' => 'Error en el nuevo formato de código de acceso, 6 a 30 longitudes de cadena']
        ],
        'ChangeIdCardRequest'   => [
            'id_card' => ['required' => 'Introduzca el número de identificación.', 'size' => 'Número de identificación incorrecto.']
        ],
        'ChangeBankRequest'     => [
            'bank_name' => ['required' => 'Seleccione el tipo actual'],
            'bank_address' => ['required' => 'Introduzca la dirección de la cuenta.'],  
            'name'      => ['required' => 'Nombre, por favor', 'between' => 'El nombre tiene 2-20 caracteres.'],
            'account'   => ['required' => 'Ingrese la cuenta.', 'max' => 'La longitud máxima de la cuenta es de 20 caracteres.'],
            'trade_pass'   => ['required' => 'Introduzca la contraseña de salida.']
        ],
        'ChangeGenderRequest'   => [
            'gender' => ['required' => 'Elige el sexo.', 'in' => 'Selección de sexo incorrecta']
        ]
    ],
    'Task'         => [
        'SubmitRequest' => [
            'id'    => ['required' => 'Seleccione la tarea'],
            'image' => ['required' => 'Cargando la Misión.', 'max' => 'La Dirección de la Sección de tareas no debe tener más de 255 caracteres']
        ],
        'TaskRequest' => [
            'category_id' => ['required' => 'Seleccione una categoría de tareas'],
            'level' => ['required' => 'Seleccione el nivel de miembro'],
            'title' => ['required' => 'Introduzca el título de la tarea', 'max' => 'Longitud del título de la tarea no superior a: max caracteres'],
            'description' => ['required' => 'Introduzca el perfil de la Misión'],
            'url' => ['required' => 'Introduzca el enlace de la dirección'],
            'amount' => ['required' => 'Introduzca el importe de la tarea'],
            'num' => ['required' => 'Introduzca el número de tareas', 'gt' => 'El número de tareas debe ser superior a 0']
        ]
    ],
    'UserRecharge' => [
        'ManualRequest' => [
            'level'    => ['required' => 'Por favor seleccione el nivel de miembro que desea recargar', 'gt' => 'Error al seleccionar el nivel de miembro'],
            'trade_no' => ['required' => 'Introduzca el número de tráfico.'],
            'image'    => ['required' => 'Por favor, suba la sección.', 'max' => 'La Dirección de la Sección de pago no debe tener más de 255 caracteres']
        ],
        'OnlineRequest' => [
            'channel' => ['required' => 'Seleccione el canal de recarga'],
            'level'   => ['required' => 'Por favor seleccione el nivel de miembro que desea recargar', 'integer' => 'Error al seleccionar el nivel de miembro', 'gt' => 'Error al seleccionar el nivel de miembro']
        ],
        'BankRequest'   => [
            'country_id' => ['required' => 'Seleccione primero el país.'],
            'bank_name'  => ['required' => 'Nombre del banco remitente'],
            'name'       => ['required' => 'Nombre del remitente, por favor.'],
            'bank'       => ['required' => 'Número de tarjeta de envío'],
            'amount'     => ['required' => 'Introduzca la cantidad de recarga'],
            'remittance' => ['required' => 'Importe de la transferencia']
        ],
        'LevelRequest' => [
            'level' => ['required' => 'Seleccione el nivel de recarga']
        ]
    ]
];