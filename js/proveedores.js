document.addEventListener('DOMContentLoaded', function() {
    cargarProveedores();
    cargarEstadisticas();
    
    document.getElementById('proveedorForm').addEventListener('submit', function(e) {
        e.preventDefault();
        guardarProveedor();
    });

    // Búsqueda en tiempo real
    let timeoutId;
    document.getElementById('buscarProveedor').addEventListener('input', function(e) {
        clearTimeout(timeoutId);
        timeoutId = setTimeout(() => {
            const busqueda = e.target.value.trim();
            
            if (busqueda === '') {
                cargarProveedores();
                return;
            }

            const xhr = new XMLHttpRequest();
            xhr.open('POST', '../controladores/proveedor_controller.php', true);
            xhr.setRequestHeader('Content-Type', 'application/x-www-form-urlencoded');

            xhr.onload = function() {
                if (xhr.status === 200) {
                    try {
                        const proveedores = JSON.parse(xhr.responseText);
                        actualizarTablaProveedores(proveedores);
                    } catch (error) {
                        console.error('Error:', error);
                    }
                }
            };

            xhr.send('accion=buscar&termino=' + encodeURIComponent(busqueda));
        }, 300);
    });
});

function cargarProveedores() {
    const xhr = new XMLHttpRequest();
    xhr.open('POST', '../controladores/proveedor_controller.php', true);
    xhr.setRequestHeader('Content-Type', 'application/x-www-form-urlencoded');

    xhr.onload = function() {
        if (xhr.status === 200) {
            try {
                const proveedores = JSON.parse(xhr.responseText);
                actualizarTablaProveedores(proveedores);
            } catch (error) {
                console.error('Error:', error);
            }
        }
    };

    xhr.send('accion=listar');
}

function actualizarTablaProveedores(proveedores) {
    let html = '';
    proveedores.forEach(proveedor => {
        html += `
            <tr>
                <td>${proveedor.id_proveedor}</td>
                <td>${proveedor.nombre_empresa}</td>
                <td>${proveedor.nif || ''}</td>
                <td>${proveedor.direccion || ''}</td>
                <td>${proveedor.telefono || ''}</td>
                <td>${proveedor.email || ''}</td>
                <td>${proveedor.contacto_nombre || ''}</td>
                <td>Activo</td>
                <td>
                    <div class="btn-group">
                        <button class="btn btn-warning btn-sm" onclick="editarProveedor(${proveedor.id_proveedor})">
                            <i class="fas fa-edit"></i>
                        </button>
                        <button class="btn btn-danger btn-sm" onclick="eliminarProveedor(${proveedor.id_proveedor})">
                            <i class="fas fa-trash"></i>
                        </button>
                    </div>
                </td>
            </tr>
        `;
    });
    document.getElementById('tablaProveedores').innerHTML = html;
}

function guardarProveedor() {
    const formData = new FormData(document.getElementById('proveedorForm'));
    const id = document.getElementById('id_proveedor').value;
    
    formData.append('accion', id ? 'actualizar' : 'insertar');
    
    console.log('Enviando datos:', Object.fromEntries(formData));

    const xhr = new XMLHttpRequest();
    xhr.open('POST', '../controladores/proveedor_controller.php', true);

    xhr.onload = function() {
        console.log('Respuesta:', xhr.responseText);
        
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
                    cargarProveedores();
                    cargarEstadisticas();
                } else {
                    throw new Error(response.message);
                }
            } catch (error) {
                console.error('Error:', error);
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

function editarProveedor(id) {
    console.log('Editando proveedor ID:', id);
    
    const xhr = new XMLHttpRequest();
    xhr.open('POST', '../controladores/proveedor_controller.php', true);
    xhr.setRequestHeader('Content-Type', 'application/x-www-form-urlencoded');

    xhr.onload = function() {
        console.log('Respuesta servidor:', xhr.responseText);
        
        if (xhr.status === 200) {
            try {
                const proveedor = JSON.parse(xhr.responseText);
                console.log('Datos proveedor:', proveedor);

                // Llenar el formulario con los datos
                document.getElementById('id_proveedor').value = proveedor.id_proveedor;
                document.getElementById('nombre_empresa').value = proveedor.nombre_empresa;
                document.getElementById('nif').value = proveedor.nif;
                document.getElementById('contacto_nombre').value = proveedor.contacto_nombre || '';
                document.getElementById('telefono').value = proveedor.telefono || '';
                document.getElementById('email').value = proveedor.email || '';
                document.getElementById('direccion').value = proveedor.direccion || '';

                // Cambiar el texto del botón submit
                const submitBtn = document.querySelector('#proveedorForm button[type="submit"]');
                submitBtn.innerHTML = '<i class="fas fa-save me-2"></i>Actualizar';
                
                // Scroll hacia el formulario
                document.querySelector('.form-section').scrollIntoView({ behavior: 'smooth' });
            } catch (error) {
                console.error('Error al procesar datos:', error);
                Swal.fire({
                    icon: 'error',
                    title: 'Error',
                    text: 'Error al cargar los datos del proveedor'
                });
            }
        }
    };

    xhr.onerror = function() {
        console.error('Error de red:', xhr.status);
        Swal.fire({
            icon: 'error',
            title: 'Error',
            text: 'Error de conexión al servidor'
        });
    };

    xhr.send(`accion=obtener&id_proveedor=${id}`);
}

function eliminarProveedor(id) {
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
            xhr.open('POST', '../controladores/proveedor_controller.php', true);
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
                        cargarProveedores();
                        cargarEstadisticas();
                    } else {
                        Swal.fire({
                            icon: 'error',
                            title: 'Error',
                            text: response.message
                        });
                    }
                }
            };

            xhr.send(`accion=eliminar&id_proveedor=${id}`);
        }
    });
}

