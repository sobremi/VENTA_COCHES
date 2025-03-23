<!DOCTYPE html>
<html lang="es">
<head>
    <meta charset="UTF-8">
    <title>Gestión de Clientes</title>
    <link href="https://cdn.jsdelivr.net/npm/bootstrap@5.1.3/dist/css/bootstrap.min.css" rel="stylesheet">
    <link href="https://cdn.jsdelivr.net/npm/sweetalert2@11.7.3/dist/sweetalert2.min.css" rel="stylesheet">
    <link href="https://cdnjs.cloudflare.com/ajax/libs/font-awesome/6.0.0/css/all.min.css" rel="stylesheet">
    <style>
        .table thead {
            background: #007bff;
            color: white;
        }
        .form-section {
            background: #f8f9fa;
            padding: 20px;
            border-radius: 5px;
            margin-bottom: 20px;
            box-shadow: 0 0 10px rgba(0,0,0,0.1);
        }
        .img-preview {
            max-width: 100px;
            max-height: 100px;
            margin-top: 10px;
        }
        .searching .input-group-text {
            background-color: #007bff;
            color: white;
        }
    </style>
</head>
<body>
    <?php include 'navegacion.php'; ?>
    
    <div class="content-wrapper">
        <div class="container-fluid">
            <div class="container mt-5">
                <h2 class="mb-4">Gestión de Clientes</h2>

                <!-- Agregar el buscador -->
                <div class="row mb-4">
                    <div class="col-md-6">
                        <div class="input-group">
                            <input type="text" class="form-control" id="buscarCliente" 
                                placeholder="Buscar cliente...">
                            <span class="input-group-text">
                                <i class="fas fa-search"></i>
                            </span>
                        </div>
                    </div>
                </div>

                <!-- Formulario -->
                <div class="form-section">
                    <form id="clienteForm" enctype="multipart/form-data">
                        <input type="hidden" id="id_cliente" name="id_cliente">
                        
                        <div class="row">
                            <div la class="col-md-4">
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
                                    <label for="direccion" class="form-label">Dirección</label>
                                    <input type="text" class="form-control" id="direccion" name="direccion">
                                </div>
                            </div>
                        </div>

                        <div class="row">
                            <div class="col-md-6">
                                <div class="mb-3">
                                    <label for="imagen" class="form-label">Imagen</label>
                                    <input type="file" class="form-control" id="imagen" name="imagen" accept="image/*">
                                    <div id="imagenPreview" class="mt-2"></div>
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
                        <h3 class="m-0">Lista de Clientes</h3>
                    </div>
                    <div class="card-body">
                        <table class="table table-striped table-hover">
                            <thead>
                                <tr>
                                    <th>ID</th>
                                    <th>Nombre</th>
                                    <th>Apellido</th>
                                    <th>DNI</th>
                                    <th>Teléfono</th>
                                    <th>Email</th>
                                    <th>Dirección</th>
                                    <th>Imagen</th>
                                    <th>Acciones</th>
                                </tr>
                            </thead>
                            <tbody id="tablaClientes">
                            </tbody>
                        </table>
                    </div>
                </div>
            </div>

            <!-- Scripts - Solo Bootstrap y SweetAlert2 -->
            <script src="https://cdn.jsdelivr.net/npm/bootstrap@5.1.3/dist/js/bootstrap.bundle.min.js"></script>
            <script src="https://cdn.jsdelivr.net/npm/sweetalert2@11.7.3/dist/sweetalert2.all.min.js"></script>
            
            <script>
                // Esperar a que el documento esté cargado
                document.addEventListener('DOMContentLoaded', function() {
                    console.log('DOM cargado');
                    cargarClientes();
                    
                    // Manejar el envío del formulario
                    document.getElementById('clienteForm').addEventListener('submit', function(e) {
                        e.preventDefault();
                        guardarCliente();
                    });
                    
                    // Preview de imagen
                    document.getElementById('imagen').addEventListener('change', function(e) {
                        const file = e.target.files[0];
                        if (file) {
                            const reader = new FileReader();
                            reader.onload = function(e) {
                                document.getElementById('imagenPreview').innerHTML = `
                                    <img src="${e.target.result}" class="img-preview rounded">
                                `;
                            }
                            reader.readAsDataURL(file);
                        }
                    });
                });

                function guardarCliente() {
                    const formData = new FormData(document.getElementById('clienteForm'));
                    const id = document.getElementById('id_cliente').value;
                    
                    formData.append('accion', id ? 'actualizar' : 'insertar');

                    fetch('../controladores/cliente_controller.php', {
                        method: 'POST',
                        body: formData
                    })
                    .then(response => response.json())
                    .then(data => {
                        if(data.success) {
                            Swal.fire({
                                icon: 'success',
                                title: '¡Éxito!',
                                text: id ? 'Cliente actualizado correctamente' : 'Cliente guardado correctamente',
                                showConfirmButton: false,
                                timer: 1500
                            });
                            limpiarFormulario();
                            cargarClientes();
                        } else {
                            throw new Error(data.message || 'Error al procesar la operación');
                        }
                    })
                    .catch(error => {
                        console.error('Error:', error);
                        Swal.fire({
                            icon: 'error',
                            title: 'Error',
                            text: error.message || 'Error en la comunicación con el servidor'
                        });
                    });
                }

                function cargarClientes() {
                    fetch('../controladores/cliente_controller.php', {
                        method: 'POST',
                        headers: {
                            'Content-Type': 'application/x-www-form-urlencoded',
                        },
                        body: 'accion=listar'
                    })
                    .then(response => response.json())
                    .then(clientes => {
                        let html = '';
                        clientes.forEach(cliente => {
                            html += `
                                <tr>
                                    <td>${cliente.id_cliente}</td>
                                    <td>${cliente.nombre}</td>
                                    <td>${cliente.apellido}</td>
                                    <td>${cliente.dni}</td>
                                    <td>${cliente.telefono}</td>
                                    <td>${cliente.email}</td>
                                    <td>${cliente.direccion}</td>
                                    <td>${cliente.imagen ? 
                                        `<img src="../uploads/${cliente.imagen}" class="img-thumbnail" style="width: 50px; height: 50px; object-fit: cover;">` : 
                                        'Sin imagen'}</td>
                                    <td>
                                        <button class="btn btn-warning btn-sm" onclick="editarCliente(${cliente.id_cliente})">
                                            <i class="fas fa-edit"></i>
                                        </button>
                                        <button class="btn btn-danger btn-sm" onclick="eliminarCliente(${cliente.id_cliente})">
                                            <i class="fas fa-trash"></i>
                                        </button>
                                    </td>
                                </tr>
                            `;
                        });
                        document.getElementById('tablaClientes').innerHTML = html;
                    })
                    .catch(error => {
                        console.error('Error:', error);
                        Swal.fire({
                            icon: 'error',
                            title: 'Error',
                            text: 'Error al cargar los clientes'
                        });
                    });
                }

                function editarCliente(id) {
                    fetch('../controladores/cliente_controller.php', {
                        method: 'POST',
                        headers: {
                            'Content-Type': 'application/x-www-form-urlencoded',
                        },
                        body: `accion=obtener&id_cliente=${id}`
                    })
                    .then(response => response.json())
                    .then(cliente => {
                        document.getElementById('id_cliente').value = cliente.id_cliente;
                        document.getElementById('nombre').value = cliente.nombre;
                        document.getElementById('apellido').value = cliente.apellido;
                        document.getElementById('dni').value = cliente.dni;
                        document.getElementById('telefono').value = cliente.telefono;
                        document.getElementById('email').value = cliente.email;
                        document.getElementById('direccion').value = cliente.direccion;
                        
                        if(cliente.imagen) {
                            document.getElementById('imagenPreview').innerHTML = `
                                <img src="../uploads/${cliente.imagen}" class="img-preview rounded">
                            `;
                        }
                        
                        // Scroll al formulario
                        window.scrollTo({ top: 0, behavior: 'smooth' });
                    })
                    .catch(error => {
                        console.error('Error:', error);
                        Swal.fire({
                            icon: 'error',
                            title: 'Error',
                            text: 'Error al cargar los datos del cliente'
                        });
                    });
                }

                function eliminarCliente(id) {
                    Swal.fire({
                        title: '¿Eliminar cliente?',
                        text: "Esta acción no se puede revertir",
                        icon: 'warning',
                        showCancelButton: true,
                        confirmButtonColor: '#d33',
                        cancelButtonColor: '#3085d6',
                        confirmButtonText: 'Sí, eliminar',
                        cancelButtonText: 'Cancelar'
                    }).then((result) => {
                        if (result.isConfirmed) {
                            fetch('../controladores/cliente_controller.php', {
                                method: 'POST',
                                headers: {
                                    'Content-Type': 'application/x-www-form-urlencoded',
                                },
                                body: `accion=eliminar&id_cliente=${id}`
                            })
                            .then(response => response.json())
                            .then(data => {
                                if(data.success) {
                                    Swal.fire({
                                        icon: 'success',
                                        title: '¡Eliminado!',
                                        text: 'Cliente eliminado correctamente',
                                        showConfirmButton: false,
                                        timer: 1500
                                    });
                                    cargarClientes();
                                } else {
                                    throw new Error(data.message || 'Error al eliminar el cliente');
                                }
                            })
                            .catch(error => {
                                console.error('Error:', error);
                                Swal.fire({
                                    icon: 'error',
                                    title: 'Error',
                                    text: error.message || 'Error al eliminar el cliente'
                                });
                            });
                        }
                    });
                }

                function limpiarFormulario() {
                    document.getElementById('clienteForm').reset();
                    document.getElementById('id_cliente').value = '';
                    document.getElementById('imagenPreview').innerHTML = '';
                }
            </script>

            <script>
                document.addEventListener('DOMContentLoaded', function() {
                    cargarClientes(); // Carga inicial

                    // Búsqueda en tiempo real
                    let timeoutId;
                    document.getElementById('buscarCliente').addEventListener('input', function(e) {
                        clearTimeout(timeoutId);
                        
                        timeoutId = setTimeout(() => {
                            const busqueda = e.target.value.trim();
                            
                            // Si el campo está vacío, mostrar todos los clientes
                            if (busqueda === '') {
                                cargarClientes();
                                return;
                            }

                            const xhr = new XMLHttpRequest();
                            xhr.open('POST', '../controladores/cliente_controller.php', true);
                            xhr.setRequestHeader('Content-Type', 'application/x-www-form-urlencoded');

                            xhr.onreadystatechange = function() {
                                if (xhr.readyState === 4) {
                                    if (xhr.status === 200) {
                                        try {
                                            const clientes = JSON.parse(xhr.responseText);
                                            actualizarTablaClientes(clientes);
                                        } catch (error) {
                                            console.error('Error al procesar la respuesta:', error);
                                        }
                                    }
                                }
                            };

                            xhr.send('accion=buscar&termino=' + encodeURIComponent(busqueda));
                        }, 300);
                    });
                });

                function actualizarTablaClientes(clientes) {
                    let html = '';
                    clientes.forEach(cliente => {
                        html += `
                            <tr>
                                <td>${cliente.id_cliente}</td>
                                <td>${cliente.nombre}</td>
                                <td>${cliente.apellido}</td>
                                <td>${cliente.dni}</td>
                                <td>${cliente.telefono || ''}</td>
                                <td>${cliente.email || ''}</td>
                                <td>${cliente.direccion || ''}</td>
                                <td>${cliente.imagen ? 
                                    `<img src="../uploads/${cliente.imagen}" class="img-thumbnail" style="width: 50px; height: 50px; object-fit: cover;">` : 
                                    'Sin imagen'}</td>
                                <td>
                                    <div class="btn-group">
                                        <button class="btn btn-warning btn-sm" onclick="editarCliente(${cliente.id_cliente})">
                                            <i class="fas fa-edit"></i>
                                        </button>
                                        <button class="btn btn-danger btn-sm" onclick="eliminarCliente(${cliente.id_cliente})">
                                            <i class="fas fa-trash"></i>
                                        </button>
                                    </div>
                                </td>
                            </tr>
                        `;
                    });
                    document.getElementById('tablaClientes').innerHTML = html;
                }
            </script>
        </div>
    </div>
</body>
</html>
