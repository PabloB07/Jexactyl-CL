@extends('layouts.admin')

@section('title')
Servidor — {{ $server->name }}: Inicialización
@endsection

@section('content-header')
    <h1>{{ $server->name }}<small>Controla el comando de inicialización y las variables.</small></h1>
    <ol class="breadcrumb">
        <li><a href="{{ route('admin.index') }}">Admin</a></li>
        <li><a href="{{ route('admin.servers') }}">Servidores</a></li>
        <li><a href="{{ route('admin.servers.view', $server->id) }}">{{ $server->name }}</a></li>
        <li class="active">Inicialización</li>
    </ol>
@endsection

@section('content')
@include('admin.servers.partials.navigation')
<form action="{{ route('admin.servers.view.startup', $server->id) }}" method="POST">
    <div class="row">
        <div class="col-xs-12">
            <div class="box box-primary">
                <div class="box-header with-border">
                    <h3 class="box-title">Modificar comando de Inicialización</h3>
                </div>
                <div class="box-body">
                    <label for="pStartup" class="form-label">Comando de inicialización</label>
                    <input id="pStartup" name="startup" class="form-control" type="text" value="{{ old('startup', $server->startup) }}" />
                    <p class="small text-muted">
                        Edite el comando de inicio de su servidor aquí.
                        Las siguientes variables están disponibles de forma predeterminada:
                          <code>@{{SERVER_MEMORY}}</code>, <code>@{{SERVER_IP}}</code>, e <code>@{{SERVER_PORT}}</code>.</p>
                </div>
                <div class="box-body">
                    <label for="pDefaultStartupCommand" class="form-label">
                        Comando de arranque predeterminado</label>
                    <input id="pDefaultStartupCommand" class="form-control" type="text" readonly />
                </div>
                <div class="box-footer">
                    {!! csrf_field() !!}
                    <button type="submit" class="btn btn-primary btn-sm pull-right">Guardar modificaciones</button>
                </div>
            </div>
        </div>
    </div>
    <div class="row">
        <div class="col-md-6">
            <div class="box">
                <div class="box-header with-border">
                    <h3 class="box-title">Configuraciones de servicios</h3>
                </div>
                <div class="box-body row">
                    <div class="col-xs-12">
                        <p class="small text-danger">
                            Cambiar cualquiera de los valores siguientes generará un comando de reinstalación en el servidor.
                            El servidor se detendrá y luego continuará.
                            Si desea que los scripts de servicio no se ejecuten, asegúrese de que la casilla esté marcada en la parte inferior.
                        </p>
                        <p class="small text-danger">
                            <strong>Esta es en muchos casos una operación destructiva.
                                Este servidor se detendrá inmediatamente para que se lleve a cabo esta acción.</strong>
                        </p>
                    </div>
                    <div class="form-group col-xs-12">
                        <label for="pNestId">Nest</label>
                        <select name="nest_id" id="pNestId" class="form-control">
                            @foreach($nests as $nest)
                                <option value="{{ $nest->id }}"
                                    @if($nest->id === $server->nest_id)
                                        selected
                                    @endif
                                >{{ $nest->name }}</option>
                            @endforeach
                        </select>
                        <p class="small text-muted no-margin">Seleccione el Nest con el que se agrupará este servidor.</p>
                    </div>
                    <div class="form-group col-xs-12">
                        <label for="pEggId">Egg</label>
                        <select name="egg_id" id="pEggId" class="form-control"></select>
                        <p class="small text-muted no-margin">Seleccione el Egg que proporcionará datos de procesamiento para este servidor.</p>
                    </div>
                    <div class="form-group col-xs-12">
                        <div class="checkbox checkbox-primary no-margin-bottom">
                            <input id="pSkipScripting" name="skip_scripts" type="checkbox" value="1" @if($server->skip_scripts) checked @endif />
                            <label for="pSkipScripting" class="strong">Omita el script de instalación del Egg</label>
                        </div>
                        <p class="small text-muted no-margin">
                            Si el Egg seleccionado tiene un script de instalación adjunto, el script se ejecutará durante la instalación.
                            Si desea omitir este paso, marque esta casilla.</p>
                    </div>
                </div>
            </div>
            <div class="box">
                <div class="box-header with-border">
                    <h3 class="box-title">Configuración dela imagen de Docker</h3>
                </div>
                <div class="box-body">
                    <div class="form-group">
                        <label for="pDockerImage">Imagen</label>
                        <select id="pDockerImage" name="docker_image" class="form-control"></select>
                        <input id="pDockerImageCustom" name="custom_docker_image" value="{{ old('custom_docker_image') }}" class="form-control" placeholder="Or enter a custom image..." style="margin-top:1rem"/>
                        <p class="small text-muted no-margin">
                            Esta es la imagen de Docker que se utilizará para ejecutar este servidor.
                            Seleccione una imagen del campo o ingrese una imagen personalizada en el campo de texto de arriba.</p>
                    </div>
                </div>
            </div>
        </div>
        <div class="col-md-6">
            <div class="row" id="appendVariablesTo"></div>
        </div>
    </div>
