@extends('layouts.admin')

@section('title')
    Nuevo Nest
@endsection

@section('content-header')
    <h1>Nuevo Nest<small>Configurar un nuevo nido para implementarlo en todos los nodos.</small></h1>
    <ol class="breadcrumb">
        <li><a href="{{ route('admin.index') }}">Administrador</a></li>
        <li><a href="{{ route('admin.nests') }}">Nests</a></li>
        <li class="active">Nuevo</li>
    </ol>
@endsection

@section('content')
<form action="{{ route('admin.nests.new') }}" method="POST">
    <div class="row">
        <div class="col-md-12">
            <div class="box">
                <div class="box-header with-border">
                    <h3 class="box-title">Nuevo Nest</h3>
                </div>
                <div class="box-body">
                    <div class="form-group">
                        <label class="control-label">Nome</label>
                        <div>
                            <input type="text" name="name" class="form-control" value="{{ old('name') }}" />
                            <p class="text-muted"><small>Este debe ser un nombre de categoría descriptivo que abarque todos los huevos dentro del nido..</small></p>
                        </div>
                    </div>
                    <div class="form-group">
                        <label class="control-label">Descripción</label>
                        <div>
                            <textarea name="description" class="form-control" rows="6">{{ old('description') }}</textarea>
                        </div>
                    </div>
                    <div class="form-group">
                        <label class="control-label">Visibilidad del Nest</label>
                        <div>
                            <select name="private" class="form-control">
                                <option selected value="0">Público</option>
                                <option value="1">Privado</option>
                            </select>
                            <p class="text-muted"><small>Determina si los usuarios pueden implementar en este nest.</small></p>
                        </div>
                    </div>
                </div>
                <div class="box-footer">
                    {!! csrf_field() !!}
                    <button type="submit" class="btn btn-primary pull-right">Guardar</button>
                </div>
            </div>
        </div>
    </div>
</form>
@endsection
