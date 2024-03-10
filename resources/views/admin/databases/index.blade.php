@extends('layouts.admin')

@section('title')
    Host de database
@endsection

@section('content-header')
    <h1>Host de Database<small>Alojamiento de bases de datos en los que los servidores pueden crear bases de datos.</small></h1>
    <ol class="breadcrumb">
        <li><a href="{{ route('admin.index') }}">Administrador</a></li>
        <li class="active">Lista de Databases</li>
    </ol>
@endsection

@section('content')
<div class="row">
    <div class="col-xs-12">
        <div class="box box-primary">
            <div class="box-header with-border">
                <h3 class="box-title">Lista de Databases</h3>
                <div class="box-tools">
                    <button class="btn btn-sm btn-primary" data-toggle="modal" data-target="#newHostModal">Crear nuevo</button>
                </div>
            </div>
            <div class="box-body table-responsive no-padding">
                <table class="table table-hover">
                    <tbody>
                        <tr>
                            <th>ID</th>
                            <th>Nombre</th>
                            <th>Alojado</th>
                            <th>Puerto</th>
                            <th>Usuario</th>
                            <th class="text-center">Database</th>
                            <th class="text-center">Nodo</th>
                        </tr>
                        @foreach ($hosts as $host)
                            <tr>
                                <td><code>{{ $host->id }}</code></td>
                                <td><a href="{{ route('admin.databases.view', $host->id) }}">{{ $host->name }}</a></td>
                                <td><code>{{ $host->host }}</code></td>
                                <td><code>{{ $host->port }}</code></td>
                                <td>{{ $host->username }}</td>
                                <td class="text-center">{{ $host->databases_count }}</td>
                                <td class="text-center">
                                    @if(! is_null($host->node))
                                        <a href="{{ route('admin.nodes.view', $host->node->id) }}">{{ $host->node->name }}</a>
                                    @else
                                        <span class="label label-default">Nada</span>
                                    @endif
                                </td>
                            </tr>
                        @endforeach
                    </tbody>
                </table>
            </div>
        </div>
    </div>
</div>
<div class="modal fade" id="newHostModal" tabindex="-1" role="dialog">
    <div class="modal-dialog" role="document">
        <div class="modal-content">
            <form action="{{ route('admin.databases') }}" method="POST">
                <div class="modal-header">
                    <button type="button" class="close" data-dismiss="modal" aria-label="Close"><span aria-hidden="true">&times;</span></button>
                    <h4 class="modal-title">Crear nuevo Database</h4>
                </div>
                <div class="modal-body">
                    <div class="form-group">
                        <label for="pName" class="form-label">Nome</label>
                        <input type="text" name="name" id="pName" class="form-control" />
                        <p class="text-muted small">Un pequeño identificador utilizado para distinguir esta ubicación de otras. Debe tener entre 1 y 60 caracteres, p.e., <code>cl.panel.db</code>.</p>
                    </div>
                    <div class="row">
                        <div class="col-md-6">
                            <label for="pHost" class="form-label">Alojamiento</label>
                            <input type="text" name="host" id="pHost" class="form-control" />
                            <p class="text-muted small">La dirección IP o FQDN que se debe utilizar al intentar conectarse a este host MySQL <em> del panel</em> para agregar nuevas databases.</p>
                        </div>
                        <div class="col-md-6">
                            <label for="pPort" class="form-label">Puerto</label>
                            <input type="text" name="port" id="pPort" class="form-control" value="3306"/>
                            <p class="text-muted small">El puerto en el que se ejecuta MySQL para este host.</p>
                        </div>
                    </div>
                    <div class="row">
                        <div class="col-md-6">
                            <label for="pUsername" class="form-label">Usuario</label>
                            <input type="text" name="username" id="pUsername" class="form-control" />
                            <p class="text-muted small">El nombre de usuario de una cuenta que tiene permisos suficientes para crear nuevos usuarios y bases de datos en el sistema..</p>
                        </div>
                        <div class="col-md-6">
                            <label for="pPassword" class="form-label">Contraseña</label>
                            <input type="password" name="password" id="pPassword" class="form-control" />
                            <p class="text-muted small">La contraseña de la cuenta definida..</p>
                        </div>
                    </div>
                    <div class="form-group">
                        <label for="pNodeId" class="form-label">Nodo linkeado</label>
                        <select name="node_id" id="pNodeId" class="form-control">
                            <option value="">Nada</option>
                            @foreach($locations as $location)
                                <optgroup label="{{ $location->short }}">
                                    @foreach($location->nodes as $node)
                                        <option value="{{ $node->id }}">{{ $node->name }}</option>
                                    @endforeach
                                </optgroup>
                            @endforeach
                        </select>
                        <p class="text-muted small">Esta configuración no hace nada más que el valor predeterminado para este host de base de datos al agregar una base de datos a un servidor en el nodo seleccionado.</p>
                    </div>
                </div>
                <div class="modal-footer">
                    <p class="text-danger small text-left">La cuenta definida para este host de base de datos <strong>el debe</strong> tener permisos con <code>OPCIÓN DE CONSECIÓN</code>. Si la cuenta definida no tiene el permiso, solicitudes para crear bases de datos <em>fallará</em>. <strong>No utilice los mismos detalles de cuenta para MySQL que definió para este panel.</strong></p>
                    {!! csrf_field() !!}
                    <button type="button" class="btn btn-default btn-sm pull-left" data-dismiss="modal">Cancelar</button>
                    <button type="submit" class="btn btn-success btn-sm">Crear</button>
                </div>
            </form>
        </div>
    </div>
</div>
@endsection

@section('footer-scripts')
    @parent
    <script>
        $('#pNodeId').select2();
    </script>
@endsection
