@extends('layouts.admin')

@section('title')
    {{ $node->name }}: Configuraciones
@endsection

@section('content-header')
    <h1>{{ $node->name }}<small>Sus archivos de configuraciones de daemon.</small></h1>
    <ol class="breadcrumb">
        <li><a href="{{ route('admin.index') }}">Administrador</a></li>
        <li><a href="{{ route('admin.nodes') }}">Nodos</a></li>
        <li><a href="{{ route('admin.nodes.view', $node->id) }}">{{ $node->name }}</a></li>
        <li class="active">Configuración</li>
    </ol>
@endsection

@section('content')
<div class="row">
    <div class="col-xs-12">
        <div class="nav-tabs-custom nav-tabs-floating">
            <ul class="nav nav-tabs">
                <li><a href="{{ route('admin.nodes.view', $node->id) }}">Sobre</a></li>
                <li><a href="{{ route('admin.nodes.view.settings', $node->id) }}">Definiciones</a></li>
                <li class="active"><a href="{{ route('admin.nodes.view.configuration', $node->id) }}">Configuraciones</a></li>
                <li><a href="{{ route('admin.nodes.view.allocation', $node->id) }}">Alocaciones</a></li>
                <li><a href="{{ route('admin.nodes.view.servers', $node->id) }}">Servidores</a></li>
            </ul>
        </div>
    </div>
</div>
<div class="row">
    <div class="col-sm-8">
        <div class="box box-primary">
            <div class="box-header with-border">
                <h3 class="box-title">Archivos de Configuración</h3>
            </div>
            <div class="box-body">
                <pre class="no-margin">{{ $node->getYamlConfiguration() }}</pre>
            </div>
            <div class="box-footer">
                <p class="no-margin">Este archivo debe colocarse en el directorio raíz de su demonio (generalmente <code>/etc/pterodactyl</code>) en un archivo llamado <code>config.yml</code>.</p>
            </div>
        </div>
    </div>
    <div class="col-sm-4">
        <div class="box box-success">
            <div class="box-header with-border">
                <h3 class="box-title">Implementación automática</h3>
            </div>
            <div class="box-body">
                <p class="text-muted small">
                    Utilice el botón siguiente para generar un comando de implementación personalizado que se puede utilizar para configurar
                    Alas en el servidor de destino con un solo comando.
                </p>
            </div>
            <div class="box-footer">
                <button type="button" id="configTokenBtn" class="btn btn-sm btn-default" style="width:100%;">Generar token</button>
            </div>
        </div>
    </div>
</div>
@endsection

@section('footer-scripts')
    @parent
    <script>
    $('#configTokenBtn').on('click', function (event) {
        $.ajax({
            method: 'POST',
            url: '{{ route('admin.nodes.view.configuration.token', $node->id) }}',
            headers: { 'X-CSRF-TOKEN': '{{ csrf_token() }}' },
        }).done(function (data) {
            swal({
                type: 'success',
                title: 'Token creado.',
                text: '<p>Para configurar automáticamente el nodo, ejecute el siguiente comando:<br /><small><pre>cd /etc/pterodactyl && sudo wings configure --panel-url {{ config('app.url') }} --token ' + data.token + ' --node ' + data.node + '{{ config('app.debug') ? ' --allow-insecure' : '' }}</pre></small></p>',
                html: true
            })
        }).fail(function () {
            swal({
                title: 'Error',
                text: 'Algo paso que no se pudo crear el token.',
                type: 'error'
            });
        });
    });
    </script>
@endsection
