<?php

/**
 * Contains all of the translation strings for different activity log
 * events. These should be keyed by the value in front of the colon (:)
 * in the event name. If there is no colon present, they should live at
 * the top level.
 */
return [
    'auth' => [
        'fail' => 'falló al cargar',
        'success' => 'Registrado hace:',
        'password-reset' => 'Restablecimiento de contraseña',
        'reset-password' => 'Restablecimiento de contraseña solicitada',
        'checkpoint' => 'Se solicita autenticación de dos factores',
        'recovery-token' => 'Token sistema de recuperación de dos factores utilizado',
        'token' => 'Reto de dos factores resuelto',
        'ip-blocked' => 'Solicitud de dirección bloqueada IP no listada para :identifier',
        'sftp' => [
            'fail' => 'Inicio de sesión sftp fallido',
        ],
    ],
    'user' => [
        'account' => [
            'email-changed' => 'Correo electrónico cambiado de :old a :new',
            'password-changed' => 'contraseña cambiada',
        ],
        'api-key' => [
            'create' => 'Nueva clave API creada :identifier',
            'delete' => 'Clave API eliminada :identifier',
        ],
        'ssh-key' => [
            'create' => 'Clave SSH agregada :fingerprint a la cuenta',
            'delete' => 'Clave SSH eliminada :fingerprint de la cuenta',
        ],
        'two-factor' => [
            'create' => 'Autenticación de dos factores habilitada',
            'delete' => 'Autenticación de dos factores desabilitado',
        ],
    ],
    'server' => [
        'reinstall' => 'Servidor reinstalado',
        'console' => [
            'command' => 'Ejecutado ":command" en el servidor',
        ],
        'power' => [
            'start' => 'Servidor iniciado',
            'stop' => 'Servidor detenido',
            'restart' => 'Servidor reiniciado',
            'kill' => 'Eliminó el proceso del servidor',
        ],
        'backup' => [
            'download' => 'Descargado la copia de seguridad :name ',
            'delete' => 'Se eliminó la copia de seguridad :name ',
            'restore' => 'Restaurado la copia de seguridad :name (deleted files: :truncate)',
            'restore-complete' => 'Restauración de la copia de seguridad completada de :name',
            'restore-failed' => 'No se pudo completar la restauración de :name',
            'start' => 'Comenzó una nueva copia de seguridad :name',
            'complete' => 'La copia de seguridad se completo :name',
            'fail' => 'La copia de seguridad falló :name',
            'lock' => 'Se bloqueo la copia de seguridad :name',
            'unlock' => 'Se desbloqueo la copia de seguridad :name',
        ],
        'database' => [
            'create' => 'Nueva base de datos creada :name',
            'rotate-password' => 'Contraseña rotada a la base de datos :name',
            'delete' => 'Base de datos eliminada :name',
        ],
        'file' => [
            'compress_one' => 'Comprimido :directory:file',
            'compress_other' => 'Comprimido :count arquivos en :directory',
            'read' => 'Leer el contenido de :file',
            'copy' => 'Creó una copia de :file',
            'create-directory' => 'Directorio creado :directory:name',
            'decompress' => 'Descomprimido :files en :directory',
            'delete_one' => 'Elimino :directory:files.0',
            'delete_other' => 'Elimino :count archivos de :directory',
            'download' => 'Descargando :file',
            'pull' => 'Descargué un archivo remoto desde :url para :directory',
            'rename_one' => 'Renombrado :directory:files.0.de a :directory:files.0.to',
            'rename_other' => 'Renombrado :count archivos a :directory',
            'write' => 'Escribí contenido nuevo para :file',
            'upload' => 'Se ha iniciado la carga de un archivo',
            'uploaded' => 'Cargado :directory:file',
        ],
        'sftp' => [
            'denied' => 'Acceso SFTP bloqueado debido a permisos',
            'create_one' => 'Creado :files.0',
            'create_other' => 'Creado :count nuevo files',
            'write_one' => 'Modificó el contenido de :files.0',
            'write_other' => 'Modificado o conteúdo de :count files',
            'delete_one' => 'Eliminado :files.0',
            'delete_other' => 'Eliminado :count files',
            'create-directory_one' => 'Creó el :files.0 directory',
            'create-directory_other' => 'Creó :count Diretórios',
            'rename_one' => 'Renombrado :files.0.de a :files.0.to',
            'rename_other' => 'Renombrado o movido :count archivos',
        ],
        'allocation' => [
            'create' => 'Agregado :allocation al servidor',
            'notes' => 'Actualizado las notas para :allocation de ":old" a ":new"',
            'primary' => 'La :allocation se asigno al servidor principal',
            'delete' => 'Elimino la asignación :allocation ',
        ],
        'schedule' => [
            'create' => 'Creó el :name schedule',
            'update' => 'Actualizado el :name schedule',
            'execute' => 'Ejecutó manualmente el :name schedule',
            'delete' => 'Eliminado el :name schedule',
        ],
        'task' => [
            'create' => 'Creó una nueva ":action" tarea para el :name schedule',
            'update' => 'Actualizo una ":action" tarea para el :name schedule',
            'delete' => 'Se eliminó una tarea de :name schedule',
        ],
        'settings' => [
            'rename' => 'Renombrado el servidor :old a :new',
        ],
        'startup' => [
            'edit' => 'Cambiado la :variable variable de ":old" a ":new"',
            'image' => 'Se actualizó la imagen de Docker para el servidor :old a :new',
        ],
        'subuser' => [
            'create' => 'Agregó :email como subusuario',
            'update' => 'Se actualizaron los permisos del subusuario :email',
            'delete' => 'Elimino :email como subusuario',
        ],
    ],
];
