/* ==========================================================
   productos.js  -  Carnicería San Juan
   Sección de Productos: arreglo de objetos, buscador,
   carrito flotante y checkout por WhatsApp.
   ========================================================== */

/* ----------------------------------------------------------
   1) OPERACIONES CON DATOS: ARREGLO DE OBJETOS
   ----------------------------------------------------------
   El arreglo "productos" NO se escribe aquí: lo crea productos.php
   (arriba del <script src="js/productos.js">) a partir de la tabla
   "productos" de la base de datos. Cada fila de la BD llega como un
   OBJETO con sus propiedades:
     { id, nombre, precio, descripcion, imagen, categoria }
   Un ARREGLO es una lista entre corchetes [ ]; este es una lista de
   objetos, donde cada posición es un producto distinto.
   Ejemplo: productos[0].nombre  ->  "Arrachera Premium"            */

/* Número de WhatsApp de la carnicería (código de país 52 + número). Sin "+" ni espacios. */
const NUMERO_WHATSAPP = "524499123456";

/* El carrito también es un ARREGLO DE OBJETOS.
   Empieza vacío y cada elemento será: { id, nombre, precio, cantidad } */
let carrito = [];

/* ----------------------------------------------------------
   Referencias a elementos del HTML (se buscan una sola vez)
   ---------------------------------------------------------- */
const contenedorProductos = document.getElementById("contenedor-productos"); // donde van las tarjetas
const inputBuscador       = document.getElementById("buscador");             // caja de texto
const textoResultados     = document.getElementById("texto-resultados");     // "Mostrando X productos"
const burbujaCarrito      = document.getElementById("burbuja-carrito");      // botón flotante
const contadorCarrito     = document.getElementById("contador-carrito");     // numerito rojo
const panelCarrito        = document.getElementById("panel-carrito");        // ventana del carrito
const listaCarrito        = document.getElementById("lista-carrito");        // donde van los productos agregados
const totalCarrito        = document.getElementById("total-carrito");        // texto del total
const botonComprar        = document.getElementById("boton-comprar");        // botón "Comprar"
const botonVaciar         = document.getElementById("boton-vaciar");        // botón "Vaciar"
const botonCerrar         = document.getElementById("cerrar-carrito");       // la "X" del panel

/* ----------------------------------------------------------
   Función auxiliar: formatear dinero  (289 -> "$289.00")
   ---------------------------------------------------------- */
function formatoDinero(numero) {
  return "$" + numero.toFixed(2); // toFixed(2) deja siempre 2 decimales
}

/* ----------------------------------------------------------
   Función auxiliar: quitar acentos y pasar a minúsculas.
   Así "pechuga" encuentra "Pechuga" y "cerdo" encuentra "CERDO",
   y "res" no depende de si escribieron acento.
   normalize("NFD") separa la letra de su acento, y el replace
   borra los acentos sueltos.
   ---------------------------------------------------------- */
function limpiarTexto(texto) {
  return texto
    .toLowerCase()
    .normalize("NFD")
    .replace(/[̀-ͯ]/g, "");
}

/* ----------------------------------------------------------
   2) MOSTRAR PRODUCTOS EN PANTALLA
   ----------------------------------------------------------
   Recibe un arreglo de productos (puede ser el completo o el
   ya filtrado) y dibuja una tarjeta por cada uno. */
