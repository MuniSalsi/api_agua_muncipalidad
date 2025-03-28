@extends('layouts.app')

@push('estilos')
    <style>
        :root {
            --primary-color: #2c3e50;
            --secondary-color: #3498db;
            --accent-color: #e74c3c;
            --light-color: #ecf0f1;
            --dark-color: #2c3e50;
            --success-color: #27ae60;
            --warning-color: #f39c12;
            --danger-color: #e74c3c;
            --info-color: #2980b9;
        }

        body {
            font-family: 'Segoe UI', Tahoma, Geneva, Verdana, sans-serif;
            background-color: #f5f7fa;
        }

        /* ELIMINADO EL MÁXIMO ANCHO PARA QUE OCUPE TODA LA PANTALLA */
        .container-fluid {
            padding: 20px;
            width: 100%;
        }

        .logo-container {
            text-align: center;
            margin-bottom: 30px;
            padding: 20px 0;
            background-color: white;
            border-radius: 10px;
            box-shadow: 0 4px 6px rgba(0, 0, 0, 0.1);
            width: 60%;
            margin: 20px auto;
        }

        .logo-container img {
            max-width: 150px;
            height: auto;
            margin-bottom: 15px;
        }

        .page-title {
            color: var(--primary-color);
            font-weight: 600;
            margin-bottom: 30px;
            text-align: center;
            position: relative;
            padding-bottom: 15px;
        }

        .page-title:after {
            content: '';
            position: absolute;
            bottom: 0;
            left: 50%;
            transform: translateX(-50%);
            width: 100px;
            height: 3px;
            background: var(--secondary-color);
        }

        .card {
            border: none;
            border-radius: 10px;
            box-shadow: 0 4px 20px rgba(0, 0, 0, 0.08);
            margin-bottom: 30px;
            overflow: hidden;
            width: 100%;
            /* AÑADIDO PARA QUE LA CARD OCUPE TODO EL ANCHO */
        }

        .card-header {
            background-color: var(--primary-color);
            color: white;
            font-weight: 600;
            padding: 15px 20px;
            border-bottom: none;
        }

        /* MODIFICADO PARA OCUPAR TODO EL ANCHO */
        .table-responsive {
            border-radius: 10px;
            overflow-x: auto;
            width: 100%;
            display: block;
        }

        /* MODIFICADO PARA OCUPAR TODO EL ANCHO */
        .table {
            margin-bottom: 0;
            width: 100%;
            table-layout: auto;
        }

        .table th {
            background-color: var(--primary-color);
            color: white;
            font-weight: 500;
            padding: 15px;
            text-align: center;
            vertical-align: middle;
            border: none;
            white-space: nowrap;
        }

        .table td {
            padding: 12px 15px;
            vertical-align: middle;
            text-align: center;
            border-top: 1px solid #e9ecef;
            white-space: nowrap;
        }

        .table-striped tbody tr:nth-of-type(odd) {
            background-color: rgba(236, 240, 241, 0.5);
        }

        .table-striped tbody tr:nth-of-type(even) {
            background-color: white;
        }

        .table-hover tbody tr:hover {
            background-color: rgba(52, 152, 219, 0.1);
            transition: all 0.3s ease;
        }

        /* Estado classes */
        .estado-borroso {
            color: #7f8c8d;
            font-weight: 500;
        }

        .estado-con-aire {
            color: #3498db;
            font-weight: 500;
        }

        .estado-en-interior {
            color: var(--success-color);
            font-weight: 500;
        }

        .estado-roto {
            color: var(--danger-color);
            font-weight: 500;
        }

        .estado-sin-instalar {
            color: var(--warning-color);
            font-weight: 500;
        }

        .estado-sin-situacion {
            color: #9b59b6;
            font-weight: 500;
        }

        .estado-tapado {
            color: var(--dark-color);
            font-weight: 500;
        }

        /* Botones */
        .btn-container {
            display: flex;
            justify-content: center;
            margin-bottom: 30px;
        }

        .btn {
            border-radius: 50px;
            padding: 10px 25px;
            font-weight: 600;
            letter-spacing: 0.5px;
            transition: all 0.3s ease;
            box-shadow: 0 2px 5px rgba(0, 0, 0, 0.1);
            border: none;
            margin: 0 10px;
        }

        .btn-lg {
            padding: 12px 30px;
            font-size: 1.1rem;
        }

        .btn-primary {
            background-color: var(--secondary-color);
        }

        .btn-primary:hover {
            background-color: #2980b9;
            transform: translateY(-2px);
            box-shadow: 0 4px 8px rgba(0, 0, 0, 0.15);
        }

        .btn-danger {
            background-color: var(--danger-color);
        }

        .btn-danger:hover {
            background-color: #c0392b;
            transform: translateY(-2px);
            box-shadow: 0 4px 8px rgba(0, 0, 0, 0.15);
        }

        .btn-info {
            background-color: var(--info-color);
            color: white;
        }

        .btn-secondary {
            background-color: #95a5a6;
        }

        /* Paginación */
        .pagination-container {
            display: flex;
            justify-content: center;
            margin: 30px 0;
        }

        .pagination {
            display: flex;
            gap: 5px;
            flex-wrap: wrap;
        }

        .page-item {
            margin: 0;
        }

        .page-link {
            color: var(--primary-color);
            border: 1px solid #ddd;
            border-radius: 5px !important;
            padding: 8px 15px;
            font-weight: 500;
            transition: all 0.3s ease;
        }

        .page-link:hover {
            background-color: var(--secondary-color);
            color: white;
            border-color: var(--secondary-color);
        }

        .page-item.active .page-link {
            background-color: var(--secondary-color);
            border-color: var(--secondary-color);
            color: white;
        }

        .page-item.disabled .page-link {
            color: #95a5a6;
            pointer-events: none;
            cursor: not-allowed;
            background-color: #ecf0f1;
            border-color: #ddd;
        }

        /* Modal */
        .modal-content {
            border: none;
            border-radius: 10px;
            overflow: hidden;
            box-shadow: 0 5px 20px rgba(0, 0, 0, 0.2);
        }

        .modal-header {
            background-color: var(--primary-color);
            color: white;
            border-bottom: none;
            padding: 15px 20px;
        }

        .modal-title {
            font-weight: 600;
        }

        .modal-body {
            padding: 20px;
        }

        .modal-footer {
            border-top: none;
            padding: 15px 20px;
            background-color: #f8f9fa;
        }

        /* Formularios */
        .form-select,
        .form-control {
            border-radius: 5px;
            padding: 10px 15px;
            border: 1px solid #ddd;
            transition: all 0.3s ease;
        }

        .form-select:focus,
        .form-control:focus {
            border-color: var(--secondary-color);
            box-shadow: 0 0 0 0.25rem rgba(52, 152, 219, 0.25);
        }

        /* Imágenes en tabla */
        .img-thumbnail {
            border-radius: 5px;
            cursor: pointer;
            transition: all 0.3s ease;
            border: 1px solid #ddd;
            padding: 2px;
            max-width: 50px;
            height: auto;
        }

        .img-thumbnail:hover {
            transform: scale(1.05);
            box-shadow: 0 4px 8px rgba(0, 0, 0, 0.1);
        }

        .no-image {
            color: #95a5a6;
            font-style: italic;
        }

        .div-img-container {
            display: flex;
            justify-content: space-evenly;
            align-items: center;
            /* Centra verticalmente las imágenes */
            margin-bottom: 30px;
            flex-wrap: wrap;
            /* Permite que los elementos se ajusten en pantallas pequeñas */
            gap: 20px;
            /* Espacio entre imágenes */
        }

        .div-img-container img {
            max-width: 100%;
            /* Asegura que las imágenes no excedan el ancho del contenedor */
            height: auto;
            /* Mantiene la proporción de aspecto */
            max-height: 180px;
            /* Altura máxima para pantallas grandes */
            object-fit: contain;
            /* Asegura que la imagen completa sea visible */
        }

        /* Responsive */
        @media (max-width: 768px) {
            .logo-container {
                width: 90%;
            }

            .div-img-container {
                gap: 15px;
                margin-bottom: 20px;
            }

            .div-img-container img {
                max-height: 120px;
                /* Altura máxima reducida para móviles */
                width: auto;
                /* Ancho automático basado en la proporción */
            }

            .btn-container {
                flex-direction: column;
                gap: 10px;
            }

            .btn {
                width: 100%;
                margin: 5px 0;
            }

            .table-responsive {
                overflow-x: auto;
                display: block;
                width: 100%;
            }

            .pagination {
                gap: 3px;
            }

            .page-link {
                padding: 6px 10px;
                font-size: 0.9rem;
            }
        }

        @media (max-width: 480px) {
            .div-img-container img {
                max-height: 90px;
                /* Altura aún más pequeña para pantallas muy pequeñas */
            }

            .logo-container h2 {
                font-size: 1.5rem;
                /* Reduce el tamaño del título principal */
            }

            .logo-container h4 {
                font-size: 1rem;
                /* Reduce el tamaño del subtítulo */
            }
        }
    </style>