function limpiarFormulario() {
    document.getElementById('proveedorForm').reset();
    document.getElementById('id_proveedor').value = '';
    const submitBtn = document.querySelector('#proveedorForm button[type="submit"]');
    submitBtn.innerHTML = '<i class="fas fa-save me-2"></i>Guardar';
}

function cargarEstadisticas() {
    const xhr = new XMLHttpRequest();
    xhr.open('POST', '../controladores/proveedor_controller.php', true);
    xhr.setRequestHeader('Content-Type', 'application/x-www-form-urlencoded');

    xhr.onload = function() {
        if (xhr.status === 200) {
            try {
                const response = JSON.parse(xhr.responseText);
                if (response.success) {
                    actualizarEstadisticas(response.data);
                }
            } catch (error) {
                console.error('Error:', error);
            }
        }
    };

    xhr.send('accion=estadisticas');
}

function actualizarEstadisticas(stats) {
    document.getElementById('totalProveedores').textContent = stats.total;
    document.getElementById('proveedoresActivos').textContent = stats.activos;
    document.getElementById('proveedoresConVehiculos').textContent = stats.con_vehiculos;
}

function cambiarEstadoProveedor(id, estado) {
    Swal.fire({
        title: '¿Está seguro?',
        text: `¿Desea ${estado ? 'activar' : 'desactivar'} este proveedor?`,
        icon: 'warning',
        showCancelButton: true,
        confirmButtonColor: estado ? '#28a745' : '#dc3545',
        cancelButtonColor: '#6c757d',
        confirmButtonText: 'Sí, continuar',
        cancelButtonText: 'Cancelar'
    }).then((result) => {
        if (result.isConfirmed) {
            const xhr = new XMLHttpRequest();
            xhr.open('POST', '../controladores/proveedor_controller.php', true);
            xhr.setRequestHeader('Content-Type', 'application/x-www-form-urlencoded');

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
                            cargarProveedores();
                            cargarEstadisticas();
                        }
                    } catch (error) {
                        console.error('Error:', error);
                    }
                }
            };

            xhr.send(`accion=cambiar_estado&id_proveedor=${id}&estado=${estado ? 1 : 0}`);
        }
    });
}

function exportarExcel() {
    const xhr = new XMLHttpRequest();
    xhr.open('POST', '../controladores/proveedor_controller.php', true);
    xhr.setRequestHeader('Content-Type', 'application/x-www-form-urlencoded');
    xhr.responseType = 'blob';

    xhr.onload = function() {
        if (xhr.status === 200) {
            const blob = new Blob([xhr.response], { type: 'application/vnd.ms-excel' });
            const url = window.URL.createObjectURL(blob);
            const a = document.createElement('a');
            a.href = url;
            a.download = 'proveedores.xlsx';
            document.body.appendChild(a);
            a.click();
            window.URL.revokeObjectURL(url);
        }
    };

    xhr.send('accion=exportar_excel');
}

function exportarPDF() {
    const xhr = new XMLHttpRequest();
    xhr.open('POST', '../controladores/proveedor_controller.php', true);
    xhr.setRequestHeader('Content-Type', 'application/x-www-form-urlencoded');
    xhr.responseType = 'blob';

    xhr.onload = function() {
        if (xhr.status === 200) {
            const blob = new Blob([xhr.response], { type: 'application/pdf' });
            const url = window.URL.createObjectURL(blob);
            const a = document.createElement('a');
            a.href = url;
            a.download = 'proveedores.pdf';
            document.body.appendChild(a);
            a.click();
            window.URL.revokeObjectURL(url);
        }
    };

    xhr.send('accion=exportar_pdf');
}

function buscarProveedores() {
    const busqueda = document.getElementById('buscarProveedor').value.trim();
    const estado = document.getElementById('filtroEstado').value;
    
    const xhr = new XMLHttpRequest();
    xhr.open('POST', '../controladores/proveedor_controller.php', true);
    xhr.setRequestHeader('Content-Type', 'application/x-www-form-urlencoded');

    xhr.onload = function() {
        if (xhr.status === 200) {
            try {
                const proveedores = JSON.parse(xhr.responseText);
                actualizarTablaProveedores(proveedores);
            } catch (error) {
                console.error('Error:', error);
            }
        }
    };

    xhr.send(`accion=buscar&termino=${encodeURIComponent(busqueda)}&estado=${estado}`);
}

