<?php

return [
    'daemon_connection_failed' => 'Hubo una excepción al intentar comunicarse con el daemon resultando en un código de respuesta http/:code. Esta excepción fue registrada.',
    'node' => [
        'servers_attached' => 'Un Nodo no debe tener servidores vinculados a él para ser eliminado.',
        'daemon_off_config_updated' => 'La configuración daemon <strong> fue actualizada </strong>, sin embargo, se produjo un error al intentar actualizar automáticamente el archivo de configuración en Daemon. Deberá actualizar manualmente el archivo de configuración. (config.yml) de manera que la daemon aplique estos cambios.',
    ],
    'allocations' => [
        'server_using' => 'Actualmente hay un servidor asignado a esta asignación. Una asignación solo se puede eliminar si actualmente no hay servidores asignados.',
        'too_many_ports' => 'No se admite agregar más de 1000 puertos en un solo rango al mismo tiempo.',
        'invalid_mapping' => 'El mapeo previsto para :port no es válido y no se podra procesar.',
        'cidr_out_of_range' => 'La notación CIDR sólo permite máscaras entre /25 a /32.',
        'port_out_of_range' => 'Los puertos en una asignación deben ser mayores que 1024 y menores o iguales a 65535.',
    ],
    'nest' => [
        'delete_has_servers' => 'Un Nest con servidores activos conectados no se puede eliminar del Panel.',
        'egg' => [
            'delete_has_servers' => 'Un Huevo con servidores activos conectados a él no se puede eliminar del Panel.',
            'invalid_copy_id' => 'El huevo seleccionado para copiar el script no existe o está copiando el script en sí.',
            'must_be_child' => 'A diretiva "Copiar configurações a partir" para este Egg deve ser uma opção Child(Propriada)  para o Nest selecionado.',
            'has_children' => 'Este Huevo es el padre de uno o más Huevos. Elimine estos huevos antes de eliminar este huevo.',
        ],
        'variables' => [
            'env_not_unique' => 'La variable de entorno :name debe ser exclusivo de este huevo.',
            'reserved_name' => 'La variable de entorno :name está protegido y no se puede asignar a una variable.',
            'bad_validation_rule' => 'La regla de validación ":rule" no es una regla válida para esta aplicación.',
        ],
        'importer' => [
            'json_error' => 'Se produjo un error al intentar analizar el archivo JSON: :error.',
            'file_error' => 'El archivo JSON proporcionado no era válido.',
            'invalid_json_provided' => 'El archivo JSON proporcionado no tiene un formato que pueda reconocerse.',
        ],
    ],
    'subusers' => [
        'editing_self' => 'No se permite editar su propia cuenta de subusuario.',
        'user_is_owner' => 'No puede agregar al propietario del servidor como subusuario para este servidor.',
        'subuser_exists' => 'El usuario con esta dirección de correo electrónico ya está asignado como subusuario de este servidor.',
    ],
    'databases' => [
        'delete_has_databases' => 'No puede eliminar un servidor host de base de datos que tenga bases de datos activas vinculadas.',
    ],
    'tasks' => [
        'chain_interval_too_long' => 'El intervalo de tiempo máximo para una tarea encadenada es de 15 minutos.',
    ],
    'locations' => [
        'has_nodes' => 'No es posible eliminar una ubicación que tenga Nodos activos adjuntos.',
    ],
    'users' => [
        'node_revocation_failed' => 'No se pudieron revocar las claves en <a href=":link">Node #:node</a>. :error',
    ],
    'deployment' => [
        'no_viable_nodes' => 'No se pudieron encontrar los nodos que cumplieran los requisitos especificados para la implementación automática.',
        'no_viable_allocations' => 'No se encontraron asignaciones que cumplan con los requisitos para la implementación automática.',
    ],
    'api' => [
        'resource_not_found' => 'El recurso solicitado no existe en este servidor.',
    ],
];