function mostrarProductos(lista) {
  contenedorProductos.innerHTML = ""; // 1. borramos lo que hubiera antes

  // Si el arreglo filtrado quedó vacío avisamos al usuario
  if (lista.length === 0) {
    contenedorProductos.innerHTML =
      '<p class="sin-resultados">No encontramos productos con esa búsqueda.</p>';
    textoResultados.textContent = "0 productos encontrados";
    return; // salimos de la función
  }

  // 2. forEach recorre el arreglo: "producto" es cada objeto, uno por uno
  lista.forEach(function (producto) {
    // Creamos el HTML de la tarjeta usando las PROPIEDADES del objeto (producto.nombre, etc.)
    contenedorProductos.innerHTML += `
      <div class="tarjeta-producto">
        <img src="imagenes/Productos/${producto.imagen}" alt="${producto.nombre}">
        <div class="fila-titulo">
          <h3>${producto.nombre}</h3>
          <h4>${formatoDinero(producto.precio)}/kg</h4>
        </div>
        <span class="etiqueta-categoria">${producto.categoria}</span>
        <p>${producto.descripcion}</p>
        <button class="boton-agregar" data-id="${producto.id}">
          <i class="fa-solid fa-cart-plus"></i> Agregar al carrito
        </button>
      </div>`;
  });

  textoResultados.textContent = lista.length + " producto(s) mostrado(s)";

  // 3. Como los botones se acaban de crear, les asignamos su evento "click" aquí
  const botonesAgregar = document.querySelectorAll(".boton-agregar");
  botonesAgregar.forEach(function (boton) {
    boton.addEventListener("click", function () {
      // data-id viene como texto, Number() lo convierte a número para poder compararlo
      agregarAlCarrito(Number(boton.dataset.id));
    });
  });
}

/* ----------------------------------------------------------
   2) BÚSQUEDA Y FILTRADO EN TIEMPO REAL
   ----------------------------------------------------------
   filter() recorre el arreglo y devuelve OTRO arreglo nuevo con
   solo los objetos que cumplan la condición (los que devuelven true).
   No modifica el arreglo original "productos". */
function filtrarProductos() {
  const textoBuscado = limpiarTexto(inputBuscador.value.trim()); // lo que escribió el usuario

  const resultado = productos.filter(function (producto) {
    // includes() es true si el texto buscado aparece dentro del texto del producto.
    // Buscamos en nombre, categoría y descripción (el || significa "o").
    return (
      limpiarTexto(producto.nombre).includes(textoBuscado) ||
      limpiarTexto(producto.categoria).includes(textoBuscado) ||
      limpiarTexto(producto.descripcion).includes(textoBuscado)
    );
  });

  mostrarProductos(resultado); // dibujamos solo los que pasaron el filtro
}

// El evento "input" se dispara CADA VEZ que cambia el texto (con cada letra),
// por eso el filtrado es en tiempo real.
inputBuscador.addEventListener("input", filtrarProductos);

/* ----------------------------------------------------------
   3) CARRITO DE COMPRAS
   ---------------------------------------------------------- */

/* Agrega un producto al carrito. Recibe el id del producto. */
function agregarAlCarrito(idProducto) {
  // find() busca en el arreglo el PRIMER objeto que cumpla la condición
  const producto = productos.find(function (p) {
    return p.id === idProducto;
  });

  // ¿Ya está en el carrito? Lo buscamos también con find()
  const enCarrito = carrito.find(function (item) {
    return item.id === idProducto;
  });

  if (enCarrito) {
    enCarrito.cantidad++; // si ya estaba, solo aumentamos la cantidad
  } else {
    // si no estaba, agregamos un objeto nuevo al arreglo con push()
    carrito.push({
      id: producto.id,
      nombre: producto.nombre,
      precio: producto.precio,
      cantidad: 1
    });
  }

  actualizarCarrito(); // refrescamos contador y lista
  animarBurbuja();     // pequeño efecto visual
}

/* Cambia la cantidad de un producto del carrito (+1 o -1). */
function cambiarCantidad(idProducto, cambio) {
  const item = carrito.find(function (i) {
    return i.id === idProducto;
  });
  item.cantidad += cambio;

  // Si la cantidad llega a 0, quitamos el producto del carrito.
  // filter() devuelve un arreglo nuevo SIN ese producto.
  if (item.cantidad <= 0) {
    carrito = carrito.filter(function (i) {
      return i.id !== idProducto;
    });
  }
  actualizarCarrito();
}

/* Calcula cuántos productos hay en total (suma de todas las cantidades). */
function contarArticulos() {
  let total = 0;
  carrito.forEach(function (item) {
    total += item.cantidad; // sumamos la cantidad de cada objeto
  });
  return total;
}

