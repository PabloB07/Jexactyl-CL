<?php

return [
    'location' => [
        'no_location_found' => 'No se puede encontrar un registro que coincida con el nombre simple proporcionado.',
        'ask_short' => 'Nombre de ubicación simple',
        'ask_long' => 'Descripción de la ubicación',
        'created' => 'Creó exitosamente una nueva ubicación (:name) con una identificación de :id.',
        'deleted' => 'Eliminó con éxito la ubicación solicitada.',
    ],
    'user' => [
        'search_users' => 'Introduzca un nombre de usuario, ID de usuario o dirección de correo electrónico',
        'select_search_user' => 'ID de usuario para eliminar (Enter \'0\' to re-search)',
        'deleted' => 'Usuario eliminado exitosamente del Panel.',
        'confirm_delete' => '¿Está seguro de que desea eliminar este usuario del Panel de control?',
        'no_users_found' => 'No se encontraron usuarios para el término de búsqueda dado.',
        'multiple_found' => 'Se encontraron varias cuentas para el usuario determinado y no se pudo eliminar un usuario debido a --no-interaction flag.',
        'ask_admin' => '¿Este usuario es administrador?',
        'ask_email' => 'Dirección de correo electrónico',
        'ask_username' => 'Nombre de usuario',
        'ask_name_first' => 'Primer nombre',
        'ask_name_last' => 'Segundo nombre',
        'ask_password' => 'Contraseña',
        'ask_password_tip' => 'Si desea crear una cuenta con una contraseña aleatoria enviada por el usuario, vuelva a ejecutar este comando (CTRL+C) y pase el `--no-password` flag.',
        'ask_password_help' => 'Las contraseñas deben tener al menos 8 caracteres y contener al menos una letra mayúscula y un número.',
        '2fa_help_text' => [
            'Este comando deshabilitará la autenticación de 2 factores para un user\'s cuenta si está activada. Esto solo debe usarse como comando de recuperación de cuenta si el usuario no puede acceder a su cuenta.',
            'Si esto no es lo que quería hacer, presione CTRL+C para salir de este proceso.',
        ],
        '2fa_disabled' => 'La autenticación de 2 factores ha sido deshabilitada por :email.',
    ],
    'schedule' => [
        'output_line' => 'Despacho de trabajo para la primera tarea en `:schedule` (:hash).',
    ],
    'maintenance' => [
        'deleting_service_backup' => 'Eliminar el archivo de copia de seguridad del servicio :file.',
    ],
    'server' => [
        'rebuild_failed' => 'Solicitud de reconstrucción para ":name" (#:id) en el nodo ":node" falló con erro: :message',
        'reinstall' => [
            'failed' => 'Solicitud de reinstalación de ":name" (#:id) en el nodo ":node" falló con erro: :message',
            'confirm' => 'Está a punto de realizar la reinstalación en un grupo de servidores. ¿Quieres continuar?',
        ],
        'power' => [
            'confirm' => 'Estás a punto de hacer un :action contra :count servidores. ¿Quieres continuar?',
            'action_failed' => 'Solicitud de acción energética para ":name" (#:id) en el nodo ":node" falló con el errr: :message',
        ],
    ],
    'environment' => [
        'mail' => [
            'ask_smtp_host' => 'Host SMTP (e.g. smtp.gmail.com)',
            'ask_smtp_port' => 'Puerto SMTP',
            'ask_smtp_username' => 'Usuario SMTP',
            'ask_smtp_password' => 'Contraseña SMTP',
            'ask_mailgun_domain' => 'Dominio Mailgun',
            'ask_mailgun_endpoint' => 'Mailgun EndPoint',
            'ask_mailgun_secret' => 'Mailgun Secret',
            'ask_mandrill_secret' => 'Mandrill Secret',
            'ask_postmark_username' => 'Chave de API de marca posta',
            'ask_driver' => '¿Qué controlador se debe utilizar para enviar correos electrónicos?',
            'ask_mail_from' => 'Os E-mails de endereço de E-mail devem ser originários de',
            'ask_mail_name' => 'Nome que os E-mails devem aparecer de',
            'ask_encryption' => 'Método de criptografia para usar',
        ],
    ],
];
