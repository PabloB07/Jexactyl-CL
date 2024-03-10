@extends('layouts.admin')
@include('partials/admin.jexactyl.nav', ['activeTab' => 'appearance'])

@section('title')
    Configuraciones de Tema
@endsection

@section('content-header')
    <h1>Apariencia de Jexactyl <small>Configurar el tema para Jexactyl.</small></h1>
    <ol class="breadcrumb">
        <li><a href="{{ route('admin.index') }}">Admin</a></li>
        <li class="active">Jexactyl</li>
    </ol>
@endsection

@section('content')
    @yield('jexactyl::nav')
    <div class="row">
        <div class="col-xs-12">
            <form action="{{ route('admin.jexactyl.appearance') }}" method="POST">
            <div class="box box-info">
                    <div class="box-header with-border">
                        <h3 class="box-title">Configuraciones generales <small>Definir configuraciones generales de apariencia.</small></h3>
                    </div>
                    <div class="box-body">
                        <div class="row">
                            <div class="form-group col-md-4">
                                <label class="control-label">Nombre del Panel</label>
                                <div>
                                    <input type="text" class="form-control" name="app:name" value="{{ old('app:name', config('app.name')) }}" />
                                    <p class="text-muted"><small>Este es el nombre que se utiliza en todo el panel y en los correos electrónicos enviados a los clientes.</small></p>
                                </div>
                            </div>
                            <div class="form-group col-md-4">
                                <label class="control-label">Logo del Panel</label>
                                <div>
                                    <input type="text" class="form-control" name="app:logo" value="{{ $logo }}" />
                                    <p class="text-muted"><small>El logotipo que se utiliza para la parte frontal del panel.</small></p>
                                </div>
                            </div>
                        </div>
                    </div>
                </div>
                <div class="box box-info">
                    <div class="box-header with-border">
                        <h3 class="box-title">Configuraciones del tema<small>La selección para el tema Jexactyl.</small></h3>
                    </div>
                    <div class="box-body">
                        <div class="row">
                            <div class="form-group col-md-4">
                                <label class="control-label">Tema del Panel Administrativo</label>
                                <div>
                                    <select name="theme:admin" class="form-control">
                                        <option @if ($admin == 'jexactyl') selected @endif value="jexactyl">Tema estandar</option>
                                        <option @if ($admin == 'dark') selected @endif value="dark">Tema Oscuro</option>
                                        <option @if ($admin == 'light') selected @endif value="light">Tema Claro</option>
                                        <option @if ($admin == 'blue') selected @endif value="blue">Tema Azul</option>
                                        <option @if ($admin == 'minecraft') selected @endif value="minecraft">Tema de Minecraft&#8482; </option>
                                    </select>
                                    <p class="text-muted"><small>Determina el tema para la interfaz de usuario de administración de Jexactyl.</small></p>
                                </div>
                            </div>
                            <div class="form-group col-md-4">
                                <label class="control-label">Tipo de Barra lateral</label>
                                <div>
                                    <select name="sidebar:tema" class="form-control">
                                        <option @if ($tema == 'sidebr') selected @endif value="sidebr">Barra Con Texto(Nuevo)</option>
                                        <option @if ($tema == 'sidejx') selected @endif value="sidejx">Barra Sin texto(Antigua)</option>
                                    </select>
                                    <p class="text-muted"><small>Determina la barra lateral que utilizará el panel.</small></p>
                                </div>
                            </div>
                            <div class="form-group col-md-4">
                                <label class="control-label">Fondo del área de cliente.</label>
                                <div>
                                    <input type="text" class="form-control" name="theme:user:background" value="{{ old('theme:user:background', config('theme.user.background')) }}" />
                                    <p class="text-muted"><small>Si ingresa una URL aquí, las páginas del cliente tendrán su imagen como fondo de página.</small></p>
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
