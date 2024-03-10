@extends('layouts.admin')

@section('title')
    {{ $node->name }}: Servidores
@endsection

@section('content-header')
    <h1>{{ $node->name }}<small>Todos los servidores actualmente atribuídos a este nodo.</small></h1>
    <ol class="breadcrumb">
        <li><a href="{{ route('admin.index') }}">Administrador</a></li>
        <li><a href="{{ route('admin.nodes') }}">Nodos</a></li>
        <li><a href="{{ route('admin.nodes.view', $node->id) }}">{{ $node->name }}</a></li>
        <li class="active">Servidores</li>
    </ol>
@endsection

@section('content')
<div class="row">
    <div class="col-xs-12">
        <div class="nav-tabs-custom nav-tabs-floating">
            <ul class="nav nav-tabs">
                <li><a href="{{ route('admin.nodes.view', $node->id) }}">Sobre</a></li>
                <li><a href="{{ route('admin.nodes.view.settings', $node->id) }}">Definiciones</a></li>
                <li><a href="{{ route('admin.nodes.view.configuration', $node->id) }}">Configuraciones</a></li>
                <li><a href="{{ route('admin.nodes.view.allocation', $node->id) }}">Alocaciones</a></li>
                <li class="active"><a href="{{ route('admin.nodes.view.servers', $node->id) }}">Servidores</a></li>
            </ul>
        </div>
    </div>
</div>
<div class="row">
    <div class="col-sm-12">
        <div class="box box-primary">
            <div class="box-header with-border">
                <h3 class="box-title">Administrador de procesos</h3>
            </div>
            <div class="box-body table-responsive no-padding">
                <table class="table table-hover">
                    <tr>
                        <th>ID</th>
                        <th>Nombre de Servidor</th>
                        <th>Dueño</th>
                        <th>Servicio</th>
                    </tr>
                    @foreach($servers as $server)
                        <tr data-server="{{ $server->uuid }}">
                            <td><code>{{ $server->uuidShort }}</code></td>
                            <td><a href="{{ route('admin.servers.view', $server->id) }}">{{ $server->name }}</a></td>
                            <td><a href="{{ route('admin.users.view', $server->owner_id) }}">{{ $server->user->username }}</a></td>
                            <td>{{ $server->nest->name }} ({{ $server->egg->name }})</td>
                        </tr>
                    @endforeach
                </table>
                @if($servers->hasPages())
                    <div class="box-footer with-border">
                        <div class="col-md-12 text-center">{!! $servers->render() !!}</div>
                    </div>
                @endif
            </div>
        </div>
    </div>
</div>
@endsection
