@extends('layouts.app')

@push('estilos')
    <style>
        .hover-effect:hover {
            background-color: #f8f9fa;
        }

        .table-striped tbody tr:nth-of-type(odd) {
            background-color: #f9f9f9;
        }

        .table-striped tbody tr:nth-of-type(even) {
            background-color: #ffffff;
        }

        .table th,
        .table td {
            vertical-align: middle;
            text-align: center;
        }

        .estado-borroso {
            color: #6c757d;
        }

        .estado-con-aire {
            color: #17a2b8;
        }

        .estado-en-interior {
            color: #28a745;
        }

        .estado-roto {
            color: #dc3545;
        }

        .estado-sin-instalar {
            color: #ffc107;
        }

        .estado-sin-situacion {
            color: #6f42c1;
        }

        .estado-tapado {
            color: #343a40;
        }

        .btn-container {
            display: flex;
            justify-content: center;
            gap: 15px;
            margin-bottom: 20px;
        }

        .logo-container {
            text-align: center;
            margin-bottom: 20px;
        }

        .logo-container img {
            max-width: 120px;
            height: auto;
        }

        .table-responsive {
            overflow-x: unset !important;
        }

        /* Diseño de botones */
        .btn-lg {
            font-size: 1.25rem;
            padding: 0.5rem 1.25rem;
        }

        .modal-header {
            background-color: #f1f1f1;
        }

        .modal-title {
            color: #007bff;
        }

        .dataTables_wrapper .dataTables_length select,
        .dataTables_wrapper .dataTables_paginate .paginate_button {
            background-color: #f8f9fa;
        }

        .dataTables_wrapper .dataTables_paginate .paginate_button:hover {
            background-color: #e9ecef;
        }
    </style>
@endpush

