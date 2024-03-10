@extends('layouts.admin')

@section('title')
    Aplicación de API
@endsection

@section('content-header')
    <h1>Aplicación API<small>Controlar credenciales de acceso para la gestión de este Panel a trav&eacute;s de la API.</small></h1>
    <ol class="breadcrumb">
        <li><a href="{{ route('admin.index') }}">Administrador</a></li>
        <li class="active">Aplicación de API</li>
    </ol>
@endsection

@section('content')
    <div class="row">
        <div class="col-xs-12">
            <div class="box box-primary">
                <div class="box-header with-border">
                    <h3 class="box-title">Lista de Credenciales</h3>
                    <div class="box-tools">
                        <a href="{{ route('admin.api.new') }}" class="btn btn-sm btn-primary">Crear nueva</a>
                    </div>
                </div>
                <div class="box-body table-responsive no-padding">
                    <table class="table table-hover">
                        <tr>
                            <th>Llave</th>
                            <th>Descripción</th>
                            <th>&Uacute;ltimo uso</th>
                            <th>Creado</th>
                            <th></th>
                        </tr>
                        @foreach($keys as $key)
                            <tr>
                                <td><code>{{ $key->identifier }}{{ decrypt($key->token) }}</code></td>
                                <td>{{ $key->memo }}</td>
                                <td>
                                    @if(!is_null($key->last_used_at))
                                        @datetimeHuman($key->last_used_at)
                                    @else
                                        &mdash;
                                    @endif
                                </td>
                                <td>@datetimeHuman($key->created_at)</td>
                                <td>
                                    <a href="#" data-action="revoke-key" data-attr="{{ $key->identifier }}">
                                        <i class="fa fa-trash-o text-danger"></i>
                                    </a>
                                </td>
                            </tr>
                        @endforeach
                    </table>
                </div>
            </div>
        </div>
    </div>
@endsection

@section('footer-scripts')
    @parent
    <script>
        $(document).ready(function() {
            $('[data-action="revoke-key"]').click(function (event) {
                var self = $(this);
                event.preventDefault();
                swal({
                    type: 'error',
                    title: 'Revocar clave API',
                    text: 'Una vez que se revoque esta clave API, se rechazarán los sistemas que usaron esta clave.',
                    showCancelButton: true,
                    allowOutsideClick: true,
                    closeOnConfirm: false,
                    confirmButtonText: 'Revocar',
                    confirmButtonColor: '#d9534f',
                    showLoaderOnConfirm: true
                }, function () {
                    $.ajax({
                        method: 'DELETE',
                        url: '/admin/api/revoke/' + self.data('attr'),
                        headers: {
                            'X-CSRF-TOKEN': '{{ csrf_token() }}'
                        }
                    }).done(function () {
                        swal({
                            type: 'success',
                            title: '',
                            text: 'La clave API fue revocado.'
                        });
                        self.parent().parent().slideUp();
                    }).fail(function (jqXHR) {
                        console.error(jqXHR);
                        swal({
                            type: 'error',
                            title: 'Ops!',
                            text: 'Se produjo un error al intentar revocar esta clave.'
                        });
                    });
                });
            });
        });
    </script>
@endsection
