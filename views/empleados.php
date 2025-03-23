<!DOCTYPE html>
<html lang="es">
<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <title>Gestión de Empleados</title>
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
                <h2 class="mb-4">Gestión de Empleados</h2>

                <!-- Después del h2 y antes del form-section -->
                <div class="row mb-4">
                    <div class="col-md-6">
                        <div class="input-group">
                            <input type="text" class="form-control" id="buscarEmpleado" 
                                   placeholder="Buscar empleado..." autocomplete="off">
                            <select class="form-select" id="filtroCargo" style="max-width: 200px;">
                                <option value="">Todos los cargos</option>
                                <option value="Gerente">Gerente</option>
                                <option value="Vendedor">Vendedor</option>
                                <option value="Mecánico">Mecánico</option>
                                <option value="Administrativo">Administrativo</option>
                            </select>
                        </div>
                    </div>
                </div>

                <!-- Formulario -->
                <div class="form-section">
                    <form id="empleadoForm">
                        <input type="hidden" id="id_empleado" name="id_empleado">
                        
                        <div class="row">
                            <div class="col-md-4">
                                <div class="mb-3">
                                    <label for="nombre" class="form-label">Nombre</label>
                                    <input type="text" class="form-control" id="nombre" name="nombre" required>
                                </div>
                            </div>
                            <div class="col-md-4">
                                <div class="mb-3">
                                    <label for="apellido" class="form-label">Apellido</label>
                                    <input type="text" class="form-control" id="apellido" name="apellido" required>
                                </div>
                            </div>
                            <div class="col-md-4">
                                <div class="mb-3">
                                    <label for="dni" class="form-label">DNI</label>
                                    <input type="text" class="form-control" id="dni" name="dni" required>
                                </div>
                            </div>
                        </div>

                        <div class="row">
                            <div class="col-md-4">
                                <div class="mb-3">
                                    <label for="cargo" class="form-label">Cargo</label>
                                    <select class="form-select" id="cargo" name="cargo" required>
                                        <option value="">Seleccione un cargo</option>
                                        <option value="Gerente">Gerente</option>
                                        <option value="Vendedor">Vendedor</option>
                                        <option value="Mecánico">Mecánico</option>
                                        <option value="Administrativo">Administrativo</option>
                                    </select>
                                </div>
                            </div>
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
                        </div>

                        <div class="row">
                            <div class="col-md-4">
                                <div class="mb-3">
                                    <label for="fecha_contratacion" class="form-label">Fecha de Contratación</label>
                                    <input type="date" class="form-control" id="fecha_contratacion" name="fecha_contratacion" required>
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

                <!-- Tabla -->
                <div class="card">
                    <div class="card-header bg-primary text-white">
                        <h3 class="m-0">Lista de Empleados</h3>
                    </div>
                    <div class="card-body">
                        <table class="table table-striped table-hover">
                            <thead>
                                <tr>
                                    <th>ID</th>
                                    <th>Nombre</th>
                                    <th>Apellido</th>
                                    <th>DNI</th>
                                    <th>Cargo</th>
                                    <th>Teléfono</th>
                                    <th>Email</th>
                                    <th>Fecha Contratación</th>
                                    <th>Acciones</th>
                                </tr>
                            </thead>
                            <tbody id="tablaEmpleados">
                            </tbody>
                        </table>
                    </div>
                </div>
            </div>
        </div>
    </div>

    <script src="https://cdn.jsdelivr.net/npm/bootstrap@5.1.3/dist/js/bootstrap.bundle.min.js"></script>
    <script src="https://cdn.jsdelivr.net/npm/sweetalert2@11.7.3/dist/sweetalert2.all.min.js"></script>
    <script src="../js/empleados.js"></script>
</body>
</html>