@section('contenido')
    <div class="container-fluid mt-5">
        <!-- Título y Logo -->
        <div class="logo-container text-center mb-4">
            <img src="{{ asset('img/logo/logo_municipalidad_salsipuedes.png') }}" alt="Logo Municipalidad Salsipuedes"
                class="img-fluid">
            <h1 class="text-primary">Dirección de Agua - Municipalidad de Salsipuedes</h1>
        </div>

        <h3 class="mb-4 text-center text-primary">Listado de Mediciones</h3>

        <!-- Botones centrados -->
        <div class="text-center mb-4">
            <button class="btn btn-danger btn-lg" data-bs-toggle="modal" data-bs-target="#periodoModal">
                <i class="bi bi-file-earmark-text"></i> Generar Reporte
            </button>
            <a class="btn btn-success btn-lg" href="#">
                <i class="bi bi-calculator"></i> Calcular Consumos
            </a>
        </div>

        <div class="table-responsive">
            <!-- Aquí es donde se generan las filas de la tabla (DataTable) -->
            <table id="medicionesTable" class="table table-striped table-bordered table-hover">
                <thead class="thead-dark">
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
                    <!-- Las filas se cargarán mediante AJAX -->
                </tbody>
            </table>
        </div>
    </div>

    <!-- Modal para las imágenes -->
    <div class="modal fade" id="imagenModal" tabindex="-1" aria-labelledby="imagenModalLabel" aria-hidden="true">
        <div class="modal-dialog modal-dialog-centered">
            <div class="modal-content">
                <div class="modal-header">
                    <h5 class="modal-title" id="imagenModalLabel">Imagen de Lectura</h5>
                    <button type="button" class="btn-close" data-bs-dismiss="modal" aria-label="Close"></button>
                </div>
                <div class="modal-body" id="modalImagenBody">
                    <!-- Las imágenes se cargarán dinámicamente aquí -->
                </div>
            </div>
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

            $('#medicionesTable').DataTable({
                "processing": true,
                "serverSide": true,
                "ajax": {
                    "url": "{{ route('get.mediciones') }}",
                    "dataSrc": function(json) {
                        return json.data.map(function(medicion) {
                            return {
                                id: medicion[0],
                                nro_cuenta: medicion[1],
                                ruta: medicion[2],
                                orden: medicion[3],
                                medicion: medicion[4],
                                consumo: medicion[5],
                                fecha: medicion[6],
                                estado: medicion[7],
                                periodo: medicion[8],
                                imagen: medicion[9] ? Object.values(medicion[9]) : []
                            };
                        });
                    }
                },
                "language": {
                    "url": idiomaUrl
                },
                "columns": [{
                        "data": "id"
                    },
                    {
                        "data": "nro_cuenta"
                    },
                    {
                        "data": "ruta"
                    },
                    {
                        "data": "orden"
                    },
                    {
                        "data": "medicion"
                    },
                    {
                        "data": "consumo"
                    },
                    {
                        "data": "fecha"
                    },
                    {
                        "data": "estado"
                    },
                    {
                        "data": "periodo"
                    },
                    {
                        "data": "imagen",
                        "render": function(data, type, row) {
                            if (data && data.length > 0) {
                                return `<button type="button" class="btn btn-info btn-sm" data-bs-toggle="modal" data-bs-target="#imagenModal" data-imagen-url="${data.join(',')}">Ver Imagen</button>`;
                            } else {
                                return 'Sin Imagen';
                            }
                        }
                    }
                ],
                "lengthChange": true, // Habilita el cambio de longitud de página
                "pageLength": 10, // Número de registros por página por defecto
                "paging": true, // Habilita la paginación
            });


            // Evento para abrir el modal y cargar las imágenes
            $('#imagenModal').on('show.bs.modal', function(event) {
                const button = $(event.relatedTarget); // Botón que activó el modal
                const imagenUrls = button.data('imagen-url').split(','); // URLs de las imágenes

                const modalBody = $(this).find('.modal-body');
                modalBody.empty(); // Limpiar el contenido previo

                if (imagenUrls.length > 1) {
                    // Crear el carrusel si hay más de una imagen
                    const carouselInner = $('<div class="carousel-inner"></div>');
                    imagenUrls.forEach(function(url, index) {
                        const isActive = index === 0 ? 'active' : '';
                        const item = $(
                            `<div class="carousel-item ${isActive}"><img src="${url}" class="d-block w-100" alt="Imagen"></div>`
                        );
                        carouselInner.append(item);
                    });

                    const carousel = $(
                        '<div id="imagenCarousel" class="carousel slide" data-bs-ride="carousel"></div>'
                    );
                    carousel.append(carouselInner);

                    // Agregar los controles del carrusel
                    const prevButton = $(
                        '<button class="carousel-control-prev" type="button" data-bs-target="#imagenCarousel" data-bs-slide="prev"><span class="carousel-control-prev-icon" aria-hidden="true"></span><span class="visually-hidden">Previous</span></button>'
                    );
                    const nextButton = $(
                        '<button class="carousel-control-next" type="button" data-bs-target="#imagenCarousel" data-bs-slide="next"><span class="carousel-control-next-icon" aria-hidden="true"></span><span class="visually-hidden">Next</span></button>'
                    );

                    carousel.append(prevButton, nextButton);
                    modalBody.append(carousel);
                } else {
                    // Mostrar una sola imagen si solo hay una
                    const img = $('<img>').attr('src', imagenUrls[0]).addClass('img-fluid mb-2');
                    modalBody.append(img);
                }
            });

            $('#periodoForm').submit(function(event) {
                event.preventDefault(); // Evita el comportamiento por defecto del formulario
                let periodo = $('#periodo').val();
                let anio = $('#anio').val();
                let periodoAnio = `${periodo} - ${anio}`;

                // Definir correctamente la URL
                let url =
                    `{{ route('api_exportar_mediciones') }}?periodo=${encodeURIComponent(periodoAnio)}`;

                // Mostrar mensaje de carga
                Swal.fire({
                    title: 'Generando reporte...',
                    text: 'Por favor, espere.',
                    allowOutsideClick: false,
                    didOpen: () => {
                        Swal.showLoading();
                    }
                });

                // Realizar la petición AJAX
                $.ajax({
                    url: url,
                    type: 'GET',
                    success: function(response) {
                        Swal.close(); // Cierra el mensaje de carga
                        window.location.href = url; // Redirige al usuario al reporte generado
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

        });
    </script>
@endpush







{{-- @section('contenido')
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
        });
    </script>
@endpush --}}


{{-- // // Manejar el envío del formulario de período
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
            // }); --}}
