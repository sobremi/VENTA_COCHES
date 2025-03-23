<!DOCTYPE html>
<html lang="es">
<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <title>AutoMotriz - Concesionario de Vehículos</title>
    <style>
        /* Reset básico */
        * {
            margin: 0;
            padding: 0;
            box-sizing: border-box;
            font-family: 'Segoe UI', Tahoma, Geneva, Verdana, sans-serif;
        }
        
        /* Estilos generales */
        body {
            background-color: #f5f5f5;
            color: #333;
            line-height: 1.6;
        }
        
        a {
            text-decoration: none;
            color: #1e5799;
        }
        
        .container {
            width: 90%;
            max-width: 1200px;
            margin: 0 auto;
        }
        
        /* Cabecera */
        header {
            background-color: #1e5799;
            color: white;
            padding: 1rem 0;
            position: sticky;
            top: 0;
            z-index: 100;
            box-shadow: 0 2px 5px rgba(0,0,0,0.1);
        }
        
        .navbar {
            display: flex;
            justify-content: space-between;
            align-items: center;
        }
        
        .logo {
            font-size: 1.8rem;
            font-weight: bold;
        }
        
        .menu {
            display: flex;
            list-style: none;
        }
        
        .menu li {
            margin-left: 1.5rem;
        }
        
        .menu a {
            color: white;
            font-weight: 500;
            padding: 0.5rem;
            transition: color 0.3s;
        }
        
        .menu a:hover {
            color: #ffd700;
        }
        
        /* Hero section */
        .hero {
            height: 500px;
            background-image: linear-gradient(rgba(0,0,0,0.5), rgba(0,0,0,0.5)), url("/api/placeholder/1200/500");
            background-size: cover;
            background-position: center;
            color: white;
            display: flex;
            align-items: center;
            text-align: center;
        }
        
        .hero-content {
            max-width: 800px;
            margin: 0 auto;
            padding: 2rem;
        }
        
        .hero h1 {
            font-size: 3rem;
            margin-bottom: 1rem;
        }
        
        .hero p {
            font-size: 1.2rem;
            margin-bottom: 2rem;
        }
        
        .btn {
            display: inline-block;
            background-color: #ffd700;
            color: #1e5799;
            padding: 0.8rem 1.5rem;
            border-radius: 5px;
            font-weight: bold;
            text-transform: uppercase;
            transition: all 0.3s;
        }
        
        .btn:hover {
            background-color: white;
            transform: translateY(-3px);
            box-shadow: 0 5px 15px rgba(0,0,0,0.1);
        }
        
        /* Sección de vehículos destacados */
        .section {
            padding: 4rem 0;
        }
        
        .section-title {
            text-align: center;
            margin-bottom: 3rem;
            font-size: 2.2rem;
            color: #1e5799;
        }
        
        .cars-grid {
            display: grid;
            grid-template-columns: repeat(auto-fill, minmax(300px, 1fr));
            gap: 2rem;
        }
        
        .car-card {
            background-color: white;
            border-radius: 8px;
            overflow: hidden;
            box-shadow: 0 3px 10px rgba(0,0,0,0.1);
            transition: transform 0.3s;
        }
        
        .car-card:hover {
            transform: translateY(-10px);
        }
        
        .car-img {
            height: 200px;
            width: 100%;
            object-fit: cover;
        }
        
        .car-info {
            padding: 1.5rem;
        }
        
        .car-title {
            font-size: 1.4rem;
            margin-bottom: 0.5rem;
            color: #1e5799;
        }
        
        .car-price {
            font-size: 1.6rem;
            font-weight: bold;
            color: #333;
            margin: 1rem 0;
        }
        
        .car-details {
            display: flex;
            justify-content: space-between;
            margin-bottom: 1rem;
            font-size: 0.9rem;
            color: #666;
        }
        
        /* Sección de servicios */
        .services {
            background-color: #e9f0fa;
        }
        
        .services-grid {
            display: grid;
            grid-template-columns: repeat(auto-fill, minmax(250px, 1fr));
            gap: 2rem;
        }
        
        .service-card {
            text-align: center;
            padding: 2rem;
            background-color: white;
            border-radius: 8px;
            box-shadow: 0 3px 10px rgba(0,0,0,0.1);
        }
        
        .service-icon {
            font-size: 2.5rem;
            color: #1e5799;
            margin-bottom: 1rem;
        }
        
        /* Sobre nosotros */
        .about-content {
            display: grid;
            grid-template-columns: 1fr 1fr;
            gap: 3rem;
            align-items: center;
        }
        
        .about-img {
            border-radius: 8px;
            box-shadow: 0 5px 15px rgba(0,0,0,0.1);
            height: 400px;
            width: 100%;
            object-fit: cover;
        }
        
        /* Contacto */
        .contact-form {
            max-width: 600px;
            margin: 0 auto;
            background-color: white;
            padding: 2rem;
            border-radius: 8px;
            box-shadow: 0 3px 10px rgba(0,0,0,0.1);
        }
        
        .form-group {
            margin-bottom: 1.5rem;
        }
        
        .form-group label {
            display: block;
            margin-bottom: 0.5rem;
            font-weight: 500;
        }
        
        .form-control {
            width: 100%;
            padding: 0.8rem;
            border: 1px solid #ddd;
            border-radius: 4px;
            font-size: 1rem;
        }
        
        textarea.form-control {
            height: 150px;
            resize: vertical;
        }
        
        /* Footer */
        footer {
            background-color: #1e5799;
            color: white;
            padding: 3rem 0 1.5rem;
        }
        
        .footer-content {
            display: grid;
            grid-template-columns: repeat(auto-fill, minmax(250px, 1fr));
            gap: 2rem;
            margin-bottom: 2rem;
        }
        
        .footer-column h3 {
            margin-bottom: 1.5rem;
            font-size: 1.2rem;
        }
        
        .footer-links {
            list-style: none;
        }
        
        .footer-links li {
            margin-bottom: 0.5rem;
        }
        
        .footer-links a {
            color: #ddd;
        }
        
        .footer-links a:hover {
            color: #ffd700;
        }
        
        .social-links {
            display: flex;
            gap: 1rem;
        }
        
        .social-links a {
            color: white;
            font-size: 1.5rem;
        }
        
        .copyright {
            text-align: center;
            padding-top: 1.5rem;
            border-top: 1px solid rgba(255,255,255,0.1);
            font-size: 0.9rem;
            color: #ddd;
        }
        
        /* Responsive */
        @media (max-width: 768px) {
            .navbar {
                flex-direction: column;
                text-align: center;
            }
            
            .menu {
                margin-top: 1rem;
                flex-wrap: wrap;
                justify-content: center;
            }
            
            .menu li {
                margin: 0.5rem;
            }
            
            .hero {
                height: 400px;
            }
            
            .hero h1 {
                font-size: 2.2rem;
            }
            
            .about-content {
                grid-template-columns: 1fr;
            }
            
            .footer-content {
                grid-template-columns: 1fr;
                text-align: center;
            }
            
            .social-links {
                justify-content: center;
            }
        }
    </style>
