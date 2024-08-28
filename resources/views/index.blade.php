@extends('layouts.app')

@push('estilos')
    <style>
        .hover-effect:hover {
            background-color: #f1f1f1;
        }

        .text-warning {
            color: #ffc107 !important;
        }

        .text-uppercase {
            text-transform: uppercase;
        }

        .text-primary {
            color: #007bff !important;
        }

        .btn-info {
            background-color: #17a2b8;
            border-color: #17a2b8;
        }

        .btn-info:hover {
            background-color: #138496;
            border-color: #117a8b;
        }

        .table-striped tbody tr:nth-of-type(odd) {
            background-color: #f2f2f2;
        }

        .table-striped tbody tr:nth-of-type(even) {
            background-color: #ffffff;
        }

        .shadow-sm {
            box-shadow: 0 .125rem .25rem rgba(0, 0, 0, .075) !important;
        }

        .estado-borroso {
            color: #6c757d;
            /* Color gris */
        }

        .estado-con-aire {
            color: #17a2b8;
            /* Color cyan */
        }

        .estado-en-interior {
            color: #28a745;
            /* Color verde */
        }

        .estado-roto {
            color: #dc3545;
            /* Color rojo */
        }

        .estado-sin-instalar {
            color: #ffc107;
            /* Color amarillo */
        }

        .estado-sin-situacion {
            color: #6f42c1;
            /* Color púrpura */
        }

        .estado-tapado {
            color: #343a40;
            /* Color oscuro */
        }

        .btn-container {
            display: flex;
            justify-content: center;
            gap: 10px;
            /* Espacio entre los botones */
        }

        .logo-container {
            text-align: center;
            margin-bottom: 20px;
        }

        .logo-container img {
            max-width: 150px;
            /* Ajusta el tamaño del logo según sea necesario */
            height: auto;
        }

        /* Asegurarse de que el desplegable de filas esté en un nivel superior */
        .dataTables_wrapper .dataTables_paginate .paginate_button {
            position: relative;
            z-index: 1050;
            /* Ajusta el valor según sea necesario */
        }

        /* Ajustar el tamaño del desplegable para filas si es necesario */
        .dataTables_wrapper .dataTables_length select {
            z-index: 1050;
            /* Ajusta el valor según sea necesario */
            margin: 0;
        }
    </style>
@endpush

@section('contenido')
    <div class="container-fluid mt-5">
        <!-- Título y Logo -->
        <div class="logo-container">
            <img src="{{ asset('img/logo/logo_municipalidad_salsipuedes.png') }}" alt="Logo Municipalidad Salsipuedes">
            <h1 class="text-center text-primary">Dirección de Agua - Municipalidad de Salsipuedes</h1>
        </div>

        <h3 class="mb-4 text-center text-primary">Listado de Mediciones</h3>

        <!-- Botones centrados -->
        <div class="text-center mb-4 btn-container">
            <a class="btn btn-danger btn-lg" href="{{ route('api_exportar_mediciones') }}">
                <i class="bi bi-file-earmark-text"></i> Generar Reporte
            </a>
            <a class="btn btn-success btn-lg" href="#">
                <i class="bi bi-calculator"></i> Calcular Consumos
            </a>
        </div>

        <div class="table-responsive">
            <table class="table table-striped table-bordered shadow-sm" id="listado_mediciones">
                <thead class="table-dark">
                    <tr>
                        <th>ID</th>
                        <th>Número de Cuenta</th>
                        <th>Ruta</th>
                        <th>Orden</th>
                        <th>Medición</th>
                        <th>Consumo</th>
                        <th>Fecha</th>
                        <th>Estado</th>
                        <th>Imagen</th>
                    </tr>
                </thead>
                <tbody>
                    @foreach ($mediciones as $medicion)
                        <tr class="hover-effect">
                            <td>{{ $medicion->id }}</td>
                            <td>{{ $medicion->nro_cuenta }}</td>
                            <td>{{ $medicion->ruta }}</td>
                            <td>{{ $medicion->orden }}</td>
                            <td>{{ $medicion->medicion }}</td>
                            <td>
                                @if (is_null($medicion->consumo))
                                    <span class="text-danger">Pendiente de calcular</span>
                                @else
                                    {{ $medicion->consumo }}
                                @endif
                            </td>
                            <td>{{ $medicion->fecha }}</td>
                            <td class="{{ 'estado-' . str_replace(' ', '-', strtolower($medicion->estado)) }}">
                                {{ $medicion->estado }}
                            </td>
                            <td>
                                @if (isset($medicion->imagenes[0]))
                                    <button type="button" class="btn btn-info btn-sm" data-bs-toggle="modal"
                                        data-bs-target="#imagenModal{{ $medicion->id }}">
                                        Ver Imagen
                                    </button>

                                    <!-- Modal -->
                                    <div class="modal fade" id="imagenModal{{ $medicion->id }}" tabindex="-1"
                                        aria-labelledby="imagenModalLabel{{ $medicion->id }}" aria-hidden="true">
                                        <div class="modal-dialog modal-dialog-centered">
                                            <div class="modal-content">
                                                <div class="modal-header">
                                                    <h5 class="modal-title" id="imagenModalLabel{{ $medicion->id }}">Imagen
                                                        de
                                                        la Medición</h5>
                                                    <button type="button" class="btn-close" data-bs-dismiss="modal"
                                                        aria-label="Close"></button>
                                                </div>
                                                <div class="modal-body">
                                                    <img src="{{ asset($medicion->imagenes[0]) }}"
                                                        alt="Imagen de la medición" class="img-fluid">
                                                </div>
                                                <div class="modal-footer">
                                                    <button type="button" class="btn btn-secondary"
                                                        data-bs-dismiss="modal">Cerrar</button>
                                                </div>
                                            </div>
                                        </div>
                                    </div>
                                @else
                                    Sin Imagen
                                @endif
                            </td>
                        </tr>
                    @endforeach
                </tbody>
            </table>
        </div>
    </div>
@endsection

@push('scripts')
    <script>
        $(document).ready(function() {
            let idiomaUrl = "{{ env('IDIOMA_DATATABLES_URL') }}";

            // Configuración de DataTable para la tabla,
            $('#listado_mediciones').DataTable({
                "language": {
                    "url": idiomaUrl
                }
            });
        });
    </script>
@endpush
