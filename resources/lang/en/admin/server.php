<?php
/**
 * Pterodactyl - Panel
 * Copyright (c) 2015 - 2017 Dane Everitt <dane@daneeveritt.com>.
 *
 * This software is licensed under the terms of the MIT license.
 * https://opensource.org/licenses/MIT
 */

return [
    'exceptions' => [
        'no_new_default_allocation' => 'Está intentando eliminar la asignación predeterminada para este servidor, pero no hay ninguna asignación alternativa para usar.',
        'marked_as_failed' => 'Este servidor fue marcado como fallido en una instalación anterior. El estado actual no se puede alternar en este estado.',
        'bad_variable' => 'Hubo un error de validación con la variable :name.',
        'daemon_exception' => 'Hubo una excepción al intentar comunicarse con el daemon, lo que generó un código HTTP/: esta excepción se registró. (request id: :request_id)',
        'default_allocation_not_found' => 'La asignación predeterminada solicitada no se encontró en las asignaciones de este servidor.',
    ],
    'alerts' => [
        'startup_changed' => 'La configuración de inicio para este servidor ha sido actualizada. Si se cambian los Nests o Huevos de este servidor, ahora se llevará a cabo una reinstalación.',
        'server_deleted' => 'El servidor se ha eliminado correctamente del sistema.',
        'server_created' => 'El servidor se ha creado correctamente en el panel. Permita que el daemon se instale y en unos minutos inicie este servidor.',
        'build_updated' => 'Los detalles de compilación de este servidor se han actualizado. Es posible que algunos cambios requieran un reinicio para que surtan efecto.',
        'suspension_toggled' => 'El estado de suspensión del servidor cambió a :status.',
        'rebuild_on_boot' => 'Se ha marcado que este servidor requiere una reconstrucción del contenedor Docker. Esto sucederá la próxima vez que se inicie el servidor.',
        'install_toggled' => 'Se ha cambiado el estado de instalación de este servidor.',
        'server_reinstalled' => 'Este servidor ha estado en cola para una reinstalación a partir de ahora.',
        'details_updated' => 'Los detalles del servidor se han actualizado correctamente.',
        'docker_image_updated' => 'Se cambió con éxito la imagen predeterminada de Docker para usar en este servidor. Es necesario reiniciar para aplicar este cambio.',
        'node_required' => 'Debe tener al menos un nodo configurado antes de poder agregar un servidor a este panel.',
        'transfer_nodes_required' => 'Debe tener al menos dos nodos configurados antes de poder transferir servidores.',
        'transfer_started' => 'La transferencia del servidor ha comenzado.',
        'transfer_not_viable' => 'El nodo seleccionado no tiene el espacio en disco o la memoria necesarios para alojar este servidor.',
    ],
];
