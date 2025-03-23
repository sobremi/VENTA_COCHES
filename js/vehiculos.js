document.addEventListener('DOMContentLoaded', function() {
    // Cargar datos iniciales
    cargarVehiculos();
    cargarProveedores();
    cargarEstadisticas();
    
    // Configurar listeners
    document.getElementById('buscarVehiculo')?.addEventListener('input', function() {
        clearTimeout(this.timeoutId);
        this.timeoutId = setTimeout(() => buscarVehiculos(), 300);
    });

    document.getElementById('filtroDisponibilidad')?.addEventListener('change', buscarVehiculos);
    document.getElementById('precioMin')?.addEventListener('input', buscarVehiculos);
    document.getElementById('precioMax')?.addEventListener('input', buscarVehiculos);

    // Configurar el modal
    const vehiculoModal = document.getElementById('vehiculoModal');
    if (vehiculoModal) {
        vehiculoModal.addEventListener('hidden.bs.modal', function() {
            document.getElementById('vehiculoForm').reset();
            document.getElementById('modalTitle').textContent = 'Nuevo Vehículo';
        });
    }
});

function cargarProveedores() {
    const xhr = new XMLHttpRequest();
    xhr.open('POST', '../controladores/proveedor_controller.php', true);
    xhr.setRequestHeader('Content-Type', 'application/x-www-form-urlencoded');

    xhr.onload = function() {
        if (xhr.status === 200) {
            try {
                const proveedores = JSON.parse(xhr.responseText);
                const select = document.getElementById('id_proveedor');
                select.innerHTML = '<option value="">Seleccione un proveedor</option>';
                
                // Log para depuración
                console.log('Proveedores cargados:', proveedores);
                
                if (Array.isArray(proveedores)) {
                    proveedores.forEach(proveedor => {
                        select.innerHTML += `
                            <option value="${proveedor.id_proveedor}">
                                ${proveedor.nombre_empresa}
                            </option>`;
                    });
                } else {
                    console.error('La respuesta no es un array:', proveedores);
                }
            } catch (error) {
                console.error('Error al parsear proveedores:', error);
            }
        } else {
            console.error('Error al cargar proveedores:', xhr.status);
        }
    };

    xhr.onerror = function() {
        console.error('Error de red al cargar proveedores');
    };

    xhr.send('accion=listar');
}

function cargarVehiculos() {
    const xhr = new XMLHttpRequest();
    xhr.open('POST', '../controladores/vehiculo_controller.php', true);
    xhr.setRequestHeader('Content-Type', 'application/x-www-form-urlencoded');

    xhr.onload = function() {
        if (xhr.status === 200) {
            try {
                const vehiculos = JSON.parse(xhr.responseText);
                actualizarGridVehiculos(vehiculos);
            } catch (error) {
                console.error('Error:', error);
            }
        }
    };

    xhr.send('accion=listar');
}

