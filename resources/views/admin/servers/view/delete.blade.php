@extends('layouts.admin')

@section('title')
Servidor — {{ $server->name }}: Remover
@endsection

@section('content-header')
    <h1>{{ $server->name }}<small>Excluir este servidor del panel.</small></h1>
    <ol class="breadcrumb">
        <li><a href="{{ route('admin.index') }}">Admin</a></li>
        <li><a href="{{ route('admin.servers') }}">Servidores</a></li>
        <li><a href="{{ route('admin.servers.view', $server->id) }}">{{ $server->name }}</a></li>
        <li class="active">Eliminar</li>
    </ol>
@endsection

@section('content')
@include('admin.servers.partials.navigation')
<div class="row">
    <form id="deleteform" action="{{ route('admin.servers.view.delete', $server->id) }}" method="POST">
        <div class="col-md-6">
            <div class="box box-warning">
                <div class="box-header with-border">
                    <h3 class="box-title">Excluir este servidor con seguridad</h3>
                </div>
                <div class="box-body">
                    <p>Esto intentará eliminar el servidor de nodo asociado y eliminar los datos vinculados a él..</p>
                    <div class="checkbox checkbox-primary no-margin-bottom">
                        <input id="pReturnResourcesSafe" name="return_resources" type="checkbox" value="1" />
                        <label for="pReturnResourcesSafe">¿Devolver recursos al usuario al eliminar el servidor?</label>
                    </div>
                </div>
                <div class="box-footer">
                    {!! csrf_field() !!}
                    <button id="deletebtn" class="btn btn-warning">Apagar este servidor de forma segura</button>
                </div>
            </div>
        </div>
    </form>
    <form id="forcedeleteform" action="{{ route('admin.servers.view.delete', $server->id) }}" method="POST">
        <div class="col-md-6">
            <div class="box box-danger">
                <div class="box-header with-border">
                    <h3 class="box-title">Excluir a fuerza este servidorr</h3>
                </div>
                <div class="box-body">
                    <p>Esto eliminará todos los datos del servidor del Panel, independientemente de si Wings puede eliminar el servidor del sistema..</p>
                    <div class="checkbox checkbox-primary no-margin-bottom">
                        <input id="pReturnResources" name="return_resources" type="checkbox" value="1" />
                        <label for="pReturnResources">Devolver recursos al usuario al eliminar el servidor?</label>
                    </div>
                </div>
                <div class="box-footer">
                    {!! csrf_field() !!}
                    <input type="hidden" name="force_delete" value="1" />
                    <button id="forcedeletebtn"><button class="btn btn-danger">Excluir a fuerza este servidor</button>
                </div>
            </div>
        </div>
    </form>
</div>
@endsection

@section('footer-scripts')
    @parent
    <script>
    $('#deletebtn').click(function (event) {
        event.preventDefault();
        swal({
            title: 'Excluir servidor',
            text: 'Todos los datos se eliminarán del Panel y de su Nodo..',
            showCancelButton: true,
            confirmButtonText: 'Delete',
            confirmButtonColor: 'orange',
            closeOnConfirm: false
        }, function () {
            $('#deleteform').submit()
        });
    });

    $('#forcedeletebtn').click(function (event) {
        event.preventDefault();
        swal({
            title: 'Excluir servidor',
            text: 'Todos los datos se eliminarán del Panel y de su Nodo..',
            showCancelButton: true,
            confirmButtonText: 'Delete',
            confirmButtonColor: 'red',
            closeOnConfirm: false
        }, function () {
            $('#forcedeleteform').submit()
        });
    });
    </script>
@endsection
