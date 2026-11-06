<?php
session_start();
$esAdmin = isset($_SESSION['rol']) && $_SESSION['rol'] === 'administrador';
$nombreUsuario = $_SESSION['nombre_usuario'] ?? '';
?>
<!DOCTYPE html>
<html lang="es">
<head>
  <meta charset="UTF-8">
  <meta name="viewport" content="width=device-width, initial-scale=1.0">
  <title>Carnicería San Juan</title>

  <!-- fuentes -->
  <link rel="preconnect" href="https://fonts.googleapis.com">
  <link rel="preconnect" href="https://fonts.gstatic.com" crossorigin>
  <link href="https://fonts.googleapis.com/css2?family=Playfair+Display:wght@700;900&family=Plus+Jakarta+Sans:wght@400;500;600;700&display=swap" rel="stylesheet">

  <!-- iconos -->
  <link rel="stylesheet" href="https://cdnjs.cloudflare.com/ajax/libs/font-awesome/6.4.0/css/all.min.css">

  <link rel="stylesheet" href="css/gonzalo.css">
</head>
<body>

  <!-- menu de navegacion -->
  <header class="encabezado">
    <div class="contenedor contenedor-navegacion">
      <div class="marca">
        <div class="icono-logo">
          <i class="fa-solid fa-shield"></i>
        </div>
        <div class="texto-marca">
          <h1>SAN JUAN</h1>
          <span>CARNICERÍA</span>
        </div>
      </div>

      <nav>
        <ul class="enlaces-navegacion">
          <li><a href="index.php" class="activo">Inicio</a></li>
          <li><a href="nosotros.html">Nosotros</a></li>
          <li><a href="productos.php">Productos</a></li>
          <li><a href="contacto.html">Contacto</a></li>
          
          <?php if ($esAdmin): ?>
              <li><a href="Altabasbajas.html" style="color: var(--fondo-dorado, #d4af37);">Panel Admin</a></li>
          <?php endif; ?>
        </ul>
      </nav>

      <div style="display: flex; align-items: center; gap: 15px;">
          <?php if (isset($_SESSION['rol'])): ?>
              <span style="color: #fff; font-size: 14px;">Hola, <strong><?php echo htmlspecialchars($nombreUsuario); ?></strong></span>
              <a href="logout.php" class="boton-primario" style="padding: 8px 15px; font-size: 14px; text-decoration: none;">Cerrar Sesión</a>
          <?php else: ?>
              <a href="inicio_de_sesion.html" class="boton-primario" style="padding: 8px 15px; font-size: 14px; text-decoration: none;">
                  <i class="fa-solid fa-user"></i> Ingresar
              </a>
          <?php endif; ?>

          <a href="tel:4499123456" class="boton-telefono">
              <i class="fa-solid fa-phone"></i> (449) 912-3456
          </a>
      </div>
    </div>
  </header>

  <!-- banner principal -->
  <section class="seccion-principal">
    <div class="contenedor cuadricula-principal">
      <div class="contenido-principal">
        <span class="etiqueta-subtitulo">— TRADICIÓN EN AGUASCALIENTES</span>
        <h1 class="titulo-principal">Carnicería<br>San Juan</h1>
        <p class="descripcion-principal">
          Tradición y calidad en cada corte desde 1998. Disfrute del sabor auténtico 
          de la mejor carne fresca, marinados artesanales y un servicio de primera.
        </p>
        <a href="#productos" class="boton-primario">
          Ver Productos <i class="fa-solid fa-arrow-right"></i>
        </a>
      </div>

      <div class="contenedor-imagen-principal">
        <img src="https://images.unsplash.com/photo-1607623814075-e51df1bdc82f?auto=format&fit=crop&w=800&q=80" alt="Cortes de Carne San Juan">
      </div>
    </div>
  </section>

  <!-- seccion de especialidades -->
  <section class="seccion-especialidades" id="productos">
    <div class="contenedor">
      <div class="encabezado-seccion">
        <span class="subtitulo-superior">NUESTRO SURTIDO</span>
        <h2 class="titulo-seccion">Nuestras Especialidades</h2>
        <p class="descripcion-seccion">Seleccionamos con maestría las carnes preferidas para sus asados y recetas diarias.</p>
        <div class="barra-separadora"></div>
      </div>

      <div class="cuadricula-3-columnas">
        <div class="tarjeta-producto">
          <img src="https://images.unsplash.com/photo-1544025162-d76694265947?auto=format&fit=crop&w=600&q=80" alt="Cortes Premium">
          <h3>Cortes Premium</h3>
          <p>Ribeye, New York, Arrachera y Tomahawk seleccionados con los más estrictos estándares de marmoleo y maduración.</p>
        </div>

        <div class="tarjeta-producto">
          <img src="https://images.unsplash.com/photo-1555939594-58d7cb561ad1?auto=format&fit=crop&w=600&q=80" alt="Carnes Marinadas">
          <h3>Carnes Marinadas</h3>
          <p>Nuestras recetas tradicionales preparadas diariamente con especias e ingredientes frescos listos para asar.</p>
        </div>

        <div class="tarjeta-producto">
          <img src="https://images.unsplash.com/photo-1558030006-450675393462?auto=format&fit=crop&w=600&q=80" alt="Productos de Temporada">
          <h3>Productos de Temporada</h3>
          <p>Especialidades artesanales preparadas con recetas de la casa, chorizos gourmet y embutidos de calidad.</p>
        </div>
      </div>
    </div>
  </section>

  <!-- por que elegirnos -->
  <section class="seccion-elegirnos">
    <div class="contenedor">
      <div class="encabezado-seccion">
        <span class="subtitulo-superior">VALOR RECONOCIDO</span>
        <h2 class="titulo-seccion">¿Por qué elegirnos?</h2>
        <p class="descripcion-seccion">Nos esforzamos por brindarle una experiencia de compra impecable respaldada por pilares firmes.</p>
        <div class="barra-separadora"></div>
      </div>

      <div class="cuadricula-4-columnas">
        <div class="tarjeta-beneficio">
          <div class="icono-beneficio"><i class="fa-solid fa-shield-halved"></i></div>
          <h4>Calidad Garantizada</h4>
          <p>Selección rigurosa de proveedores nacionales certificados.</p>
        </div>

        <div class="tarjeta-beneficio">
          <div class="icono-beneficio"><i class="fa-solid fa-drumstick-bite"></i></div>
          <h4>Cortes Frescos Diario</h4>
          <p>Nuestros maestros carniceros preparan la carne cada mañana.</p>
        </div>

        <div class="tarjeta-beneficio">
          <div class="icono-beneficio"><i class="fa-solid fa-user-check"></i></div>
          <h4>Atención Personalizada</h4>
          <p>Cortamos la pieza exactamente al grosor de su preferencia.</p>
        </div>

        <div class="tarjeta-beneficio">
          <div class="icono-beneficio"><i class="fa-solid fa-scale-balanced"></i></div>
          <h4>Precios Justos</h4>
          <p>Excelente relación entre la más alta calidad y su economía.</p>
        </div>
      </div>
    </div>
  </section>

  <!-- banner ubicacion -->
  <section class="banner-contacto">
    <div class="contenedor contenedor-banner">
      <div class="contenido-banner">
        <span class="etiqueta-subtitulo">— PREPARADOS PARA ATENDERLE</span>
        <h2>¿Planeando una parrillada? Visítanos hoy mismo.</h2>
        <p>Calle de las fuentes #310 Fidel Velázquez, Aguascalientes, Aguascalientes</p>
      </div>
      <div>
        <a href="https://maps.app.goo.gl/FMjFikNgSgRAGsMU9" target="_blank" class="boton-dorado">Ver Ubicación</a>
      </div>
    </div>
  </section>

  <!-- pie de pagina -->
  <footer class="pie-pagina">
    <div class="contenedor">
      <div class="cuadricula-pie">
        <div class="marca-pie">
          <h3><i class="fa-solid fa-bullhorn icono-dorado"></i> San Juan</h3>
          <p>Manteniendo viva la tradición del buen comer con los mejores cortes de carne fresca y atención familiar desde hace más de dos décadas.</p>
        </div>

        <div class="columna-pie">
          <h4>ENLACES</h4>
          <ul>
            <li><a href="index.php">Inicio</a></li>
            <li><a href="nosotros.html">Nosotros</a></li>
            <li><a href="productos.html">Productos</a></li>
            <li><a href="contacto.html">Contacto</a></li>
          </ul>
        </div>

        <div class="columna-pie">
          <h4>UBICACIÓN Y CONTACTO</h4>
          <div class="item-contacto">
            <i class="fa-solid fa-location-dot"></i>
             <a href="https://maps.app.goo.gl/FMjFikNgSgRAGsMU9"><span>Calle de las fuentes #310 Fidel Velázquez Aguascalientes, Aguascalientes</span> </a>
          </div>
          <div class="item-contacto">
            <i class="fa-solid fa-phone"></i>
            <span>(449) 912-3456</span>
          </div>
          <div class="item-contacto">
            <i class="fa-solid fa-envelope"></i>
            <span>contacto@carniceriasanjuan.com</span>
          </div>
        </div>

        <div class="columna-pie">
          <h4>HORARIO DE ATENCIÓN</h4>
          <p class="texto-horario">
            Lunes a Sábado:<br>
            <strong>7:00 AM – 8:00 PM</strong>
          </p>
          <p class="texto-horario">
            Domingo:<br>
            <strong>8:00 AM – 3:00 PM</strong>
          </p>
        </div>
      </div>

      <div class="redes-sociales">
        <a href="#"><i class="fa-brands fa-facebook-f"></i></a>
        <a href="#"><i class="fa-brands fa-instagram"></i></a>
        <a href="#"><i class="fa-brands fa-whatsapp"></i></a>
      </div>
    </div>
  </footer>

</body>
</html>