function actualizarGridVehiculos(vehiculos) {
    const grid = document.getElementById('gridVehiculos');
    let html = '';

    vehiculos.forEach(vehiculo => {
        // Determinar la ruta de la imagen
        const imagenUrl = vehiculo.foto 
            ? `../uploads/vehiculos/${vehiculo.foto}` 
            : '../assets/img/default-car.jpg';

        html += `
            <div class="col-md-4 mb-4">
                <div class="card vehicle-card h-100">
                    <div class="position-relative">
                        <span class="badge ${vehiculo.disponible ? 'bg-success' : 'bg-danger'} badge-disponible">
                            ${vehiculo.disponible ? 'Disponible' : 'No disponible'}
                        </span>
                        <div class="card-img-wrapper" style="height: 200px; overflow: hidden;">
                            <img src="${imagenUrl}" 
                                 class="card-img-top vehicle-image" 
                                 alt="${vehiculo.marca} ${vehiculo.modelo}"
                                 style="width: 100%; height: 100%; object-fit: cover;"
                                 onerror="this.onerror=null; this.src='../assets/img/default-car.jpg'">
                        </div>
                    </div>
                    <div class="card-body">
                        <h5 class="card-title">${vehiculo.marca} ${vehiculo.modelo}</h5>
                        <p class="card-text">
                            <strong>Año:</strong> ${vehiculo.año || 'No especificado'}<br>
                            <strong>Color:</strong> ${vehiculo.color || 'No especificado'}<br>
                            <strong>Combustible:</strong> ${vehiculo.tipo_combustible || 'No especificado'}<br>
                            <strong>Precio:</strong> €${vehiculo.precio || '0'}<br>
                            <strong>Estado:</strong> ${vehiculo.estado || 'No especificado'}<br>
                            <strong>Kilometraje:</strong> ${vehiculo.kilometraje || '0'} km<br>
                            <strong>Proveedor:</strong> ${vehiculo.proveedor || 'No especificado'}
                        </p>
                    </div>
                    <div class="card-footer bg-transparent border-top-0">
                        <div class="btn-group w-100" role="group">
                            <button type="button" class="btn btn-warning btn-sm" 
                                    onclick="editarVehiculo(${vehiculo.id_vehiculo})" 
                                    title="Editar">
                                <i class="fas fa-edit"></i>
                            </button>
                            <button type="button" 
                                    class="btn btn-${vehiculo.disponible ? 'danger' : 'success'} btn-sm"
                                    onclick="cambiarDisponibilidad(${vehiculo.id_vehiculo}, ${!vehiculo.disponible})"
                                    title="${vehiculo.disponible ? 'Desactivar' : 'Activar'}">
                                <i class="fas fa-${vehiculo.disponible ? 'ban' : 'check'}"></i>
                            </button>
                            <button type="button" class="btn btn-danger btn-sm" 
                                    onclick="eliminarVehiculo(${vehiculo.id_vehiculo})"
                                    title="Eliminar">
                                <i class="fas fa-trash"></i>
                            </button>
                        </div>
                    </div>
                </div>
            </div>`;
    });

    grid.innerHTML = html || '<div class="col-12"><p class="text-center">No se encontraron vehículos</p></div>';
}

function guardarVehiculo() {
    const form = document.getElementById('vehiculoForm');
    if (!form.checkValidity()) {
        form.reportValidity();
        return;
    }

    const formData = new FormData(form);
    const id = document.getElementById('id_vehiculo').value;
    
    // Asegurarnos que el año se envía con el nombre correcto
    formData.set('año', formData.get('anio')); // Corregir nombre del campo
    formData.append('accion', id ? 'actualizar' : 'insertar');

    // Debug para ver qué datos se están enviando
    const datosEnviados = {};
    formData.forEach((value, key) => {
        datosEnviados[key] = value;
    });
    console.log('Datos a enviar:', datosEnviados);

    const xhr = new XMLHttpRequest();
    xhr.open('POST', '../controladores/vehiculo_controller.php', true);

    xhr.onload = function() {
        console.log('Respuesta del servidor:', xhr.responseText);
        
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
                    }).then(() => {
                        form.reset();
                        const modal = bootstrap.Modal.getInstance(document.getElementById('vehiculoModal'));
                        modal.hide();
                        actualizarDespuesDeCambios();
                    });
                } else {
                    throw new Error(response.message || 'Error al guardar el vehículo');
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

    xhr.onerror = function() {
        console.error('Error de red');
        Swal.fire({
            icon: 'error',
            title: 'Error',
            text: 'Error de conexión al servidor'
        });
    };

    xhr.send(formData);
}

