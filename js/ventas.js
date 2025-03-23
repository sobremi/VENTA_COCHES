document.addEventListener('DOMContentLoaded', function() {
    cargarVentas();
    cargarClientes();
    cargarVehiculos();
    
    document.getElementById('ventaForm').addEventListener('submit', function(e) {
        e.preventDefault();
        guardarVenta();
    });
});

function cargarClientes() {
    const xhr = new XMLHttpRequest();
    xhr.open('POST', '../controladores/cliente_controller.php', true);
    xhr.setRequestHeader('Content-Type', 'application/x-www-form-urlencoded');

    xhr.onload = function() {
        if (xhr.status === 200) {
            const clientes = JSON.parse(xhr.responseText);
            const select = document.getElementById('id_cliente');
            select.innerHTML = '<option value="">Seleccione un cliente</option>';
            
            clientes.forEach(cliente => {
                select.innerHTML += `
                    <option value="${cliente.id_cliente}">
                        ${cliente.nombre} ${cliente.apellido} - ${cliente.dni}
                    </option>
                `;
            });
        }
    };

    xhr.send('accion=listar');
}

function cargarVehiculos() {
    const xhr = new XMLHttpRequest();
    xhr.open('POST', '../controladores/vehiculo_controller.php', true);
    xhr.setRequestHeader('Content-Type', 'application/x-www-form-urlencoded');

    xhr.onload = function() {
        if (xhr.status === 200) {
            const vehiculos = JSON.parse(xhr.responseText);
            const select = document.getElementById('id_vehiculo');
            select.innerHTML = '<option value="">Seleccione un vehículo</option>';
            
            vehiculos.forEach(vehiculo => {
                if (vehiculo.disponible == 1) {
                    select.innerHTML += `
                        <option value="${vehiculo.id_vehiculo}">
                            ${vehiculo.marca} ${vehiculo.modelo} - ${vehiculo.año}
                        </option>
                    `;
                }
            });
        }
    };

    xhr.send('accion=listar');
}

function guardarVenta() {
    const formData = new FormData(document.getElementById('ventaForm'));
    const id = document.getElementById('id_venta').value;
    
    formData.append('accion', id ? 'actualizar' : 'insertar');

    const xhr = new XMLHttpRequest();
    xhr.open('POST', '../controladores/venta_controller.php', true);

    xhr.onload = function() {
        if (xhr.status === 200) {
            try {
                const response = JSON.parse(xhr.responseText);
                if (response.success) {
                    Swal.fire({
                        icon: 'success',
                        title: '¡Éxito!',
                        text: response.message,
                        showConfirmButton: false,
                        timer: 1500
                    });
                    limpiarFormulario();
                    cargarVentas();
                } else {
                    throw new Error(response.message);
                }
            } catch (error) {
                Swal.fire({
                    icon: 'error',
                    title: 'Error',
                    text: error.message
                });
            }
        }
    };

    xhr.send(formData);
}

function cargarVentas() {
    const xhr = new XMLHttpRequest();
    xhr.open('POST', '../controladores/venta_controller.php', true);
    xhr.setRequestHeader('Content-Type', 'application/x-www-form-urlencoded');

    xhr.onload = function() {
        if (xhr.status === 200) {
            const ventas = JSON.parse(xhr.responseText);
            let html = '';
            
            ventas.forEach(venta => {
                const estado = {
                    'pendiente': 'bg-warning',
                    'completada': 'bg-success',
                    'cancelada': 'bg-danger'
                };

                html += `
                    <tr>
                        <td>${venta.id_venta}</td>
                        <td>${venta.nombre_cliente} ${venta.apellido_cliente}</td>
                        <td>${venta.marca} ${venta.modelo}</td>
                        <td>${venta.fecha_venta}</td>
                        <td>${parseFloat(venta.precio_venta).toLocaleString('es-ES', {
                            style: 'currency',
                            currency: 'EUR'
                        })}</td>
                        <td>${venta.metodo_pago}</td>
                        <td><span class="badge ${estado[venta.estado_venta]}">${venta.estado_venta}</span></td>
                        <td>
                            <div class="btn-group">
                                <button class="btn btn-warning btn-sm" onclick="editarVenta(${venta.id_venta})">
                                    <i class="fas fa-edit"></i>
                                </button>
                                <button class="btn btn-danger btn-sm" onclick="eliminarVenta(${venta.id_venta})">
                                    <i class="fas fa-trash"></i>
                                </button>
                            </div>
                        </td>
                    </tr>
                `;
            });
            
            document.getElementById('tablaVentas').innerHTML = html;
        }
    };

    xhr.send('accion=listar');
}

function editarVenta(id) {
    const xhr = new XMLHttpRequest();
    xhr.open('POST', '../controladores/venta_controller.php', true);
    xhr.setRequestHeader('Content-Type', 'application/x-www-form-urlencoded');

    xhr.onload = function() {
        if (xhr.status === 200) {
            const venta = JSON.parse(xhr.responseText);
            document.getElementById('id_venta').value = venta.id_venta;
            document.getElementById('id_cliente').value = venta.id_cliente;
            document.getElementById('id_vehiculo').value = venta.id_vehiculo;
            document.getElementById('fecha_venta').value = venta.fecha_venta;
            document.getElementById('precio_venta').value = venta.precio_venta;
            document.getElementById('metodo_pago').value = venta.metodo_pago;
            document.getElementById('estado_venta').value = venta.estado_venta;
            document.getElementById('observaciones').value = venta.observaciones;
        }
    };

    xhr.send(`accion=obtener&id_venta=${id}`);
}

function eliminarVenta(id) {
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
            const xhr = new XMLHttpRequest();
            xhr.open('POST', '../controladores/venta_controller.php', true);
            xhr.setRequestHeader('Content-Type', 'application/x-www-form-urlencoded');

            xhr.onload = function() {
                if (xhr.status === 200) {
                    const response = JSON.parse(xhr.responseText);
                    if (response.success) {
                        Swal.fire({
                            icon: 'success',
                            title: '¡Eliminado!',
                            text: response.message,
                            showConfirmButton: false,
                            timer: 1500
                        });
                        cargarVentas();
                    } else {
                        Swal.fire({
                            icon: 'error',
                            title: 'Error',
                            text: response.message
                        });
                    }
                }
            };

            xhr.send(`accion=eliminar&id_venta=${id}`);
        }
    });
}

function limpiarFormulario() {
    document.getElementById('ventaForm').reset();
    document.getElementById('id_venta').value = '';
}