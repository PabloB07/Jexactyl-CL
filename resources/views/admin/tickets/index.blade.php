@extends('layouts.admin')

@section('title')
Lista de Tickets
@endsection

@section('content-header')
    <h1>Tickets<small>Ver todos los tickets del sistema.</small></h1>
    <ol class="breadcrumb">
        <li><a href="{{ route('admin.index') }}">Admin</a></li>
        <li class="active">Tickets</li>
    </ol>
@endsection

@section('content')
<div class="row">
    <div class="col-xs-12">
        <form action="{{ route('admin.tickets.index') }}" method="POST">
            <div class="box @if($enabled == 'true') box-success @else box-danger @endif">
                <div class="box-header with-border">
                    <i class="fa fa-ticket"></i> <h3 class="box-title">Sistema de Tickets <small>Cambiar los tickets para ser usados.</small></h3>
                </div>
                <div class="box-body">
                    <div class="row">
                        <div class="form-group col-md-4">
                            <label class="control-label">Permitir la creación de tickets?</label>
                            <div>
                                <select name="enabled" class="form-control">
                                    <option @if ($enabled == 'false') selected @endif value="false">Deshabilitar</option>
                                    <option @if ($enabled == 'true') selected @endif value="true">Habilitar</option>
                                </select>
                                <p class="text-muted"><small>Determina si las personas pueden crear tickets a través de la interfaz de usuario del cliente..</small></p>
                            </div>
                        </div>
                        <div class="form-group col-md-4">
                                <label class="control-label">Valor máximo de tickets</label>
                                <div>
                                    <input type="text" class="form-control" name="max" value="{{ $max }}" />
                                    <p class="text-muted"><small>Establecer la cantidad máxima de tickets que un usuario puede crear.</small></p>
                                </div>
                            </div>
                    </div>
                    {!! csrf_field() !!}
                    <button type="submit" name="_method" value="POST" class="btn btn-default pull-right">Guardar Cambios</button>
                </div>
            </div>
        </form>
        <div class="box box-primary">
            <div class="box-header with-border">
                <h3 class="box-title">Lista de Tickets</h3>
            </div>
            <div class="box-body table-responsive no-padding">
                <table class="table table-hover">
                    <tbody>
                        <tr>
                            <th>Ticket ID</th>
                            <th>E-mail de cliente</th>
                            <th>Título</th>
                            <th>Creado En</th>
                            <th></th>
                        </tr>
                        @foreach ($tickets as $ticket)
                            <tr data-ticket="{{ $ticket->id }}">
                                <td><a href="{{ route('admin.tickets.view', $ticket->id) }}">{{ $ticket->id }}</a></td>
                                <td><a href="{{ route('admin.users.view', $ticket->client_id) }}">{{ $ticket->user->email ?? 'N/A' }}</a></td>
                                <td style="
                                    white-space: nowrap;
                                    overflow: hidden;
                                    text-overflow: ellipsis;
                                    max-width: 32ch;
                                "><code title="{{ $ticket->title }}">{{ $ticket->title }}</code></td>
                                <td>{{ $ticket->created_at->diffForHumans() }}</td>
                                <td class="text-center">
                                    @if($ticket->status == 'pendente')
                                        <span class="label bg-black">Pendiente</span>
                                    @elseif($ticket->status == 'em-andamento')
                                        <span class="label label-warning">En proceso</span>
                                    @elseif($ticket->status == 'não-resolvido')
                                        <span class="label label-danger">No Resolvido</span>
                                    @else
                                        <span class="label label-success">Resolvido</span>
                                    @endif
                                </td>
                            </tr>
                        @endforeach
                    </tbody>
                </table>
            </div>
        </div>
    </div>
</div>
@endsection