@endpush

@section('contenido')
    <div class="container-fluid mt-4">
        <!-- Título y Logo -->
        <div class="logo-container">
            <div class="div-img-container">
                <img src="{{ asset('img/logo/logo_municipalidad_salsipuedes.png') }}" alt="Logo Municipalidad Salsipuedes">
                <img src="{{ asset('img/logo/logo-agua.svg') }}" alt="Logo app agua">
            </div>

            <h2 class="text-dark mb-0">Dirección de Agua</h2>
            <h4 class="text-muted">Municipalidad de Salsipuedes</h4>
        </div>

        <h2 class="page-title">Listado de Mediciones</h2>

        <!-- Botón de acción -->
        <div class="btn-container">
            <button class="btn btn-danger btn-lg" data-bs-toggle="modal" data-bs-target="#periodoModal">
                <i class="bi bi-file-earmark-text me-2"></i> Generar Reporte
            </button>
        </div>

        <!-- Paginación -->
        <div class="pagination-container">
            <nav aria-label="Page navigation">
                <ul class="pagination" id="pagination">
                </ul>
            </nav>
        </div>

        <!-- Tabla de mediciones -->
        <div class="card">
            <div class="card-body p-0">
                <div class="table-responsive">
                    <table id="medicionesTable" class="table-striped table-hover table">
                        <thead>
                            <tr>
                                <th>ID</th>
                                <th>Número de Cuenta</th>
                                <th>Ruta</th>
                                <th>Orden</th>
                                <th>Medición</th>
                                <th>Fecha</th>
                                <th>Estado</th>
                                <th>Periodo</th>
                                <th>Imagen</th>
                            </tr>
                        </thead>
                        <tbody>
                        </tbody>
                    </table>
                </div>
            </div>
        </div>
    </div>

    <!-- Modal para las imágenes -->
    <div class="modal fade" id="imagenModal" tabindex="-1" aria-labelledby="imagenModalLabel" aria-hidden="true">
        <div class="modal-dialog modal-lg modal-dialog-centered">
            <div class="modal-content">
                <div class="modal-header">
                    <h5 class="modal-title" id="imagenModalLabel">Imagen de Lectura</h5>
                    <button type="button" class="btn-close btn-close-white" data-bs-dismiss="modal"
                        aria-label="Close"></button>
                </div>
                <div class="modal-body text-center">
                    <img id="modalImagen" src="" alt="Imagen de lectura" class="img-fluid rounded"
                        style="max-height: 70vh;">
                </div>
                <div class="modal-footer">
                    <button type="button" class="btn btn-secondary" data-bs-dismiss="modal">Cerrar</button>
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
                    <button type="button" class="btn-close btn-close-white" data-bs-dismiss="modal"
                        aria-label="Close"></button>
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
                </div>
                <div class="modal-footer">
                    <button type="button" class="btn btn-secondary" data-bs-dismiss="modal">Cancelar</button>
                    <button type="submit" class="btn btn-primary">Generar Reporte</button>
                </div>
                </form>
            </div>
        </div>
    </div>
