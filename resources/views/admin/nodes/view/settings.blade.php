@extends('layouts.admin')

@section('title')
    {{ $node->name }}: Definiciones
@endsection

@section('content-header')
    <h1>{{ $node->name }}<small>Definir las definiçiones del nodo.</small></h1>
    <ol class="breadcrumb">
        <li><a href="{{ route('admin.index') }}">Administración</a></li>
        <li><a href="{{ route('admin.nodes') }}">Nodos</a></li>
        <li><a href="{{ route('admin.nodes.view', $node->id) }}">{{ $node->name }}</a></li>
        <li class="active">Definições</li>
    </ol>
@endsection

@section('content')
<div class="row">
    <div class="col-xs-12">
        <div class="nav-tabs-custom nav-tabs-floating">
            <ul class="nav nav-tabs">
                <li><a href="{{ route('admin.nodes.view', $node->id) }}">Sobre</a></li>
                <li class="active"><a href="{{ route('admin.nodes.view.settings', $node->id) }}">Definiciones</a></li>
                <li><a href="{{ route('admin.nodes.view.configuration', $node->id) }}">Configuración</a></li>
                <li><a href="{{ route('admin.nodes.view.allocation', $node->id) }}">Alocaciones</a></li>
                <li><a href="{{ route('admin.nodes.view.servers', $node->id) }}">Servidores</a></li>
            </ul>
        </div>
    </div>
</div>
<form action="{{ route('admin.nodes.view.settings', $node->id) }}" method="POST">
    <div class="row">
        <div class="col-sm-6">
            <div class="box">
                <div class="box-header with-border">
                    <h3 class="box-title">Definiciones</h3>
                </div>
                <div class="box-body row">
                    <div class="form-group col-xs-12">
                        <label for="name" class="control-label">Nombre del Nodo</label>
                        <div>
                            <input type="text" autocomplete="off" name="name" class="form-control" value="{{ old('name', $node->name) }}" />
                            <p class="text-muted"><small>Límites de carácteres: <code>a-zA-Z0-9_.-</code> e <code>[Espacio]</code> (min 1, max 100 caracteres).</small></p>
                        </div>
                    </div>
                    <div class="form-group col-xs-12">
                        <label for="description" class="control-label">Descripción</label>
                        <div>
                            <textarea name="description" id="description" rows="4" class="form-control">{{ $node->description }}</textarea>
                        </div>
                    </div>
                    <div class="form-group col-xs-12">
                        <label for="name" class="control-label">Localización</label>
                        <div>
                            <select name="location_id" class="form-control">
                                @foreach($locations as $location)
                                    <option value="{{ $location->id }}" {{ (old('location_id', $node->location_id) === $location->id) ? 'selected' : '' }}>{{ $location->long }} ({{ $location->short }})</option>
                                @endforeach
                            </select>
                        </div>
                    </div>
                    <div class="form-group col-xs-12">
                        <label for="public" class="control-label">Permitir alocacion automática <sup><a data-toggle="tooltip" data-placement="top" title="Permitir alocacion automática para este Nodo?">?</a></sup></label>
                        <div>
                            <input type="radio" name="public" value="1" {{ (old('public', $node->public)) ? 'checked' : '' }} id="public_1" checked> <label for="public_1" style="padding-left:5px;">Si</label><br />
                            <input type="radio" name="public" value="0" {{ (old('public', $node->public)) ? '' : 'checked' }} id="public_0"> <label for="public_0" style="padding-left:5px;">No</label>
                        </div>
                    </div>
                    <div class="form-group col-xs-12">
                        <label for="fqdn" class="control-label">FQDN Wings</label>
                        <div>
                            <input type="text" autocomplete="off" name="fqdn" class="form-control" value="{{ old('fqdn', $node->fqdn) }}" />
                        </div>
                        <p class="text-muted"><small>Ingresa el nombre de domínio (por ejemplo,<code> nodo.example.com</code>) que se utilizará para conectarse al demonio. Solo se puede utilizar una dirección IP si no está utilizando SSL para ese nodo.
                                <a tabindex="0" data-toggle="popover" data-trigger="focus" title="¿Por qué necesito un FQDN?" data-content="
