<?php
// 1. Conexión a la base de datos (igual que en tus prácticas)
$conexion = new mysqli("localhost", "root", "", "carniceria_san_juan");

// 2. Consulta para obtener todos los mensajes de la tabla
$resultado = $conexion->query("SELECT * FROM mensajes_contacto");
?>
<!DOCTYPE html>
<html lang="es">
<head>
  <meta charset="UTF-8">
  <meta name="viewport" content="width=device-width, initial-scale=1.0">
  <title>Mensajes Registrados - Carnicería San Juan</title>

  <!-- fuentes -->
  <link rel="preconnect" href="https://fonts.googleapis.com">
  <link rel="preconnect" href="https://fonts.gstatic.com" crossorigin>
  <link href="https://fonts.googleapis.com/css2?family=Playfair+Display:wght@700;900&family=Plus+Jakarta+Sans:wght@400;500;600;700&display=swap" rel="stylesheet">

  <!-- iconos -->
  <link rel="stylesheet" href="https://cdnjs.cloudflare.com/ajax/libs/font-awesome/6.4.0/css/all.min.css">

  <!-- estilos generales y de contacto -->
  <link rel="stylesheet" href="css/gonzalo.css">
  <link rel="stylesheet" href="css/contacto.css">

  <style>
    /* Estilos sencillos para la tabla */
    .seccion-tabla {
      padding: 60px 0;
      background-color: #f9f9f9;
    }
    .contenedor-tabla {
      background: #fff;
      padding: 30px;
      border-radius: 8px;
      box-shadow: 0 4px 15px rgba(0,0,0,0.05);
      overflow-x: auto;
    }
    .tabla-datos {
      width: 100%;
      border-collapse: collapse;
      margin-top: 20px;
    }
    .tabla-datos th, .tabla-datos td {
      border: 1px solid #e0e0e0;
      padding: 12px 15px;
      text-align: left;
      font-size: 14px;
    }
    .tabla-datos th {
      background-color: #1a1a1a;
      color: #fff;
      font-family: 'Playfair Display', serif;
    }
    .tabla-datos tr:nth-child(even) {
      background-color: #fcfcfc;
    }
  </style>
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
          <li><a href="index.html">Inicio</a></li>
          <li><a href="nosotros.html">Nosotros</a></li>
          <li><a href="productos.html">Productos</a></li>
          <li><a href="contacto.html">Contacto</a></li>
        </ul>
      </nav>

      <a href="tel:4499123456" class="boton-telefono">
        <i class="fa-solid fa-phone"></i> (449) 912-3456
      </a>
    </div>
  </header>

  <!-- banner -->
  <section class="banner-contacto-pagina">
    <div class="contenedor">
      <span class="etiqueta-subtitulo">— PANEL DE REGISTROS</span>
      <h1 class="titulo-principal-contacto">Mensajes Recibidos</h1>
    </div>
  </section>

  <!-- seccion de la tabla -->
  <section class="seccion-tabla">
    <div class="contenedor contenedor-tabla">
      <h2>Bandeja de Entrada</h2>
      <p>Listado de mensajes y consultas enviadas por los usuarios desde la página de contacto.</p>

      <table class="tabla-datos">
        <thead>
          <tr>
            <th>ID</th>
            <th>Nombre</th>
            <th>Teléfono</th>
            <th>Correo</th>
            <th>Mensaje</th>
          </tr>
        </thead>
        <tbody>
          <?php 
            while($fila = $resultado->fetch_assoc()) { 
          ?>
            <tr>
              <td><?php echo $fila['id']; ?></td>
              <td><?php echo $fila['nombre']; ?></td>
              <td><?php echo $fila['telefono']; ?></td>
              <td><?php echo $fila['correo']; ?></td>
              <td><?php echo $fila['mensaje']; ?></td>
            </tr>
          <?php 
            } // Fin del while
          ?>
        </tbody>
      </table>

      <div style="margin-top: 25px;">
        <a href="contacto.html" class="boton-primario" style="text-decoration: none; display: inline-block; padding: 10px 20px;">Volver al Formulario</a>
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
            <li><a href="index.html">Inicio</a></li>
            <li><a href="nosotros.html">Nosotros</a></li>
            <li><a href="productos.html">Productos</a></li>
            <li><a href="contacto.html">Contacto</a></li>
            <li><a href="mensajes.php" style="color: var(--fondo-dorado); margin-top: 15px; display: inline-block;">Ver Mensajes <i class="fa-solid fa-table"></i></a></li>
          </ul>
        </div>

        <div class="columna-pie">
          <h4>UBICACIÓN Y CONTACTO</h4>
          <div class="item-contacto">
            <i class="fa-solid fa-location-dot"></i>
            <a href="https://maps.app.goo.gl/FMjFikNgSgRAGsMU9" target="_blank">Calle de las fuentes #310 Fidel Velázquez Aguascalientes, Aguascalientes</a>
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
<?php 
// 3. Cerramos la conexión al terminar
$conexion->close(); 
?>