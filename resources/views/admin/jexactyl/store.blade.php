@extends('layouts.admin')
@include('partials/admin.jexactyl.nav', ['activeTab' => 'store'])

@section('title')
    Caja Jexactyl
@endsection

@section('content-header')
    <h1>Caja Jexactyl<small>Configurar a loja do Jexactyl.</small></h1>
    <ol class="breadcrumb">
        <li><a href="{{ route('admin.index') }}">Admin</a></li>
        <li class="active">Jexactyl</li>
    </ol>
@endsection

@section('content')
    @yield('jexactyl::nav')
    <div class="row">
        <div class="col-xs-12">
            <form action="{{ route('admin.jexactyl.store') }}" method="POST">
                <div class="box
                    @if($enabled == 'true')
                        box-success
                    @else
                        box-danger
                    @endif
                ">
                    <div class="box-header with-border">
                        <i class="fa fa-shopping-cart"></i> <h3 class="box-title">Caja de Jexactyl <small>Configurar si ciertas opciones de la tienda están habilitadas.</small></h3>
                    </div>
                    <div class="box-body">
                        <div class="row">
                            <div class="form-group col-md-4">
                                <label class="control-label">Habilitar Caja</label>
                                <div>
                                    <select name="store:enabled" class="form-control">
                                        <option @if ($enabled == 'false') selected @endif value="false">Deshabilitado</option>
                                        <option @if ($enabled == 'true') selected @endif value="true">Habilitado</option>
                                    </select>
                                    <p class="text-muted"><small>Determina si los usuarios podrán acceder a la interfaz gráfica de la tienda.</small></p>
                                </div>
                            </div>
                            <div class="form-group col-md-4">
                                <label class="control-label">Habilitar PayPal</label>
                                <div>
                                    <select name="store:paypal:enabled" class="form-control">
                                        <option @if ($paypal_enabled == 'false') selected @endif value="false">Deshabilitado</option>
                                        <option @if ($paypal_enabled == 'true') selected @endif value="true">Habilitado</option>
                                    </select>
                                    <p class="text-muted"><small>Determina se os usuários poderão comprar créditos com PayPal.</small></p>
                                </div>
                            </div>
                            <div class="form-group col-md-4">
                                <label class="control-label">Habilitar Stripe</label>
                                <div>
                                    <select name="store:stripe:enabled" class="form-control">
                                        <option @if ($stripe_enabled == 'false') selected @endif value="false">Desabilitado</option>
                                        <option @if ($stripe_enabled == 'true') selected @endif value="true">Habilitado</option>
                                    </select>
                                    <p class="text-muted"><small>Determina si los usuarios podrán comprar créditos con Stripe.</small></p>
                                </div>
                            </div>
                            <div class="form-group col-md-4">
                                <label class="control-label" for="store:currency">Nombre de moneda</label>
                                <select name="store:currency" id="store:currency" class="form-control">
                                    @foreach ($currencies as $currency)
                                        <option @if ($selected_currency === $currency['code']) selected @endif value="{{ $currency['code'] }}">{{ $currency['name'] }}</option>
                                    @endforeach
                                </select>
                                <p class="text-muted"><small>El nombre de la moneda que se utilizará en Jexactyl.</small></p>
                            </div>
                        </div>
                    </div>
                </div>
                <div class="box box-info">
                    <div class="box-header with-border">
                        <i class="fa fa-money"></i> <h3 class="box-title">Pagos con MercadoPago <small>Configuración del sistema de pagos de MercadoPago.</small></h3>
                    </div>
                    <div class="box-body">
                        <div class="row">
                            <div class="form-group col-md-4">
                                <label class="control-label">Habilitar MercadoPago</label>
                                <div>
                                    <select name="store:mpago:enabled" class="form-control">
                                        <option @if ($mpago_enabled == 'false') selected @endif value="false">Deshabilitado</option>
                                        <option @if ($mpago_enabled == 'true') selected @endif value="true">Habilitado</option>
                                    </select>
                                    <p class="text-muted"><small>Determina si los usuarios podrán adquirir créditos con MercadoPago.</small></p>
                                </div>
                            </div>
                            <div class="form-group col-md-4">
                                <label class="control-label">Habilitar Discord WebHook Mercado Pago IPN</label>
                                <div>
                                    <select name="store:mpago:discord:enabled" class="form-control">
                                        <option @if ($mpago_discord_enabled == 'false') selected @endif value="false">Deshabilitado</option>
                                        <option @if ($mpago_discord_enabled == 'true') selected @endif value="true">Habilitado</option>
                                    </select>
                                    <p class="text-muted"><small>Habilita el sistema de webhook de notificación de pago para discord, permite ver el estado de la compra y los pagos realizados en tiempo real.</small></p>
                                </div>
                            </div>
                            <div class="form-group col-md-4">
                                <label class="control-label">Discord WebHook Link</label>
                                <div>
                                    <input type="password" class="form-control" name="store:mpago:discord:webhook" value="{{ $mpago_discord_webhook }}" />
                                    <p class="text-muted"><small>Link de Webhook de o IPN va a mostrar modificaciones.</small></p>
                                </div>
                            </div>
                        </div>
                    </div>
                </div>
                <div class="box box-info">
                    <div class="box-header with-border">
                        <i class="fa fa-money"></i> <h3 class="box-title">Ganar con (AFK) <small>Configurar ajustes para la posibilidad de ganar créditos pasivos.</small></h3>
                    </div>
                    <div class="box-body">
                        <div class="row">
                            <div class="form-group col-md-4">
                                <label class="control-label">Habilitado</label>
                                <div>
                                    <select name="earn:enabled" class="form-control">
                                        <option @if ($earn_enabled == 'false') selected @endif value="false">Deshabilitado</option>
                                        <option @if ($earn_enabled == 'true') selected @endif value="true">Habilitado</option>
                                    </select>
                                    <p class="text-muted"><small>Determina si los usuarios pueden ganar créditos cuando están inactivos en la página del Panel de control..</small></p>
                                </div>
                            </div>
                            <div class="form-group col-md-4">
                                <label class="control-label">Número de créditos por minuto</label>
                                <div>
                                    <input type="text" class="form-control" name="earn:amount" value="{{ $earn_amount }}" />
                                    <p class="text-muted"><small>La cantidad de créditos que recibirá un usuario por minuto de AFK.</small></p>
                                </div>
                            </div>
                        </div>
                    </div>
                </div>
                <div class="box box-info">
                    <div class="box-header with-border">
                        <i class="fa fa-dollar"></i> <h3 class="box-title">Precios de recursos <small>Definir precios específicos para los recursos.</small></h3>
                    </div>
                    <div class="box-body">
                        <div class="row">
                            <div class="form-group col-md-4">
                                <label class="control-label">Costo por 50% de CPU</label>
                                <div>
                                    <input type="text" class="form-control" name="store:cost:cpu" value="{{ $cpu }}" />
                                    <p class="text-muted"><small>Utilizado para calcular el costo total para 50% de CPU.</small></p>
                                </div>
                            </div>
                            <div class="form-group col-md-4">
                                <label class="control-label">Costo por 1GB de RAM</label>
                                <div>
                                    <input type="text" class="form-control" name="store:cost:memory" value="{{ $memory }}" />
                                    <p class="text-muted"><small>Utilizado para calcular el costo total de 1GB de RAM.</small></p>
                                </div>
                            </div>
                            <div class="form-group col-md-4">
                                <label class="control-label">Costo por 1GB de Disco</label>
                                <div>
                                    <input type="text" class="form-control" name="store:cost:disk" value="{{ $disk }}" />
                                    <p class="text-muted"><small>Utilizado para calcular el costo total de 1GB de disco.</small></p>
                                </div>
                            </div>
                            <div class="form-group col-md-4">
                                <label class="control-label">Costo por 1 Slot de servidor</label>
                                <div>
                                    <input type="text" class="form-control" name="store:cost:slot" value="{{ $slot }}" />
                                    <p class="text-muted"><small>Utilizado para calcular el costo total de un slot de servidor.</small></p>
                                </div>
                            </div>
                            <div class="form-group col-md-4">
                                <label class="control-label">Costo por 1 Alocación de Red</label>
                                <div>
                                    <input type="text" class="form-control" name="store:cost:port" value="{{ $port }}" />
                                    <p class="text-muted"><small>Utilizado para calcular o costo total de un puerto.</small></p>
                                </div>
                            </div>
                            <div class="form-group col-md-4">
                                <label class="control-label">Costo por 1 Backup de Servidor</label>
                                <div>
                                    <input type="text" class="form-control" name="store:cost:backup" value="{{ $backup }}" />
                                    <p class="text-muted"><small>Utilizado para calcular o costo total de un backup.</small></p>
                                </div>
                            </div>
                            <div class="form-group col-md-4">
                                <label class="control-label">Costo por 1 Database do servidor</label>
                                <div>
                                    <input type="text" class="form-control" name="store:cost:database" value="{{ $database }}" />
                                    <p class="text-muted"><small>Utilizado para calcular el costo total de un Database.</small></p>
                                </div>
                            </div>
                        </div>
                    </div>
                </div>
                <div class="box box-info">
                    <div class="box-header with-border">
                        <i class="fa fa-area-chart"></i> <h3 class="box-title">Límites de recursos <small>Estabelecer limites para las cantidades de cada recurso con que un servidor puede ser desplegado.</small></h3>
                    </div>
                    <div class="box-body">
                        <div class="row">
                            <div class="form-group col-md-4">
                                <label class="control-label">Límite de CPU</label>
                                <div>
                                    <div class="input-group">
                                        <input type="text" class="form-control" name="store:limit:cpu" value="{{ $limit_cpu }}" />
                                        <span class="input-group-addon">%</span>
                                    </div>
                                    <p class="text-muted"><small>La Cantidad máxima de CPU con la que se puede implementar un servidor. </small></p>
                                </div>
                            </div>
                            <div class="form-group col-md-4">
                                <label class="control-label">Límite de RAM</label>
                                <div>
                                    <div class="input-group">
                                        <input type="text" class="form-control" name="store:limit:memory" value="{{ $limit_memory }}" />
                                        <span class="input-group-addon">MB</span>
                                    </div>
                                    <p class="text-muted"><small>La cantidad mínima de RAM con que un servidor puede ser desplegado. </small></p>
                                </div>
                            </div>
                            <div class="form-group col-md-4">
                                <label class="control-label">Límite de disco</label>
                                <div>
                                    <div class="input-group">
                                        <input type="text" class="form-control" name="store:limit:disk" value="{{ $limit_disk }}" />
                                        <span class="input-group-addon">MB</span>
                                    </div>
                                    <p class="text-muted"><small>La cantidad minima de Disco con que un servidor puede ser desplegado. </small></p>
                                </div>
                            </div>
                            <div class="form-group col-md-4">
                                <label class="control-label">Limite de alocaciones de Red</label>
                                <div>
                                    <div class="input-group">
                                        <input type="text" class="form-control" name="store:limit:port" value="{{ $limit_port }}" />
                                        <span class="input-group-addon">puertos</span>
                                    </div>
                                    <p class="text-muted"><small>La cantidad máxima de alocaciones con las cuales un servidor puede ser desplegado. </small></p>
                                </div>
                            </div>
                            <div class="form-group col-md-4">
                                <label class="control-label">Límite de backups</label>
                                <div>
                                    <div class="input-group">
                                        <input type="text" class="form-control" name="store:limit:backup" value="{{ $limit_backup }}" />
                                        <span class="input-group-addon">backups</span>
                                    </div>
                                    <p class="text-muted"><small>La cantidad de backups que un servidor puede ser desplegado. </small></p>
                                </div>
                            </div>
                            <div class="form-group col-md-4">
                                <label class="control-label">Límite de Database</label>
                                <div>
                                    <div class="input-group">
                                        <input type="text" class="form-control" name="store:limit:database" value="{{ $limit_database }}" />
                                        <span class="input-group-addon">Database</span>
                                    </div>
                                    <p class="text-muted"><small>El número máximo de bases de datos con las que se puede implementar un servidor. </small></p>
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
