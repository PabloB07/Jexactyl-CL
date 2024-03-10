@extends('layouts.admin')
@include('partials/admin.jexactyl.nav', ['activeTab' => 'server'])

@section('title')
   Servidores Jexactyl
@endsection

@section('content-header')
    <h1>Configuraciones de servidor<small>Configurar los ajustes del servidor Jexactyl.</small></h1>
    <ol class="breadcrumb">
        <li><a href="{{ route('admin.index') }}">Admin</a></li>
        <li class="active">Jexactyl</li>
    </ol>
@endsection

@section('content')
    @yield('jexactyl::nav')
    <div class="row">
        <div class="col-xs-12">
            <form action="{{ route('admin.jexactyl.server') }}" method="POST">
                <div class="box
                    @if($enabled == 'true')
                        box-success
                    @else
                        box-danger
                    @endif
                ">
                    <div class="box-header with-border">
                        <i class="fa fa-clock-o"></i> <h3 class="box-title">Renovaciones de servidores <small>Configurar los ajustes de renovación del servidor.</small></h3>
                    </div>
                    <div class="box-body">
                        <div class="row">
                            <div class="form-group col-md-4">
                                <label class="control-label">Sistema de renovación</label>
                                <div>
                                    <select name="enabled" class="form-control">
                                        <option @if ($enabled == 'false') selected @endif value="false">Deshabilitado</option>
                                        <option @if ($enabled == 'true') selected @endif value="true">Habilitado</option>
                                    </select>
                                    <p class="text-muted"><small>Determina se os usuários precisam renovar seus servidores.</small></p>
                                </div>
                            </div>
                            <div class="form-group col-md-4">
                                <label class="control-label">Plazo de renovación estándar</label>
                                <div>
                                    <div class="input-group">
                                        <input type="text" id="default" name="default" class="form-control" value="{{ $default }}" />
                                        <span class="input-group-addon">días</span>
                                    </div>
                                    <p class="text-muted"><small>Determina la cantidad de días que tienen los servidores antes de que se requiera la renovación..</small></p>
                                </div>
                            </div>
                            <div class="form-group col-md-4">
                                <label class="control-label">Costo de renovación</label>
                                <div>
                                    <div class="input-group">
                                        <input type="text" id="cost" name="cost" class="form-control" value="{{ $cost }}" />
                                        <span class="input-group-addon">créditos</span>
                                    </div>
                                    <p class="text-muted"><small>Determina la cantidad de créditos que cuesta una renovación.</small></p>
                                </div>
                            </div>
                        </div>
                    </div>
                </div>
                <div class="box
                    @if($editing == 'true')
                        box-success
                    @else
                        box-danger
                    @endif
                ">
                    <div class="box-header with-border">
                        <i class="fa fa-server"></i> <h3 class="box-title">Configuraciones de servidores <small>Definir las configuraciones de los servidores.</small></h3>
                    </div>
                    <div class="box-body">
                        <div class="row">
                            <div class="form-group col-md-4">
                                <label class="control-label">Editar recursos de los servidores</label>
                                <div>
                                    <select name="editing" class="form-control">
                                        <option @if ($editing == 'false') selected @endif value="false">Deshabilitado</option>
                                        <option @if ($editing == 'true') selected @endif value="true">Habilitado</option>
                                    </select>
                                    <p class="text-muted"><small>Determina si los usuarios pueden editar la cantidad de recursos asignados a su servidor.</small></p>
                                </div>
                            </div>
                            <div class="form-group col-md-4">
                                <label class="control-label">Permitir la eliminación del servidor</label>
                                <div>
                                    <select name="deletion" class="form-control">
                                        <option @if ($deletion == 'false') selected @endif value="false">Deshabilitado</option>
                                        <option @if ($deletion == 'true') selected @endif value="true">Habilitado</option>
                                    </select>
                                    <p class="text-muted"><small>Determina si los usuarios pueden eliminar sus propios servidores. (Predeterminado: habilitado)</small></p>
                                </div>
                            </div>
                        </div>
                    </div>
                </div>
                {!! csrf_field() !!}
                <button type="submit" name="_method" value="PATCH" class="btn btn-default pull-right">Guardar cambios</button>
            </form>
        </div>
    </div>
@endsection
