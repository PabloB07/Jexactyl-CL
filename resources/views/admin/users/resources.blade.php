@extends('layouts.admin')
@include('partials/admin.users.nav', ['activeTab' => 'resources', 'user' => $user])

@section('title')
    Recursos: {{ $user->username }}
@endsection

@section('content-header')
    <h1>{{ $user->name_first }} {{ $user->name_last}}<small>{{ $user->username }}</small></h1>
    <ol class="breadcrumb">
        <li><a href="{{ route('admin.index') }}">Admin</a></li>
        <li><a href="{{ route('admin.users') }}">Usuarios</a></li>
        <li class="{{ route('admin.users.view', ['user' => $user]) }}">{{ $user->username }}</li>
        <li class="active">Recursos</li>
    </ol>
@endsection

@section('content')
    @yield('users::nav')
    <div class="row">
            <div class="col-xs-12">
                <div class="box box-info">
                    <div class="box-header with-border">
                        <h3 class="box-title">Recursos de Usuario</h3>
                    </div>
                    <form action="{{ route('admin.users.resources', $user->id) }}" method="POST">
                        <div class="box-body">
                            <div class="row">
                                <div class="form-group col-md-3">
                                    <label for="store_balance" class="control-label">Total en tu Cartera</label>
                                    <div class="input-group">
                                        <input type="text" id="store_balance" value="{{ $user->store_balance }}" name="store_balance" class="form-control form-autocomplete-stop">
                                        <span class="input-group-addon">créditos</span>
                                    </div>
                                </div>
                                <div class="form-group col-md-3">
                                    <label for="store_cpu" class="control-label">Total de CPU disponíble</label>
                                    <div class="input-group">
                                        <input type="text" id="store_cpu" value="{{ $user->store_cpu }}" name="store_cpu" class="form-control form-autocomplete-stop">
                                        <span class="input-group-addon">%</span>
                                    </div>
                                </div>
                                <div class="form-group col-md-3">
                                    <label for="store_memory" class="control-label">Total de Memoria disponible</label>
                                    <div class="input-group">
                                        <input type="text" id="store_memory" value="{{ $user->store_memory }}" name="store_memory" class="form-control form-autocomplete-stop">
                                        <span class="input-group-addon">MB</span>
                                    </div>
                                </div>
                                <div class="form-group col-md-3">
                                    <label for="store_disk" class="control-label">Total de Disco disponible</label>
                                    <div class="input-group">
                                        <input type="text" id="store_disk" value="{{ $user->store_disk }}" name="store_disk" class="form-control form-autocomplete-stop">
                                        <span class="input-group-addon">MB</span>
                                    </div>
                                </div>
                                <div class="form-group col-md-3">
                                    <label for="store_slots" class="control-label">Total de Slots disponíbles</label>
                                    <div class="input-group">
                                        <input type="text" id="store_slots" value="{{ $user->store_slots }}" name="store_slots" class="form-control form-autocomplete-stop">
                                        <span class="input-group-addon">slots</span>
                                    </div>
                                </div>
                                <div class="form-group col-md-3">
                                    <label for="store_ports" class="control-label">Total de Puertos disponíbles</label>
                                    <div class="input-group">
                                        <input type="text" id="store_ports" value="{{ $user->store_ports }}" name="store_ports" class="form-control form-autocomplete-stop">
                                        <span class="input-group-addon">portas</span>
                                    </div>
                                </div>
                                <div class="form-group col-md-3">
                                    <label for="store_backups" class="control-label">Total de Backups disponible</label>
                                    <div class="input-group">
                                        <input type="text" id="store_backups" value="{{ $user->store_backups }}" name="store_backups" class="form-control form-autocomplete-stop">
                                        <span class="input-group-addon">backups</span>
                                    </div>
                                </div>
                                <div class="form-group col-md-3">
                                    <label for="store_databases" class="control-label">Total de Databases disponible</label>
                                    <div class="input-group">
                                        <input type="text" id="store_databases" value="{{ $user->store_databases }}" name="store_databases" class="form-control form-autocomplete-stop">
                                        <span class="input-group-addon">databases</span>
                                    </div>
                                </div>
                            </div>
                        </div>
                        <div class="box-footer">
                            {!! csrf_field() !!}
                            <button type="submit" name="_method" value="PATCH" class="btn btn-sm btn-primary pull-right">Guardar cambios</button>
                        </div>
                    </form>
                </div>
            </div>
        </div>
    </div>
@endsection
