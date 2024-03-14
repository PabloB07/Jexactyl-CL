<?php

return [
    'email' => [
        'title' => 'Actualiza tu correo electrónico',
        'updated' => 'Su dirección de correo electrónico ha sido actualizada.',
    ],
    'password' => [
        'title' => 'cambia tu contraseña',
        'requirements' => 'Su nueva contraseña debe tener al menos 8 caracteres.',
        'updated' => 'Tu contraseña ha sido actualizada.',
    ],
    'two_factor' => [
        'button' => 'Configurar la autenticación de 2 factores',
        'disabled' => 'La autenticación de dos factores ha sido deshabilitada en su cuenta. Ya no se le pedirá un token al iniciar sesión.',
        'enabled' => '¡Se ha habilitado la autenticación de dos factores en su cuenta! A partir de ahora, al iniciar sesión se te pedirá que proporciones el código generado por tu dispositivo.',
        'invalid' => 'El token proporcionado no era válido.',
        'setup' => [
            'title' => 'Configuración de autenticación de dos factores',
            'help' => '¿No puedes escanear el código? Ingrese el siguiente código en su aplicación:',
            'field' => 'Coloca el token',
        ],
        'disable' => [
            'title' => 'Deshabilitar la autenticación de dos factores',
            'field' => 'Coloca el token',
        ],
    ],
];
