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
            <button class="btn btn-danger btn-lg" data-bs-toggle="modal" data-bs-target="#periodoModal">
                <i class="bi bi-file-earmark-text"></i> Generar Reporte
            </button>
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
                        <th>Periodo</th>
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
                            <td>{{ $medicion->periodo }}</td>
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

    <!-- Modal para seleccionar período -->
    <div class="modal fade" id="periodoModal" tabindex="-1" aria-labelledby="periodoModalLabel" aria-hidden="true">
        <div class="modal-dialog modal-dialog-centered">
            <div class="modal-content">
                <div class="modal-header">
                    <h5 class="modal-title" id="periodoModalLabel">Seleccionar Período</h5>
                    <button type="button" class="btn-close" data-bs-dismiss="modal" aria-label="Close"></button>
                </div>
                <div class="modal-body">
                    <form id="periodoForm">
                        @csrf
                        <div class="mb-3">
                            <label for="periodo" class="form-label">Período</label>
                            <select class="form-select" id="periodo" name="periodo" required>
                                <option value="Periodo 1">Periodo 1 (21/12 al 20/02)</option>
                                <option value="Periodo 3">Periodo 3 (21/02 al 20/04)</option>
                                <option value="Periodo 5">Periodo 5 (21/04 al 20/06)</option>
                                <option value="Periodo 7">Periodo 7 (21/06 al 20/08)</option>
                                <option value="Periodo 9">Periodo 9 (21/08 al 20/10)</option>
                                <option value="Periodo 11">Periodo 11 (21/10 al 20/12)</option>
                            </select>
                        </div>
                        <div class="mb-3">
                            <label for="anio" class="form-label">Año</label>
                            <select class="form-select" id="anio" name="anio" required>
                                <script>
                                    // Generar dinámicamente los últimos 10 años y los próximos 10 años
                                    const currentYear = new Date().getFullYear();
                                    let options = '';
                                    for (let year = currentYear + 10; year >= currentYear - 10; year--) {
                                        const selected = year === currentYear ? 'selected' : '';
                                        options += `<option value="${year.toString().slice(-2)}" ${selected}>${year}</option>`;
                                    }
                                    document.write(options);
                                </script>
                            </select>
                        </div>
                        <div class="text-center">
                            <button type="submit" class="btn btn-primary">Generar Reporte</button>
                        </div>
                    </form>
                </div>
            </div>
        </div>
    </div>
@endsection

@push('scripts')
    <script>
        $(document).ready(function() {
            let idiomaUrl = "{{ env('IDIOMA_DATATABLES_URL') }}";

            // Verifica si el DataTable ya está inicializado
            if (!$.fn.DataTable.isDataTable('#listado_mediciones')) {
                // Configuración de DataTable para la tabla
                $('#listado_mediciones').DataTable({
                    "language": {
                        "url": idiomaUrl
                    }
                });
            }

            // Manejar el envío del formulario de período
            $('#periodoForm').submit(function(event) {
                event.preventDefault(); // Evita el comportamiento por defecto del formulario

                let periodo = $('#periodo').val();
                let anio = $('#anio').val();
                let periodoAnio = `${periodo} - ${anio}`;
                let url =
                    `{{ route('api_exportar_mediciones') }}?periodo=${encodeURIComponent(periodoAnio)}`;

                // Mostrar mensaje de carga
                Swal.fire({
                    title: 'Generando reporte...',
                    text: 'Por favor, espere.',
                    allowOutsideClick: false,
                    didOpen: () => {
                        Swal.showLoading()
                    }
                });

                // Redirige a la URL con el período y año seleccionado
                $.ajax({
                    url: url,
                    type: 'GET',
                    success: function(response) {
                        Swal.close(); // Cierra el mensaje de carga
                        window.location.href = url;
                    },
                    error: function(xhr) {
                        Swal.close(); // Cierra el mensaje de carga
                        if (xhr.status === 404) {
                            Swal.fire({
                                icon: 'error',
                                title: 'Error',
                                text: 'No se encontraron mediciones para el período especificado.',
                                confirmButtonText: 'Aceptar'
                            });
                        } else {
                            Swal.fire({
                                icon: 'error',
                                title: 'Error',
                                text: 'Hubo un problema al generar el reporte.',
                                confirmButtonText: 'Aceptar'
                            });
                        }
                    }
                });
            });

            // // Manejar el envío del formulario de período
            // $('#periodoForm').submit(function(event) {
            //     event.preventDefault(); // Evita el comportamiento por defecto del formulario

            //     let periodo = $('#periodo').val();
            //     let url = `{{ route('api_exportar_mediciones') }}?periodo=${periodo}`;

            //     // Mostrar mensaje de carga
            //     Swal.fire({
            //         title: 'Generando reporte...',
            //         text: 'Por favor, espere.',
            //         allowOutsideClick: false,
            //         didOpen: () => {
            //             Swal.showLoading()
            //         }
            //     });

            //     // Redirige a la URL con el período seleccionado
            //     $.ajax({
            //         url: url,
            //         type: 'GET',
            //         success: function(response) {
            //             Swal.close(); // Cierra el mensaje de carga
            //             window.location.href = url;
            //         },
            //         error: function(xhr) {
            //             Swal.close(); // Cierra el mensaje de carga
            //             if (xhr.status === 404) {
            //                 Swal.fire({
            //                     icon: 'error',
            //                     title: 'Error',
            //                     text: 'No se encontraron mediciones para el período especificado.',
            //                     confirmButtonText: 'Aceptar'
            //                 });
            //             } else {
            //                 Swal.fire({
            //                     icon: 'error',
            //                     title: 'Error',
            //                     text: 'Hubo un problema al generar el reporte.',
            //                     confirmButtonText: 'Aceptar'
            //                 });
            //             }
            //         }
            //     });
            // });
        });
    </script>
@endpush
