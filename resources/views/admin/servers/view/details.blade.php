@extends('layouts.admin')

@section('title')
Servidor — {{ $server->name }}: Detalles
@endsection

@section('content-header')
    <h1>{{ $server->name }}<small>Editar detalles para este servidor, incluyendo proprietário y contenedor</small></h1>
    <ol class="breadcrumb">
        <li><a href="{{ route('admin.index') }}">Admin</a></li>
        <li><a href="{{ route('admin.servers') }}">Servidores</a></li>
        <li><a href="{{ route('admin.servers.view', $server->id) }}">{{ $server->name }}</a></li>
        <li class="active">Detalhes</li>
    </ol>
@endsection

@section('content')
@include('admin.servers.partials.navigation')
<div class="row">
    <div class="col-xs-12">
        <div class="box box-primary">
            <div class="box-header with-border">
                <h3 class="box-title">Informaciones básicas</h3>
            </div>
            <form action="{{ route('admin.servers.view.details', $server->id) }}" method="POST">
                <div class="box-body">
                    <div class="form-group">
                        <label for="name" class="control-label">Nombre del servidor <span class="field-required"></span></label>
                        <input type="text" name="name" value="{{ old('name', $server->name) }}" class="form-control" />
                        <p class="text-muted small">Limites de caracteres: <code>a-zA-Z0-9_-</code> e <code>[Espacio]</code>.</p>
                    </div>
                    <div class="form-group">
                        <label for="external_id" class="control-label">Identificador externo</label>
                        <input type="text" name="external_id" value="{{ old('external_id', $server->external_id) }}" class="form-control" />
                        <p class="text-muted small">Déjelo vacío para no asignar un identificador externo a este servidor. La ID externa debe ser única para este servidor y no debe ser utilizada por ningún otro servidor..</p>
                    </div>
                    <div class="form-group">
                        <label for="pUserId" class="control-label">Dono do servidor <span class="field-required"></span></label>
                        <select name="owner_id" class="form-control" id="pUserId">
                            <option value="{{ $server->owner_id }}" selected>{{ $server->user->email }}</option>
                        </select>
                        <p class="text-muted small">Puede cambiar el propietario de este servidor cambiando este campo a un Correo electrónico que corresponda a otro usuario de este sistema. Si hace esto, se generará automáticamente un nuevo demonio de token de seguridad..</p>
                    </div>
                    <div class="form-group">
                        <label for="description" class="control-label">Descripción del servidor</label>
                        <textarea name="description" rows="3" class="form-control">{{ old('description', $server->description) }}</textarea>
                        <p class="text-muted small">Una breve descripción de este servidor.</p>
                    </div>
                    <div class="form-group">
                        <label for="renewable" class="control-label">Renovable <span class="field-required"></span></label>
                        <select name="renewable" class="form-control">
                            <option @if (!$server->renewable) selected @endif value="0">Deshabilitado</option>
                            <option @if ($server->renewable) selected @endif value="1">Habilitado</option>
                        </select>
                        <p class="text-muted small">Determina si este servidor es renovado o no por el sistema de renovación..</p>
                    </div>
                    <div class="form-group">
                        <label for="renewal" class="control-label">Días hasta la renovación <span class="field-required"></span></label>
                        <input type="text" name="renewal" value="{{ $server->renewal }}" class="form-control" />
                        <p class="text-muted small">Establecer el número de días hasta que se deba renovar el servidor.</p>
                    </div>
                </div>
                <div class="box-footer">
                    {!! csrf_field() !!}
                    {!! method_field('PATCH') !!}
                    <input type="submit" class="btn btn-sm btn-primary" value="Update Details" />
                </div>
            </form>
        </div>
    </div>
</div>
@endsection

@section('footer-scripts')
    @parent
    <script>
    function escapeHtml(str) {
        var div = document.createElement('div');
        div.appendChild(document.createTextNode(str));
        return div.innerHTML;
    }

    $('#pUserId').select2({
        ajax: {
            url: '/admin/users/accounts.json',
            dataType: 'json',
            delay: 250,
            data: function (params) {
                return {
                    filter: { email: params.term },
                    page: params.page,
                };
            },
            processResults: function (data, params) {
                return { results: data };
            },
            cache: true,
        },
        escapeMarkup: function (markup) { return markup; },
        minimumInputLength: 2,
        templateResult: function (data) {
            if (data.loading) return escapeHtml(data.text);

            return '<div class="user-block"> \
                <img class="img-circle img-bordered-xs" src="https://www.gravatar.com/avatar/' + escapeHtml(data.md5) + '?s=120" alt="User Image"> \
                <span class="username"> \
                    <a href="#">' + escapeHtml(data.name_first) + ' ' + escapeHtml(data.name_last) +'</a> \
                </span> \
                <span class="description"><strong>' + escapeHtml(data.email) + '</strong> - ' + escapeHtml(data.username) + '</span> \
            </div>';
        },
        templateSelection: function (data) {
            if (typeof data.name_first === 'undefined') {
                data = {
                    md5: '{{ md5(strtolower($server->user->email)) }}',
                    name_first: '{{ $server->user->name_first }}',
                    name_last: '{{ $server->user->name_last }}',
                    email: '{{ $server->user->email }}',
                    id: {{ $server->owner_id }}
                };
            }

            return '<div> \
                <span> \
                    <img class="img-rounded img-bordered-xs" src="https://www.gravatar.com/avatar/' + escapeHtml(data.md5) + '?s=120" style="height:28px;margin-top:-4px;" alt="User Image"> \
                </span> \
                <span style="padding-left:5px;"> \
                    ' + escapeHtml(data.name_first) + ' ' + escapeHtml(data.name_last) + ' (<strong>' + escapeHtml(data.email) + '</strong>) \
                </span> \
            </div>';
        }
    });
    </script>
@endsection
