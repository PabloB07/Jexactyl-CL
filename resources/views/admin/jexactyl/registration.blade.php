@extends('layouts.admin')
@include('partials/admin.jexactyl.nav', ['activeTab' => 'registration'])

@section('title')
    Configuración de Jexactyl
@endsection

@section('content-header')
    <h1>Registro de usuarios<small>Definir las configuraciones para el registro de usuarios de Jexactyl.</small></h1>
    <ol class="breadcrumb">
        <li><a href="{{ route('admin.index') }}">Admin</a></li>
        <li class="active">Jexactyl</li>
    </ol>
@endsection

@section('content')
@yield('jexactyl::nav')
    <div class="row">
        <div class="col-xs-12">
            <form action="{{ route('admin.jexactyl.registration') }}" method="POST">
                <div class="box
                @if($enabled == 'true') box-success @else box-danger @endif">
                    <div class="box-header with-border">
                        <i class="fa fa-at"></i> <h3 class="box-title">Registro via E-mail <small>Configuración para registros e inicios de sesión a través de correo electrónico.</small></h3>
                    </div>
                    <div class="box-body">
                        <div class="row">
                            <div class="form-group col-md-4">
                                <label class="control-label">Habilitado</label>
                                <div>
                                    <select name="registration:enabled" class="form-control">
                                        <option @if ($enabled == 'false') selected @endif value="false">Deshabilitado</option>
                                        <option @if ($enabled == 'true') selected @endif value="true">Habilitado</option>
                                    </select>
                                    <p class="text-muted"><small>Determina si las personas pueden registrar cuentas mediante el correo electrónico.</small></p>
                                </div>
                            </div>
                        </div>
                    </div>
                </div>
                <div class="box @if($discord_enabled == 'true') box-success @else box-danger @endif">
                    <div class="box-header with-border">
                        <i class="fa fa-comments-o"></i> <h3 class="box-title">Registro via Discord <small>Configuraciones para registro e inicios de sesión en Discord.</small></h3>
                    </div>
                    <div class="box-body">
                        <div class="row">
                            <div class="form-group col-md-4">
                                <label class="control-label">Habilitado</label>
                                <div>
                                    <select name="discord:enabled" class="form-control">
                                        <option @if ($discord_enabled == 'false') selected @endif value="false">Deshabilitado</option>
                                        <option @if ($discord_enabled == 'true') selected @endif value="true">Habilitado</option>
                                    </select>
                                    @if($discord_enabled != 'true')
                                        <p class="text-danger">Las personas no podrán registrarse ni iniciar sesión en Discord si esto está deshabilitado!</p>
                                    @else
                                        <p class="text-muted"><small>Determina si las personas pueden registrarse usando Discord.</small></p>
                                    @endif
                                </div>
                            </div>
                            <div class="form-group col-md-4">
                                <label class="control-label">Discord Client ID</label>
                                <div>
                                    <input type="text" class="form-control" name="discord:id" value="{{ $discord_id }}" />
                                    <p class="text-muted"><small>El ID de cliente de su aplicación OAuth. Normalmente entre 17 y 20 dígitos.</small></p>
                                </div>
                            </div>
                            <div class="form-group col-md-4">
                                <label class="control-label">Discord Client Secret</label>
                                <div>
                                    <input type="password" class="form-control" name="discord:secret" value="{{ $discord_secret }}" />
                                    <p class="text-muted"><small>El secreto del cliente para su aplicación OAuth. Trata esto como una contraseña.</small></p>
                                </div>
                            </div>
                        </div>
                    </div>
                </div>
                <div class="box box-info">
                    <div class="box-header with-border">
                        <h3 class="box-title"><i class="fa fa-envelope"></i> Verificación de E-mail <small>Activa esto para verificarse por E-mail.</small></h3>
                    </div>
                    <div class="box-body row">
                        <div class="form-group col-md-4">
                            <label for="verification" class="control-label">Status</label>
                            <select name="registration:verification" id="verification" class="form-control">
                                <option value="{{ 1 }}" @if ($verification) selected @endif>Habilitado</option>
                                <option value="{{ 0 }}" @if (!$verification) selected @endif>Deshabilitado</option>
                            </select>
                        </div>
                    </div>
                </div>
                <div class="box box-info">
                    <div class="box-header with-border">
                        <i class="fa fa-microchip"></i> <h3 class="box-title">Recursos estándar <small>Los recursos predeterminados asignados a un usuario al registrarse.</small></h3>
                    </div>
                    <div class="box-body">
                        <div class="row">
                            <div class="form-group col-md-4">
                                <label class="control-label">Cantidad de CPU</label>
                                <div>
                                    <input type="text" class="form-control" name="registration:cpu" value="{{ $cpu }}" />
                                    <p class="text-muted"><small>La cantidad de CPU que se debe entregar a un usuario al momento de registrarse en %..</small></p>
                                </div>
                            </div>
                            <div class="form-group col-md-4">
                                <label class="control-label">Cantidad de RAM</label>
                                <div>
                                    <input type="text" class="form-control" name="registration:memory" value="{{ $memory }}" />
                                    <p class="text-muted"><small>La cantidad de RAM que se le debe dar a un usuario al registrarse en MB.</small></p>
                                </div>
                            </div>
                            <div class="form-group col-md-4">
                                <label class="control-label">Cantidad de almacenamiento</label>
                                <div>
                                    <input type="text" class="form-control" name="registration:disk" value="{{ $disk }}" />
                                    <p class="text-muted"><small>La cantidad de almacenamiento que se debe entregar a un usuario al registrarse en MB.</small></p>
                                </div>
                            </div>
                            <div class="form-group col-md-4">
                                <label class="control-label">Cantidad de slots</label>
                                <div>
                                    <input type="text" class="form-control" name="registration:slot" value="{{ $slot }}" />
                                    <p class="text-muted"><small>La cantidad de espacios de servidor que se deben otorgar a un usuario al registrarse.</small></p>
                                </div>
                            </div>
                            <div class="form-group col-md-4">
                                <label class="control-label">Cantidad de alocaciones</label>
                                <div>
                                    <input type="text" class="form-control" name="registration:port" value="{{ $port }}" />
                                    <p class="text-muted"><small>La cantidad de puertos de servidor que se deben entregar a un usuario en el momento del registro.</small></p>
                                </div>
                            </div>
                            <div class="form-group col-md-4">
                                <label class="control-label">Cantidad de Backups</label>
                                <div>
                                    <input type="text" class="form-control" name="registration:backup" value="{{ $backup }}" />
                                    <p class="text-muted"><small>La cantidad de copias de seguridad del servidor que se deben entregar a un usuario en el momento del registro.</small></p>
                                </div>
                            </div>
                            <div class="form-group col-md-4">
                                <label class="control-label">Cantidad de Databases</label>
                                <div>
                                    <input type="text" class="form-control" name="registration:database" value="{{ $database }}" />
                                    <p class="text-muted"><small>La cantidad de bases de datos del servidor que se deben entregar a un usuario en el momento del registro..</small></p>
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
