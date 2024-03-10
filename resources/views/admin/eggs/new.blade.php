@extends('layouts.admin')

@section('title')
    Nests &rarr; Nuevo Egg
@endsection

@section('content-header')
    <h1>Nuevo Egg<small>Crear un nuevo Egg para asignar a los servidores.</small></h1>
    <ol class="breadcrumb">
        <li><a href="{{ route('admin.index') }}">Administrador</a></li>
        <li><a href="{{ route('admin.nests') }}">Nests</a></li>
        <li class="active">Nuevo Egg</li>
    </ol>
@endsection

@section('content')
<form action="{{ route('admin.nests.egg.new') }}" method="POST">
    <div class="row">
        <div class="col-xs-12">
            <div class="box">
                <div class="box-header with-border">
                    <h3 class="box-title">Configuraciones</h3>
                </div>
                <div class="box-body">
                    <div class="row">
                        <div class="col-sm-6">
                            <div class="form-group">
                                <label for="pNestId" class="form-label">Nest Associado</label>
                                <div>
                                    <select name="nest_id" id="pNestId">
                                        @foreach($nests as $nest)
                                            <option value="{{ $nest->id }}" {{ old('nest_id') != $nest->id ?: 'selected' }}>{{ $nest->name }} &lt;{{ $nest->author }}&gt;</option>
                                        @endforeach
                                    </select>
                                    <p class="text-muted small">Piense en un Nest como una categoría. Puedes colocar varios huevos en un nido, pero considera colocar solo huevos que estén relacionados entre sí en cada nest.</p>
                                </div>
                            </div>
                            <div class="form-group">
                                <label for="pName" class="form-label">Nombre</label>
                                <input type="text" id="pName" name="name" value="{{ old('name') }}" class="form-control" />
                                <p class="text-muted small">Un nombre simple y legible por humanos que se utilizará como identificador de estos huevos. Esto es lo que los usuarios verán como tu tipo de servidor de juego.</p>
                            </div>
                            <div class="form-group">
                                <label for="pDescription" class="form-label">Descripción</label>
                                <textarea id="pDescription" name="description" class="form-control" rows="8">{{ old('description') }}</textarea>
                                <p class="text-muted small">Una descripción de este Egg.</p>
                            </div>
                            <div class="form-group">
                                <div class="checkbox checkbox-primary no-margin-bottom">
                                    <input id="pForceOutgoingIp" name="force_outgoing_ip" type="checkbox" value="1" {{ \Pterodactyl\Helpers\Utilities::checked('force_outgoing_ip', 0) }} />
                                    <label for="pForceOutgoingIp" class="strong">Forzar IP saliente</label>
                                    <p class="text-muted small">
                                        Obliga a todo el tráfico de red saliente a tener su IP de origen NAT en la IP de la IP de asignación principal del servidor.
                                        Necesario para que ciertos juegos funcionen correctamente cuando el Nodo tiene múltiples direcciones IP públicas.
                                        <br>
                                        <strong>
                                            Habilitar esta opción deshabilitará la red interna para cualquier servidor que use este huevo,
                                            lo que significa que no pueden acceder internamente a otros servidores en el mismo nodo.
                                        </strong>
                                    </p>
                                </div>
                            </div>
                        </div>
                        <div class="col-sm-6">
                            <div class="form-group">
                                <label for="pDockerImage" class="control-label">Imagen de Docker</label>
                                <textarea id="pDockerImages" name="docker_images" rows="4" placeholder="quay.io/pterodactyl/service" class="form-control">{{ old('docker_images') }}</textarea>
                                <p class="text-muted small">Las imágenes de Docker disponibles para servidores que usan este huevo. Ingrese uno por línea. Los usuarios podrán seleccionar de esta lista de imágenes si se proporciona más de un valor..</p>
                            </div>
                            <div class="form-group">
                                <label for="pStartup" class="control-label">Comando de inicialización</label>
                                <textarea id="pStartup" name="startup" class="form-control" rows="10">{{ old('startup') }}</textarea>
                                <p class="text-muted small">El comando de inicio predeterminado que debe usarse para los nuevos servidores creados con este Egg. Puede cambiar esto por servidor según sea necesario.</p>
                            </div>
                        </div>
                    </div>
                </div>
            </div>
        </div>
        <div class="col-xs-12">
            <div class="box">
                <div class="box-header with-border">
                    <h3 class="box-title">Gestión de procesos</h3>
                </div>
                <div class="box-body">
                    <div class="row">
                        <div class="col-xs-12">
                            <div class="alert alert-warning">
                                <p>Todos los campos son obligatorios a menos que seleccione una opción separada en el cuadro desplegable "Copiar configuración de", en cuyo caso los campos se pueden dejar en blanco para usar los valores de esa opción.</p>
                            </div>
                        </div>
                        <div class="col-sm-6">
                            <div class="form-group">
                                <label for="pConfigFrom" class="form-label">Configuraciones de cópia de</label>
                                <select name="config_from" id="pConfigFrom" class="form-control">
                                    <option value="">Nada</option>
                                </select>
                                <p class="text-muted small">Si desea establecer la configuración predeterminada de otro huevo, selecciónelo en el menú desplegable de arriba.</p>
                            </div>
                            <div class="form-group">
                                <label for="pConfigStop" class="form-label">Comando de Parar</label>
                                <input type="text" id="pConfigStop" name="config_stop" class="form-control" value="{{ old('config_stop') }}" />
                                <p class="text-muted small">El comando que se debe enviar a los procesos del servidor para detenerlos correctamente. Si necesita enviar un <code>SIGINT</code> que tu debes escribir <code>^C</code> aqui.</p>
                            </div>
                            <div class="form-group">
                                <label for="pConfigLogs" class="form-label">Configuración de log</label>
                                <textarea data-action="handle-tabs" id="pConfigLogs" name="config_logs" class="form-control" rows="6">{{ old('config_logs') }}</textarea>
                                <p class="text-muted small">Essa deve ser uma representação JSON de onde os arquivos de log são armazenados e se o daemon deve ou não estar criando logs personalizados.</p>
                            </div>
                        </div>
                        <div class="col-sm-6">
                            <div class="form-group">
                                <label for="pConfigFiles" class="form-label">Archivos de configuración</label>
                                <textarea data-action="handle-tabs" id="pConfigFiles" name="config_files" class="form-control" rows="6">{{ old('config_files') }}</textarea>
                                <p class="text-muted small">Esta debería ser una representación JSON de los archivos de configuración que se modificarán y qué partes se deben cambiar.</p>
                            </div>
                            <div class="form-group">
                                <label for="pConfigStartup" class="form-label">Configuración de Iniciar</label>
                                <textarea data-action="handle-tabs" id="pConfigStartup" name="config_startup" class="form-control" rows="6">{{ old('config_startup') }}</textarea>
                                <p class="text-muted small">Esta debería ser una representación JSON de los valores que el demonio debería buscar al iniciar un servidor para determinar la finalización.</p>
                            </div>
                        </div>
                    </div>
                </div>
                <div class="box-footer">
                    {!! csrf_field() !!}
                    <button type="submit" class="btn btn-success btn-sm pull-right">Crear</button>
                </div>
            </div>
        </div>
    </div>
</form>
@endsection

@section('footer-scripts')
    @parent
    {!! Theme::js('vendor/lodash/lodash.js') !!}
    <script>
    $(document).ready(function() {
        $('#pNestId').select2().change();
        $('#pConfigFrom').select2();
    });
    $('#pNestId').on('change', function (event) {
        $('#pConfigFrom').html('<option value="">None</option>').select2({
            data: $.map(_.get(Pterodactyl.nests, $(this).val() + '.eggs', []), function (item) {
                return {
                    id: item.id,
                    text: item.name + ' <' + item.author + '>',
                };
            }),
        });
    });
    $('textarea[data-action="handle-tabs"]').on('keydown', function(event) {
        if (event.keyCode === 9) {
            event.preventDefault();

            var curPos = $(this)[0].selectionStart;
            var prepend = $(this).val().substr(0, curPos);
            var append = $(this).val().substr(curPos);

            $(this).val(prepend + '    ' + append);
        }
    });
    </script>
@endsection
