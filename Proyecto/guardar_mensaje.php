<?php
$nombre = $_POST['nombre'];
$telefono = $_POST['telefono'];
$correo = $_POST['correo'];
$mensaje = $_POST['mensaje'];

$conexion = new mysqli("localhost", "root", "", "carniceria_san_juan");

if ($conexion->connect_error) {
    die("Error de conexión");
}

$sql = "INSERT INTO mensajes_contacto(nombre, telefono, correo, mensaje) 
        VALUES ('$nombre', '$telefono', '$correo', '$mensaje')";

if ($conexion->query($sql) === TRUE) {
    echo "Mensaje guardado";
} else {
    echo "Error";
}

$conexion->close();
?>