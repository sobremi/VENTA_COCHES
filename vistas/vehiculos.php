<!DOCTYPE html>
<html lang="es">
<head>
    <meta charset="UTF-8">
    <title>Gestión de Vehículos</title>
    <link href="https://cdn.jsdelivr.net/npm/bootstrap@5.1.3/dist/css/bootstrap.min.css" rel="stylesheet">
    <link href="https://cdn.jsdelivr.net/npm/sweetalert2@11.7.3/dist/sweetalert2.min.css" rel="stylesheet">
</head>
<body>
    <div class="container mt-5">
        <h2>Gestión de Vehículos</h2>
        
        <div class="row mb-4">
            <div class="col-md-6">
                <div class="input-group">
                    <input type="text" class="form-control" id="buscarVehiculo" 
                           placeholder="Buscar vehículo..." autocomplete="off">
                    <span class="input-group-text">
                        <i class="fas fa-search"></i>
                    </span>
                </div>
            </div>
        </div>

        <form id="vehiculoForm" class="mb-4" enctype="multipart/form-data">
            <input type="hidden" id="id_vehiculo" name="id_vehiculo">
            <div class="row">
                <div class="col-md-4">
                    <div class="mb-3">
                        <label for="marca" class="form-label">Marca</label>
                        <input type="text" class="form-control" id="marca" name="marca" required>
                    </div>
                </div>
                <div class="col-md-4">
                    
                    <div class="mb-3">
                        <label for="modelo" class="form-label">Modelo</label>
                        <input type="text" class="form-control" id="modelo" name="modelo" required>
                    </div>
                </div>
                <div class="col-md-4">
                    <div class="mb-3">
                        <label for="año" class="form-label">Año</label>
                        <input type="number" class="form-control" id="año" name="año" required>
                    </div>
                </div>
            </div>

            <div class="row">
                <div class="col-md-4">
                    <div class="mb-3">
                        <label for="color" class="form-label">Color</label>
                        <input type="text" class="form-control" id="color" name="color">
                    </div>
                </div>
                <div class="col-md-4">
                    <div class="mb-3">
                        <label for="tipo_combustible" class="form-label">Tipo de Combustible</label>
                        <select class="form-control" id="tipo_combustible" name="tipo_combustible" required>
                            <option value="Gasolina">Gasolina</option>
                            <option value="Diésel">Diésel</option>
                            <option value="Eléctrico">Eléctrico</option>
                            <option value="Híbrido">Híbrido</option>
                        </select>
                    </div>
                </div>

                <div class="col-md-4">
                    <div class="mb-3">
                        <label for="precio" class="form-label">Precio</label>
                        <input type="number" step="0.01" class="form-control" id="precio" name="precio" required>
                    </div>
                </div>
            </div>

            <div class="row">
                <div class="col-md-4">
                    <div class="mb-3">
                        <label for="estado" class="form-label">Estado</label>
                        <select class="form-control" id="estado" name="estado" required>
                            <option value="Nuevo">Nuevo</option>
                            <option value="Usado">Usado</option>
                        </select>
                    </div>
                </div>
                <div class="col-md-4">
                    <div class="mb-3">
                        <label for="kilometraje" class="form-label">Kilometraje</label>
                        <input type="number" class="form-control" id="kilometraje" name="kilometraje" value="0">
                    </div>
                </div>
                <div class="col-md-4">
                    <div class="mb-3">
                        <label for="fecha_ingreso" class="form-label">Fecha de Ingreso</label>
                        <input type="date" class="form-control" id="fecha_ingreso" name="fecha_ingreso" required>
                    </div>
                </div>
            </div>

            <div class="row">
                <div class="col-md-4">
                    <div class="mb-3">
                        <label for="foto" class="form-label">Foto</label>
                        <input type="file" class="form-control" id="foto" name="foto" accept="image/*">
                        <div id="fotoPreview" class="mt-2"></div>
                    </div>
                </div>
                <div class="col-md-4">
                    <div class="mb-3">
                        <label for="disponible" class="form-label">Disponible</label>
                        <div class="form-check mt-2">
                            <input type="checkbox" class="form-check-input" id="disponible" name="disponible" value="1" checked>
                            <label class="form-check-label" for="disponible">Sí</label>
                        </div>
                    </div>
                </div>
            </div>
            <button type="submit" class="btn btn-primary">Guardar</button>
            <button type="button" class="btn btn-secondary" onclick="limpiarFormulario()">Nuevo</button>
        </form>

        <!-- Tabla -->
        <table class="table">
            <thead>
                <tr>
                    <th>ID</th>
                    <th>Marca</th>
                    <th>Modelo</th>
                    <th>Año</th>
                    <th>Color</th>
                    <th>Tipo Combustible</th>
                    <th>Precio</th>
                    <th>Estado</th>
                    <th>Kilometraje</th>
                    <th>Fecha Ingreso</th>
                    <th>Foto</th>
                    <th>Proveedor</th>
                    <th>Disponible</th>
                    <th>Acciones</th>
                </tr>
            </thead>
            <tbody id="vehiculosTabla">
            </tbody>
        </table>
    </div>

    <script src="https://cdn.jsdelivr.net/npm/bootstrap@5.1.3/dist/js/bootstrap.bundle.min.js"></script>
    <script src="https://cdn.jsdelivr.net/npm/sweetalert2@11.7.3/dist/sweetalert2.all.min.js"></script>

    <script>
        // Esperar a que el documento esté cargado
        document.addEventListener('DOMContentLoaded', function() {
            cargarVehiculos();
            
            // Manejar el envío del formulario
            document.getElementById('vehiculoForm').addEventListener('submit', function(e) {
                e.preventDefault();
                guardarVehiculo();
            });
            
            // Preview de imagen
            document.getElementById('foto').addEventListener('change', function(e) {
                const file = e.target.files[0];
                if (file) {
                    const reader = new FileReader();
                    reader.onload = function(e) {
                        document.getElementById('fotoPreview').innerHTML = `
                            <img src="${e.target.result}" class="img-preview rounded" style="max-width: 100px;">
                        `;
                    }
                    reader.readAsDataURL(file);
                }
            });

            // Agregar dentro del DOMContentLoaded
            document.getElementById('buscarVehiculo').addEventListener('keypress', function(e) {
                if (e.key === 'Enter') {
                    e.preventDefault();
                    buscarVehiculos();
                }
            });

            // Búsqueda en tiempo real
            let timeoutId;
            document.getElementById('buscarVehiculo').addEventListener('input', function(e) {
                clearTimeout(timeoutId); // Limpiar el timeout anterior
                
                // Esperar 300ms después de que el usuario deje de escribir
                timeoutId = setTimeout(() => {
                    const busqueda = e.target.value.trim();
                    
                    // Si el campo está vacío, mostrar todos los vehículos
                    if (busqueda === '') {
                        cargarVehiculos();
                        return;
                    }

                    const xhr = new XMLHttpRequest();
                    xhr.open('POST', '../controladores/vehiculo_controller.php', true);
                    xhr.setRequestHeader('Content-Type', 'application/x-www-form-urlencoded');

                    xhr.onreadystatechange = function() {
                        if (xhr.readyState === 4) {
                            if (xhr.status === 200) {
                                try {
                                    const vehiculos = JSON.parse(xhr.responseText);
                                    let html = '';
                                    vehiculos.forEach(vehiculo => {
                                        const fotoUrl = vehiculo.foto ? `../uploads/${vehiculo.foto}` : '';
                                        html += `
                                            <tr>
                                                <td>${vehiculo.id_vehiculo}</td>
                                                <td>${vehiculo.marca}</td>
                                                <td>${vehiculo.modelo}</td>
                                                <td>${vehiculo.año}</td>
                                                <td>${vehiculo.color}</td>
                                                <td>${vehiculo.tipo_combustible}</td>
                                                <td>${vehiculo.precio}</td>
                                                <td>${vehiculo.estado}</td>
                                                <td>${vehiculo.kilometraje}</td>
                                                <td>${vehiculo.fecha_ingreso}</td>
                                                <td>${vehiculo.foto ? 
                                                    `<img src="${fotoUrl}" alt="Foto vehículo" class="img-thumbnail" style="width: 50px; height: 50px; object-fit: cover;">` : 
                                                    'Sin imagen'}</td>
                                                <td>${vehiculo.disponible == 1 ? 
                                                    '<span class="badge bg-success">Disponible</span>' : 
                                                    '<span class="badge bg-danger">No disponible</span>'}</td>
                                                <td>
                                                    <div class="btn-group">
                                                        <button class="btn btn-warning btn-sm" onclick="editarVehiculo(${vehiculo.id_vehiculo})">
                                                            <i class="fas fa-edit"></i>
                                                        </button>
                                                        <button class="btn btn-danger btn-sm" onclick="eliminarVehiculo(${vehiculo.id_vehiculo})">
                                                            <i class="fas fa-trash"></i>
                                                        </button>
                                                    </div>
                                                </td>
                                            </tr>
                                        `;
                                    });
                                    document.getElementById('vehiculosTabla').innerHTML = html;
                                } catch (error) {
                                    console.error('Error al procesar la respuesta:', error);
                                }
                            }
                        }
                    };

                    xhr.send('accion=buscar&termino=' + encodeURIComponent(busqueda));
                }, 300); // Esperar 300ms antes de hacer la búsqueda
            });
        });

        function guardarVehiculo() {
            const formData = new FormData();
            const id = document.getElementById('id_vehiculo').value;

            // Agregar todos los campos al FormData
            formData.append('marca', document.getElementById('marca').value);
            formData.append('modelo', document.getElementById('modelo').value);
            formData.append('año', document.getElementById('año').value);
            formData.append('color', document.getElementById('color').value);
            formData.append('tipo_combustible', document.getElementById('tipo_combustible').value);
            formData.append('precio', document.getElementById('precio').value);
            formData.append('estado', document.getElementById('estado').value);
            formData.append('kilometraje', document.getElementById('kilometraje').value);
            formData.append('fecha_ingreso', document.getElementById('fecha_ingreso').value);
            formData.append('disponible', document.getElementById('disponible').checked ? 1 : 0);
            
            // Manejar el archivo de foto
            const fotoInput = document.getElementById('foto');
            if (fotoInput.files.length > 0) {
                formData.append('foto', fotoInput.files[0]);
            }

            formData.append('accion', id ? 'actualizar' : 'insertar');
            if(id) formData.append('id_vehiculo', id);

            fetch('../controladores/vehiculo_controller.php', {
                method: 'POST',
                body: formData
            })
            .then(response => response.json())
            .then(data => {
                if(data.success) {
                    Swal.fire({
                        icon: 'success',
                        title: '¡Éxito!',
                        text: id ? 'Vehículo actualizado correctamente' : 'Vehículo guardado correctamente',
                        showConfirmButton: false,
                        timer: 1500
                    });
                    limpiarFormulario();
                    cargarVehiculos();
                } else {
                    throw new Error(data.message || 'Hubo un problema al procesar la operación');
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

        function cargarVehiculos() {
            fetch('../controladores/vehiculo_controller.php', {
                method: 'POST',
                headers: {
                    'Content-Type': 'application/x-www-form-urlencoded',
                },
                body: 'accion=listar'
            })
            .then(response => response.json())
            .then(vehiculos => {
                let html = '';
                vehiculos.forEach(vehiculo => {
                    const fotoUrl = vehiculo.foto ? `../uploads/${vehiculo.foto}` : '';
                    html += `
                        <tr>
                            <td>${vehiculo.id_vehiculo}</td>
                            <td>${vehiculo.marca}</td>
                            <td>${vehiculo.modelo}</td>
                            <td>${vehiculo.año}</td>
                            <td>${vehiculo.color}</td>
                            <td>${vehiculo.tipo_combustible}</td>
                            <td>${vehiculo.precio}</td>
                            <td>${vehiculo.estado}</td>
                            <td>${vehiculo.kilometraje}</td>
                            <td>${vehiculo.fecha_ingreso}</td>
                            <td>${vehiculo.foto ? 
                                `<img src="${fotoUrl}" alt="Foto vehículo" class="img-thumbnail" style="width: 50px; height: 50px; object-fit: cover;">` : 
                                'Sin imagen'}</td>
                            <td>${vehiculo.id_proveedor || 'N/A'}</td>
                            <td>${vehiculo.disponible == 1 ? 'Sí' : 'No'}</td>
                            <td>
                                <button class="btn btn-warning btn-sm" onclick="editarVehiculo(${vehiculo.id_vehiculo})">
                                    <i class="fas fa-edit"></i> Editar
                                </button>
                                <button class="btn btn-danger btn-sm" onclick="eliminarVehiculo(${vehiculo.id_vehiculo})">
                                    <i class="fas fa-trash"></i> Eliminar
                                </button>
                            </td>
                        </tr>
                    `;
                });
                document.getElementById('vehiculosTabla').innerHTML = html;
            })
            .catch(error => {
                console.error('Error:', error);
                Swal.fire({
                    icon: 'error',
                    title: 'Error',
                    text: 'Error al cargar los vehículos'
                });
            });
        }

        function limpiarFormulario() {
            document.getElementById('vehiculoForm').reset();
            document.getElementById('id_vehiculo').value = '';
        }

        function editarVehiculo(id) {
            fetch('../controladores/vehiculo_controller.php', {
                method: 'POST',
                headers: {
                    'Content-Type': 'application/x-www-form-urlencoded',
                },
                body: `accion=obtener&id_vehiculo=${id}`
            })
            .then(response => response.json())
            .then(vehiculo => {
                document.getElementById('id_vehiculo').value = vehiculo.id_vehiculo;
                document.getElementById('marca').value = vehiculo.marca;
                document.getElementById('modelo').value = vehiculo.modelo;
                document.getElementById('año').value = vehiculo.año;
                document.getElementById('color').value = vehiculo.color;
                document.getElementById('tipo_combustible').value = vehiculo.tipo_combustible;
                document.getElementById('precio').value = vehiculo.precio;
                document.getElementById('estado').value = vehiculo.estado;
                document.getElementById('kilometraje').value = vehiculo.kilometraje;
                document.getElementById('fecha_ingreso').value = vehiculo.fecha_ingreso;
                document.getElementById('disponible').checked = vehiculo.disponible == 1;
            })
            .catch(error => {
                console.error('Error:', error);
                Swal.fire({
                    icon: 'error',
                    title: 'Error',
                    text: 'Error al cargar los datos del vehículo'
                });
            });
        }

        function eliminarVehiculo(id) {
            Swal.fire({
                title: '¿Está seguro?',
                text: "Esta acción no se puede revertir",
                icon: 'warning',
                showCancelButton: true,
                confirmButtonColor: '#d33',
                cancelButtonColor: '#3085d6',
                confirmButtonText: 'Sí, eliminar',
                cancelButtonText: 'Cancelar'
            }).then((result) => {
                if (result.isConfirmed) {
                    fetch('../controladores/vehiculo_controller.php', {
                        method: 'POST',
                        headers: {
                            'Content-Type': 'application/x-www-form-urlencoded',
                        },
                        body: `accion=eliminar&id_vehiculo=${id}`
                    })
                    .then(response => response.json())
                    .then(data => {
                        if(data.success) {
                            Swal.fire({
                                icon: 'success',
                                title: '¡Eliminado!',
                                text: 'El vehículo ha sido eliminado.',
                                showConfirmButton: false,
                                timer: 1500
                            });
                            cargarVehiculos();
                        } else {
                            throw new Error(data.message || 'Error al eliminar el vehículo');
                        }
                    })
                    .catch(error => {
                        console.error('Error:', error);
                        Swal.fire({
                            icon: 'error',
                            title: 'Error',
                            text: error.message || 'Error al eliminar el vehículo'
                        });
                    });
                }
            });
        }

        function buscarVehiculos() {
            const busqueda = document.getElementById('buscarVehiculo').value;
            const xhr = new XMLHttpRequest();
            
            xhr.open('POST', '../controladores/vehiculo_controller.php', true);
            xhr.setRequestHeader('Content-Type', 'application/x-www-form-urlencoded');
            
            xhr.onreadystatechange = function() {
                if (xhr.readyState === 4) {
                    if (xhr.status === 200) {
                        try {
                            const vehiculos = JSON.parse(xhr.responseText);
                            let html = '';
                            vehiculos.forEach(vehiculo => {
                                const fotoUrl = vehiculo.foto ? `../uploads/${vehiculo.foto}` : '';
                                html += `
                                    <tr>
                                        <td>${vehiculo.id_vehiculo}</td>
                                        <td>${vehiculo.marca}</td>
                                        <td>${vehiculo.modelo}</td>
                                        <td>${vehiculo.año}</td>
                                        <td>${vehiculo.color}</td>
                                        <td>${vehiculo.tipo_combustible}</td>
                                        <td>${vehiculo.precio}</td>
                                        <td>${vehiculo.estado}</td>
                                        <td>${vehiculo.kilometraje}</td>
                                        <td>${vehiculo.fecha_ingreso}</td>
                                        <td>${vehiculo.foto ? 
                                            `<img src="${fotoUrl}" alt="Foto vehículo" class="img-thumbnail" style="width: 50px; height: 50px; object-fit: cover;">` : 
                                            'Sin imagen'}</td>
                                        <td>${vehiculo.id_proveedor || 'N/A'}</td>
                                        <td>${vehiculo.disponible == 1 ? 'Sí' : 'No'}</td>
                                        <td>
                                            <button class="btn btn-warning btn-sm" onclick="editarVehiculo(${vehiculo.id_vehiculo})">
                                                <i class="fas fa-edit"></i> Editar
                                            </button>
                                            <button class="btn btn-danger btn-sm" onclick="eliminarVehiculo(${vehiculo.id_vehiculo})">
                                                <i class="fas fa-trash"></i> Eliminar
                                            </button>
                                        </td>
                                    </tr>
                                `;
                            });
                            document.getElementById('vehiculosTabla').innerHTML = html;
                        } catch (error) {
                            console.error('Error al procesar la respuesta:', error);
                            Swal.fire({
                                icon: 'error',
                                title: 'Error',
                                text: 'Error al procesar la búsqueda'
                            });
                        }
                    } else {
                        Swal.fire({
                            icon: 'error',
                            title: 'Error',
                            text: 'Error en la comunicación con el servidor'
                        });
                    }
                }
            };

            xhr.onerror = function() {
                Swal.fire({
                    icon: 'error',
                    title: 'Error',
                    text: 'Error en la comunicación con el servidor'
                });
            };

            xhr.send('accion=buscar&termino=' + encodeURIComponent(busqueda));
        }
    </script>
</body>
</html>