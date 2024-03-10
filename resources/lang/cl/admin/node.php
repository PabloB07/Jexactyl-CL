<?php
/**
 * Pterodactyl - Panel
 * Copyright (c) 2015 - 2017 Dane Everitt <dane@daneeveritt.com>.
 *
 * This software is licensed under the terms of the MIT license.
 * https://opensource.org/licenses/MIT
 */

return [
    'validation' => [
        'fqdn_not_resolvable' => 'El FQDN o la dirección IP proporcionada no se resuelve en una dirección IP válida.',
        'fqdn_required_for_ssl' => 'Se requiere un nombre de dominio completo que se resuelva en una dirección IP pública para utilizar SSL para este nodo.',
    ],
    'notices' => [
        'allocations_added' => 'Las asignaciones se han agregado exitosamente a este nodo.',
        'node_deleted' => 'El nodo se eliminó exitosamente del panel.',
        'location_required' => 'Debe tener al menos una ubicación configurada antes de poder agregar un nodo a este panel.',
        'node_created' => 'Creó exitosamente un nuevo Nodo. Puede configurar automáticamente el daemon en esta máquina visitando el\'Configuration\' tab. <strong>Antes de agregar servidores, primero debe asignar al menos una dirección IP y un puerto.</strong>',
        'node_updated' => 'La información del nodo ha sido actualizada. Si se ha cambiado alguna configuración del daemon, deberá reiniciar el daemon para que estos cambios surtan efecto.',
        'unallocated_deleted' => 'Se eliminaron todos los puertos no asignados a <code>:ip</code>.',
    ],
];
