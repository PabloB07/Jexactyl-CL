@extends('layouts.admin')
@include('partials/admin.jexactyl.nav', ['activeTab' => 'index'])

@section('title')
    Configuraciones de Jexactyl
@endsection

@section('content-header')
    <h1>Configuraciones de Jexactyl<small>Configure los ajustes específicos de Jexactyl para el Panel de control.</small></h1>
    <ol class="breadcrumb">
        <li><a href="{{ route('admin.index') }}">Admin</a></li>
        <li class="active">Jexactyl</li>
    </ol>
@endsection

@section('content')
    @yield('jexactyl::nav')
    <div class="row">
        <div class="col-xs-12">
            <div class="box
                @if($version->isLatestPanel())
                    box-success
                @else
                    box-danger
                @endif
            ">
                <div class="box-header with-border">
                    <i class="fa fa-code-fork"></i> <h3 class="box-title">Versión de Software <small>Verifique que Jexactyl esté actualizado</small></h3>
                </div>
                <div class="box-body">
                    @if ($version->isLatestPanel())
                    Estás usando Jexactyl <code>{{ config('app.version') }}</code>.
                    @else
                    Jexactyl no está actualizado. <code>{{ config('app.version') }} (current) -> <a href="https://github.com/Next-Panel/Jexactyl-BR/releases/v{{ $version->getPanel() }}" target="_blank">{{ $version->getPanel() }}</a> (latest)</code>
                    @endif
                </div>
            </div>
        </div>
    </div>
    <div class="row">
        <div class="col-xs-12">
            <div class="col-xs-12 col-md-3">
                <div class="info-box">
                    <span class="info-box-icon"><i class="fa fa-server"></i></span>
                    <div class="info-box-content" style="padding: 23px 10px 0;">
                        <span class="info-box-text">Total de servidores</span>
                        <span class="info-box-number">{{ count($servers) }}</span>
                    </div>
                </div>
            </div>
            <div class="col-xs-12 col-md-3">
                <div class="info-box">
                    <span class="info-box-icon"><i class="fa fa-wifi"></i></span>
                    <div class="info-box-content" style="padding: 23px 10px 0;">
                        <span class="info-box-text">Alocaciones totales</span>
                        <span class="info-box-number">{{ $allocations }}</span>
                    </div>
                </div>
            </div>
            <div class="col-xs-12 col-md-3">
                <div class="info-box">
                    <span class="info-box-icon"><i class="fa fa-pie-chart"></i></span>
                    <div class="info-box-content" style="padding: 23px 10px 0;">
                        <span class="info-box-text">Uso total da RAM</span>
                        <span class="info-box-number">{{ $used['memory'] }} MB de {{ $available['memory'] }} MB</span>
                    </div>
                </div>
            </div>
            <div class="col-xs-12 col-md-3">
                <div class="info-box">
                    <span class="info-box-icon"><i class="fa fa-hdd-o"></i></span>
                    <div class="info-box-content" style="padding: 23px 10px 0;">
                        <span class="info-box-text">Uso total en disco</span>
                        <span class="info-box-number">{{ $used['disk'] }} MB de {{ $available['disk'] }} MB </span>
                    </div>
                </div>
            </div>
        </div>
    </div>
    <div class="row">
        <div class="col-xs-12">
            <div class="box box-primary">
                <div class="box-header with-border">
                    <i class="fa fa-bar-chart"></i> <h3 class="box-title">Utilización de recursos <small>Un resumen de la cantidad total de recursos utilizados.</small></h3>
                </div>
                <div class="box-body">
                    <div class="col-xs-12 col-md-4">
                        <canvas id="servers_chart" width="100%" height="50">
                            <p class="text-muted">No hay datos disponibles para este gráfico.</p>
                        </canvas>
                    </div>
                    <div class="col-xs-12 col-md-4">
                        <canvas id="ram_chart" width="100%" height="50">
                            <p class="text-muted">No hay datos disponibles para este gráfico.</p>
                        </canvas>
                    </div>
                    <div class="col-xs-12 col-md-4">
                        <canvas id="disk_chart" width="100%" height="50">
                            <p class="text-muted">No hay datos disponibles para este gráfico.</p>
                        </canvas>
                    </div>
                </div>
            </div>
        </div>
    </div>
@endsection

@section('footer-scripts')
    @parent
    {!! Theme::js('vendor/chartjs/chart.min.js') !!}
    {!! Theme::js('js/admin/statistics.js') !!}
@endsection