Para proteger las comunicaciones entre su servidor y este Nodo, utilizamos SSL. No es posible generar un certificado SSL para direcciones IP, por lo que deberá proporcionar un FQDN.">¿Por qué?</a>
                            </small></p>
                    </div>
                    <div class="form-group col-xs-12">
                        <label for="daemonSFTPIP" class="control-label">FQDN SFTP (Opcional)</label>
                        <div>
                        <input type="text" name="daemonSFTPIP" class="form-control" value="{{ old('daemonSFTPIP', $node->daemonSFTPIP) }}"/>
                        </div>
                        <p class="text-muted"><small>Ingresa el nombre de domínio SFTP o tu ip (por ejemplo, <code>sftp.example.com</code> o <code>123.456.789.123</code>) para ser utilizado para conectarse al sftp del demonio.
                                <a tabindex="0" data-toggle="popover" data-trigger="focus" title="¿Por qué necesito un FQDN para SFTP?" data-content="Al utilizar un SFTP independiente, puede configurar un servidor Wings detrás del proxy Cloudflare. Esto puede mejorar la seguridad y la eficiencia del servidor.">¿Por qué?</a>
                            </small></p>
                    </div>
                    <div class="form-group col-xs-12">
                        <label class="form-label"><span class="label label-warning"><i class="fa fa-power-off"></i></span> Comunicar por SSL</label>
                        <div>
                            <div class="radio radio-success radio-inline">
                                <input type="radio" id="pSSLTrue" value="https" name="scheme" {{ (old('scheme', $node->scheme) === 'https') ? 'checked' : '' }}>
                                <label for="pSSLTrue"> Usar conexión SSL</label>
                            </div>
                            <div class="radio radio-danger radio-inline">
                                <input type="radio" id="pSSLFalse" value="http" name="scheme" {{ (old('scheme', $node->scheme) !== 'https') ? 'checked' : '' }}>
                                <label for="pSSLFalse"> Usar conexión HTTP</label>
                            </div>
                        </div>
                        <p class="text-muted small">En la mayoría de los casos, deberá optar por utilizar una conexión SSL. Si utiliza una dirección IP o si no desea utilizar SSL, seleccione una conexión HTTP.</p>
                    </div>
                    <div class="form-group col-xs-12">
                        <label class="form-label"><span class="label label-warning"><i class="fa fa-power-off"></i></span>Servicios CDN(Proxy)</label>
                        <div>
                            <div class="radio radio-success radio-inline">
                                <input type="radio" id="pProxyFalse" value="0" name="behind_proxy" {{ (old('behind_proxy', $node->behind_proxy) == false) ? 'checked' : '' }}>
                                <label for="pProxyFalse"> No usar Proxy </label>
                            </div>
                            <div class="radio radio-info radio-inline">
                                <input type="radio" id="pProxyTrue" value="1" name="behind_proxy" {{ (old('behind_proxy', $node->behind_proxy) == true) ? 'checked' : '' }}>
                                <label for="pProxyTrue"> Usar Proxy </label>
                            </div>
                        </div>
                        <p class="text-muted small">Si está utilizando servicios CDN que tienen proxy habilitado, escriba <code>"Use Proxy"</code>; de lo contrario, deje <code>"Do not use Proxy"</code>. En CloudFlare esto puede no ser necesario.</p>
                    </div>
                    <div class="form-group col-xs-12">
                        <label class="form-label"><span class="label label-warning"><i class="fa fa-wrench"></i></span> Modo en Mantención</label>
                        <div>
                            <div class="radio radio-success radio-inline">
                                <input type="radio" id="pMaintenanceFalse" value="0" name="maintenance_mode" {{ (old('behind_proxy', $node->maintenance_mode) == false) ? 'checked' : '' }}>
                                <label for="pMaintenanceFalse"> Desactivado</label>
                            </div>
                            <div class="radio radio-warning radio-inline">
                                <input type="radio" id="pMaintenanceTrue" value="1" name="maintenance_mode" {{ (old('behind_proxy', $node->maintenance_mode) == true) ? 'checked' : '' }}>
                                <label for="pMaintenanceTrue"> Activado</label>
                            </div>
                        </div>
                        <p class="text-muted small">Si el Nodo está marcado como 'En Mantenimiento', los usuarios no podrán acceder a los servidores que se encuentran en ese nodo.</p>
                    </div>
                </div>
            </div>
        </div>
        <div class="col-sm-6">
            <div class="box">
                <div class="box-header with-border">
                    <h3 class="box-title">Límites de alocaciones</h3>
                </div>
                <div class="box-body row">
                    <div class="col-xs-12">
                        <div class="row">
                            <div class="form-group col-xs-6">
                                <label for="memory" class="control-label">Memoria Total</label>
                                <div class="input-group">
                                    <input type="text" name="memory" class="form-control" data-multiplicator="true" value="{{ old('memory', $node->memory) }}"/>
                                    <span class="input-group-addon">MiB</span>
                                </div>
                            </div>
                            <div class="form-group col-xs-6">
                                <label for="memory_overallocate" class="control-label">Sobreasignación</label>
                                <div class="input-group">
                                    <input type="text" name="memory_overallocate" class="form-control" value="{{ old('memory_overallocate', $node->memory_overallocate) }}"/>
                                    <span class="input-group-addon">%</span>
                                </div>
                            </div>
                        </div>
                        <p class="text-muted small">Ingrese la cantidad total de memoria disponible en este nodo para su asignación a servidores. También puedes proporcionar un porcentaje que te permita asignar más memoria que la definida.</p>
                    </div>
                    <div class="col-xs-12">
                        <div class="row">
                            <div class="form-group col-xs-6">
                                <label for="disk" class="control-label">Espacio en disco</label>
                                <div class="input-group">
                                    <input type="text" name="disk" class="form-control" data-multiplicator="true" value="{{ old('disk', $node->disk) }}"/>
                                    <span class="input-group-addon">MiB</span>
                                </div>
                            </div>
                            <div class="form-group col-xs-6">
                                <label for="disk_overallocate" class="control-label">Sobreasignación</label>
                                <div class="input-group">
                                    <input type="text" name="disk_overallocate" class="form-control" value="{{ old('disk_overallocate', $node->disk_overallocate) }}"/>
                                    <span class="input-group-addon">%</span>
                                </div>
                            </div>
                        </div>
                        <p class="text-muted small">Ingrese la cantidad total de espacio en disco disponible en este nodo para la asignación del servidor. También puede proporcionar un porcentaje que determinará la cantidad de espacio en disco permitido por encima del límite establecido.</p>
                    </div>
                </div>
            </div>
        </div>
        <div class="col-sm-6">
            <div class="box">
                <div class="box-header with-border">
                    <h3 class="box-title">Configuración General</h3>
                </div>
                <div class="box-body row">
                    <div class="form-group col-xs-12">
                        <label for="disk_overallocate" class="control-label">Tamaño máximo de archivo de carga web</label>
                        <div class="input-group">
                            <input type="text" name="upload_size" class="form-control" value="{{ old('upload_size', $node->upload_size) }}"/>
                            <span class="input-group-addon">MiB</span>
                        </div>
                        <p class="text-muted"><small>Ingrese el tamaño máximo de archivos que se pueden cargar a través del administrador de archivos basado en web.</small></p>
                    </div>
                    <div class="col-xs-12">
                        <div class="row">
                            <div class="form-group col-md-6">
                                <label for="daemonListen" class="control-label"><span class="label label-warning"><i class="fa fa-power-off"></i></span>Puerto de Daemon</label>
                                <div>
                                    <input type="text" name="daemonListen" class="form-control" value="{{ old('daemonListen', $node->daemonListen) }}"/>
                                </div>
                            </div>
                            <div class="form-group col-md-6">
                                <label for="daemonSFTP" class="control-label"><span class="label label-warning"><i class="fa fa-power-off"></i></span>Puerto SFTP de Daemon</label>
                                <div>
                                    <input type="text" name="daemonSFTP" class="form-control" value="{{ old('daemonSFTP', $node->daemonSFTP) }}"/>
                                </div>
                            </div>
                        </div>
                        <div class="row">
                            <div class="col-md-12">
                                <p class="text-muted"><small>El demonio ejecuta su propio contenedor de administración SFTP y no utiliza el proceso SSHd en el servidor físico principal. <Strong>No utilice el mismo puerto que asignó al proceso SSH del servidor físico.</strong></small></p>
                            </div>
                        </div>
                    </div>
                </div>
            </div>
        </div>
        <div class="col-sm-6">
            <div class="box">
                <div class="box-header with-border">
                    <h3 class="box-title">Implementación</h3>
                </div>
                <div class="box-body row">
                    <div class="form-group col-xs-12">
                        <label for="deployable" class="control-label">Implementable a través de Jexactyl Store</label>
                        <div>
                            <div class="radio radio-success radio-inline">
                                <input type="radio" id="pDeployableTrue" value="1" name="deployable" {{ (old('deployable', $node->deployable)) ? 'checked' : '' }}>
                                <label for="pDeployableTrue"> Permitir</label>
                            </div>
                            <div class="radio radio-danger radio-inline">
                                <input type="radio" id="pDeployableFalse" value="0" name="deployable" {{ (old('deployable', $node->deployable)) ? '' : 'checked' }}>
                                <label for="pDeployableFalse"> Negar</label>
                            </div>
                        </div>
                        <p class="text-muted"><small>
                                Esta opción le permite controlar si este nodo es visible a través de la página Creación de servidor del escaparate de Jexactyl.
                                Si se configura en denegar, los usuarios no podrán implementar en este nodo.
                        </small></p>
                    </div>
                                        <div class="form-group col-xs-12">
                        <label for="deploy_fee" class="control-label">Tasa de implementación de la tienda</label>
                        <div>
                            <input type="text" autocomplete="off" name="deploy_fee" class="form-control" value="{{ old('deploy_fee', $node->deploy_fee) }}" />
                        </div>
                        <p class="text-muted"><small>Introducir un valor aquí significa que los usuarios que implementen un servidor a través de Showcase deben pagar una tarifa en créditos para implementarlo en ese nodo.</small></p>
                    </div>
                </div>
            </div>
        </div>
        <div class="col-xs-12">
            <div class="box box-primary">
                <div class="box-header with-border">
                    <h3 class="box-title">Guardar configuraciones</h3>
                </div>
                <div class="box-body row">
                    <div class="form-group col-sm-6">
                        <div>
                            <input type="checkbox" name="reset_secret" id="reset_secret" /> <label for="reset_secret" class="control-label">Restablecer la clave maestra del demonio</label>
                        </div>
                        <p class="text-muted"><small>Restablecer la clave maestra del demonio anulará cualquier solicitud proveniente de la clave anterior. Esta clave se utiliza para todas las operaciones confidenciales en el demonio, incluida la creación y eliminación del servidor. Le sugerimos cambiar esta clave periódicamente para estar seguro.</small></p>
                    </div>
                </div>
                <div class="box-footer">
                    {!! method_field('PATCH') !!}
                    {!! csrf_field() !!}
                    <button type="submit" class="btn btn-primary pull-right">Guardar Cambios</button>
                </div>
            </div>
        </div>
    </div>
</form>
@endsection

@section('footer-scripts')
    @parent
    <script>
    $('[data-toggle="popover"]').popover({
        placement: 'auto'
    });
    $('select[name="location_id"]').select2();
    </script>
@endsection