</form>
@endsection

@section('footer-scripts')
    @parent
    {!! Theme::js('vendor/lodash/lodash.js') !!}
    <script>
    $(document).ready(function () {
        $('#pEggId').select2({placeholder: 'Select a Nest Egg'}).on('change', function () {
            var selectedEgg = _.isNull($(this).val()) ? $(this).find('option').first().val() : $(this).val();
            var parentChain = _.get(Pterodactyl.nests, $("#pNestId").val());
            var objectChain = _.get(parentChain, 'eggs.' + selectedEgg);

            const images = _.get(objectChain, 'docker_images', [])
            $('#pDockerImage').html('');
            const keys = Object.keys(images);
            for (let i = 0; i < keys.length; i++) {
                let opt = document.createElement('option');
                opt.value = images[keys[i]];
                opt.innerHTML = keys[i] + " (" + images[keys[i]] + ")";
                if (objectChain.id === parseInt(Pterodactyl.server.egg_id) && Pterodactyl.server.image == opt.value) {
                    opt.selected = true
                }
                $('#pDockerImage').append(opt);
            }
            $('#pDockerImage').on('change', function () {
                $('#pDockerImageCustom').val('');
            })

            if (objectChain.id === parseInt(Pterodactyl.server.egg_id)) {
                if ($('#pDockerImage').val() != Pterodactyl.server.image) {
                    $('#pDockerImageCustom').val(Pterodactyl.server.image);
                }
            }

            if (!_.get(objectChain, 'startup', false)) {
                $('#pDefaultStartupCommand').val(_.get(parentChain, 'startup', 'ERROR: Startup Not Defined!'));
            } else {
                $('#pDefaultStartupCommand').val(_.get(objectChain, 'startup'));
            }

            $('#appendVariablesTo').html('');
            $.each(_.get(objectChain, 'variables', []), function (i, item) {
                var setValue = _.get(Pterodactyl.server_variables, item.env_variable, item.default_value);
                var isRequired = (item.required === 1) ? '<span class="label label-danger">Required</span> ' : '';
                var dataAppend = ' \
                    <div class="col-xs-12"> \
                        <div class="box"> \
                            <div class="box-header with-border"> \
                                <h3 class="box-title">' + isRequired + item.name + '</h3> \
                            </div> \
                            <div class="box-body"> \
                                <input name="environment[' + item.env_variable + ']" class="form-control" type="text" id="egg_variable_' + item.env_variable + '" /> \
                                <p class="no-margin small text-muted">' + item.description + '</p> \
                            </div> \
                            <div class="box-footer"> \
                                <p class="no-margin text-muted small"><strong>Startup Command Variable:</strong> <code>' + item.env_variable + '</code></p> \
                                <p class="no-margin text-muted small"><strong>Input Rules:</strong> <code>' + item.rules + '</code></p> \
                            </div> \
                        </div> \
                    </div>';
                $('#appendVariablesTo').append(dataAppend).find('#egg_variable_' + item.env_variable).val(setValue);
            });
        });

        $('#pNestId').select2({placeholder: 'Select a Nest'}).on('change', function () {
            $('#pEggId').html('').select2({
                data: $.map(_.get(Pterodactyl.nests, $(this).val() + '.eggs', []), function (item) {
                    return {
                        id: item.id,
                        text: item.name,
                    };
                }),
            });

            if (_.isObject(_.get(Pterodactyl.nests, $(this).val() + '.eggs.' + Pterodactyl.server.egg_id))) {
                $('#pEggId').val(Pterodactyl.server.egg_id);
            }

            $('#pEggId').change();
        }).change();
    });
    </script>
@endsection