@endsection

@push('scripts')
    <script>
        document.addEventListener('DOMContentLoaded', function() {
            // Función para cargar los datos de las mediciones
            const loadMedicionesData = async (page = 1) => {
                try {
                    const response = await fetch(`{{ route('api.mediciones_index2') }}?page=${page}`);
                    const data = await response.json();

                    // Actualizar la tabla con los datos recibidos
                    updateTable(data.data);

                    // Actualizar la paginación
                    updatePagination(data);
                } catch (error) {
                    console.error('Error al cargar los datos:', error);
                }
            };

            // Función para actualizar la tabla con los datos
            const updateTable = (mediciones) => {
                const tbody = document.querySelector('#medicionesTable tbody');
                tbody.innerHTML = ''; // Limpiar la tabla antes de agregar nuevos datos

                if (mediciones.length === 0) {
                    tbody.innerHTML = `
                        <tr>
                            <td colspan="9" class="text-center py-4">No se encontraron mediciones</td>
                        </tr>
                    `;
                    return;
                }

                mediciones.forEach(medicion => {
                    const row = document.createElement('tr');

                    // Determinar la clase de estado según el valor
                    let estadoClass = '';
                    switch (medicion.estado) {
                        case 'Borroso':
                            estadoClass = 'estado-borroso';
                            break;
                        case 'Con Aire':
                            estadoClass = 'estado-con-aire';
                            break;
                        case 'En Interior':
                            estadoClass = 'estado-en-interior';
                            break;
                        case 'Roto':
                            estadoClass = 'estado-roto';
                            break;
                        case 'Sin Instalar':
                            estadoClass = 'estado-sin-instalar';
                            break;
                        case 'Sin Situacion':
                            estadoClass = 'estado-sin-situacion';
                            break;
                        case 'Tapado':
                            estadoClass = 'estado-tapado';
                            break;
                        default:
                            estadoClass = '';
                    }

                    // Crear el botón o mensaje para la imagen
                    let imagenCell = '';
                    if (medicion.url_foto) {
                        imagenCell = `
                            <button class="btn btn-info btn-sm"
                                    onclick="showImageModal('${medicion.url_foto}')">
                                Ver Imagen
                            </button>
                        `;
                    } else {
                        imagenCell = '<span class="no-image">Sin imagen</span>';
                    }

                    row.innerHTML = `
                        <td>${medicion.id}</td>
                        <td>${medicion.nro_cuenta}</td>
                        <td>${medicion.ruta}</td>
                        <td>${medicion.orden}</td>
                        <td>${medicion.medicion}</td>
                        <td>${medicion.fecha}</td>
                        <td class="${estadoClass}">${medicion.estado}</td>
                        <td>${medicion.periodo}</td>
                        <td>${imagenCell}</td>
                    `;
                    tbody.appendChild(row);
                });
            };

            // Y actualiza los onclick en la paginación para pasar el evento:
            const updatePagination = (data) => {
                const pagination = document.getElementById('pagination');
                pagination.innerHTML = ''; // Limpiar la paginación anterior

                const currentPage = data.current_page;
                const lastPage = data.last_page;

                // Botón Anterior
                const prevLi = document.createElement('li');
                prevLi.className = `page-item ${currentPage === 1 ? 'disabled' : ''}`;
                prevLi.innerHTML = `
                    <a class="page-link" href="#" aria-label="Previous" onclick="loadPage(${currentPage - 1}, event)">
                        <span aria-hidden="true">&laquo; Anterior</span>
                    </a>
                `;
                pagination.appendChild(prevLi);

                // Números de página
                for (let i = 1; i <= lastPage; i++) {
                    const pageLi = document.createElement('li');
                    pageLi.className = `page-item ${i === currentPage ? 'active' : ''}`;
                    pageLi.innerHTML = `
            <a class="page-link" href="#" onclick="loadPage(${i}, event)">${i}</a>
        `;
                    pagination.appendChild(pageLi);
                }

                // Botón Siguiente
                const nextLi = document.createElement('li');
                nextLi.className = `page-item ${currentPage === lastPage ? 'disabled' : ''}`;
                nextLi.innerHTML = `
        <a class="page-link" href="#" aria-label="Next" onclick="loadPage(${currentPage + 1}, event)">
            <span aria-hidden="true">Siguiente &raquo;</span>
        </a>
    `;
                pagination.appendChild(nextLi);
            };

            // Modifica la función loadPage para prevenir el comportamiento predeterminado
            window.loadPage = (page, event) => {
                if (event) {
                    event.preventDefault(); // Esto evita el comportamiento predeterminado del enlace
                }
                loadMedicionesData(page);
            };

            // Función global para mostrar el modal de imagen
            window.showImageModal = (imageUrl) => {
                const modalImagen = document.getElementById('modalImagen');
                modalImagen.src = imageUrl;

                // Inicializar y mostrar el modal
                const imagenModal = new bootstrap.Modal(document.getElementById('imagenModal'));
                imagenModal.show();
            };

            // Cargar los datos iniciales
            loadMedicionesData(1);
        });
    </script>