</head>
<body>
    <!-- Cabecera -->
    <header>
        <div class="container navbar">
            <div class="logo">AutoMotriz</div>
            <ul class="menu">
                <li><a href="#inicio">Inicio</a></li>
                <li><a href="#vehiculos">Vehículos</a></li>
                <li><a href="#servicios">Servicios</a></li>
                <li><a href="#nosotros">Nosotros</a></li>
                <li><a href="#contacto">Contacto</a></li>
            </ul>
        </div>
    </header>
    
    <!-- Hero Section -->
    <section id="inicio" class="hero">
        <div class="hero-content">
            <h1>Los mejores vehículos a tu alcance</h1>
            <p>Descubre nuestra amplia selección de automóviles nuevos y usados con las mejores garantías del mercado</p>
            <a href="#vehiculos" class="btn">Ver catálogo</a>
        </div>
    </section>
    
    <!-- Vehículos Destacados -->
    <section id="vehiculos" class="section">
        <div class="container">
            <h2 class="section-title">Vehículos Destacados</h2>
            <div class="cars-grid">
                <!-- Coche 1 -->
                <div class="car-card">
                    <img src="/api/placeholder/300/200" alt="Sedan Luxury" class="car-img">
                    <div class="car-info">
                        <h3 class="car-title">Sedan Luxury 2025</h3>
                        <div class="car-details">
                            <span>Automático</span>
                            <span>Gasolina</span>
                            <span>180 CV</span>
                        </div>
                        <p>Sedán de lujo con acabados premium, sistema de navegación avanzado y asientos de cuero.</p>
                        <p class="car-price">35.900 €</p>
                        <a href="#contacto" class="btn">Más información</a>
                    </div>
                </div>
                
                <!-- Coche 2 -->
                <div class="car-card">
                    <img src="/api/placeholder/300/200" alt="SUV Family" class="car-img">
                    <div class="car-info">
                        <h3 class="car-title">SUV Family 2024</h3>
                        <div class="car-details">
                            <span>Automático</span>
                            <span>Híbrido</span>
                            <span>220 CV</span>
                        </div>
                        <p>SUV espacioso ideal para familias, con 7 plazas, maletero amplio y tecnología de asistencia a la conducción.</p>
                        <p class="car-price">42.500 €</p>
                        <a href="#contacto" class="btn">Más información</a>
                    </div>
                </div>
                
                <!-- Coche 3 -->
                <div class="car-card">
                    <img src="/api/placeholder/300/200" alt="Compacto City" class="car-img">
                    <div class="car-info">
                        <h3 class="car-title">Compacto City 2025</h3>
                        <div class="car-details">
                            <span>Manual</span>
                            <span>Gasolina</span>
                            <span>95 CV</span>
                        </div>
                        <p>Coche urbano de bajo consumo, ideal para ciudad con facilidad de aparcamiento y gran maniobrabilidad.</p>
                        <p class="car-price">18.750 €</p>
                        <a href="#contacto" class="btn">Más información</a>
                    </div>
                </div>
                
                <!-- Coche 4 -->
                <div class="car-card">
                    <img src="/api/placeholder/300/200" alt="Eléctrico Future" class="car-img">
                    <div class="car-info">
                        <h3 class="car-title">Eléctrico Future 2025</h3>
                        <div class="car-details">
                            <span>Automático</span>
                            <span>Eléctrico</span>
                            <span>204 CV</span>
                        </div>
                        <p>Vehículo 100% eléctrico con 450 km de autonomía, carga rápida y conectividad avanzada.</p>
                        <p class="car-price">39.900 €</p>
                        <a href="#contacto" class="btn">Más información</a>
                    </div>
                </div>
            </div>
        </div>
    </section>
    
    <!-- Servicios -->
    <section id="servicios" class="section services">
        <div class="container">
            <h2 class="section-title">Nuestros Servicios</h2>
            <div class="services-grid">
                <!-- Servicio 1 -->
                <div class="service-card">
                    <div class="service-icon">🚗</div>
                    <h3>Venta de Vehículos Nuevos</h3>
                    <p>Amplio catálogo de vehículos nuevos de las mejores marcas con garantía oficial.</p>
                </div>
                
                <!-- Servicio 2 -->
                <div class="service-card">
                    <div class="service-icon">🔧</div>
                    <h3>Servicio Técnico</h3>
                    <p>Taller especializado con técnicos certificados y las últimas tecnologías de diagnóstico.</p>
                </div>
                
                <!-- Servicio 3 -->
                <div class="service-card">
                    <div class="service-icon">💰</div>
                    <h3>Financiación Personalizada</h3>
                    <p>Opciones de financiación adaptadas a tus necesidades con las mejores condiciones del mercado.</p>
                </div>
                
                <!-- Servicio 4 -->
                <div class="service-card">
                    <div class="service-icon">🔄</div>
                    <h3>Vehículos de Ocasión</h3>
                    <p>Coches seminuevos y de ocasión revisados y con garantía de concesionario.</p>
                </div>
            </div>
        </div>
    </section>
    
    <!-- Sobre Nosotros -->
    <section id="nosotros" class="section">
        <div class="container">
            <h2 class="section-title">Sobre Nosotros</h2>
            <div class="about-content">
                <img src="/api/placeholder/500/400" alt="Concesionario" class="about-img">
                <div>
                    <h3>Tu concesionario de confianza desde 1995</h3>
                    <p>En AutoMotriz nos dedicamos a ofrecer la mejor experiencia en la compra de vehículos. Con más de 25 años de experiencia en el sector, nuestro equipo de profesionales está comprometido con la satisfacción del cliente.</p>
                    <p>Disponemos de instalaciones modernas con más de 3.000 m² de exposición donde podrás encontrar una amplia gama de vehículos nuevos y de ocasión.</p>
                    <p>Nuestro compromiso es ofrecerte:</p>
                    <ul>
                        <li>Asesoramiento personalizado</li>
                        <li>Transparencia en todas nuestras operaciones</li>
                        <li>El mejor servicio post-venta</li>
                        <li>Garantía y calidad en todos nuestros vehículos</li>
                    </ul>
                </div>
            </div>
        </div>
    </section>
    
    <!-- Contacto -->
    <section id="contacto" class="section">
        <div class="container">
            <h2 class="section-title">Contacto</h2>
            <div class="contact-form">
                <form>
                    <div class="form-group">
                        <label for="nombre">Nombre completo</label>
                        <input type="text" id="nombre" class="form-control" required>
                    </div>
                    <div class="form-group">
                        <label for="email">Correo electrónico</label>
                        <input type="email" id="email" class="form-control" required>
                    </div>
                    <div class="form-group">
                        <label for="telefono">Teléfono</label>
                        <input type="tel" id="telefono" class="form-control">
                    </div>
                    <div class="form-group">
                        <label for="interes">Vehículo de interés</label>
                        <select id="interes" class="form-control">
                            <option value="">Selecciona un modelo</option>
                            <option value="sedan">Sedan Luxury 2025</option>
                            <option value="suv">SUV Family 2024</option>
                            <option value="compacto">Compacto City 2025</option>
                            <option value="electrico">Eléctrico Future 2025</option>
                            <option value="otro">Otro modelo</option>
                        </select>
                    </div>
                    <div class="form-group">
                        <label for="mensaje">Mensaje</label>
                        <textarea id="mensaje" class="form-control" required></textarea>
                    </div>
                    <button type="submit" class="btn">Enviar mensaje</button>
                </form>
            </div>
        </div>
    </section>
    
    <!-- Footer -->
    <footer>
        <div class="container">
            <div class="footer-content">
                <!-- Columna 1 -->
                <div class="footer-column">
                    <h3>AutoMotriz</h3>
                    <p>Tu concesionario de confianza con la mejor selección de vehículos nuevos y de ocasión.</p>
                    <div class="social-links">
                        <a href="#">📱</a>
                        <a href="#">📘</a>
                        <a href="#">📸</a>
                        <a href="#">▶️</a>
                    </div>
                </div>
                
                <!-- Columna 2 -->
                <div class="footer-column">
                    <h3>Enlaces rápidos</h3>
                    <ul class="footer-links">
                        <li><a href="#inicio">Inicio</a></li>
                        <li><a href="#vehiculos">Vehículos</a></li>
                        <li><a href="#servicios">Servicios</a></li>
                        <li><a href="#nosotros">Nosotros</a></li>
                        <li><a href="#contacto">Contacto</a></li>
                    </ul>
                </div>
                
                <!-- Columna 3 -->
                <div class="footer-column">
                    <h3>Horario</h3>
                    <p>Lunes a Viernes: 9:00 - 20:00</p>
                    <p>Sábados: 10:00 - 14:00</p>
                    <p>Domingos: Cerrado</p>
                </div>
                
                <!-- Columna 4 -->
                <div class="footer-column">
                    <h3>Contacto</h3>
                    <p>📍 Avenida Principal, 123</p>
                    <p>📞 +34 912 345 678</p>
                    <p>✉️ info@automotriz.com</p>
                </div>
            </div>
            
            <div class="copyright">
                <p>&copy; 2025 AutoMotriz. Todos los derechos reservados.</p>
            </div>
        </div>
    </footer>
</body>
</html>