// Añadir listener para búsqueda en tiempo real
document.getElementById('buscarProveedor').addEventListener('input', function() {
    clearTimeout(this.timeoutId);
    this.timeoutId = setTimeout(() => buscarProveedores(), 300);
});

// Añadir listener para cambio de filtro
document.getElementById('filtroEstado').addEventListener('change', buscarProveedores);

// Agregar después de las funciones existentes

function verDetalleProveedor(id) {
    const xhr = new XMLHttpRequest();
    xhr.open('POST', '../controladores/proveedor_controller.php', true);
    xhr.setRequestHeader('Content-Type', 'application/x-www-form-urlencoded');

    xhr.onload = function() {
        if (xhr.status === 200) {
            try {
                const response = JSON.parse(xhr.responseText);
                const proveedor = response.proveedor;
                const historial = response.historial;

                let historialHtml = '';
                historial.forEach(h => {
                    historialHtml += `
                        <tr>
                            <td>${h.fecha}</td>
                            <td>${h.accion}</td>
                            <td>${h.usuario}</td>
                            <td>${h.detalles}</td>
                        </tr>
                    `;
                });

                Swal.fire({
                    title: `Detalles del Proveedor: ${proveedor.nombre_empresa}`,
                    html: `
                        <div class="container">
                            <div class="row mb-3">
                                <div class="col">
                                    <h5>Información General</h5>
                                    <p><strong>NIF:</strong> ${proveedor.nif}</p>
                                    <p><strong>Contacto:</strong> ${proveedor.contacto_nombre || 'No especificado'}</p>
                                    <p><strong>Teléfono:</strong> ${proveedor.telefono || 'No especificado'}</p>
                                    <p><strong>Email:</strong> ${proveedor.email || 'No especificado'}</p>
                                    <p><strong>Dirección:</strong> ${proveedor.direccion || 'No especificada'}</p>
                                    <p><strong>Estado:</strong> 
                                        <span class="badge ${proveedor.estado ? 'bg-success' : 'bg-danger'}">
                                            ${proveedor.estado ? 'Activo' : 'Inactivo'}
                                        </span>
                                    </p>
                                </div>
                            </div>
                            <div class="row">
                                <div class="col">
                                    <h5>Historial de Cambios</h5>
                                    <div class="table-responsive" style="max-height: 200px;">
                                        <table class="table table-sm">
                                            <thead>
                                                <tr>
                                                    <th>Fecha</th>
                                                    <th>Acción</th>
                                                    <th>Usuario</th>
                                                    <th>Detalles</th>
                                                </tr>
                                            </thead>
                                            <tbody>
                                                ${historialHtml}
                                            </tbody>
                                        </table>
                                    </div>
                                </div>
                            </div>
                        </div>
                    `,
                    width: '800px',
                    showCloseButton: true,
                    showConfirmButton: false
                });
            } catch (error) {
                console.error('Error:', error);
                Swal.fire({
                    icon: 'error',
                    title: 'Error',
                    text: 'Error al cargar los detalles del proveedor'
                });
            }
        }
    };

    xhr.send(`accion=ver_detalle&id_proveedor=${id}`);
}

function validarNIF(nif) {
    const letras = "TRWAGMYFPDXBNJZSQVHLCKE";
    
    // Validar NIF
    if (/^[0-9]{8}[TRWAGMYFPDXBNJZSQVHLCKE]$/i.test(nif)) {
        const numero = nif.substr(0, 8);
        const letra = nif.charAt(8).toUpperCase();
        const calculada = letras.charAt(numero % 23);
        return letra === calculada;
    }
    
    // Validar CIF
    if (/^[ABCDEFGHJKLMNPQRSUVW][0-9]{7}[0-9A-J]$/i.test(nif)) {
        return true;
    }
    
    // Validar NIE
    if (/^[XYZ][0-9]{7}[TRWAGMYFPDXBNJZSQVHLCKE]$/i.test(nif)) {
        const numero = nif.charAt(0).replace('X', '0')
                                  .replace('Y', '1')
                                  .replace('Z', '2') + nif.substr(1, 7);
        const letra = nif.charAt(8).toUpperCase();
        const calculada = letras.charAt(numero % 23);
        return letra === calculada;
    }
    
    return false;
}

function notificarCambioEstado(id, estado) {
    const notification = new Notification('Cambio de Estado', {
        body: `El proveedor ha sido ${estado ? 'activado' : 'desactivado'}`,
        icon: '/path/to/icon.png'
    });

    // Enviar notificación por email si está configurado
    const xhr = new XMLHttpRequest();
    xhr.open('POST', '../controladores/proveedor_controller.php', true);
    xhr.setRequestHeader('Content-Type', 'application/x-www-form-urlencoded');
    xhr.send(`accion=notificar_cambio&id_proveedor=${id}&estado=${estado}`);
}