<!DOCTYPE html>
<html lang="es">
<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <title>Gestión de Ventas</title>
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
                <h2 class="mb-4">Gestión de Ventas</h2>

                <!-- Formulario de Venta -->
                <div class="form-section">
                    <form id="ventaForm">
                        <input type="hidden" id="id_venta" name="id_venta">
                        
                        <div class="row">
                            <div class="col-md-6">
                                <div class="mb-3">
                                    <label for="id_cliente" class="form-label">Cliente</label>
                                    <select class="form-select" id="id_cliente" name="id_cliente" required>
                                        <option value="">Seleccione un cliente</option>
                                    </select>
                                </div>
                            </div>
                            <div class="col-md-6">
                                <div class="mb-3">
                                    <label for="id_vehiculo" class="form-label">Vehículo</label>
                                    <select class="form-select" id="id_vehiculo" name="id_vehiculo" required>
                                        <option value="">Seleccione un vehículo</option>
                                    </select>
                                </div>
                            </div>
                        </div>

                        <div class="row">
                            <div class="col-md-4">
                                <div class="mb-3">
                                    <label for="fecha_venta" class="form-label">Fecha de Venta</label>
                                    <input type="date" class="form-control" id="fecha_venta" name="fecha_venta" required>
                                </div>
                            </div>
                            <div class="col-md-4">
                                <div class="mb-3">
                                    <label for="precio_venta" class="form-label">Precio de Venta</label>
                                    <input type="number" class="form-control" id="precio_venta" name="precio_venta" required>
                                </div>
                            </div>
                            <div class="col-md-4">
                                <div class="mb-3">
                                    <label for="metodo_pago" class="form-label">Método de Pago</label>
                                    <select class="form-select" id="metodo_pago" name="metodo_pago" required>
                                        <option value="">Seleccione método</option>
                                        <option value="efectivo">Efectivo</option>
                                        <option value="tarjeta">Tarjeta</option>
                                        <option value="transferencia">Transferencia</option>
                                        <option value="financiacion">Financiación</option>
                                    </select>
                                </div>
                            </div>
                        </div>

                        <div class="row">
                            <div class="col-md-6">
                                <div class="mb-3">
                                    <label for="estado_venta" class="form-label">Estado de la Venta</label>
                                    <select class="form-select" id="estado_venta" name="estado_venta" required>
                                        <option value="pendiente">Pendiente</option>
                                        <option value="completada">Completada</option>
                                        <option value="cancelada">Cancelada</option>
                                    </select>
                                </div>
                            </div>
                            <div class="col-md-6">
                                <div class="mb-3">
                                    <label for="observaciones" class="form-label">Observaciones</label>
                                    <textarea class="form-control" id="observaciones" name="observaciones" rows="3"></textarea>
                                </div>
                            </div>
                        </div>

                        <div class="row mt-3">
                            <div class="col-12">
                                <button type="submit" class="btn btn-primary">
                                    <i class="fas fa-save me-2"></i>Guardar Venta
                                </button>
                                <button type="button" class="btn btn-secondary" onclick="limpiarFormulario()">
                                    <i class="fas fa-plus me-2"></i>Nueva Venta
                                </button>
                            </div>
                        </div>
                    </form>
                </div>

                <!-- Tabla de Ventas -->
                <div class="card">
                    <div class="card-header bg-primary text-white">
                        <h3 class="m-0">Lista de Ventas</h3>
                    </div>
                    <div class="card-body">
                        <table class="table table-striped table-hover">
                            <thead>
                                <tr>
                                    <th>ID</th>
                                    <th>Cliente</th>
                                    <th>Vehículo</th>
                                    <th>Fecha</th>
                                    <th>Precio</th>
                                    <th>Método Pago</th>
                                    <th>Estado</th>
                                    <th>Acciones</th>
                                </tr>
                            </thead>
                            <tbody id="tablaVentas">
                            </tbody>
                        </table>
                    </div>
                </div>
            </div>
        </div>
    </div>

    <script src="https://cdn.jsdelivr.net/npm/bootstrap@5.1.3/dist/js/bootstrap.bundle.min.js"></script>
    <script src="https://cdn.jsdelivr.net/npm/sweetalert2@11.7.3/dist/sweetalert2.all.min.js"></script>
    <script src="../js/ventas.js"></script>
</body>
</html>