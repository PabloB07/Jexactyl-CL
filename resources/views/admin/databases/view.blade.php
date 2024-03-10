@extends('layouts.admin')

@section('title')
    Host de Database &rarr; Ver &rarr; {{ $host->name }}
@endsection

@section('content-header')
    <h1>{{ $host->name }}<small>Ver bases de datos asociadas y detalles de esta base de datos</small></h1>
    <ol class="breadcrumb">
        <li><a href="{{ route('admin.index') }}">Administrador</a></li>
        <li><a href="{{ route('admin.databases') }}">Host de Database</a></li>
        <li class="active">{{ $host->name }}</li>
    </ol>
@endsection

@section('content')
<form action="{{ route('admin.databases.view', $host->id) }}" method="POST">
    <div class="row">
        <div class="col-sm-6">
            <div class="box box-primary">
                <div class="box-header with-border">
                    <h3 class="box-title">Detalles de Host</h3>
                </div>
                <div class="box-body">
                    <div class="form-group">
                        <label for="pName" class="form-label">Nombre</label>
                        <input type="text" id="pName" name="name" class="form-control" value="{{ old('name', $host->name) }}" />
                    </div>
                    <div class="form-group">
                        <label for="pHost" class="form-label">Alojado</label>
                        <input type="text" id="pHost" name="host" class="form-control" value="{{ old('host', $host->host) }}" />
                        <p class="text-muted small">La dirección IP o FQDN que se debe usar al intentar conectarse a este host MySQL desde el panel</em> para agregar nuevas databases.</p>
                    </div>
                    <div class="form-group">
                        <label for="pPort" class="form-label">Puerto</label>
                        <input type="text" id="pPort" name="port" class="form-control" value="{{ old('port', $host->port) }}" />
                        <p class="text-muted small">El puerto en el que se ejecuta MySQL para este host.</p>
                    </div>
                    <div class="form-group">
                        <label for="pNodeId" class="form-label">Nodo linkeado</label>
                        <select name="node_id" id="pNodeId" class="form-control">
                            <option value="">Nada</option>
                            @foreach($locations as $location)
                                <optgroup label="{{ $location->short }}">
                                    @foreach($location->nodes as $node)
                                        <option value="{{ $node->id }}" {{ $host->node_id !== $node->id ?: 'selected' }}>{{ $node->name }}</option>
                                    @endforeach
                                </optgroup>
                            @endforeach
                        </select>
                        <p class="text-muted small">Esta configuración no hace nada más que el valor predeterminado para este host de base de datos al agregar una base de datos a un servidor en el nodo seleccionado..</p>
                    </div>
                </div>
            </div>
        </div>
        <div class="col-sm-6">
            <div class="box box-primary">
                <div class="box-header with-border">
                    <h3 class="box-title">Detalles de usuario </h3>
                </div>
                <div class="box-body">
                    <div class="form-group">
                        <label for="pUsername" class="form-label">Usuario</label>
                        <input type="text" name="username" id="pUsername" class="form-control" value="{{ old('username', $host->username) }}" />
                        <p class="text-muted small">El nombre de usuario de una cuenta que tiene permisos suficientes para crear nuevos usuarios y bases de datos en el sistema.</p>
                    </div>
                    <div class="form-group">
                        <label for="pPassword" class="form-label">Contraseña</label>
                        <input type="password" name="password" id="pPassword" class="form-control" />
                        <p class="text-muted small">La contraseña de la cuenta definida. Déjelo en blanco para continuar usando la contraseña asignada.</p>
                    </div>
                    <hr />
                    <p class="text-danger small text-left">La cuenta definida para este host de base de datos <strong>debe</strong> tener permisos con <code>OPCIÓN DE CONCÉNSO</code>. Si la cuenta definida no tiene este permiso, solicitudes para crear bases de datos <em>fallará</em>. <strong>No utilice los mismos detalles de cuenta para MySQL que definió para este panel</strong></p>
                </div>
                <div class="box-footer">
                    {!! csrf_field() !!}
                    <button name="_method" value="PATCH" class="btn btn-sm btn-primary pull-right">Guardar</button>
                    <button name="_method" value="DELETE" class="btn btn-sm btn-danger pull-left muted muted-hover"><i class="fa fa-trash-o"></i></button>
                </div>
            </div>
        </div>
    </div>
</form>
<div class="row">
    <div class="col-xs-12">
        <div class="box">
            <div class="box-header with-border">
                <h3 class="box-title">Databases</h3>
            </div>
            <div class="box-body table-responsive no-padding">
                <table class="table table-hover">
                    <tr>
                        <th>Servidor</th>
                        <th>Nombre de base de datos</th>
                        <th>Usuario</th>
                        <th>Conexión de</th>
                        <th>Conexíon máximas</th>
                        <th></th>
                    </tr>
                    @foreach($databases as $database)
                        <tr>
                            <td class="middle"><a href="{{ route('admin.servers.view', $database->getRelation('server')->id) }}">{{ $database->getRelation('server')->name }}</a></td>
                            <td class="middle">{{ $database->database }}</td>
                            <td class="middle">{{ $database->username }}</td>
                            <td class="middle">{{ $database->remote }}</td>
                            @if($database->max_connections != null)
                                <td class="middle">{{ $database->max_connections }}</td>
                            @else
                                <td class="middle">Ilimitado</td>
                            @endif
                            <td class="text-center">
                                <a href="{{ route('admin.servers.view.database', $database->getRelation('server')->id) }}">
                                    <button class="btn btn-xs btn-primary">Administrar</button>
                                </a>
                            </td>
                        </tr>
                    @endforeach
                </table>
            </div>
            @if($databases->hasPages())
                <div class="box-footer with-border">
                    <div class="col-md-12 text-center">{!! $databases->render() !!}</div>
                </div>
            @endif
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
