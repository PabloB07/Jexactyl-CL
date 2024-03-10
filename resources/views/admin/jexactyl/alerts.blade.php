@extends('layouts.admin')
@include('partials/admin.jexactyl.nav', ['activeTab' => 'alerts'])

@section('title')
    Configuraciones de alerta
@endsection

@section('content-header')
    <h1>Alertas Jexactyl<small>Enviar alertas a los clientes a través de la interfaz de usuario.</small></h1>
    <ol class="breadcrumb">
        <li><a href="{{ route('admin.index') }}">Admin</a></li>
        <li class="active">Jexactyl</li>
    </ol>
@endsection

@section('content')
    @yield('jexactyl::nav')
    <div class="row">
        <div class="col-xs-12">
            <form action="{{ route('admin.jexactyl.alerts') }}" method="POST">
                <div class="box box-info">
                    <div class="box-header with-border">
                        <h3 class="box-title">Configuraciones de alerta <small>Configurar ajustes para la alerta actual.</small></h3>
                    </div>
                    <div class="box-body">
                        <div class="row">
                            <div class="form-group col-md-4">
                                <label class="control-label">Tipo de Alerta</label>
                                <div>
                                <select name="alert:type" class="form-control">
                                        <option @if ($type == 'success') selected @endif value="success">Éxito</option>
                                        <option @if ($type == 'info') selected @endif value="info">Info</option>
                                        <option @if ($type == 'warning') selected @endif value="warning">Aviso</option>
                                        <option @if ($type == 'danger') selected @endif value="danger">Peligro</option>
                                    </select>
                                    <p class="text-muted"><small>Este es el tipo de alerta que se envía al frontend.</small></p>
                                </div>
                            </div>
                            <div class="form-group col-md-4">
                                <label class="control-label">Mensaje de Alerta</label>
                                <div>
                                    <input type="text" class="form-control" name="alert:message" value="{{ $message }}" />
                                    <p class="text-muted"><small>Este es el texto que contendrá la alerta.</small></p>
                                </div>
                            </div>
                        </div>
                    </div>
                </div>
                {!! csrf_field() !!}
                <button type="submit" name="_method" value="PATCH" class="btn btn-default pull-right">Actualizar Alerta</button>
            </form>
            <form action="{{ route('admin.jexactyl.alerts.remove') }}" method="POST">
                {!! csrf_field() !!}
                <button type="submit" name="_method" value="POST" class="btn btn-danger pull-right" style="margin-right: 8px;">Remover Alerta</button>
            </form>
        </div>
    </div>
@endsection
