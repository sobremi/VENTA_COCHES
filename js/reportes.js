/**
 * Función principal para solicitar y mostrar el reporte general de vehículos
 * Realiza una petición AJAX al controlador para obtener las estadísticas generales
 */
function mostrarReporteGeneral() {
    const xhr = new XMLHttpRequest();
    xhr.open('POST', '../controladores/vehiculo_controller.php', true);
    xhr.setRequestHeader('Content-Type', 'application/x-www-form-urlencoded');

    // Manejador de la respuesta del servidor
    xhr.onload = function() {
        if (xhr.status === 200) {
            try {
                const data = JSON.parse(xhr.responseText);
                if (data.success) {
                    mostrarModalReporteGeneral(data.data);
                } else {
                    throw new Error(data.message);
                }
            } catch (error) {
                // Manejo de errores con SweetAlert2
                Swal.fire({
                    icon: 'error',
                    title: 'Error',
                    text: error.message || 'Error al cargar el reporte'
                });
            }
        }
    };

    xhr.send('accion=reporte_general');
}

/**
 * Muestra un modal con la información del reporte general
 * Utiliza SweetAlert2 para mostrar una tabla con estadísticas
 * @param {Object} data - Objeto con los datos del reporte
 */
function mostrarModalReporteGeneral(data) {
    Swal.fire({
        title: 'Reporte General de Vehículos',
        // Template literal para construir la tabla HTML con los datos
        html: `
            <div class="table-responsive">
                <table class="table table-bordered">
                    <tr>
                        <th>Total de Vehículos</th>
                        <td>${data.total}</td>
                    </tr>
                    <!-- Datos estadísticos formateados -->
                    <tr>
                        <th>Disponibles</th>
                        <td>${data.disponibles}</td>
                    </tr>
                    <tr>
                        <th>Precio Promedio</th>
                        <td>${formatearPrecio(data.precio_promedio)}</td>
                    </tr>
                    <tr>
                        <th>Precio Mínimo</th>
                        <td>${formatearPrecio(data.precio_minimo)}</td>
                    </tr>
                    <tr>
                        <th>Precio Máximo</th>
                        <td>${formatearPrecio(data.precio_maximo)}</td>
                    </tr>
                    <tr>
                        <th>Total de Marcas</th>
                        <td>${data.total_marcas}</td>
                    </tr>
                    <tr>
                        <th>Total de Modelos</th>
                        <td>${data.total_modelos}</td>
                    </tr>
                </table>
            </div>
        `,
        width: 600,
        confirmButtonText: 'Cerrar'
    });
}

/**
 * Función para obtener y mostrar el gráfico de estadísticas mensuales
 * Realiza una petición AJAX para obtener los datos mensuales
 */
function mostrarGraficoMensual() {
    const xhr = new XMLHttpRequest();
    xhr.open('POST', '../controladores/vehiculo_controller.php', true);
    xhr.setRequestHeader('Content-Type', 'application/x-www-form-urlencoded');

    // Manejador de la respuesta para los datos mensuales
    xhr.onload = function() {
        if (xhr.status === 200) {
            try {
                const data = JSON.parse(xhr.responseText);
                if (data.success) {
                    // Si la petición es exitosa, muestra el gráfico
                    mostrarModalGraficoMensual(data.data);
                } else {
                    throw new Error(data.message);
                }
            } catch (error) {
                // Manejo de errores en la carga de datos mensuales
                Swal.fire({
                    icon: 'error',
                    title: 'Error',
                    text: error.message || 'Error al cargar los datos'
                });
            }
        }
    };

    xhr.send('accion=reporte_mensual');
}

/**
 * Muestra el gráfico mensual de estadísticas en un modal
 * @param {Array} data - Array con los datos mensuales
 */
function mostrarModalGraficoMensual(data) {
    Swal.fire({
        title: 'Estadísticas Mensuales',
        html: `
            <!-- Contenedor para el gráfico -->
            <div class="chart-container">
                <canvas id="graficoMensual"></canvas>
            </div>
            <!-- Tabla de resumen mensual -->
            <div class="table-responsive mt-4">
                <table class="table table-sm table-striped">
                    <thead>
                        <tr>
                            <th>Mes</th>
                            <th>Total Vehículos</th>
                            <th>Valor Total</th>
                        </tr>
                    </thead>
                    <tbody>
                        ${generarFilasTabla(data)}
                    </tbody>
                </table>
            </div>
        `,
        width: 800,
        didRender: () => inicializarGrafico(data)
    });
}

/**
 * Genera las filas de la tabla de resumen
 * @param {Array} data - Datos mensuales
 * @returns {string} HTML con las filas de la tabla
 */
function generarFilasTabla(data) {
    return data.map(item => `
        <tr>
            <td>${formatearFecha(item.mes)}</td>
            <td class="text-center">${item.total_ingresos}</td>
            <td class="text-end">${formatearPrecio(item.valor_total)}</td>
        </tr>
    `).join('');
}

/**
 * Inicializa el gráfico de Chart.js con los datos mensuales
 * @param {Array} data - Datos para el gráfico
 */
function inicializarGrafico(data) {
    const ctx = document.getElementById('graficoMensual').getContext('2d');
    new Chart(ctx, {
        type: 'bar',
        data: {
            labels: data.map(item => formatearFecha(item.mes)),
            datasets: [
                {
                    label: 'Valor Total (€)',
                    data: data.map(item => item.valor_total),
                    backgroundColor: 'rgba(54, 162, 235, 0.5)',
                    borderColor: 'rgba(54, 162, 235, 1)',
                    yAxisID: 'y-valor'
                },
                {
                    label: 'Cantidad de Vehículos',
                    data: data.map(item => item.total_ingresos),
                    backgroundColor: 'rgba(255, 99, 132, 0.5)',
                    borderColor: 'rgba(255, 99, 132, 1)',
                    yAxisID: 'y-cantidad'
                }
            ]
        },
        options: configurarOpcionesGrafico()
    });
}

/**
 * Configura las opciones del gráfico
 * @returns {Object} Opciones de configuración para Chart.js
 */
function configurarOpcionesGrafico() {
    return {
        responsive: true,
        interaction: {
            intersect: false,
            mode: 'index'
        },
        scales: {
            'y-valor': {
                type: 'linear',
                position: 'left',
                title: {
                    display: true,
                    text: 'Valor Total (€)'
                }
            },
            'y-cantidad': {
                type: 'linear',
                position: 'right',
                grid: {
                    drawOnChartArea: false
                },
                title: {
                    display: true,
                    text: 'Cantidad'
                }
            }
        },
        plugins: {
            tooltip: {
                callbacks: {
                    label: function(context) {
                        const label = context.dataset.label || '';
                        const value = context.parsed.y;
                        return label.includes('€') ? 
                            `${label}: ${formatearPrecio(value)}` :
                            `${label}: ${value}`;
                    }
                }
            }
        }
    };
}

/**
 * Formatea una fecha en formato YYYY-MM a texto legible
 * @param {string} fecha - Fecha en formato YYYY-MM
 * @returns {string} Fecha formateada (ej: "Enero 2024")
 */
function formatearFecha(fecha) {
    const [year, month] = fecha.split('-');
    return new Date(year, month - 1)
        .toLocaleDateString('es-ES', { 
            year: 'numeric', 
            month: 'long' 
        });
}