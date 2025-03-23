<!DOCTYPE html>
<html lang="es">
<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <title>Gestión de Proveedores</title>
    <link href="https://cdn.jsdelivr.net/npm/bootstrap@5.1.3/dist/css/bootstrap.min.css" rel="stylesheet">
    <link href="https://cdn.jsdelivr.net/npm/sweetalert2@11.7.3/dist/sweetalert2.min.css" rel="stylesheet">
    <link href="https://cdnjs.cloudflare.com/ajax/libs/font-awesome/6.0.0/css/all.min.css" rel="stylesheet">
    <style>
        .form-section {
            background: #fff;
            padding: 20px;
            border-radius: 8px;
            box-shadow: 0 0 10px rgba(0,0,0,0.1);
            margin-bottom: 20px;
        }
    </style>
</head>
<body>
    <?php include 'navegacion.php'; ?>

    <div class="content-wrapper">
        <div class="container-fluid">
            <div class="container mt-5">
                <h2 class="mb-4">Gestión de Proveedores</h2>

                <!-- Buscador y Filtros -->
                <div class="row mb-4">
                    <div class="col-md-6">
                        <div class="input-group">
                            <input type="text" class="form-control" id="buscarProveedor" 
                                   placeholder="Buscar proveedor..." autocomplete="off">
                            <select class="form-select" id="filtroEstado" style="max-width: 150px;">
                                <option value="">Todos</option>
                                <option value="1">Activos</option>
                                <option value="0">Inactivos</option>
                            </select>
                            <button class="btn btn-primary" onclick="buscarProveedores()">
                                <i class="fas fa-search"></i>
                            </button>
                        </div>
                    </div>
                    <div class="col-md-6 text-end">
                        <button class="btn btn-success" onclick="exportarExcel()">
                            <i class="fas fa-file-excel me-2"></i>Exportar a Excel
                        </button>
                        <button class="btn btn-danger" onclick="exportarPDF()">
                            <i class="fas fa-file-pdf me-2"></i>Exportar a PDF
                        </button>
                    </div>
                </div>

                <!-- Formulario -->
                <div class="form-section">
                    <form id="proveedorForm">
                        <input type="hidden" id="id_proveedor" name="id_proveedor">
                        
                        <div class="row">
                            <div class="col-md-4">
                                <div class="mb-3">
                                    <label for="nombre_empresa" class="form-label">Nombre de la Empresa</label>
                                    <input type="text" class="form-control" id="nombre_empresa" name="nombre_empresa" required>
                                </div>
                            </div>
                            <div class="col-md-4">
                                <div class="mb-3">
                                    <label for="nif" class="form-label">NIF</label>
                                    <input type="text" class="form-control" id="nif" name="nif" required>
                                </div>
                            </div>
                            <div class="col-md-4">
                                <div class="mb-3">
                                    <label for="direccion" class="form-label">Dirección</label>
                                    <input type="text" class="form-control" id="direccion" name="direccion">
                                </div>
                            </div>
                        </div>

                        <div class="row">
                            <div class="col-md-4">
                                <div class="mb-3">
                                    <label for="telefono" class="form-label">Teléfono</label>
                                    <input type="tel" class="form-control" id="telefono" name="telefono">
                                </div>
                            </div>
                            <div class="col-md-4">
                                <div class="mb-3">
                                    <label for="email" class="form-label">Email</label>
                                    <input type="email" class="form-control" id="email" name="email">
                                </div>
                            </div>
                            <div class="col-md-4">
                                <div class="mb-3">
                                    <label for="contacto_nombre" class="form-label">Nombre de Contacto</label>
                                    <input type="text" class="form-control" id="contacto_nombre" name="contacto_nombre">
                                </div>
                            </div>
                        </div>

                        <div class="row mt-3">
                            <div class="col-12">
                                <button type="submit" class="btn btn-primary">
                                    <i class="fas fa-save me-2"></i>Guardar
                                </button>
                                <button type="button" class="btn btn-secondary" onclick="limpiarFormulario()">
                                    <i class="fas fa-plus me-2"></i>Nuevo
                                </button>
                            </div>
                        </div>
                    </form>
                </div>

                <!-- Añadir antes de la tabla en proveedores.php -->
                <div class="row mb-4">
                    <div class="col-md-4">
                        <div class="card bg-primary text-white">
                            <div class="card-body">
                                <h5 class="card-title">Total Proveedores</h5>
                                <h2 id="totalProveedores">0</h2>
                            </div>
                        </div>
                    </div>
                    <div class="col-md-4">
                        <div class="card bg-success text-white">
                            <div class="card-body">
                                <h5 class="card-title">Proveedores Activos</h5>
                                <h2 id="proveedoresActivos">0</h2>
                            </div>
                        </div>
                    </div>
                    <div class="col-md-4">
                        <div class="card bg-info text-white">
                            <div class="card-body">
                                <h5 class="card-title">Con Vehículos</h5>
                                <h2 id="proveedoresConVehiculos">0</h2>
                            </div>
                        </div>
                    </div>
                </div>

                <!-- Tabla -->
                <div class="card">
                    <div class="card-header bg-primary text-white">
                        <h3 class="m-0">Lista de Proveedores</h3>
                    </div>
                    <div class="card-body">
                        <table class="table table-striped table-hover">
                            <thead>
                                <tr>
                                    <th>ID</th>
                                    <th>Nombre Empresa</th>
                                    <th>NIF</th>
                                    <th>Dirección</th>
                                    <th>Teléfono</th>
                                    <th>Email</th>
                                    <th>Contacto</th>
                                    <th>Estado</th>
                                    <th>Acciones</th>
                                </tr>
                            </thead>
                            <tbody id="tablaProveedores">
                            </tbody>
                        </table>
                    </div>
                </div>
            </div>
        </div>
    </div>

    <script src="https://cdn.jsdelivr.net/npm/bootstrap@5.1.3/dist/js/bootstrap.bundle.min.js"></script>
    <script src="https://cdn.jsdelivr.net/npm/sweetalert2@11.7.3/dist/sweetalert2.all.min.js"></script>
    <script src="../js/proveedores.js"></script>
</body>
</html>