function editarVehiculo(id) {
    console.log('Editando vehículo:', id);
    
    const xhr = new XMLHttpRequest();
    xhr.open('POST', '../controladores/vehiculo_controller.php', true);
    xhr.setRequestHeader('Content-Type', 'application/x-www-form-urlencoded');

    xhr.onload = function() {
        console.log('Respuesta servidor:', xhr.responseText);

        if (xhr.status === 200) {
            try {
                const vehiculo = JSON.parse(xhr.responseText);
                
                // Rellenar formulario
                const form = document.getElementById('vehiculoForm');
                form.id_vehiculo.value = vehiculo.id_vehiculo;
                form.marca.value = vehiculo.marca;
                form.modelo.value = vehiculo.modelo;
                form.anio.value = vehiculo.anio;
                form.precio.value = vehiculo.precio;
                form.kilometraje.value = vehiculo.kilometraje;
                form.id_proveedor.value = vehiculo.id_proveedor;

                // Actualizar título y mostrar modal
                document.getElementById('modalTitle').textContent = 'Editar Vehículo';
                const modal = new bootstrap.Modal(document.getElementById('vehiculoModal'));
                modal.show();

            } catch (error) {
                console.error('Error:', error);
                Swal.fire({
                    icon: 'error',
                    title: 'Error',
                    text: 'Error al cargar los datos del vehículo'
                });
            }
        }
    };

    xhr.send(`accion=obtener&id_vehiculo=${id}`);
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
            const xhr = new XMLHttpRequest();
            xhr.open('POST', '../controladores/vehiculo_controller.php', true);
            xhr.setRequestHeader('Content-Type', 'application/x-www-form-urlencoded');

            xhr.onload = function() {
                if (xhr.status === 200) {
                    try {
                        const response = JSON.parse(xhr.responseText);
                        if (response.success) {
                            cargarVehiculos(); // Recargar la lista
                            Swal.fire({
                                icon: 'success',
                                title: '¡Eliminado!',
                                text: response.message,
                                showConfirmButton: false,
                                timer: 1500
                            });
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

            xhr.send(`accion=eliminar&id_vehiculo=${id}`);
        }
    });
}

function cambiarDisponibilidad(id, disponible) {
    console.log('Cambiando disponibilidad:', id, disponible);
    
    const xhr = new XMLHttpRequest();
    xhr.open('POST', '../controladores/vehiculo_controller.php', true);
    xhr.setRequestHeader('Content-Type', 'application/x-www-form-urlencoded');

    xhr.onload = function() {
        if (xhr.status === 200) {
            try {
                const response = JSON.parse(xhr.responseText);
                if (response.success) {
                    cargarVehiculos(); // Recargar la lista
                    Swal.fire({
                        icon: 'success',
                        title: '¡Éxito!',
                        text: 'Estado actualizado correctamente',
                        showConfirmButton: false,
                        timer: 1500
                    });
                }
            } catch (error) {
                console.error('Error:', error);
                Swal.fire({
                    icon: 'error',
                    title: 'Error',
                    text: 'No se pudo actualizar el estado'
                });
            }
        }
    };

    xhr.send(`accion=cambiar_disponibilidad&id_vehiculo=${id}&disponible=${disponible ? 1 : 0}`);
}

function buscarVehiculos() {
    const busqueda = document.getElementById('buscarVehiculo').value.trim();
    const disponibilidad = document.getElementById('filtroDisponibilidad').value;
    const precioMin = document.getElementById('precioMin').value;
    const precioMax = document.getElementById('precioMax').value;
    
    const xhr = new XMLHttpRequest();
    xhr.open('POST', '../controladores/vehiculo_controller.php', true);
    xhr.setRequestHeader('Content-Type', 'application/x-www-form-urlencoded');

    xhr.onload = function() {
        if (xhr.status === 200) {
            try {
                const vehiculos = JSON.parse(xhr.responseText);
                actualizarGridVehiculos(vehiculos);
            } catch (error) {
                console.error('Error:', error);
            }
        }
    };

    xhr.send(`accion=listar&marca=${busqueda}&disponible=${disponibilidad}&precio_min=${precioMin}&precio_max=${precioMax}`);
}

function mostrarNotificacion(mensaje, tipo = 'success') {
    const toast = document.getElementById('alertToast');
    toast.querySelector('.toast-body').textContent = mensaje;
    toast.classList.add(`bg-${tipo}`);
    const bsToast = new bootstrap.Toast(toast);
    bsToast.show();
}

function resetearFormulario() {
    const form = document.getElementById('vehiculoForm');
    form.reset();
    form.querySelectorAll('.is-invalid').forEach(element => {
        element.classList.remove('is-invalid');
    });
}

function formatearPrecio(precio) {
    return new Intl.NumberFormat('es-ES', {
        style: 'currency',
        currency: 'XAF'
    }).format(precio);
}

function validarFormulario() {
    const form = document.getElementById('vehiculoForm');
    const campos = [
        { id: 'marca', mensaje: 'La marca es obligatoria' },
        { id: 'modelo', mensaje: 'El modelo es obligatorio' },
        { id: 'año', mensaje: 'El año es obligatorio' },
        { id: 'color', mensaje: 'El color es obligatorio' },
        { id: 'tipo_combustible', mensaje: 'El tipo de combustible es obligatorio' },
        { id: 'precio', mensaje: 'El precio es obligatorio' },
        { id: 'estado', mensaje: 'El estado es obligatorio' },
        { id: 'kilometraje', mensaje: 'El kilometraje es obligatorio' },
        { id: 'id_proveedor', mensaje: 'El proveedor es obligatorio' }
    ];

    let valido = true;
    let mensajesError = [];

    campos.forEach(campo => {
        const elemento = form[campo.id];
        if (!elemento.value.trim()) {
            valido = false;
            mensajesError.push(campo.mensaje);
            elemento.classList.add('is-invalid');
        } else {
            elemento.classList.remove('is-invalid');
        }
    });

    if (!valido) {
        Swal.fire({
            icon: 'error',
            title: 'Error de validación',
            html: mensajesError.join('<br>')
        });
    }

    return valido;
}

function cargarEstadisticas() {
    const xhr = new XMLHttpRequest();
    xhr.open('POST', '../controladores/vehiculo_controller.php', true);
    xhr.setRequestHeader('Content-Type', 'application/x-www-form-urlencoded');

    xhr.onload = function() {
        if (xhr.status === 200) {
            try {
                const stats = JSON.parse(xhr.responseText);
                actualizarEstadisticas(stats);
            } catch (error) {
                console.error('Error al cargar estadísticas:', error);
            }
        }
    };

    xhr.send('accion=estadisticas');
}

function actualizarEstadisticas(stats) {
    document.getElementById('totalVehiculos').textContent = stats.total || 0;
    document.getElementById('vehiculosDisponibles').textContent = stats.disponibles || 0;
    document.getElementById('precioPromedio').textContent = formatearPrecio(stats.precioPromedio || 0);
}

function previsualizarImagen(input) {
    if (input.files && input.files[0]) {
        const reader = new FileReader();
        reader.onload = function(e) {
            const preview = document.getElementById('previewImagen');
            preview.src = e.target.result;
            preview.style.display = 'block';
        };
        reader.readAsDataURL(input.files[0]);
    }
}

function exportarExcel() {
    const xhr = new XMLHttpRequest();
    xhr.open('POST', '../controladores/vehiculo_controller.php', true);
    xhr.setRequestHeader('Content-Type', 'application/x-www-form-urlencoded');
    xhr.responseType = 'blob'; // Para manejar la descarga del archivo

    xhr.onload = function() {
        if (xhr.status === 200) {
            const blob = new Blob([xhr.response], { 
                type: 'application/vnd.ms-excel' 
            });
            const url = window.URL.createObjectURL(blob);
            const a = document.createElement('a');
            a.href = url;
            a.download = 'vehiculos.xls';
            document.body.appendChild(a);
            a.click();
            window.URL.revokeObjectURL(url);
        } else {
            Swal.fire({
                icon: 'error',
                title: 'Error',
                text: 'Error al exportar a Excel'
            });
        }
    };

    xhr.send('accion=exportar_excel');
}

function generarReporte() {
    const xhr = new XMLHttpRequest();
    xhr.open('POST', '../controladores/vehiculo_controller.php', true);
    xhr.setRequestHeader('Content-Type', 'application/x-www-form-urlencoded');

    xhr.onload = function() {
        if (xhr.status === 200) {
            try {
                const data = JSON.parse(xhr.responseText);
                
                Swal.fire({
                    title: 'Reporte de Vehículos',
                    html: `
                        <div class="table-responsive">
                            <table class="table table-bordered">
                                <tr>
                                    <th>Total Vehículos</th>
                                    <td>${data.total}</td>
                                </tr>
                                <tr>
                                    <th>Vehículos Disponibles</th>
                                    <td>${data.disponibles}</td>
                                </tr>
                                <tr>
                                    <th>Precio Promedio</th>
                                    <td>${formatearPrecio(data.precioPromedio)}</td>
                                </tr>
                            </table>
                        </div>
                    `,
                    width: 600
                });
            } catch (error) {
                console.error('Error:', error);
                Swal.fire({
                    icon: 'error',
                    title: 'Error',
                    text: 'Error al generar el reporte'
                });
            }
        }
    };

    xhr.send('accion=estadisticas');
}

function mostrarGraficos() {
    const xhr = new XMLHttpRequest();
    xhr.open('POST', '../controladores/vehiculo_controller.php', true);
    xhr.setRequestHeader('Content-Type', 'application/x-www-form-urlencoded');

    xhr.onload = function() {
        if (xhr.status === 200) {
            try {
                const data = JSON.parse(xhr.responseText);
                
                Swal.fire({
                    title: 'Estadísticas Detalladas',
                    html: `
                        <div class="row">
                            <div class="col-md-6">
                                <canvas id="graficoDisponibilidad"></canvas>
                            </div>
                            <div class="col-md-6">
                                <canvas id="graficoCombustible"></canvas>
                            </div>
                        </div>
                        <div class="row mt-3">
                            <div class="col-12">
                                <canvas id="graficoEstados"></canvas>
                            </div>
                        </div>
                    `,
                    width: 900,
                    didRender: () => {
                        // Gráfico de disponibilidad
                        new Chart(document.getElementById('graficoDisponibilidad'), {
                            type: 'pie',
                            data: {
                                labels: ['Disponibles', 'No Disponibles'],
                                datasets: [{
                                    data: [data.disponibles, data.total - data.disponibles],
                                    backgroundColor: ['#4bc0c0', '#ff6384']
                                }]
                            }
                        });

                        // Gráfico por tipo de combustible
                        const combustibleData = data.porTipoCombustible;
                        new Chart(document.getElementById('graficoCombustible'), {
                            type: 'bar',
                            data: {
                                labels: combustibleData.map(item => item.tipo_combustible),
                                datasets: [{
                                    label: 'Por Tipo de Combustible',
                                    data: combustibleData.map(item => item.cantidad),
                                    backgroundColor: '#36a2eb'
                                }]
                            }
                        });

                        // Gráfico por estado
                        const estadoData = data.porEstado;
                        new Chart(document.getElementById('graficoEstados'), {
                            type: 'bar',
                            data: {
                                labels: estadoData.map(item => item.estado),
                                datasets: [{
                                    label: 'Por Estado',
                                    data: estadoData.map(item => item.cantidad),
                                    backgroundColor: '#ffcd56'
                                }]
                            }
                        });
                    }
                });
            } catch (error) {
                console.error('Error:', error);
                Swal.fire({
                    icon: 'error',
                    title: 'Error',
                    text: 'Error al cargar los gráficos'
                });
            }
        }
    };

    xhr.send('accion=estadisticas');
}

function exportarPDF() {
    const xhr = new XMLHttpRequest();
    xhr.open('POST', '../controladores/vehiculo_controller.php', true);
    xhr.setRequestHeader('Content-Type', 'application/x-www-form-urlencoded');

    xhr.onload = function() {
        if (xhr.status === 200) {
            try {
                const vehiculos = JSON.parse(xhr.responseText);
                generarPDF(vehiculos);
            } catch (error) {
                console.error('Error:', error);
                Swal.fire({
                    icon: 'error',
                    title: 'Error',
                    text: 'Error al generar el PDF'
                });
            }
        }
    };

    xhr.send('accion=obtener_todos');
}

function generarPDF(vehiculos) {
    const { jsPDF } = window.jspdf;
    const doc = new jsPDF();

    // Título
    doc.setFontSize(20);
    doc.text('Reporte de Vehículos', 14, 20);

    // Fecha
    doc.setFontSize(10);
    doc.text(`Fecha: ${new Date().toLocaleDateString()}`, 14, 30);

    // Tabla de vehículos
    const columns = [
        'ID', 'Marca', 'Modelo', 'Año', 'Color', 'Combustible', 
        'Precio', 'Estado', 'Kilometraje'
    ];

    const data = vehiculos.map(v => [
        v.id_vehiculo,
        v.marca,
        v.modelo,
        v.año,
        v.color,
        v.tipo_combustible,
        `€${v.precio}`,
        v.estado,
        `${v.kilometraje} km`
    ]);

    doc.autoTable({
        head: [columns],
        body: data,
        startY: 35,
        styles: {
            fontSize: 8
        },
        headStyles: {
            fillColor: [41, 128, 185],
            textColor: 255
        },
        alternateRowStyles: {
            fillColor: [245, 245, 245]
        }
    });

    // Estadísticas
    const startY = doc.lastAutoTable.finalY + 10;
    doc.setFontSize(12);
    doc.text('Resumen:', 14, startY);
    doc.setFontSize(10);
    doc.text(`Total de vehículos: ${vehiculos.length}`, 20, startY + 7);
    doc.text(`Disponibles: ${vehiculos.filter(v => v.disponible).length}`, 20, startY + 14);

    // Guardar PDF
    doc.save('vehiculos.pdf');
}
