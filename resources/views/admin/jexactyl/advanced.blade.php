@extends('layouts.admin')
@include('partials/admin.jexactyl.nav', ['activeTab' => 'advanced'])

@section('title')
    Avanzado
@endsection

@section('content-header')
    <h1>Avanzado<small>Configurar ajustes avanzados para el Panel de control.</small></h1>
    <ol class="breadcrumb">
        <li><a href="{{ route('admin.index') }}">Admin</a></li>
        <li class="active">Jexactyl</li>
    </ol>
@endsection

@section('content')
    @yield('jexactyl::nav')
        <form action="{{ route('admin.jexactyl.advanced') }}" method="POST">
            <div class="row">
                <div class="col-xs-12">
                    <div class="box">
                        <div class="box-header with-border">
                            <h3 class="box-title">Configuraciones ddel panel</h3>
                        </div>
                        <div class="box-body">
                            <div class="row">
                                <div class="form-group col-md-4">
                                    <label class="control-label">Exigir auntentiación de 2 pasos</label>
                                    <div>
                                        <div class="btn-group" data-toggle="buttons">
                                            @php
                                                $level = old('pterodactyl:auth:2fa_required', config('pterodactyl.auth.2fa_required'));
                                            @endphp
                                            <label class="btn btn-primary @if ($level == 0) active @endif">
                                                <input type="radio" name="pterodactyl:auth:2fa_required" autocomplete="off" value="0" @if ($level == 0) checked @endif> No es necesario
                                            </label>
                                            <label class="btn btn-primary @if ($level == 1) active @endif">
                                                <input type="radio" name="pterodactyl:auth:2fa_required" autocomplete="off" value="1" @if ($level == 1) checked @endif> Solamente el Admin
                                            </label>
                                            <label class="btn btn-primary @if ($level == 2) active @endif">
                                                <input type="radio" name="pterodactyl:auth:2fa_required" autocomplete="off" value="2" @if ($level == 2) checked @endif> Todos los usuarios
                                            </label>
                                        </div>
                                        <p class="text-muted"><small>Si está habilitado, cualquier cuenta que pertenezca al grupo seleccionado debe tener habilitada la autenticación de 2 factores para usar el Panel.</small></p>
                                    </div>
                                </div>
                            </div>
                        </div>
                    </div>
                </div>
            </div>
            <div class="row">
                <div class="col-xs-12">
                    <div class="box">
                        <div class="box-header with-border">
                            <h3 class="box-title">reCAPTCHA</h3>
                        </div>
                        <div class="box-body">
                            <div class="row">
                                <div class="form-group col-md-4">
                                    <label class="control-label">Status</label>
                                    <div>
                                        <select class="form-control" name="recaptcha:enabled">
                                            <option value="true">Habilitado</option>
                                            <option value="false" @if(old('recaptcha:enabled', config('recaptcha.enabled')) == '0') selected @endif>Deshabilitado</option>
                                        </select>
                                        <p class="text-muted small">Si están habilitados, los formularios de restablecimiento de contraseña y inicio de sesión realizarán una verificación silenciosa del captcha y mostrarán un captcha visible si es necesario.</p>
                                    </div>
                                </div>
                                <div class="form-group col-md-4">
                                    <label class="control-label">Llave del sitio</label>
                                    <div>
                                        <input type="text" required class="form-control" name="recaptcha:website_key" value="{{ old('recaptcha:website_key', config('recaptcha.website_key')) }}">
                                    </div>
                                </div>
                                <div class="form-group col-md-4">
                                    <label class="control-label">Llave secreta</label>
                                    <div>
                                        <input type="text" required class="form-control" name="recaptcha:secret_key" value="{{ old('recaptcha:secret_key', config('recaptcha.secret_key')) }}">
                                        <p class="text-muted small">Se utiliza para la comunicación entre su sitio web y Google. Asegúrate de mantenerlo en secreto.</p>
                                    </div>
                                </div>
                            </div>
                            @if($warning)
                                <div class="row">
                                    <div class="col-xs-12">
                                        <div class="alert alert-warning no-margin">
                                            Actualmente estás utilizando las claves reCAPTCHA que se enviaron con este Panel. Para mayor seguridad, se recomienda <a href="https://www.google.com/recaptcha/admin">generar nuevas claves reCAPTCHA invisibles</a> que estén específicamente vinculadas a su sitio web..
                                        </div>
                                    </div>
                                </div>
                            @endif
                        </div>
                    </div>
                    <div class="box">
                        <div class="box-header with-border">
                            <h3 class="box-title">Conexiones HTTP</h3>
                        </div>
                        <div class="box-body">
                            <div class="row">
                                <div class="form-group col-md-6">
                                    <label class="control-label">Tiempo límite de conexión</label>
                                    <div>
                                        <input type="number" required class="form-control" name="pterodactyl:guzzle:connect_timeout" value="{{ old('pterodactyl:guzzle:connect_timeout', config('pterodactyl.guzzle.connect_timeout')) }}">
                                        <p class="text-muted small">El tiempo en segundos que se debe esperar a que se abra una conexión antes de generar un error.</p>
                                    </div>
                                </div>
                                <div class="form-group col-md-6">
                                    <label class="control-label">Solicitación de tiempo límite</label>
                                    <div>
                                        <input type="number" required class="form-control" name="pterodactyl:guzzle:timeout" value="{{ old('pterodactyl:guzzle:timeout', config('pterodactyl.guzzle.timeout')) }}">
                                        <p class="text-muted small">La cantidad de tiempo en segundos que se debe esperar a que se complete una solicitud antes de generar un error.</p>
                                    </div>
                                </div>
                            </div>
                        </div>
                    </div>
                    <div class="box">
                        <div class="box-header with-border">
                            <h3 class="box-title">Creación automática de asignaciones.</h3>
                        </div>
                        <div class="box-body">
                            <div class="row">
                                <div class="form-group col-md-4">
                                    <label class="control-label">Status</label>
                                    <div>
                                        <select class="form-control" name="pterodactyl:client_features:allocations:enabled">
                                            <option value="false">Deshabilitado</option>
                                            <option value="true" @if(old('pterodactyl:client_features:allocations:enabled', config('pterodactyl.client_features.allocations.enabled'))) selected @endif>Habilitado</option>
                                        </select>
                                        <p class="text-muted small">Si está habilitado, los usuarios tendrán la opción de crear automáticamente nuevas asignaciones para su servidor a través del front-end.</p>
                                    </div>
                                </div>
                                <div class="form-group col-md-4">
                                    <label class="control-label">Portal Inicial</label>
                                    <div>
                                        <input type="number" class="form-control" name="pterodactyl:client_features:allocations:range_start" value="{{ old('pterodactyl:client_features:allocations:range_start', config('pterodactyl.client_features.allocations.range_start')) }}">
                                        <p class="text-muted small">El puerto de inicio en el rango que se puede asignar automáticamente.</p>
                                    </div>
                                </div>
                                <div class="form-group col-md-4">
                                    <label class="control-label">Portal Final</label>
                                    <div>
                                        <input type="number" class="form-control" name="pterodactyl:client_features:allocations:range_end" value="{{ old('pterodactyl:client_features:allocations:range_end', config('pterodactyl.client_features.allocations.range_end')) }}">
                                        <p class="text-muted small">El puerto final en el rango que se puede asignar automáticamente.</p>
                                    </div>
                                </div>
                            </div>
                        </div>
                    </div>
                    {{ csrf_field() }}
                    <button type="submit" name="_method" value="PATCH" class="btn btn-default pull-right">Guardar configuraciones</button>
                </div>
            </div>
        </form>
@endsection