@endpush

{{-- VERSION FUNCIONAL --}}
{{-- @extends('layouts.app')

@push('estilos')
    <style>
        :root {
            --primary-color: #2c3e50;
            --secondary-color: #3498db;
            --accent-color: #e74c3c;
            --light-color: #ecf0f1;
            --dark-color: #2c3e50;
            --success-color: #27ae60;
            --warning-color: #f39c12;
            --danger-color: #e74c3c;
            --info-color: #2980b9;
        }

        body {
            font-family: 'Segoe UI', Tahoma, Geneva, Verdana, sans-serif;
            background-color: #f5f7fa;
        }

        .container-fluid {
            max-width: 1400px;
            padding: 20px;
        }

        .logo-container {
            text-align: center;
            margin-bottom: 30px;
            padding: 20px 0;
            background-color: white;
            border-radius: 10px;
            box-shadow: 0 4px 6px rgba(0, 0, 0, 0.1);
        }

        .logo-container img {
            max-width: 150px;
            height: auto;
            margin-bottom: 15px;
        }

        .page-title {
            color: var(--primary-color);
            font-weight: 600;
            margin-bottom: 30px;
            text-align: center;
            position: relative;
            padding-bottom: 15px;
        }

        .page-title:after {
            content: '';
            position: absolute;
            bottom: 0;
            left: 50%;
            transform: translateX(-50%);
            width: 100px;
            height: 3px;
            background: var(--secondary-color);
        }

        .card {
            border: none;
            border-radius: 10px;
            box-shadow: 0 4px 20px rgba(0, 0, 0, 0.08);
            margin-bottom: 30px;
            overflow: hidden;
        }

        .card-header {
            background-color: var(--primary-color);
            color: white;
            font-weight: 600;
            padding: 15px 20px;
            border-bottom: none;
        }

        .table-responsive {
            border-radius: 10px;
            overflow: hidden;
            width: 100%;
        }

        .table {
            margin-bottom: 0;
            width: 100%;
        }

        .table th {
            background-color: var(--primary-color);
            color: white;
            font-weight: 500;
            padding: 15px;
            text-align: center;
            vertical-align: middle;
            border: none;
        }

        .table td {
            padding: 12px 15px;
            vertical-align: middle;
            text-align: center;
            border-top: 1px solid #e9ecef;
        }

        .table-striped tbody tr:nth-of-type(odd) {
            background-color: rgba(236, 240, 241, 0.5);
        }

        .table-striped tbody tr:nth-of-type(even) {
            background-color: white;
        }

        .table-hover tbody tr:hover {
            background-color: rgba(52, 152, 219, 0.1);
            transition: all 0.3s ease;
        }

        /* Estado classes */
        .estado-borroso {
            color: #7f8c8d;
            font-weight: 500;
        }

        .estado-con-aire {
            color: #3498db;
            font-weight: 500;
        }

        .estado-en-interior {
            color: var(--success-color);
            font-weight: 500;
        }

        .estado-roto {
            color: var(--danger-color);
            font-weight: 500;
        }

        .estado-sin-instalar {
            color: var(--warning-color);
            font-weight: 500;
        }

        .estado-sin-situacion {
            color: #9b59b6;
            font-weight: 500;
        }

        .estado-tapado {
            color: var(--dark-color);
            font-weight: 500;
        }

        /* Botones */
        .btn-container {
            display: flex;
            justify-content: center;
            margin-bottom: 30px;
        }

        .btn {
            border-radius: 50px;
            padding: 10px 25px;
            font-weight: 600;
            letter-spacing: 0.5px;
            transition: all 0.3s ease;
            box-shadow: 0 2px 5px rgba(0, 0, 0, 0.1);
            border: none;
            margin: 0 10px;
        }

        .btn-lg {
            padding: 12px 30px;
            font-size: 1.1rem;
        }

        .btn-primary {
            background-color: var(--secondary-color);
        }

        .btn-primary:hover {
            background-color: #2980b9;
            transform: translateY(-2px);
            box-shadow: 0 4px 8px rgba(0, 0, 0, 0.15);
        }

        .btn-danger {
            background-color: var(--danger-color);
        }

        .btn-danger:hover {
            background-color: #c0392b;
            transform: translateY(-2px);
            box-shadow: 0 4px 8px rgba(0, 0, 0, 0.15);
        }

        .btn-info {
            background-color: var(--info-color);
        }

        .btn-secondary {
            background-color: #95a5a6;
        }

        /* Paginación */
        .pagination-container {
            display: flex;
            justify-content: center;
            margin: 30px 0;
        }

        .pagination {
            display: flex;
            gap: 5px;
            flex-wrap: wrap;
        }

        .page-item {
            margin: 0;
        }

        .page-link {
            color: var(--primary-color);
            border: 1px solid #ddd;
            border-radius: 5px !important;
            padding: 8px 15px;
            font-weight: 500;
            transition: all 0.3s ease;
        }

        .page-link:hover {
            background-color: var(--secondary-color);
            color: white;
            border-color: var(--secondary-color);
        }

        .page-item.active .page-link {
            background-color: var(--secondary-color);
            border-color: var(--secondary-color);
            color: white;
        }

        .page-item.disabled .page-link {
            color: #95a5a6;
            pointer-events: none;
            cursor: not-allowed;
            background-color: #ecf0f1;
            border-color: #ddd;
        }

        /* Modal */
        .modal-content {
            border: none;
            border-radius: 10px;
            overflow: hidden;
            box-shadow: 0 5px 20px rgba(0, 0, 0, 0.2);
        }

        .modal-header {
            background-color: var(--primary-color);
            color: white;
            border-bottom: none;
            padding: 15px 20px;
        }

        .modal-title {
            font-weight: 600;
        }

        .modal-body {
            padding: 20px;
        }

        .modal-footer {
            border-top: none;
            padding: 15px 20px;
            background-color: #f8f9fa;
        }

        /* Formularios */
        .form-select,
        .form-control {
            border-radius: 5px;
            padding: 10px 15px;
            border: 1px solid #ddd;
            transition: all 0.3s ease;
        }

        .form-select:focus,
        .form-control:focus {
            border-color: var(--secondary-color);
            box-shadow: 0 0 0 0.25rem rgba(52, 152, 219, 0.25);
        }

        /* Imágenes en tabla */
        .img-thumbnail {
            border-radius: 5px;
            cursor: pointer;
            transition: all 0.3s ease;
            border: 1px solid #ddd;
            padding: 2px;
            max-width: 50px;
            height: auto;
        }

        .img-thumbnail:hover {
            transform: scale(1.05);
            box-shadow: 0 4px 8px rgba(0, 0, 0, 0.1);
        }

        .no-image {
            color: #95a5a6;
            font-style: italic;
        }

        /* Responsive */
        @media (max-width: 768px) {
            .btn-container {
                flex-direction: column;
                gap: 10px;
            }

            .btn {
                width: 100%;
                margin: 5px 0;
            }

            .table-responsive {
                overflow-x: auto;
            }

            .pagination {
                gap: 3px;
            }

            .page-link {
                padding: 6px 10px;
                font-size: 0.9rem;
            }
        }
    </style>
@endpush

@section('contenido')
    <div class="container-fluid mt-4">
        <!-- Título y Logo -->
        <div class="logo-container">
            <img src="{{ asset('img/logo/logo_municipalidad_salsipuedes.png') }}" alt="Logo Municipalidad Salsipuedes">
            <h2 class="text-dark mb-0">Dirección de Agua</h2>
            <h4 class="text-muted">Municipalidad de Salsipuedes</h4>
        </div>

        <h2 class="page-title">Listado de Mediciones</h2>

        <!-- Botón de acción -->
        <div class="btn-container">
            <button class="btn btn-danger btn-lg" data-bs-toggle="modal" data-bs-target="#periodoModal">
                <i class="bi bi-file-earmark-text me-2"></i> Generar Reporte
            </button>
        </div>

        <!-- Paginación -->
        <div class="pagination-container">
            <nav aria-label="Page navigation">
                <ul class="pagination" id="pagination">
                </ul>
            </nav>
        </div>

        <!-- Tabla de mediciones -->
        <div class="card">
            <div class="card-body p-0">
                <div class="table-responsive">
                    <table id="medicionesTable" class="table-striped table-hover table">
                        <thead>
                            <tr>
                                <th>ID</th>
                                <th>Número de Cuenta</th>
                                <th>Ruta</th>
                                <th>Orden</th>
                                <th>Medición</th>
                                <th>Fecha</th>
                                <th>Estado</th>
                                <th>Periodo</th>
                                <th>Imagen</th>
                            </tr>
                        </thead>

                        <tbody>
                        </tbody>
                    </table>
                </div>
            </div>
        </div>
    </div>

    <!-- Modal para las imágenes -->
    <div class="modal fade" id="imagenModal" tabindex="-1" aria-labelledby="imagenModalLabel" aria-hidden="true">
        <div class="modal-dialog modal-lg modal-dialog-centered">
            <div class="modal-content">
                <div class="modal-header">
                    <h5 class="modal-title" id="imagenModalLabel">Imagen de Lectura</h5>
                    <button type="button" class="btn-close btn-close-white" data-bs-dismiss="modal"
                        aria-label="Close"></button>
                </div>
                <div class="modal-body text-center">
                    <img id="modalImagen" src="" alt="Imagen de lectura" class="img-fluid rounded"
                        style="max-height: 70vh;">
                </div>
                <div class="modal-footer">
                    <button type="button" class="btn btn-secondary" data-bs-dismiss="modal">Cerrar</button>
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
                    <button type="button" class="btn-close btn-close-white" data-bs-dismiss="modal"
                        aria-label="Close"></button>
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
                </div>
                <div class="modal-footer">
                    <button type="button" class="btn btn-secondary" data-bs-dismiss="modal">Cancelar</button>
                    <button type="submit" class="btn btn-primary">Generar Reporte</button>
                </div>
                </form>
            </div>
        </div>
    </div>
@endsection

@push('scripts')
    <script>
        document.addEventListener('DOMContentLoaded', function() {
            // Función para cargar los datos de las mediciones
            const loadMedicionesData = async (page = 1) => {
                try {
                    const response = await fetch(`{{ route('api.mediciones_index2') }}?page=${page}`);
                    const data = await response.json();

                    // Actualizar la tabla con los datos recibidos
                    updateTable(data.data);

                    // Actualizar la paginación
                    updatePagination(data);
                } catch (error) {
                    console.error('Error al cargar los datos:', error);
                }
            };

            // Función para actualizar la tabla con los datos
            const updateTable = (mediciones) => {
                const tbody = document.querySelector('#medicionesTable tbody');
                tbody.innerHTML = ''; // Limpiar la tabla antes de agregar nuevos datos

                if (mediciones.length === 0) {
                    tbody.innerHTML = `
                        <tr>
                            <td colspan="9" class="text-center py-4">No se encontraron mediciones</td>
                        </tr>
                    `;
                    return;
                }

                mediciones.forEach(medicion => {
                    const row = document.createElement('tr');

                    // Determinar la clase de estado según el valor
                    let estadoClass = '';
                    switch (medicion.estado) {
                        case 'Borroso':
                            estadoClass = 'estado-borroso';
                            break;
                        case 'Con Aire':
                            estadoClass = 'estado-con-aire';
                            break;
                        case 'En Interior':
                            estadoClass = 'estado-en-interior';
                            break;
                        case 'Roto':
                            estadoClass = 'estado-roto';
                            break;
                        case 'Sin Instalar':
                            estadoClass = 'estado-sin-instalar';
                            break;
                        case 'Sin Situacion':
                            estadoClass = 'estado-sin-situacion';
                            break;
                        case 'Tapado':
                            estadoClass = 'estado-tapado';
                            break;
                        default:
                            estadoClass = '';
                    }

                    // Crear el botón o mensaje para la imagen
                    let imagenCell = '';
                    if (medicion.url_foto) {
                        imagenCell = `
                            <button class="btn btn-info btn-sm"
                                    onclick="showImageModal('${medicion.url_foto}')">
                                Ver Imagen
                            </button>
                        `;
                    } else {
                        imagenCell = '<span class="no-image">Sin imagen</span>';
                    }

                    row.innerHTML = `
                        <td>${medicion.id}</td>
                        <td>${medicion.nro_cuenta}</td>
                        <td>${medicion.ruta}</td>
                        <td>${medicion.orden}</td>
                        <td>${medicion.medicion}</td>
                        <td>${medicion.fecha}</td>
                        <td class="${estadoClass}">${medicion.estado}</td>
                        <td>${medicion.periodo}</td>
                        <td>${imagenCell}</td>
                    `;
                    tbody.appendChild(row);
                });
            };

            // Función para actualizar la paginación
            const updatePagination = (data) => {
                const pagination = document.getElementById('pagination');
                pagination.innerHTML = ''; // Limpiar la paginación anterior

                const currentPage = data.current_page;
                const lastPage = data.last_page;

                // Botón Anterior - siempre visible pero deshabilitado si es la primera página
                const prevLi = document.createElement('li');
                prevLi.className = `page-item ${currentPage === 1 ? 'disabled' : ''}`;
                prevLi.innerHTML = `
                    <a class="page-link" href="#" aria-label="Previous" onclick="loadPage(${currentPage - 1})">
                        <span aria-hidden="true">&laquo; Anterior</span>
                    </a>
                `;
                pagination.appendChild(prevLi);

                // Números de página
                for (let i = 1; i <= lastPage; i++) {
                    const pageLi = document.createElement('li');
                    pageLi.className = `page-item ${i === currentPage ? 'active' : ''}`;
                    pageLi.innerHTML = `
                        <a class="page-link" href="#" onclick="loadPage(${i})">${i}</a>
                    `;
                    pagination.appendChild(pageLi);
                }

                // Botón Siguiente - siempre visible pero deshabilitado si es la última página
                const nextLi = document.createElement('li');
                nextLi.className = `page-item ${currentPage === lastPage ? 'disabled' : ''}`;
                nextLi.innerHTML = `
                    <a class="page-link" href="#" aria-label="Next" onclick="loadPage(${currentPage + 1})">
                        <span aria-hidden="true">Siguiente &raquo;</span>
                    </a>
                `;
                pagination.appendChild(nextLi);
            };

            // Función global para cargar una página específica
            window.loadPage = (page) => {
                loadMedicionesData(page);
                // Desplazarse suavemente hacia arriba
                window.scrollTo({
                    top: 0,
                    behavior: 'smooth'
                });
            };

            // Función global para mostrar el modal de imagen
            window.showImageModal = (imageUrl) => {
                const modalImagen = document.getElementById('modalImagen');
                modalImagen.src = imageUrl;

                // Inicializar y mostrar el modal
                const imagenModal = new bootstrap.Modal(document.getElementById('imagenModal'));
                imagenModal.show();
            };

            // Cargar los datos iniciales
            loadMedicionesData(1);
        });
    </script>
@endpush --}}

