<!DOCTYPE html>
<html lang="es">
<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <title>Vehículos - AutoMotriz</title>
    <link rel="stylesheet" href="../delante/style.css">
</head>
<body>
    <header>
        <div class="container">
            <div class="header-content">
                <div class="logo">Auto<span>Motriz</span></div>
                <nav>
                <ul>
                        <li><a href="../delante/index.php">Inicio</a></li>
                        <li><a href="../delante/vehiculos.php">Vehículos</a></li>
                        <li><a href="../delante/servicios.php" class="active">Servicios</a></li>
                        <li><a href="../delante/testimonios.php">Testimonios</a></li>
                        <li><a href="../delante/sobrenosotros.php">Sobre Nosotros</a></li>
                        <li><a href="../delante/contacto.php">Contacto</a></li>
                    </ul>
                </nav>
            </div>
        </div>
    </header>

    <section class="page-banner">
        <div class="banner-content">
            <h1>Nuestros Vehículos</h1>
            <p>Encuentra el auto perfecto para ti</p>
        </div>
    </section>

    <section class="vehicle-filters">
        <div class="container">
            <div class="filter-container">
                <div class="filter-group">
                    <label for="brand">Marca:</label>
                    <select id="brand" class="filter-control">
                        <option value="">Todas las marcas</option>
                        <option value="Toyota">Toyota</option>
                        <option value="Honda">Honda</option>
                        <option value="Mazda">Mazda</option>
                        <option value="Ford">Ford</option>
                        <option value="Chevrolet">Chevrolet</option>
                    </select>
                </div>
                <div class="filter-group">
                    <label for="type">Tipo:</label>
                    <select id="type" class="filter-control">
                        <option value="">Todos los tipos</option>
                        <option value="Sedan">Sedán</option>
                        <option value="SUV">SUV</option>
                        <option value="Hatchback">Hatchback</option>
                        <option value="Pickup">Pickup</option>
                    </select>
                </div>
                <div class="filter-group">
                    <label for="price">Precio máximo:</label>
                    <input type="range" id="price" class="filter-control" min="10000" max="50000" step="1000" value="50000">
                    <span id="price-value">$50,000</span>
                </div>
                <button class="btn filter-btn">Aplicar filtros</button>
            </div>
        </div>
    </section>

    <section class="vehicles-list">
        <div class="container">
            <div class="section-title">
                <h2>Vehículos disponibles</h2>
                <p>Amplia selección de modelos para todos los gustos y presupuestos</p>
            </div>
            
            <div class="featured-cars">
                <div class="car-card" id="toyota-corolla">
                    <div class="car-image">
                        <img src="/api/placeholder/300/200" alt="Toyota Corolla">
                    </div>
                    <div class="car-details">
                        <h3 class="car-title">Toyota Corolla 2024</h3>
                        <p class="car-price">$24,990</p>
                        <div class="car-features">
                            <span class="feature">2.0L</span>
                            <span class="feature">Automático</span>
                            <span class="feature">0 km</span>
                        </div>
                        <p class="car-description">El nuevo Toyota Corolla combina eficiencia, seguridad y tecnología en un diseño elegante y moderno. Equipado con el sistema Toyota Safety Sense 2.0 y pantalla táctil de 8 pulgadas con Apple CarPlay y Android Auto.</p>
                        <a href="#" class="btn">Solicitar prueba de manejo</a>
                    </div>
                </div>
                
                <div class="car-card" id="honda-civic">
                    <div class="car-image">
                        <img src="/api/placeholder/300/200" alt="Honda Civic">
                    </div>
                    <div class="car-details">
                        <h3 class="car-title">Honda Civic 2024</h3>
                        <p class="car-price">$26,500</p>
                        <div class="car-features">
                            <span class="feature">1.5L Turbo</span>
                            <span class="feature">CVT</span>
                            <span class="feature">0 km</span>
                        </div>
                        <p class="car-description">El Honda Civic 2024 presenta un rendimiento excepcional con su motor turbo y una excelente economía de combustible. Equipado con Honda Sensing y un sistema de infoentretenimiento de última generación.</p>
                        <a href="#" class="btn">Solicitar prueba de manejo</a>
                    </div>
                </div>
                
                <div class="car-card" id="mazda-cx5">
                    <div class="car-image">
                        <img src="/api/placeholder/300/200" alt="Mazda CX-5">
                    </div>
                    <div class="car-details">
                        <h3 class="car-title">Mazda CX-5 2023</h3>
                        <p class="car-price">$29,990</p>
                        <div class="car-features">
                            <span class="feature">2.5L</span>
                            <span class="feature">Automático</span>
                            <span class="feature">10,000 km</span>
                        </div>
                        <p class="car-description">El Mazda CX-5 ofrece una experiencia de conducción premium con materiales de alta calidad y tecnología avanzada. Su sistema i-ACTIVSENSE garantiza máxima seguridad en todas tus rutas.</p>
                        <a href="#" class="btn">Solicitar prueba de manejo</a>
                    </div>
                </div>
                
                <div class="car-card">
                    <div class="car-image">
                        <img src="/api/placeholder/300/200" alt="Ford Escape">
                    </div>
                    <div class="car-details">
                        <h3 class="car-title">Ford Escape 2024</h3>
                        <p class="car-price">$28,500</p>
                        <div class="car-features">
                            <span class="feature">2.0L EcoBoost</span>
                            <span class="feature">Automático</span>
                            <span class="feature">0 km</span>
                        </div>
                        <p class="car-description">El Ford Escape combina versatilidad y eficiencia con su potente motor EcoBoost. Cuenta con asistentes de conducción Ford Co-Pilot360 y un amplio espacio interior para toda la familia.</p>
                        <a href="#" class="btn">Solicitar prueba de manejo</a>
                    </div>
                </div>
                
                <div class="car-card">
                    <div class="car-image">
                        <img src="/api/placeholder/300/200" alt="Chevrolet Equinox">
                    </div>
                    <div class="car-details">
                        <h3 class="car-title">Chevrolet Equinox 2024</h3>
                        <p class="car-price">$27,900</p>
                        <div class="car-features">
                            <span class="feature">1.5L Turbo</span>
                            <span class="feature">Automático</span>
                            <span class="feature">0 km</span>
                        </div>
                        <p class="car-description">El Chevrolet Equinox ofrece un equilibrio perfecto entre estilo, espacio y tecnología. Su sistema de infoentretenimiento Chevrolet Infotainment 3 y las características de seguridad avanzadas lo convierten en una excelente opción.</p>
                        <a href="#" class="btn">Solicitar prueba de manejo</a>
                    </div>
                </div>
                
                <div class="car-card">
                    <div class="car-image">
                        <img src="/api/placeholder/300/200" alt="Hyundai Tucson">
                    </div>
                    <div class="car-details">
                        <h3 class="car-title">Hyundai Tucson 2023</h3>
                        <p class="car-price">$26,800</p>
                        <div class="car-features">
                            <span class="feature">2.5L</span>
                            <span class="feature">Automático</span>
                            <span class="feature">5,000 km</span>
                        </div>
                        <p class="car-description">El Hyundai Tucson destaca por su diseño futurista y funcionalidad. Con Hyundai SmartSense y pantalla táctil de 10.25 pulgadas, ofrece una experiencia de conducción moderna y conectada.</p>
                        <a href="#" class="btn">Solicitar prueba de manejo</a>
                    </div>
                </div>
            </div>
        </div>
    </section>

    <section class="vehicle-inquiry">
        <div class="container">
            <div class="inquiry-content">
                <h2>¿No encuentras lo que buscas?</h2>
                <p>Dinos qué vehículo estás buscando y te ayudaremos a encontrarlo</p>
                <a href="contacto.html" class="btn">Contactar un asesor</a>
            </div>
        </div>
    </section>

    <footer>
        <div class="container">
            <div class="footer-content">
                <div class="footer-column">
                    <h3>AutoMotriz</h3>
                    <p>Tu concesionario de confianza con más de 20 años de experiencia en el mercado automotriz.</p>
                    <div class="social-icons">
                        <a href="#" class="social-icon">F</a>
                        <a href="#" class="social-icon">I</a>
                        <a href="#" class="social-icon">T</a>
                        <a href="#" class="social-icon">Y</a>
                    </div>
                </div>
                <div class="footer-column">
                    <h3>Enlaces rápidos</h3>
                    <ul class="footer-links">
                        <li><a href="index.html">Inicio</a></li>
                        <li><a href="vehiculos.html">Vehículos</a></li>
                        <li><a href="servicios.html">Servicios</a></li>
                        <li><a href="testimonios.html">Testimonios</a></li>
                        <li><a href="sobre-nosotros.html">Sobre Nosotros</a></li>
                        <li><a href="contacto.html">Contacto</a></li>
                    </ul>
                </div>
                <div class="footer-column">
                    <h3>Servicios</h3>
                    <ul class="footer-links">
                        <li><a href="servicios.html#venta">Venta de vehículos</a></li>
                        <li><a href="servicios.html#financiamiento">Financiamiento</a></li>
                        <li><a href="servicios.html#servicio-tecnico">Servicio técnico</a></li>
                        <li><a href="servicios.html#repuestos">Repuestos originales</a></li>
                        <li><a href="servicios.html#seguros">Seguros</a></li>
                    </ul>
                </div>
                <div class="footer-column">
                    <h3>Boletín</h3>
                    <p>Suscríbete para recibir nuestras últimas novedades y ofertas</p>
                    <div class="form-group">
                        <input type="email" class="form-control" placeholder="Tu correo electrónico">
                    </div>
                    <button class="submit-btn">Suscribirse</button>
                </div>
            </div>
            <div class="copyright">
                <p>&copy; 2025 AutoMotriz. Todos los derechos reservados.</p>
            </div>
        </div>
    </footer>

    <script src="scripts.js"></script>
</body>
</html>