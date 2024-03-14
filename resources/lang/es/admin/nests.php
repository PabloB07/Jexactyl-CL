<?php
/**
 * Pterodactyl - Panel
 * Copyright (c) 2015 - 2017 Dane Everitt <dane@daneeveritt.com>.
 *
 * This software is licensed under the terms of the MIT license.
 * https://opensource.org/licenses/MIT
 */

return [
    'notices' => [
        'created' => 'Un nuevo nido, :name, fue creado exitosamente.',
        'deleted' => 'Se eliminó correctamente el Nido solicitado del Panel de control..',
        'updated' => 'Se actualizaron correctamente las opciones de configuración de Nido.',
    ],
    'eggs' => [
        'notices' => [
            'imported' => 'Ha importado con éxito este huevo y sus variables asociadas.',
            'updated_via_import' => 'Este huevo se ha actualizado utilizando el archivo proporcionado.',
            'deleted' => 'Se eliminó con éxito el huevo solicitado del Panel de control.',
            'updated' => 'La configuración del huevo se ha actualizado correctamente.',
            'script_updated' => 'El script de instalación de huevos se ha actualizado y se ejecutará siempre que se instalen servidores.',
            'egg_created' => 'Se ha colocado con éxito un nuevo huevo. Deberá reiniciar cualquier daemon en ejecución para aplicar este nuevo huevo.',
        ],
    ],
    'variables' => [
        'notices' => [
            'variable_deleted' => 'La variable ":variable" se ha eliminado y ya no estará disponible para los servidores una vez reconstruido.',
            'variable_updated' => 'La variable ":variable" Ha sido actualizado. Deberá reconstruir los servidores que utilicen esta variable para aplicar los cambios.',
            'variable_created' => 'La nueva variable se creó y asignó correctamente a este huevo.',
        ],
    ],
];
