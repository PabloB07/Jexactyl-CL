<?php

return [
    'sign_in' => 'Entrar',
    'go_to_login' => 'Ir a Iniciar sesión',
    'failed' => 'No se pudieron encontrar cuentas que coincidan con estas credenciales.',

    'forgot_password' => [
        'label' => 'olvido la contraseña?',
        'label_help' => 'Ingrese la dirección de correo electrónico de su cuenta para recibir instrucciones sobre cómo restablecer su contraseña.',
        'button' => 'Recuperar cuenta',
    ],

    'reset_password' => [
        'button' => 'Restablecer e iniciar sesión',
    ],

    'two_factor' => [
        'label' => 'Token de 2 factores',
        'label_help' => 'Esta cuenta requiere una segunda capa de autenticación para continuar. Ingrese el código generado por su dispositivo para completar este inicio de sesión.',
        'checkpoint_failed' => 'El token de autenticación de dos factores no era válido.',
    ],

    'throttle' => 'Demasiados intentos de inicio de sesión. Por favor intenta nuevamente en :seconds segundos.',
    'password_requirements' => 'La contraseña debe tener al menos 8 caracteres y debe ser exclusiva de este sitio.',
    '2fa_must_be_enabled' => 'El administrador ha requerido que se habilite la autenticación de dos factores para que su cuenta utilice el Panel.',
];
