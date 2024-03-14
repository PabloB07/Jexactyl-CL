@extends('layouts.admin')

@section('title')
Servidor  — {{ $server->name }}:Administrar
@endsection

@section('content-header')
    <h1>{{ $server->name }}<small>Acciones adicionales para este servidor.</small></h1>
    <ol class="breadcrumb">
        <li><a href="{{ route('admin.index') }}">Admin</a></li>
        <li><a href="{{ route('admin.servers') }}">Servidores</a></li>
        <li><a href="{{ route('admin.servers.view', $server->id) }}">{{ $server->name }}</a></li>
        <li class="active">Gerenciar</li>
    </ol>
@endsection

@section('content')
    @include('admin.servers.partials.navigation')
    <div class="row">
        <div class="col-sm-4">
            <div class="box box-danger">
                <div class="box-header with-border">
                    <h3 class="box-title">Reinstalar servidor</h3>
                </div>
                <div class="box-body">
                    <p>Esto instala el servidor con los scripts de servicio designados..
                         <strong>Peligro!</strong>
                        Esto podría sobrescribir los datos del servidor.
                    </p>
                </div>
                <div class="box-footer">
                    @if($server->isInstalled())
                        <form action="{{ route('admin.servers.view.manage.reinstall', $server->id) }}" method="POST">
                            {!! csrf_field() !!}
                            <button type="submit" class="btn btn-danger">Reinstalar servidor</button>
                        </form>
                    @else
                        <button class="btn btn-danger disabled">
                            El servidor debe estar instalado correctamente para instalar</button>
                    @endif
                </div>
            </div>
        </div>
        <div class="col-sm-4">
            <div class="box box-primary">
                <div class="box-header with-border">
                    <h3 class="box-title">Status de instalacióno</h3>
                </div>
                <div class="box-body">
                    <p>
                        Si necesita cambiar el estado de la instalación de desinstalada a instalada, o viceversa, puede hacerlo con el botón a continuación.</p>
                </div>
                <div class="box-footer">
                    <form action="{{ route('admin.servers.view.manage.toggle', $server->id) }}" method="POST">
                        {!! csrf_field() !!}
                        <button type="submit" class="btn btn-primary">Alternar status de instalación</button>
                    </form>
                </div>
            </div>
        </div>

        @if(! $server->isSuspended())
            <div class="col-sm-4">
                <div class="box box-warning">
                    <div class="box-header with-border">
                        <h3 class="box-title">Suspender Servidor</h3>
                    </div>
                    <div class="box-body">
                        <p>
                            Esto suspenderá el servidor, detendrá cualquier proceso en ejecución e inmediatamente impedirá que el usuario pueda acceder a sus archivos o administrar el servidor a través del panel o API.</p>
                    </div>
                    <div class="box-footer">
                        <form action="{{ route('admin.servers.view.manage.suspension', $server->id) }}" method="POST">
                            {!! csrf_field() !!}
                            <input type="hidden" name="action" value="suspend" />
                            <button type="submit" class="btn btn-warning @if(! is_null($server->transfer)) disabled @endif">Servidor suspendido</button>
                        </form>
                    </div>
                </div>
            </div>
        @else
            <div class="col-sm-4">
                <div class="box box-success">
                    <div class="box-header with-border">
                        <h3 class="box-title">Desbloquear servidor</h3>
                    </div>
                    <div class="box-body">
                        <p>Esto suspenderá el servidor y restaurará el acceso manual del usuario..</p>
                    </div>
                    <div class="box-footer">
                        <form action="{{ route('admin.servers.view.manage.suspension', $server->id) }}" method="POST">
                            {!! csrf_field() !!}
                            <input type="hidden" name="action" value="unsuspend" />
                            <button type="submit" class="btn btn-success">Desbloquear servidor</button>
                        </form>
                    </div>
                </div>
            </div>
        @endif

        @if(is_null($server->transfer))
            <div class="col-sm-4">
                <div class="box box-success">
                    <div class="box-header with-border">
                        <h3 class="box-title">Transferir Servidor</h3>
                    </div>
                    <div class="box-body">
                        <p>
                            Transfiera este servidor a otro Nodo conectado a este panel.
                            <strong>¡Atención!</strong> Esta función no se ha probado completamente y puede tener errores.
                        </p>
                    </div>

                    <div class="box-footer">
                        @if($canTransfer)
                            <button class="btn btn-success" data-toggle="modal" data-target="#transferServerModal">Transfer Server</button>
                        @else
                            <button class="btn btn-success disabled">Transferir Servidor</button>
                            <p style="padding-top: 1rem;">
                                La transferencia de un servidor requiere que se configure más de un nodo en su panel de control.</p>
                        @endif
                    </div>
                </div>
            </div>
        @else
            <div class="col-sm-4">
                <div class="box box-success">
                    <div class="box-header with-border">
                        <h3 class="box-title">Transferir Servidor</h3>
                    </div>
                    <div class="box-body">
                        <p>
                            Este servidor se está transfiriendo actualmente a otro Nodo.
                            La transferencia se inició en <strong>{{ $server->transfer->created_at }}</strong>
                        </p>
                    </div>

                    <div class="box-footer">
                        <button class="btn btn-success disabled">Transferir Servidor</button>
                    </div>
                </div>
            </div>
        @endif
    </div>

    <div class="modal fade" id="transferServerModal" tabindex="-1" role="dialog">
        <div class="modal-dialog" role="document">
            <div class="modal-content">
                <form action="{{ route('admin.servers.view.manage.transfer', $server->id) }}" method="POST">
                    <div class="modal-header">
                        <button type="button" class="close" data-dismiss="modal" aria-label="Close"><span aria-hidden="true">&times;</span></button>
                        <h4 class="modal-title">Transferir Servidor</h4>
                    </div>

                    <div class="modal-body">
                        <div class="row">
                            <div class="form-group col-md-12">
                                <label for="pNodeId">Node</label>
                                <select name="node_id" id="pNodeId" class="form-control">
                                    @foreach($locations as $location)
                                        <optgroup label="{{ $location->long }} ({{ $location->short }})">
                                            @foreach($location->nodes as $node)

                                                @if($node->id != $server->node_id)
                                                    <option value="{{ $node->id }}"
                                                            @if($location->id === old('location_id')) selected @endif
                                                    >{{ $node->name }}</option>
                                                @endif

                                            @endforeach
                                        </optgroup>
                                    @endforeach
                                </select>
                                <p class="small text-muted no-margin">El nodo al que se transferirá este servidor.</p>
                            </div>

                            <div class="form-group col-md-12">
                                <label for="pAllocation">Asignación predeterminada</label>
                                <select name="allocation_id" id="pAllocation" class="form-control"></select>
                                <p class="small text-muted no-margin">
                                    La asignación principal que se asignará a este servidor..</p>
                            </div>

                            <div class="form-group col-md-12">
                                <label for="pAllocationAdditional">
                                    Asignaciones adicionales</label>
                                <select name="allocation_additional[]" id="pAllocationAdditional" class="form-control" multiple></select>
                                <p class="small text-muted no-margin">Asignaciones adicionales que se asignarán a este servidor al momento de su creación.</p>
                            </div>
                        </div>
                    </div>

                    <div class="modal-footer">
                        {!! csrf_field() !!}
                        <button type="button" class="btn btn-default btn-sm pull-left" data-dismiss="modal">Cancelar</button>
                        <button type="submit" class="btn btn-success btn-sm">Confirmar</button>
                    </div>
                </form>
            </div>
        </div>
    </div>
@endsection

@section('footer-scripts')
    @parent
    {!! Theme::js('vendor/lodash/lodash.js') !!}

    @if($canTransfer)
        {!! Theme::js('js/admin/server/transfer.js') !!}
    @endif
@endsection