/* Calcula el precio total del carrito (precio x cantidad de cada producto). */
function calcularTotal() {
  let total = 0;
  carrito.forEach(function (item) {
    total += item.precio * item.cantidad;
  });
  return total;
}

/* Redibuja el contador de la burbuja, la lista y el total. */
function actualizarCarrito() {
  // Contador numérico de la burbuja flotante
  contadorCarrito.textContent = contarArticulos();

  // Limpiamos la lista y la volvemos a llenar recorriendo el carrito
  listaCarrito.innerHTML = "";

  if (carrito.length === 0) {
    listaCarrito.innerHTML = '<p class="carrito-vacio">Tu carrito está vacío.</p>';
  }

  carrito.forEach(function (item) {
    listaCarrito.innerHTML += `
      <div class="item-carrito">
        <div class="item-info">
          <strong>${item.nombre}</strong>
          <span>${formatoDinero(item.precio)} x ${item.cantidad} = ${formatoDinero(item.precio * item.cantidad)}</span>
        </div>
        <div class="item-cantidad">
          <button class="boton-menos" data-id="${item.id}">-</button>
          <span>${item.cantidad}</span>
          <button class="boton-mas" data-id="${item.id}">+</button>
        </div>
      </div>`;
  });

  // Asignamos eventos a los botones + y - recién creados
  document.querySelectorAll(".boton-mas").forEach(function (b) {
    b.addEventListener("click", function () {
      cambiarCantidad(Number(b.dataset.id), 1);
    });
  });
  document.querySelectorAll(".boton-menos").forEach(function (b) {
    b.addEventListener("click", function () {
      cambiarCantidad(Number(b.dataset.id), -1);
    });
  });

  // Mostramos el total
  totalCarrito.textContent = "Total: " + formatoDinero(calcularTotal());
}

/* Efecto: la burbuja "salta" un momento al agregar un producto. */
function animarBurbuja() {
  burbujaCarrito.classList.add("saltar");
  setTimeout(function () {
    burbujaCarrito.classList.remove("saltar");
  }, 300); // a los 300 ms le quitamos la clase
}

/* Abrir / cerrar el panel del carrito */
burbujaCarrito.addEventListener("click", function () {
  panelCarrito.classList.toggle("abierto"); // toggle: si la tiene la quita, si no la pone
});
botonCerrar.addEventListener("click", function () {
  panelCarrito.classList.remove("abierto");
});

/* Vaciar el carrito */
botonVaciar.addEventListener("click", function () {
  carrito = [];          // reiniciamos el arreglo
  actualizarCarrito();
});

/* ----------------------------------------------------------
   4) CHECKOUT POR WHATSAPP
   ----------------------------------------------------------
   Recorre el carrito, arma un mensaje con cada producto,
   calcula el total y abre la API de WhatsApp (wa.me). */
botonComprar.addEventListener("click", function () {
  // Si no hay nada, avisamos y no hacemos nada más
  if (carrito.length === 0) {
    alert("Agrega al menos un producto al carrito.");
    return;
  }

  // Empezamos el mensaje. \n significa "salto de línea".
  let mensaje = "Hola, quiero hacer el siguiente pedido en Carnicería San Juan:\n\n";
  let total = 0;

  // Recorremos cada objeto del carrito con forEach
  carrito.forEach(function (item) {
    const subtotal = item.precio * item.cantidad; // subtotal de este producto
    total += subtotal;                             // lo vamos sumando al total
    mensaje += "- " + item.nombre + " x" + item.cantidad + " (" + formatoDinero(subtotal) + ")\n";
  });

  mensaje += "\nTotal: " + formatoDinero(total);

  // encodeURIComponent convierte espacios, acentos y saltos de línea
  // a un formato seguro para ponerlo dentro de una URL.
  const url = "https://wa.me/" + NUMERO_WHATSAPP + "?text=" + encodeURIComponent(mensaje);

  // Abrimos WhatsApp en otra pestaña
  window.open(url, "_blank");
});

/* ----------------------------------------------------------
   Al cargar la página: mostramos todos los productos
   y dibujamos el carrito vacío.
   ---------------------------------------------------------- */
mostrarProductos(productos);
actualizarCarrito();
