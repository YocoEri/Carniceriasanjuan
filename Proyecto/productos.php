<?php
session_start();
$esAdmin = isset($_SESSION['rol']) && $_SESSION['rol'] === 'administrador';
$nombreUsuario = $_SESSION['nombre_usuario'] ?? '';

// Conexión a la base de datos
$servidor = "localhost";
$usuario_db = "root";
$password_db = "";
$base_datos = "carniceria_san_juan";

$productos = [];
try {
    $conexion = new PDO("mysql:host=$servidor;dbname=$base_datos;charset=utf8", $usuario_db, $password_db);
    $conexion->setAttribute(PDO::ATTR_ERRMODE, PDO::ERRMODE_EXCEPTION);

    // Traemos TODOS los productos (el buscador de JS filtra sobre esta lista)
    $stmt = $conexion->query("SELECT * FROM productos");
    $productos = $stmt->fetchAll(PDO::FETCH_ASSOC);

    // Cada fila de la BD se convierte en un objeto de JS; aseguramos que id y precio sean números
    foreach ($productos as &$fila) {
        $fila['id'] = (int)$fila['id'];
        $fila['precio'] = (float)$fila['precio'];
        if (empty($fila['categoria'])) { $fila['categoria'] = 'General'; }
    }
    unset($fila);
} catch (PDOException $e) {
    $error_db = "Error de conexión a la base de datos.";
}

?>
<!DOCTYPE html>
<html lang="es">
<head>
  <meta charset="UTF-8">
  <meta name="viewport" content="width=device-width, initial-scale=1.0">
  <link rel="stylesheet" href="css/gonzalo.css">
  <link rel="stylesheet" href="css/productos.css">
  <title>Productos - Carnicería San Juan</title>

  <!-- fuentes -->
  <link rel="preconnect" href="https://fonts.googleapis.com">
  <link rel="preconnect" href="https://fonts.gstatic.com" crossorigin>
  <link href="https://fonts.googleapis.com/css2?family=Playfair+Display:wght@700;900&family=Plus+Jakarta+Sans:wght@400;500;600;700&display=swap" rel="stylesheet">

  <!-- iconos -->
  <link rel="stylesheet" href="https://cdnjs.cloudflare.com/ajax/libs/font-awesome/6.4.0/css/all.min.css">
</head>
<body>
  <!-- Encabezado -->
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
          <li><a href="index.php">Inicio</a></li>
          <li><a href="nosotros.html">Nosotros</a></li>
          <li><a href="productos.php" class="activo">Productos</a></li>
          <li><a href="contacto.html">Contacto</a></li>
          
          <?php if ($esAdmin): ?>
              <li><a href="Altabasbajas.html" style="color: var(--fondo-dorado, #d4af37);">Panel Admin</a></li>
          <?php endif; ?>
        </ul>
      </nav>

      <div style="display: flex; align-items: center; gap: 15px;">
          <?php if (isset($_SESSION['rol'])): ?>
              <a href="logout.php" class="boton-primario" style="padding: 8px 15px; font-size: 14px; text-decoration: none; background-color: #8b0000; color: #fff; border-radius: 4px;">Cerrar Sesión</a>
          <?php else: ?>
              <a href="login.php" class="boton-primario" style="padding: 8px 15px; font-size: 14px; text-decoration: none; background-color: #8b0000; color: #fff; border-radius: 4px;">
                  <i class="fa-solid fa-user"></i> Ingresar
              </a>
          <?php endif; ?>

          <a href="tel:4499123456" class="boton-telefono">
            <i class="fa-solid fa-phone"></i> (449) 912-3456
          </a>
      </div>
    </div>
  </header>

  <!-- Banner de productos -->
  <section class="banner-productos">
    <div class="contenedor">
      <span class="etiqueta-subtitulo">LO MEJOR PARA SU MESA</span>
      <h1 class="baprodu">Nuestros Productos</h1>
    </div>
  </section>

  <!-- Buscador -->
  <section class="zona-buscador">
    <div class="caja-buscador">
      <i class="fa-solid fa-magnifying-glass"></i>
      <input type="text" id="buscador" placeholder="Buscar producto (ej. ribeye, cerdo, pollo...)" autocomplete="off">
    </div>
    <p id="texto-resultados" class="texto-resultados"></p>
  </section>

  <!-- Aquí JavaScript dibuja las tarjetas desde el arreglo de productos -->
  <section class="productos" id="contenedor-productos"></section>

  <!-- Burbuja flotante del carrito -->
  <button id="burbuja-carrito" class="burbuja-carrito" aria-label="Abrir carrito">
    <i class="fa-solid fa-cart-shopping"></i>
    <span id="contador-carrito" class="contador-carrito">0</span>
  </button>

  <!-- Panel del carrito -->
  <aside id="panel-carrito" class="panel-carrito">
    <div class="panel-encabezado">
      <h3>Tu pedido</h3>
      <button id="cerrar-carrito" aria-label="Cerrar carrito">&times;</button>
    </div>
    <div id="lista-carrito" class="lista-carrito"></div>
    <div class="panel-pie">
      <p id="total-carrito" class="total-carrito">Total: $0.00</p>
      <button id="boton-comprar" class="boton-comprar"><i class="fa-brands fa-whatsapp"></i> Comprar</button>
      <button id="boton-vaciar" class="boton-vaciar">Vaciar carrito</button>
    </div>
  </aside>


  <section class="banner-contacto">
    <div class="contenedor contenedor-banner">
      <div class="contenido-banner">
        <span class="etiqueta-subtitulo">EVENTOS Y MAYOREO</span>
        <h2>¿Requiere cortes personalizados o volumen especial?</h2>
        <p>En San Juan preparamos pedidos específicos para parrilladas de fin de semana, banquetes familiares, taquerías y restaurantes de la zona. Contáctenos para cotizar y cortamos a la medida de sus requisitos.</p>
      </div>
      <div>
        <a href="https://maps.app.goo.gl/FMjFikNgSgRAGsMU9" target="_blank" class="boton-dorado"> <i class="fa-regular fa-comment-dots"></i> Cotizar Pedido Especial</a>
      </div>
    </div>
  </section>

  <!-- Pie de página -->
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
            <li><a href="productos.php">Productos</a></li>
            <li><a href="contacto.html">Contacto</a></li>
          </ul>
        </div>

        <div class="columna-pie">
          <h4>UBICACIÓN Y CONTACTO</h4>
          <div class="item-contacto">
            <i class="fa-solid fa-location-dot"></i>
            <a href="https://maps.app.goo.gl/FMjFikNgSgRAGsMU9"><span>Calle de las fuentes #310 Fidel Velázquez Aguascalientes, Aguascalientes</span></a>
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
  <script>
    // PHP imprime aquí los productos de la base de datos como un arreglo de objetos de JavaScript
    const productos = <?php echo json_encode($productos, JSON_HEX_TAG | JSON_HEX_AMP | JSON_UNESCAPED_UNICODE); ?>;
  </script>
  <script src="js/productos.js"></script>
</body>
</html>