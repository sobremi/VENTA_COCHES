<!DOCTYPE html>
<html lang="es">
<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <title>Gestión de Vehículos</title>
    <link href="https://cdn.jsdelivr.net/npm/bootstrap@5.1.3/dist/css/bootstrap.min.css" rel="stylesheet">
    <link href="https://cdn.jsdelivr.net/npm/sweetalert2@11.7.3/dist/sweetalert2.min.css" rel="stylesheet">
    <link href="https://cdnjs.cloudflare.com/ajax/libs/font-awesome/6.0.0/css/all.min.css" rel="stylesheet">
    <link href="../assets/css/styles.css" rel="stylesheet">
    <style>
        .form-section {
            background: #fff;
            padding: 20px;
            border-radius: 8px;
            box-shadow: 0 0 10px rgba(0,0,0,0.1);
            margin-bottom: 20px;
        }
        .vehicle-card {
            transition: transform 0.3s;
        }
        .vehicle-card:hover {
            transform: translateY(-5px);
        }
        .vehicle-image {
            height: 200px;
            object-fit: cover;
        }
        .badge-disponible {
            position: absolute;
            top: 10px;
            right: 10px;
        }
    </style>
    <script src="https://cdn.jsdelivr.net/npm/chart.js"></script>
</head>
<body>
    <?php include 'navegacion.php'; ?>

    <div class="content-wrapper">
        <div class="container-fluid">
            <div class="container mt-5">
                <h2 class="mb-4">Gestión de Vehículos</h2>

                <!-- Panel de Estadísticas -->
                <div class="row mb-4">
                    <div class="col-md-4">
                        <div class="card">
                            <div class="card-body">
                                <h5 class="card-title">Total Vehículos</h5>
                                <p class="card-text h2" id="totalVehiculos">0</p>
                            </div>
                        </div>
                    </div>
                    <div class="col-md-4">
                        <div class="card">
                            <div class="card-body">
                                <h5 class="card-title">Vehículos Disponibles</h5>
                                <p class="card-text h2" id="vehiculosDisponibles">0</p>
                            </div>
                        </div>
                    </div>
                    <div class="col-md-4">
                        <div class="card">
                            <div class="card-body">
                                <h5 class="card-title">Precio Promedio</h5>
                                <p class="card-text h2" id="precioPromedio">€0</p>
                            </div>
                        </div>
                    </div>
                </div>

                <!-- Botones de acciones -->
                <div class="row mb-4">
                    <div class="col-md-12">
                        <div class="btn-group">
                            <button class="btn btn-success" onclick="exportarExcel()">
                                <i class="fas fa-file-excel"></i> Exportar a Excel
                            </button>
                            <button class="btn btn-primary" onclick="generarReporte()">
                                <i class="fas fa-chart-bar"></i> Reporte Detallado
                            </button>
                            <button class="btn btn-info" onclick="mostrarGraficos()">
                                <i class="fas fa-chart-pie"></i> Gráficos
                            </button>
                        </div>
                    </div>
                </div>

                <!-- Añadir después del panel de estadísticas -->
                <div class="row mb-4">
                    <div class="col-12">
                        <div class="btn-group">
                            <button class="btn btn-success" onclick="exportarExcel()">
                                <i class="fas fa-file-excel me-2"></i>
                                Exportar Excel
                            </button>
                            <button class="btn btn-info" onclick="mostrarGraficos()">
                                <i class="fas fa-chart-bar me-2"></i>
                                Ver Gráficos
                            </button>
                            <button class="btn btn-primary" onclick="generarReporte()">
                                <i class="fas fa-file-alt me-2"></i>
                                Generar Reporte
                            </button>
                        </div>
                    </div>
                </div>

                <!-- Filtros y Búsqueda -->
                <div class="row mb-4">
                    <div class="col-md-8">
                        <div class="input-group">
                            <input type="text" class="form-control" id="buscarVehiculo" 
                                   placeholder="Buscar vehículo...">
                            <input type="number" class="form-control" id="precioMin" 
                                   placeholder="Precio mínimo">
                            <input type="number" class="form-control" id="precioMax" 
                                   placeholder="Precio máximo">
                            <select class="form-select" id="filtroDisponibilidad">
                                <option value="">Todos</option>
                                <option value="1">Disponibles</option>
                                <option value="0">No disponibles</option>
                            </select>
                            <button class="btn btn-primary" onclick="buscarVehiculos()">
                                <i class="fas fa-search"></i>
                            </button>
                        </div>
                    </div>
                    <div class="col-md-4 text-end">
                        <button class="btn btn-success" data-bs-toggle="modal" 
                                data-bs-target="#vehiculoModal">
                            <i class="fas fa-plus me-2"></i>Nuevo Vehículo
                        </button>
                    </div>
                </div>

                <!-- Grid de Vehículos -->
                <div class="row" id="gridVehiculos">
                    <!-- Los vehículos se cargarán aquí dinámicamente -->
                </div>
            </div>
        </div>
    </div>

    <!-- Modal de Vehículo -->
    <div class="modal fade" id="vehiculoModal" tabindex="-1">
        <div class="modal-dialog modal-lg">
            <div class="modal-content">
                <div class="modal-header">
                    <h5 class="modal-title" id="modalTitle">Nuevo Vehículo</h5>
                    <button type="button" class="btn-close" data-bs-dismiss="modal"></button>
                </div>
                <div class="modal-body">
                    <form id="vehiculoForm" enctype="multipart/form-data" novalidate>
                        <input type="hidden" id="id_vehiculo" name="id_vehiculo">
                        <div class="row">
                            <div class="col-md-6">
                                <div class="mb-3">
                                    <label for="marca" class="form-label">Marca</label>
                                    <input type="text" class="form-control" id="marca" name="marca" required>
                                    <div class="invalid-feedback">
                                        Por favor, complete este campo
                                    </div>
                                </div>
                            </div>
                            <div class="col-md-6">
                                <div class="mb-3">
                                    <label for="modelo" class="form-label">Modelo</label>
                                    <input type="text" class="form-control" id="modelo" name="modelo" required>
                                    <div class="invalid-feedback">
                                        Por favor, complete este campo
                                    </div>
                                </div>
                            </div>
                        </div>
                        <div class="row">
                            <div class="col-md-3">
                                <div class="mb-3">
                                    <label for="anio" class="form-label">Año</label>
                                    <input type="number" class="form-control" id="anio" name="año" 
                                           min="1900" max="2024" required>
                                    <div class="invalid-feedback">
                                        Por favor, complete este campo
                                    </div>
                                </div>
                            </div>
                            <div class="col-md-3">
                                <div class="mb-3">
                                    <label for="color" class="form-label">Color</label>
                                    <input type="text" class="form-control" id="color" name="color" required>
                                    <div class="invalid-feedback">
                                        Por favor, complete este campo
                                    </div>
                                </div>
                            </div>
                            <div class="col-md-3">
                                <div class="mb-3">
                                    <label for="tipo_combustible" class="form-label">Tipo Combustible</label>
                                    <select class="form-select" id="tipo_combustible" name="tipo_combustible" required>
                                        <option value="">Seleccione...</option>
                                        <option value="Gasolina">Gasolina</option>
                                        <option value="Diésel">Diésel</option>
                                        <option value="Eléctrico">Eléctrico</option>
                                        <option value="Híbrido">Híbrido</option>
                                    </select>
                                    <div class="invalid-feedback">
                                        Por favor, complete este campo
                                    </div>
                                </div>
                            </div>
                            <div class="col-md-3">
                                <div class="mb-3">
                                    <label for="estado" class="form-label">Estado</label>
                                    <select class="form-select" id="estado" name="estado" required>
                                        <option value="">Seleccione...</option>
                                        <option value="Nuevo">Nuevo</option>
                                        <option value="Usado">Usado</option>
                                    </select>
                                    <div class="invalid-feedback">
                                        Por favor, complete este campo
                                    </div>
                                </div>
                            </div>
                        </div>
                        <div class="row">
                            <div class="col-md-4">
                                <div class="mb-3">
                                    <label for="precio" class="form-label">Precio</label>
                                    <input type="number" class="form-control" id="precio" name="precio" 
                                           min="0" step="0.01" required>
                                    <div class="invalid-feedback">
                                        Por favor, complete este campo
                                    </div>
                                </div>
                            </div>
                            <div class="col-md-4">
                                <div class="mb-3">
                                    <label for="kilometraje" class="form-label">Kilometraje</label>
                                    <input type="number" class="form-control" id="kilometraje" name="kilometraje" 
                                           min="0" required>
                                    <div class="invalid-feedback">
                                        Por favor, complete este campo
                                    </div>
                                </div>
                            </div>
                            <div class="col-md-4">
                                <div class="mb-3">
                                    <label for="id_proveedor" class="form-label">Proveedor</label>
                                    <select class="form-select" id="id_proveedor" name="id_proveedor" required>
                                        <option value="">Seleccione un proveedor</option>
                                    </select>
                                    <div class="invalid-feedback">
                                        Por favor, complete este campo
                                    </div>
                                </div>
                            </div>
                        </div>
                        <div class="row">
                            <div class="col-md-12">
                                <div class="mb-3">
                                    <label for="foto" class="form-label">Foto</label>
                                    <input type="file" class="form-control" id="foto" name="foto" 
                                           accept="image/*">
                                </div>
                            </div>
                        </div>

                        <!-- Previsualización de imagen en el modal -->
                        <div class="mb-3">
                            <label for="foto" class="form-label">Foto del Vehículo</label>
                            <input type="file" class="form-control" id="foto" name="foto" 
                                   accept="image/*" onchange="previsualizarImagen(this)">
                            <div class="mt-2">
                                <img id="previewImagen" src="#" alt="Vista previa" 
                                     style="display: none; max-width: 200px; max-height: 200px;">
                            </div>
                        </div>
                    </form>
                </div>
                <div class="modal-footer">
                    <button type="button" class="btn btn-secondary" data-bs-dismiss="modal">Cancelar</button>
                    <button type="button" class="btn btn-primary" onclick="guardarVehiculo()">Guardar</button>
                </div>
            </div>
        </div>
    </div>

    <div class="toast-container position-fixed bottom-0 end-0 p-3">
        <div id="alertToast" class="toast" role="alert">
            <div class="toast-header">
                <strong class="me-auto">Notificación</strong>
                <button type="button" class="btn-close" data-bs-dismiss="toast"></button>
            </div>
            <div class="toast-body"></div>
        </div>
    </div>

    <script src="https://cdn.jsdelivr.net/npm/bootstrap@5.1.3/dist/js/bootstrap.bundle.min.js"></script>
    <script src="https://cdn.jsdelivr.net/npm/sweetalert2@11.7.3/dist/sweetalert2.all.min.js"></script>
    <script src="../js/vehiculos.js"></script>
</body>
</html>