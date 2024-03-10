@extends('layouts.admin')

@section('title')
    Nodos &rarr; Nuevos
@endsection

@section('content-header')
    <h1>Nuevo Nodo<small>Crear un nuevo Nodo local o remoto para los servidores a instalar.</small></h1>
    <ol class="breadcrumb">
        <li><a href="{{ route('admin.index') }}">Administración</a></li>
        <li><a href="{{ route('admin.nodes') }}">Nodos</a></li>
        <li class="active">Nuevo</li>
    </ol>
@endsection

@section('content')
<form action="{{ route('admin.nodes.new') }}" method="POST">
    <div class="row">
        <div class="col-sm-6">
            <div class="box box-primary">
                <div class="box-header with-border">
                    <h3 class="box-title">Detalles Básicos</h3>
                </div>
                <div class="box-body">
                    <div class="form-group">
                        <label for="pName" class="form-label">Nome</label>
                        <input type="text" name="name" id="pName" class="form-control" value="{{ old('name') }}"/>
                        <p class="text-muted small">Límites de caracteres: <code>a-zA-Z0-9_.-</code> e <code>[Espacio]</code> (min 1, max 100 caracteres).</p>
                    </div>
                    <div class="form-group">
                        <label for="pDescription" class="form-label">Descripción</label>
                        <textarea name="description" id="pDescription" rows="4" class="form-control">{{ old('description') }}</textarea>
                    </div>
                    <div class="form-group">
                        <label for="pLocationId" class="form-label">Localización</label>
                        <select name="location_id" id="pLocationId">
                            @foreach($locations as $location)
                                <option value="{{ $location->id }}" {{ $location->id != old('location_id') ?: 'selected' }}>{{ $location->short }}</option>
                            @endforeach
                        </select>
                    </div>
                    <div class="form-group">
                        <label class="form-label">Visibilidad del Nodo</label>
                        <div>
                            <div class="radio radio-success radio-inline">

                                <input type="radio" id="pPublicTrue" value="1" name="public" checked>
                                <label for="pPublicTrue"> Público </label>
                            </div>
                            <div class="radio radio-danger radio-inline">
                                <input type="radio" id="pPublicFalse" value="0" name="public">
                                <label for="pPublicFalse"> Privado </label>
                            </div>
                        </div>
                        <p class="text-muted small">Por definir un Nodo como<code>privado</code>, tu estaras negando la capacidad de implantar automaticamente ese nodo.
                    </div>
                    <div class="form-group">
                        <label for="pFQDN" class="form-label">FQDN Wings</label>
                        <input type="text" name="fqdn" id="pFQDN" class="form-control" value="{{ old('fqdn') }}"/>
                        <p class="text-muted small">Introduzca el nombre de dominio (por ejemplo, <código> nodo.ejemplo.com</código>) que se utilizará para conectarse al demonio. Se puede usar una dirección IP <em>sólo</em> si no estás usando SSL para ese nodo.</p>
                    </div>
                    <div class="form-group">
                        <label for="daemonSFTPIP" class="control-label">FQDN SFTP (Opcional)</label>
                        <div>
                        <input type="text" name="daemonSFTPIP" class="form-control" id="pDaemonSFTPIP"/>
                        </div>
                        <p class="text-muted"><small>Ingrese el nombre de dominio SFTP o IP (por ejemplo, <code>sftp.example.com</code> o <code>123.456.789.123</code>) que se usará para conectarse al sftp del demonio. SFTP separado, puede configurar un servidor Wings detrás del Cloudflare Proxy. Esto puede mejorar la seguridad y la eficiencia del servidor.
                            </small></p>
                    </div>
                    <div class="form-group">
                        <label class="form-label">Comunicarse por SSL</label>
                        <div>
                            <div class="radio radio-success radio-inline">
                                <input type="radio" id="pSSLTrue" value="https" name="scheme" checked>
                                <label for="pSSLTrue"> Usar conexión SSL</label>
                            </div>
                            <div class="radio radio-danger radio-inline">
                                <input type="radio" id="pSSLFalse" value="http" name="scheme" @if(request()->isSecure()) disabled @endif>
                                <label for="pSSLFalse"> Usar conexión HTTP</label>
                            </div>
                        </div>
                        @if(request()->isSecure())
                            <p class="text-danger small">Su Panel de control está actualmente configurado para utilizar una conexión segura. Para que los navegadores se conecten a su nodo, <strong>debe</strong> utilizar una conexión SSL.</p>
                        @else
                            <p class="text-muted small">En la mayoría de los casos, deberá optar por utilizar una conexión SSL. Si utiliza una dirección IP o si no desea utilizar SSL, seleccione una conexión HTTP.</p>
                        @endif
                    </div>
                    <div class="form-group">
                        <label class="form-label">Servicios CDN(Proxy)</label>
                        <div>
                            <div class="radio radio-success radio-inline">
                                <input type="radio" id="pProxyFalse" value="0" name="behind_proxy" checked>
                                <label for="pProxyFalse"> No usar Proxy </label>
                            </div>
                            <div class="radio radio-info radio-inline">
                                <input type="radio" id="pProxyTrue" value="1" name="behind_proxy">
                                <label for="pProxyTrue"> Usar proxy </label>
                            </div>
                        </div>
                        <p class="text-muted small">Si está utilizando servicios CDN que tienen proxy habilitado, escriba <code>"Use Proxy"</code>; de lo contrario, deje <code>"Do not use Proxy"</code>. En CloudFlare esto puede no ser necesario.</p>
                    </div>
                </div>
            </div>
        </div>
        <div class="col-sm-6">
            <div class="box box-primary">
                <div class="box-header with-border">
                    <h3 class="box-title">Configuraciones</h3>
                </div>
                <div class="box-body">
                    <div class="row">
                        <div class="form-group col-md-6">
                            <label for="pDaemonBase" class="form-label">Directorio de archivos del servidor Daemon </label>
                            <input type="text" name="daemonBase" id="pDaemonBase" class="form-control" value="/var/lib/pterodactyl/volumes" />
                            <p class="text-muted small">Ingrese el directorio donde se deben almacenar los archivos del servidor. <strong>Si utilizas OVH deberías comprobar tu esquema de partición. Es posible que necesites usar <code>/home/daemon-data</code> para tener suficiente espacio.</strong></p>
                        </div>
                        <div class="form-group col-md-6">
                            <label for="pMemory" class="form-label">Memoria Total </label>
                            <div class="input-group">
                                <input type="text" name="memory" data-multiplicator="true" class="form-control" id="pMemory" value="{{ old('memory') }}"/>
                                <span class="input-group-addon">MiB</span>
                            </div>
                        </div>
                        <div class="form-group col-md-6">
                            <label for="pMemoryOverallocate" class="form-label">Sobreasignación de memoria</label>
                            <div class="input-group">
                                <input type="text" name="memory_overallocate" class="form-control" id="pMemoryOverallocate" value="{{ old('memory_overallocate') }}"/>
                                <span class="input-group-addon">%</span>
                            </div>
                        </div>
                        <div class="col-md-12">
                            <p class="text-muted small">Ingrese la cantidad total de memoria disponible para nuevos servidores. Si desea permitir la sobreasignación de memoria, ingrese el porcentaje que desea permitir. Para deshabilitar la verificación de sobreasignación, ingrese <code>-1</code> en el campo. Escribir <code>0</code> evitará la creación de nuevos servidores si excede el límite de nodos.</p>
                        </div>
                    </div>
                    <div class="row">
                        <div class="form-group col-md-6">
                            <label for="pDisk" class="form-label">Espacio en disco Total</label>
                            <div class="input-group">
                                <input type="text" name="disk" data-multiplicator="true" class="form-control" id="pDisk" value="{{ old('disk') }}"/>
                                <span class="input-group-addon">MiB</span>
                            </div>
                        </div>
                        <div class="form-group col-md-6">
                            <label for="pDiskOverallocate" class="form-label">Sobreasignación de disco</label>
                            <div class="input-group">
                                <input type="text" name="disk_overallocate" class="form-control" id="pDiskOverallocate" value="{{ old('disk_overallocate') }}"/>
                                <span class="input-group-addon">%</span>
                            </div>
                        </div>
                        <div class="col-md-12">
                            <p class="text-muted small">Ingrese la cantidad total de espacio en disco disponible para nuevos servidores. Si desea permitir la sobreasignación de espacio en disco, ingrese el porcentaje que desea permitir. Para deshabilitar la verificación de sobreasignación, ingrese <code>-1</code> en el campo. Escribir <code>0</code> evitará la creación de nuevos servidores si excede el límite de nodos.</p>
                        </div>
                    </div>
                        <div class="row">
                            <div class="form-group col-md-6">
                                <label for="daemonListen" class="control-label"><span class="label label-warning"><i class="fa fa-power-off"></i></span>Puerto del Daemon</label>
                                    <div>
                                        <input type="text" name="daemonListen" class="form-control" id="pDaemonListen" value="8080" />
                                    </div>
                                </div>
                            <div class="form-group col-md-6">
                                <label for="daemonSFTP" class="control-label"><span class="label label-warning"><i class="fa fa-power-off"></i></span>Puerto SFTP del Daemon</label>
                                    <div>
                                        <input type="text" name="daemonSFTP" class="form-control" id="pDaemonSFTP" value="2022" />
                                </div>
                        </div>
                    <div class="col-md-12">
                            <p class="text-muted small">El demonio ejecuta su propio contenedor de administración SFTP y no utiliza el proceso SSHd en el servidor físico principal. <Strong>No utilice el mismo puerto que designó para el proceso SSH de su servidor físico.</strong> Si está ejecutando el demonio detrás de CloudFlare&reg; debe configurar el puerto del demonio en <code>8443</code> para permitir el proxy websocket a través de SSL.</p>
                        </div>
                </div>
                <div class="form-group">
                    <label class="form-label">Implementable a través de Jexactyl Store</label>
                        <div>
                            <div class="radio radio-success radio-inline">
                                <input type="radio" id="pDeployableTrue" value="1" name="deployable" checked>
                                <label for="pDeployableTrue"> Permitir </label>
                            </div>
                            <div class="radio radio-danger radio-inline">
                                <input type="radio" id="pDeployableFalse" value="0" name="deployable">
                                <label for="pDeployableFalse"> Negar </label>
                            </div>
                            </div>
                        <p class="text-muted"><small>
                                Esta opción le permite controlar si este nodo es visible a través de la página Creación de servidor del escaparate de Jexactyl.
                                Si se configura en denegar, los usuarios no podrán implementar en este nodo.
                        </small></p>
                    </div>
                </div>
                <div class="box-footer">
                    {!! csrf_field() !!}
                    <button type="submit" class="btn btn-success pull-right">Crear Nodo</button>
                </div>
            </div>
        </div>
    </div>
</form>
@endsection

@section('footer-scripts')
    @parent
    <script>
        $('#pLocationId').select2();
    </script>
@endsection
