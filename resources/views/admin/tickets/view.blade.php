@extends('layouts.admin')

@section('title')
    Ver Ticket {{ $ticket->id }}
@endsection

@section('content-header')
    <h1>Ticket #{{ $ticket->id }}<small>{{ $ticket->title }}</small></h1>
    <ol class="breadcrumb">
        <li><a href="{{ route('admin.index') }}">Admin</a></li>
        <li><a href="{{ route('admin.tickets.index') }}">Tickets</a></li>
        <li class="active">Ver Ticket {{ $ticket->id }}</li>
    </ol>
@endsection

@section('content')
<div class="row">
    <div class="col-xs-12">
        <div class="alert
            @if($ticket->status == 'pendente')
                alert-warning
            @elseif($ticket->status == 'em-proceso')
                bg-primary
            @elseif($ticket->status == 'no-resolvido')
                alert-danger
            @else
                alert-success
            @endif
        ">
            Este ticket está actualmente marcado como <code>{{ $ticket->status }}</code>
        </div>
    </div>
</div>
<div class="row">
        <div class="col-xs-12">
            <div class="box box-success">
                <div class="box-header with-border">
                    <form id="deleteform" action="{{ route('admin.tickets.delete', $ticket->id) }}" method="POST">
                        <div class="pull-left">
                            {!! csrf_field() !!}
                            <button class="btn btn-danger">Excluir Ticket</button>
                        </div>
                    </form>
                    <form id="statusform" action="{{ route('admin.tickets.status', $ticket->id) }}" method="POST">
                        {!! csrf_field() !!}
                        <div class="pull-right">
                            <button id="unresolvedButton" class="btn btn-danger" name="status" value="não-resolvido">Marcar como No Resolvido</button>
                            <button id="pendingButton" class="btn btn-warning" style="margin-left: 8px;" name="status" value="pendente">Marcar como Pendente</button>
                            <button id="resolvedButton" class="btn btn-success" style="margin-left: 8px;" name="status" value="resolvido">Marcar como Resolvido</button>
                            <button id="inProgressButton" class="btn btn-info" style="margin-left: 8px;" name="status" value="em-andamento">Marcar como En proceso</button>
                        </div>
                    </form>
                 </div>
                <div class="box-body table-responsive no-padding">
                    <table class="table table-hover">
                        <tbody>
                            <tr>
                                <th>Autor</th>
                                <th>Contenido</th>
                                <th></th>
                                <th></th>
                                <th>Mensaje Enviado</th>
                            </tr>
                            @foreach ($messages as $message)
                                <tr>
                                @if($message->user_id == 0)
                                    <td>Mensaje del sistema <i class="fa fa-cog text-white"></i></td>
                                @else
                                    <td><a href="{{ route('admin.users.view', $ticket->user->id) }}">{{ $ticket->user->email }}</a> @if($ticket->user->root_admin)<i class="fa fa-star text-yellow"></i>@endif</td>
                                @endif
                                    <td>{{ $message->content }}</td>
                                    <td></td>
                                    <td></td>
                                    <td>{{ $message->created_at->diffForHumans() }}</td>
                                </tr>
                            @endforeach
                        </tbody>
                    </table>
                </div>
            </div>
        </div>
    </div>
<div class="row">
        <div class="col-xs-12">
            <div class="box box-success">
                <div class="box-header with-border">
                    <h3 class="box-title">Enviar un mensaje</h3>
                    <form id="messageform" action="{{ route('admin.tickets.message', $ticket->id) }}" method="POST">
                        <div class="box-body">
                            <div class="row">
                                <div class="form-group col-md-12">
                                    <div>
                                        <input type="text" class="form-control" name="content" />
                                        <p class="text-muted"><small>Enviar um mensaje para el ticket que podrá ser vista por el cliente.</small></p>
                                    </div>
                                </div>
                            </div>
                        </div>
                        {!! csrf_field() !!}
                        <button type="submit" name="_method" value="POST" class="btn btn-default pull-right">Enviar Mensaje</button>
                    </form>
                </div>
            </div>
        </div>
    </div>
</div>
@endsection
