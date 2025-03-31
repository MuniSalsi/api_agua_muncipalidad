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

        .filters .input-group-sm .form-control,
        .filters .input-group-sm .btn {
            height: calc(1.5em + 0.5rem + 2px);
            padding: 0.25rem 0.5rem;
            font-size: 0.875rem;
        }

        .filters label {
            font-weight: 500;
            color: #495057;
        }

        /* Estilos para el selector de items por página */
        #perPage {
            appearance: none;
            -webkit-appearance: none;
            -moz-appearance: none;
            background-image: url("data:image/svg+xml,%3Csvg xmlns='http://www.w3.org/2000/svg' width='16' height='16' fill='%232c3e50' viewBox='0 0 16 16'%3E%3Cpath d='M7.247 11.14 2.451 5.658C1.885 5.013 2.345 4 3.204 4h9.592a1 1 0 0 1 .753 1.659l-4.796 5.48a1 1 0 0 1-1.506 0z'/%3E%3C/svg%3E");
            background-repeat: no-repeat;
            background-position: right 0.75rem center;
            background-size: 16px 12px;
            padding-right: 2rem;
            cursor: pointer;
            transition: all 0.3s ease;
            border: 1px solid #ced4da;
            box-shadow: 0 1px 2px rgba(0, 0, 0, 0.05);
        }

        #perPage:focus {
            border-color: var(--secondary-color);
            box-shadow: 0 0 0 0.25rem rgba(52, 152, 219, 0.25);
            outline: none;
        }

        /* Estilo para la opción "Todos" */
        #perPage option[value="all"] {
            font-weight: 600;
            color: var(--primary-color);
            background-color: rgba(52, 152, 219, 0.1);
        }

        /* Contenedor del selector con ícono */
        .per-page-container {
            position: relative;
        }

        .per-page-container::before {
            content: "\f123";
            font-family: "bootstrap-icons";
            position: absolute;
            right: 10px;
            top: 50%;
            transform: translateY(-50%);
            pointer-events: none;
            color: var(--primary-color);
        }

        /* Estilo para el mensaje cuando se selecciona "Todos" */
        .pagination-info {
            background-color: rgba(52, 152, 219, 0.1);
            border-radius: 5px;
            padding: 10px 15px;
            color: var(--primary-color);
            font-weight: 500;
            border-left: 3px solid var(--secondary-color);
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

            .filters .d-flex {
                gap: 1rem !important;
            }

            .filters>div>div {
                width: 100% !important;
            }

            #perPage {
                width: 100% !important;
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
        <!-- Spinner de carga -->
        <div id="loadingSpinner" class="my-3 text-center" style="display: none;">
            <div class="spinner-border text-primary" role="status">
                <span class="visually-hidden">Cargando...</span>
            </div>
            <p>Cargando datos...</p>
        </div>

        <!-- Mensaje de error -->
        <div id="errorAlert" class="alert alert-danger" style="display: none;"></div>

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

        <!-- Botón de generar reporte -->
        <div class="btn-container d-flex justify-content-center mb-5">
            <button class="btn btn-danger btn-lg" data-bs-toggle="modal" data-bs-target="#periodoModal">
                <i class="bi bi-file-earmark-text me-2"></i> Generar Reporte
            </button>

        </div>

        <!-- Filtros -->
        <div class="filters mb-4">
            <div class="d-flex align-items-end justify-content-center flex-wrap gap-3">
                <!-- Items por página -->
                <div style="width: 180px">
                    <label for="perPage" class="form-label small mb-1">Items por página</label>
                    <select name="per_page" id="perPage" class="form-select form-select-sm">
                        <option value="10">10</option>
                        <option value="20">20</option>
                        <option value="50">50</option>
                        <option value="100">100</option>
                        <option value="all">Todos</option>
                    </select>
                </div>
                <!-- Fecha - Más compacto -->
                <div style="width: 180px">
                    <label for="date" class="form-label small mb-1">Fecha</label>
                    <input type="date" placeholder="Seleccione una fecha" name="date" id="date"
                        class="form-control form-control-sm">
                </div>

                <!-- Estado -->
                <div style="width: 200px">
                    <label for="estado" class="form-label small mb-1">Estados</label>
                    <select name="estado" id="estado" class="form-select form-select-sm">
                        <option value="">Todos los estados</option>
                        @foreach ($estados as $estado)
                            <option value="{{ $estado->id }}">{{ $estado->tipo }}</option>
                        @endforeach
                    </select>
                </div>

                <!-- Búsqueda - Más compacto -->
                <div style="width: 300px">
                    <label for="searchInput" class="form-label small mb-1">Búsqueda</label>
                    <div class="input-group input-group-sm">
                        <input type="text" id="searchInput" class="form-control"
                            placeholder="Buscar por nro cuenta, ruta, orden..." autocomplete="off">
                        <button class="btn btn-outline-secondary" type="button">
                            <i class="bi bi-search"></i>
                        </button>
                    </div>
                </div>

                <!-- Botón Reset al final -->
                <div class="mb-1">
                    <button id="resetFilters" class="btn btn-secondary btn-sm">
                        <i class="bi bi-arrow-counterclockwise me-1"></i> Resetear
                    </button>
                </div>
            </div>
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
                            <!-- Los datos se cargarán dinámicamente via JavaScript -->
                        </tbody>
                    </table>
                </div>
            </div>
        </div>

        <!-- Paginación -->
        <div class="pagination-container mt-3">
            <nav aria-label="Page navigation">
                <ul class="pagination justify-content-center" id="pagination">
                    <!-- La paginación se generará dinámicamente via JavaScript -->
                </ul>
            </nav>
        </div>
    </div>

    <!-- Modal para las imágenes -->
    <div class="modal fade" id="imagenModal" tabindex="-1" aria-labelledby="imagenModalLabel" aria-hidden="true">
        <div class="modal-dialog modal-lg modal-dialog-centered">
            <div class="modal-content">
                <div class="modal-header">
                    <h5 class="modal-title" id="imagenModalLabel">Imagen de Lectura</h5>
                    <button type="button" class="btn-close" data-bs-dismiss="modal" aria-label="Close"></button>
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
                                @php
                                    $currentYear = date('Y');
                                    for ($year = $currentYear + 10; $year >= $currentYear - 10; $year--) {
                                        $selected = $year == $currentYear ? 'selected' : '';
                                        echo "<option value='" . substr($year, -2) . "' $selected>$year</option>";
                                    }
                                @endphp
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
        // Mover loadMedicionesData al ámbito global
        let loadMedicionesData;

        // Funciones globales que necesitan ser accesibles
        function performSearch() {
            const searchTerm = document.getElementById('searchInput').value.trim();
            const fecha = document.getElementById('date').value;
            const estadoId = document.getElementById('estado').value;
            loadMedicionesData(1, searchTerm, fecha, estadoId);
        }

        function handlePaginationClick(page, event, searchTerm, fecha, estadoId) {
            if (event) event.preventDefault();
            loadMedicionesData(page, searchTerm, fecha, estadoId);
            window.scrollTo({
                top: 0,
                behavior: 'smooth'
            });
        }

        function showImageModal(imageUrl) {
            const modalImagen = document.getElementById('modalImagen');
            modalImagen.src = imageUrl;
            const imagenModal = new bootstrap.Modal(document.getElementById('imagenModal'));
            imagenModal.show();
        }

        document.addEventListener('DOMContentLoaded', function() {
            // Elementos del DOM
            const searchInput = document.getElementById('searchInput');
            const dateFilter = document.getElementById('date');
            const estadoFilter = document.getElementById('estado');
            const perPageFilter = document.getElementById('perPage');
            let searchTimeout;

            // Event listeners
            searchInput.addEventListener('input', function() {
                clearTimeout(searchTimeout);
                searchTimeout = setTimeout(performSearch, 300);
            });

            dateFilter.addEventListener('change', performSearch);
            estadoFilter.addEventListener('change', performSearch);
            perPageFilter.addEventListener('change', performSearch);

            // Definición de loadMedicionesData (ahora accesible globalmente)
            loadMedicionesData = async function(page = 1, searchTerm = '', fecha = '', estadoId = '') {
                try {
                    toggleLoading(true);

                    let url = new URL(`{{ route('api.mediciones_index2') }}`);
                    url.searchParams.append('page', page);

                    // Agregar parámetro per_page
                    const perPage = document.getElementById('perPage').value;
                    url.searchParams.append('per_page', perPage);

                    if (searchTerm) url.searchParams.append('search', searchTerm);
                    if (fecha) url.searchParams.append('date', fecha);
                    if (estadoId) url.searchParams.append('estado', estadoId);

                    const response = await fetch(url);
                    if (!response.ok) throw new Error('Error en la respuesta del servidor');

                    const data = await response.json();
                    updateTable(data.data);
                    updatePagination(data, searchTerm, fecha, estadoId);
                } catch (error) {
                    console.error('Error:', error);
                    showError('Error al cargar datos. Intente nuevamente.');
                } finally {
                    toggleLoading(false);
                }
            };

            const updateTable = (mediciones) => {
                const tbody = document.querySelector('#medicionesTable tbody');
                tbody.innerHTML = '';

                if (!mediciones || mediciones.length === 0) {
                    tbody.innerHTML =
                        `<tr><td colspan="9" class="text-center py-4">No se encontraron mediciones</td></tr>`;
                    return;
                }

                mediciones.forEach(medicion => {
                    const estadoClass = getEstadoClass(medicion.estado);
                    const imagenCell = medicion.url_foto ?
                        `<button class="btn btn-info btn-sm" onclick="showImageModal('${medicion.url_foto}')">Ver Imagen</button>` :
                        '<span class="no-image">Sin imagen</span>';

                    const row = `
                    <tr>
                        <td>${medicion.id}</td>
                        <td>${medicion.nro_cuenta}</td>
                        <td>${medicion.ruta}</td>
                        <td>${medicion.orden}</td>
                        <td>${medicion.medicion}</td>
                        <td>${medicion.fecha}</td>
                        <td class="${estadoClass}">${medicion.estado}</td>
                        <td>${medicion.periodo}</td>
                        <td>${imagenCell}</td>
                    </tr>
                `;
                    tbody.insertAdjacentHTML('beforeend', row);
                });
            };

            function getEstadoClass(estado) {
                const clases = {
                    'Borroso': 'estado-borroso',
                    'Con Aire': 'estado-con-aire',
                    'En Interior': 'estado-en-interior',
                    'Roto': 'estado-roto',
                    'Sin Instalar': 'estado-sin-instalar',
                    'Sin Situacion': 'estado-sin-situacion',
                    'Tapado': 'estado-tapado'
                };
                return clases[estado] || '';
            }

            const updatePagination = (data, searchTerm, fecha, estadoId) => {
                const pagination = document.getElementById('pagination');
                if (!pagination) return;

                pagination.innerHTML = '';

                // Si per_page es "all", mostrar mensaje en lugar de paginación
                const perPage = document.getElementById('perPage').value;
                if (perPage === 'all') {
                    const info = document.createElement('div');
                    info.className = 'text-center text-muted small';
                    info.textContent = `Mostrando ${data.total} resultados`;
                    pagination.appendChild(info);
                    return;
                }

                const {
                    current_page,
                    last_page
                } = data;

                if (current_page > 1) {
                    pagination.appendChild(createPageItem(
                        '&laquo; Anterior',
                        current_page - 1,
                        searchTerm,
                        fecha,
                        estadoId,
                        false,
                        'Previous'
                    ));
                }

                const start = Math.max(1, current_page - 2);
                const end = Math.min(last_page, current_page + 2);

                if (start > 1) {
                    pagination.appendChild(createPageItem(1, 1, searchTerm, fecha, estadoId));
                    if (start > 2) {
                        pagination.appendChild(createEllipsis());
                    }
                }

                for (let i = start; i <= end; i++) {
                    pagination.appendChild(createPageItem(
                        i,
                        i,
                        searchTerm,
                        fecha,
                        estadoId,
                        i === current_page
                    ));
                }

                if (end < last_page) {
                    if (end < last_page - 1) {
                        pagination.appendChild(createEllipsis());
                    }
                    pagination.appendChild(createPageItem(last_page, last_page, searchTerm, fecha, estadoId));
                }

                if (current_page < last_page) {
                    pagination.appendChild(createPageItem(
                        'Siguiente &raquo;',
                        current_page + 1,
                        searchTerm,
                        fecha,
                        estadoId,
                        false,
                        'Next'
                    ));
                }
            };

            function createPageItem(text, page, searchTerm, fecha, estadoId, isActive = false, ariaLabel = null) {
                const li = document.createElement('li');
                li.className = `page-item ${isActive ? 'active' : ''}`;

                const a = document.createElement('a');
                a.className = 'page-link';
                a.href = '#';
                a.innerHTML = text;
                if (ariaLabel) a.setAttribute('aria-label', ariaLabel);
                a.onclick = (e) => {
                    e.preventDefault();
                    loadMedicionesData(page, searchTerm, fecha, estadoId);
                };

                li.appendChild(a);
                return li;
            }

            function createEllipsis() {
                const li = document.createElement('li');
                li.className = 'page-item disabled';
                li.innerHTML = '<span class="page-link">...</span>';
                return li;
            }

            function toggleLoading(show) {
                const spinner = document.getElementById('loadingSpinner');
                if (spinner) spinner.style.display = show ? 'block' : 'none';
            }

            function showError(message) {
                const errorAlert = document.getElementById('errorAlert');
                if (errorAlert) {
                    errorAlert.textContent = message;
                    errorAlert.style.display = 'block';
                    setTimeout(() => errorAlert.style.display = 'none', 5000);
                }
            }

            document.getElementById('resetFilters')?.addEventListener('click', () => {
                searchInput.value = '';
                dateFilter.value = '';
                estadoFilter.value = '';
                document.getElementById('perPage').value = '10';
                loadMedicionesData(1);
            });

            // Carga inicial
            loadMedicionesData(1);
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
                    console.log(response);
                    Swal.close(); // Cierra el mensaje de carga
                    window.location.href = url; // Redirige al usuario al reporte generado
                },
                error: function(xhr) {
                    console.log(xhr);
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
    </script>
@endpush

{{-- @push('scripts')
    <script>
        // Mover loadMedicionesData al ámbito global
        let loadMedicionesData;

        // Funciones globales que necesitan ser accesibles
        function performSearch() {
            const searchTerm = document.getElementById('searchInput').value.trim();
            const fecha = document.getElementById('date').value;
            const estadoId = document.getElementById('estado').value;
            loadMedicionesData(1, searchTerm, fecha, estadoId);
        }

        function handlePaginationClick(page, event, searchTerm, fecha, estadoId) {
            if (event) event.preventDefault();
            loadMedicionesData(page, searchTerm, fecha, estadoId);
            window.scrollTo({
                top: 0,
                behavior: 'smooth'
            });
        }

        function showImageModal(imageUrl) {
            const modalImagen = document.getElementById('modalImagen');
            modalImagen.src = imageUrl;
            const imagenModal = new bootstrap.Modal(document.getElementById('imagenModal'));
            imagenModal.show();
        }

        document.addEventListener('DOMContentLoaded', function() {
            // Elementos del DOM
            const searchInput = document.getElementById('searchInput');
            const dateFilter = document.getElementById('date');
            const estadoFilter = document.getElementById('estado');
            let searchTimeout;

            // Event listeners
            searchInput.addEventListener('input', function() {
                clearTimeout(searchTimeout);
                searchTimeout = setTimeout(performSearch, 300);
            });

            dateFilter.addEventListener('change', performSearch);
            estadoFilter.addEventListener('change', performSearch);

            // Definición de loadMedicionesData (ahora accesible globalmente)
            loadMedicionesData = async function(page = 1, searchTerm = '', fecha = '', estadoId = '') {
                try {
                    toggleLoading(true);

                    let url = new URL(`{{ route('api.mediciones_index2') }}`);
                    url.searchParams.append('page', page);

                    if (searchTerm) url.searchParams.append('search', searchTerm);
                    if (fecha) url.searchParams.append('date', fecha);
                    if (estadoId) url.searchParams.append('estado', estadoId);

                    const response = await fetch(url);
                    if (!response.ok) throw new Error('Error en la respuesta del servidor');

                    const data = await response.json();
                    updateTable(data.data);
                    updatePagination(data, searchTerm, fecha, estadoId);
                } catch (error) {
                    console.error('Error:', error);
                    showError('Error al cargar datos. Intente nuevamente.');
                } finally {
                    toggleLoading(false);
                }
            };

            // Resto de las funciones (updateTable, updatePagination, etc.) permanecen igual
            const updateTable = (mediciones) => {
                const tbody = document.querySelector('#medicionesTable tbody');
                tbody.innerHTML = '';

                if (!mediciones || mediciones.length === 0) {
                    tbody.innerHTML =
                        `<tr><td colspan="9" class="text-center py-4">No se encontraron mediciones</td></tr>`;
                    return;
                }

                mediciones.forEach(medicion => {
                    const estadoClass = getEstadoClass(medicion.estado);
                    const imagenCell = medicion.url_foto ?
                        `<button class="btn btn-info btn-sm" onclick="showImageModal('${medicion.url_foto}')">Ver Imagen</button>` :
                        '<span class="no-image">Sin imagen</span>';

                    const row = `
                    <tr>
                        <td>${medicion.id}</td>
                        <td>${medicion.nro_cuenta}</td>
                        <td>${medicion.ruta}</td>
                        <td>${medicion.orden}</td>
                        <td>${medicion.medicion}</td>
                        <td>${medicion.fecha}</td>
                        <td class="${estadoClass}">${medicion.estado}</td>
                        <td>${medicion.periodo}</td>
                        <td>${imagenCell}</td>
                    </tr>
                `;
                    tbody.insertAdjacentHTML('beforeend', row);
                });
            };

            function getEstadoClass(estado) {
                const clases = {
                    'Borroso': 'estado-borroso',
                    'Con Aire': 'estado-con-aire',
                    'En Interior': 'estado-en-interior',
                    'Roto': 'estado-roto',
                    'Sin Instalar': 'estado-sin-instalar',
                    'Sin Situacion': 'estado-sin-situacion',
                    'Tapado': 'estado-tapado'
                };
                return clases[estado] || '';
            }

            const updatePagination = (data, searchTerm, fecha, estadoId) => {
                const pagination = document.getElementById('pagination');
                if (!pagination) return;

                pagination.innerHTML = '';
                const {
                    current_page,
                    last_page
                } = data;

                if (current_page > 1) {
                    pagination.appendChild(createPageItem(
                        '&laquo; Anterior',
                        current_page - 1,
                        searchTerm,
                        fecha,
                        estadoId,
                        false,
                        'Previous'
                    ));
                }

                const start = Math.max(1, current_page - 2);
                const end = Math.min(last_page, current_page + 2);

                if (start > 1) {
                    pagination.appendChild(createPageItem(1, 1, searchTerm, fecha, estadoId));
                    if (start > 2) {
                        pagination.appendChild(createEllipsis());
                    }
                }

                for (let i = start; i <= end; i++) {
                    pagination.appendChild(createPageItem(
                        i,
                        i,
                        searchTerm,
                        fecha,
                        estadoId,
                        i === current_page
                    ));
                }

                if (end < last_page) {
                    if (end < last_page - 1) {
                        pagination.appendChild(createEllipsis());
                    }
                    pagination.appendChild(createPageItem(last_page, last_page, searchTerm, fecha, estadoId));
                }

                if (current_page < last_page) {
                    pagination.appendChild(createPageItem(
                        'Siguiente &raquo;',
                        current_page + 1,
                        searchTerm,
                        fecha,
                        estadoId,
                        false,
                        'Next'
                    ));
                }
            };

            function createPageItem(text, page, searchTerm, fecha, estadoId, isActive = false, ariaLabel = null) {
                const li = document.createElement('li');
                li.className = `page-item ${isActive ? 'active' : ''}`;

                const a = document.createElement('a');
                a.className = 'page-link';
                a.href = '#';
                a.innerHTML = text;
                if (ariaLabel) a.setAttribute('aria-label', ariaLabel);
                a.onclick = (e) => {
                    e.preventDefault();
                    loadMedicionesData(page, searchTerm, fecha, estadoId);
                };

                li.appendChild(a);
                return li;
            }

            function createEllipsis() {
                const li = document.createElement('li');
                li.className = 'page-item disabled';
                li.innerHTML = '<span class="page-link">...</span>';
                return li;
            }

            function toggleLoading(show) {
                const spinner = document.getElementById('loadingSpinner');
                if (spinner) spinner.style.display = show ? 'block' : 'none';
            }

            function showError(message) {
                const errorAlert = document.getElementById('errorAlert');
                if (errorAlert) {
                    errorAlert.textContent = message;
                    errorAlert.style.display = 'block';
                    setTimeout(() => errorAlert.style.display = 'none', 5000);
                }
            }

            document.getElementById('resetFilters')?.addEventListener('click', () => {
                searchInput.value = '';
                dateFilter.value = '';
                estadoFilter.value = '';
                loadMedicionesData(1);
            });

            // Carga inicial
            loadMedicionesData(1);
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
                    console.log(response);
                    Swal.close(); // Cierra el mensaje de carga
                    window.location.href = url; // Redirige al usuario al reporte generado
                },
                error: function(xhr) {
                    console.log(xhr);
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
    </script>
@endpush --}}