{{-- VERSION ANTERIOR --}}
{{-- @extends('layouts.app')

@push('estilos')
    <style>
        :root {
            --primary-color: #2c3e50;
            --secondary-color: #3498db;
            --accent-color: #e74c3c;
            --light-color: #ecf0f1;
            --dark-color: #2c3e50;
            --success-color: #27ae60;
            --warning-color: #f39c12;
            --danger-color: #e74c3c;
            --info-color: #2980b9;
        }

        body {
            font-family: 'Segoe UI', Tahoma, Geneva, Verdana, sans-serif;
            background-color: #f5f7fa;
        }

        .container-fluid {
            max-width: 1400px;
            padding: 20px;
        }

        .logo-container {
            text-align: center;
            margin-bottom: 30px;
            padding: 20px 0;
            background-color: white;
            border-radius: 10px;
            box-shadow: 0 4px 6px rgba(0, 0, 0, 0.1);
        }

        .logo-container img {
            max-width: 150px;
            height: auto;
            margin-bottom: 15px;
        }

        .page-title {
            color: var(--primary-color);
            font-weight: 600;
            margin-bottom: 30px;
            text-align: center;
            position: relative;
            padding-bottom: 15px;
        }

        .page-title:after {
            content: '';
            position: absolute;
            bottom: 0;
            left: 50%;
            transform: translateX(-50%);
            width: 100px;
            height: 3px;
            background: var(--secondary-color);
        }

        .card {
            border: none;
            border-radius: 10px;
            box-shadow: 0 4px 20px rgba(0, 0, 0, 0.08);
            margin-bottom: 30px;
            overflow: hidden;
        }

        .card-header {
            background-color: var(--primary-color);
            color: white;
            font-weight: 600;
            padding: 15px 20px;
            border-bottom: none;
        }

        .table-responsive {
            border-radius: 10px;
            overflow: hidden;
        }

        .table {
            margin-bottom: 0;
        }

        .table th {
            background-color: var(--primary-color);
            color: white;
            font-weight: 500;
            padding: 15px;
            text-align: center;
            vertical-align: middle;
            border: none;
        }

        .table td {
            padding: 12px 15px;
            vertical-align: middle;
            text-align: center;
            border-top: 1px solid #e9ecef;
        }

        .table-striped tbody tr:nth-of-type(odd) {
            background-color: rgba(236, 240, 241, 0.5);
        }

        .table-striped tbody tr:nth-of-type(even) {
            background-color: white;
        }

        .table-hover tbody tr:hover {
            background-color: rgba(52, 152, 219, 0.1);
            transition: all 0.3s ease;
        }

        /* Estado classes */
        .estado-borroso {
            color: #7f8c8d;
            font-weight: 500;
        }

        .estado-con-aire {
            color: #3498db;
            font-weight: 500;
        }

        .estado-en-interior {
            color: var(--success-color);
            font-weight: 500;
        }

        .estado-roto {
            color: var(--danger-color);
            font-weight: 500;
        }

        .estado-sin-instalar {
            color: var(--warning-color);
            font-weight: 500;
        }

        .estado-sin-situacion {
            color: #9b59b6;
            font-weight: 500;
        }

        .estado-tapado {
            color: var(--dark-color);
            font-weight: 500;
        }

        /* Botones */
        .btn-container {
            display: flex;
            justify-content: center;
            margin-bottom: 30px;
        }

        .btn {
            border-radius: 50px;
            padding: 10px 25px;
            font-weight: 600;
            letter-spacing: 0.5px;
            transition: all 0.3s ease;
            box-shadow: 0 2px 5px rgba(0, 0, 0, 0.1);
            border: none;
            margin: 0 10px;
        }

        .btn-lg {
            padding: 12px 30px;
            font-size: 1.1rem;
        }

        .btn-primary {
            background-color: var(--secondary-color);
        }

        .btn-primary:hover {
            background-color: #2980b9;
            transform: translateY(-2px);
            box-shadow: 0 4px 8px rgba(0, 0, 0, 0.15);
        }

        .btn-danger {
            background-color: var(--danger-color);
        }

        .btn-danger:hover {
            background-color: #c0392b;
            transform: translateY(-2px);
            box-shadow: 0 4px 8px rgba(0, 0, 0, 0.15);
        }

        .btn-info {
            background-color: var(--info-color);
        }

        /* Paginación */
        .pagination-container {
            display: flex;
            justify-content: center;
            margin-top: 30px;
        }

        .pagination {
            display: flex;
            gap: 5px;
        }

        .page-item {
            margin: 0;
        }

        .page-link {
            color: var(--primary-color);
            border: 1px solid #ddd;
            border-radius: 5px !important;
            padding: 8px 15px;
            font-weight: 500;
            transition: all 0.3s ease;
        }

        .page-link:hover {
            background-color: var(--secondary-color);
            color: white;
            border-color: var(--secondary-color);
        }

        .page-item.active .page-link {
            background-color: var(--secondary-color);
            border-color: var(--secondary-color);
            color: white;
        }

        .page-item.disabled .page-link {
            color: #95a5a6;
            pointer-events: none;
            cursor: not-allowed;
            background-color: #ecf0f1;
            border-color: #ddd;
        }

        /* Modal */
        .modal-content {
            border: none;
            border-radius: 10px;
            overflow: hidden;
            box-shadow: 0 5px 20px rgba(0, 0, 0, 0.2);
        }

        .modal-header {
            background-color: var(--primary-color);
            color: white;
            border-bottom: none;
            padding: 15px 20px;
        }

        .modal-title {
            font-weight: 600;
        }

        .modal-body {
            padding: 20px;
        }

        .modal-footer {
            border-top: none;
            padding: 15px 20px;
            background-color: #f8f9fa;
        }

        /* Formularios */
        .form-select,
        .form-control {
            border-radius: 5px;
            padding: 10px 15px;
            border: 1px solid #ddd;
            transition: all 0.3s ease;
        }

        .form-select:focus,
        .form-control:focus {
            border-color: var(--secondary-color);
            box-shadow: 0 0 0 0.25rem rgba(52, 152, 219, 0.25);
        }

        /* Imágenes en tabla */
        .img-thumbnail {
            border-radius: 5px;
            cursor: pointer;
            transition: all 0.3s ease;
            border: 1px solid #ddd;
            padding: 2px;
            max-width: 50px;
            height: auto;
        }

        .img-thumbnail:hover {
            transform: scale(1.05);
            box-shadow: 0 4px 8px rgba(0, 0, 0, 0.1);
        }

        /* Responsive */
        @media (max-width: 768px) {
            .btn-container {
                flex-direction: column;
                gap: 10px;
            }

            .btn {
                width: 100%;
                margin: 5px 0;
            }

            .table-responsive {
                overflow-x: auto;
            }
        }
    </style>
@endpush

@section('contenido')
    <div class="container-fluid mt-4">
        <!-- Título y Logo -->
        <div class="logo-container">
            <img src="{{ asset('img/logo/logo_municipalidad_salsipuedes.png') }}" alt="Logo Municipalidad Salsipuedes">
            <h2 class="text-dark mb-0">Dirección de Agua</h2>
            <h4 class="text-muted">Municipalidad de Salsipuedes</h4>
        </div>

        <h2 class="page-title">Listado de Mediciones</h2>

        <!-- Botón de acción -->
        <div class="btn-container">
            <button class="btn btn-danger btn-lg" data-bs-toggle="modal" data-bs-target="#periodoModal">
                <i class="bi bi-file-earmark-text me-2"></i> Generar Reporte
            </button>
        </div>

        <!-- Paginación -->
        <div class="pagination-container">
            <nav aria-label="Page navigation">
                <ul class="pagination">
                    <li class="page-item" id="prevPage">
                        <a class="page-link" href="#" aria-label="Previous">
                            <span aria-hidden="true">&laquo; Anterior</span>
                        </a>
                    </li>
                    <li class="page-item" id="nextPage">
                        <a class="page-link" href="#" aria-label="Next">
                            <span aria-hidden="true">Siguiente &raquo;</span>
                        </a>
                    </li>
                </ul>
            </nav>
        </div>

        <!-- Tabla de mediciones -->
        <div class="card">
            <div class="card-body p-0">
                <div class="table-responsive">
                    <table id="medicionesTable" class="table-striped table-hover table">
                        <thead>
                            <tr>
                                <th>ID</th>
                                <th>Número de Cuenta</th>
                                <th>Ruta</th>
                                <th>Orden</th>
                                <th>Medición</th>
                                <th>Fecha</th>
                                <th>Estado</th>
                                <th>Periodo</th>
                                <th>Imagen</th>
                            </tr>
                        </thead>
                        <tbody>
                            <!-- Filas se generarán dinámicamente aquí -->
                        </tbody>
                    </table>
                </div>
            </div>
        </div>
    </div>

    <!-- Modal para las imágenes -->
    <div class="modal fade" id="imagenModal" tabindex="-1" aria-labelledby="imagenModalLabel" aria-hidden="true">
        <div class="modal-dialog modal-lg modal-dialog-centered">
            <div class="modal-content">
                <div class="modal-header">
                    <h5 class="modal-title" id="imagenModalLabel">Imagen de Lectura</h5>
                    <button type="button" class="btn-close btn-close-white" data-bs-dismiss="modal"
                        aria-label="Close"></button>
                </div>
                <div class="modal-body text-center">
                    <img id="modalImagen" src="" alt="Imagen de lectura" class="img-fluid rounded"
                        style="max-height: 70vh;">
                </div>
                <div class="modal-footer">
                    <button type="button" class="btn btn-secondary" data-bs-dismiss="modal">Cerrar</button>
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
                    <button type="button" class="btn-close btn-close-white" data-bs-dismiss="modal"
                        aria-label="Close"></button>
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
                </div>
                <div class="modal-footer">
                    <button type="button" class="btn btn-secondary" data-bs-dismiss="modal">Cancelar</button>
                    <button type="submit" class="btn btn-primary">Generar Reporte</button>
                </div>
                </form>
            </div>
        </div>
    </div>
@endsection

@push('scripts')
    <script>
        document.addEventListener('DOMContentLoaded', function() {
            const perPage = 10; // Número de resultados por página
            let currentPage = 1; // Página inicial

            // Cargar los datos
            const loadMedicionesData = async (page = 1) => {
                const response = await fetch(`{{ route('api.mediciones_index2') }}?page=${page}`);
                const data = await response.json();

                const tbody = document.querySelector('#medicionesTable tbody');
                tbody.innerHTML = ''; // Limpiar la tabla antes de agregar nuevos datos

                data.data.forEach(medicion => {
                    const row = document.createElement('tr');
                    row.innerHTML = `
                    <td>${medicion.id}</td>
                    <td>${medicion.nro_cuenta}</td>
                    <td>${medicion.ruta}</td>
                    <td>${medicion.orden}</td>
                    <td>${medicion.medicion}</td>
                    <td>${medicion.fecha}</td>
                    <td>${medicion.estado}</td>
                    <td>${medicion.periodo}</td>
                    <td>
                        <button class="btn btn-info btn-sm" onclick="showImageModal('${medicion.url_foto}')">Ver Imagen</button>
                    </td>
                `;
                    tbody.appendChild(row);
                });

                // Cargar la paginación
                loadPagination(data);
            };

            // Mostrar la paginación
            const loadPagination = (data) => {
                const pagination = document.getElementById('pagination');
                pagination.innerHTML = ''; // Limpiar los botones de paginación previos

                const totalPages = data.last_page;
                const currentPage = data.current_page;

                // Botón "Anterior"
                if (currentPage > 1) {
                    const prevButton = document.createElement('button');
                    prevButton.className = 'btn btn-secondary btn-sm';
                    prevButton.textContent = 'Anterior';
                    prevButton.onclick = () => loadMedicionesData(currentPage - 1);
                    pagination.appendChild(prevButton);
                }

                // Botones de páginas
                for (let page = 1; page <= totalPages; page++) {
                    const pageButton = document.createElement('button');
                    pageButton.className = `btn btn-sm ${page === currentPage ? 'btn-primary' : 'btn-light'}`;
                    pageButton.textContent = page;
                    pageButton.onclick = () => loadMedicionesData(page);
                    pagination.appendChild(pageButton);
                }

                // Botón "Siguiente"
                if (currentPage < totalPages) {
                    const nextButton = document.createElement('button');
                    nextButton.className = 'btn btn-secondary btn-sm';
                    nextButton.textContent = 'Siguiente';
                    nextButton.onclick = () => loadMedicionesData(currentPage + 1);
                    pagination.appendChild(nextButton);
                }
            };

            // Cargar los datos de la primera página al cargar la vista
            loadMedicionesData(currentPage);

            // Mostrar la imagen en un modal
            window.showImageModal = (url) => {
                const modalBody = document.getElementById('modalImagenBody');
                modalBody.innerHTML = `<img src="${url}" class="img-fluid" alt="Imagen de Lectura">`;

                // Asegúrate de inicializar el modal correctamente
                const myModal = new bootstrap.Modal(document.getElementById('imagenModal'));
                myModal.show(); // Esto debería abrir el modal
            };
        });
    </script>
@endpush --}}
