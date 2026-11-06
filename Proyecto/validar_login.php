<?php
header('Content-Type: application/json');
session_start();

$contenido = file_get_contents('php://input');
$datos = json_decode($contenido, true);

$usuario_ingresado = trim($datos['usuario'] ?? '');
$password_ingresado = $datos['password'] ?? '';

$servidor = "localhost";
$usuario_db = "root";
$password_db = "";
$base_datos = "carniceria_san_juan";    

try {
    $conexion = new PDO("mysql:host=$servidor;dbname=$base_datos;charset=utf8", $usuario_db, $password_db);
    $conexion->setAttribute(PDO::ATTR_ERRMODE, PDO::ERRMODE_EXCEPTION);

    $sql = "SELECT * FROM usuarios WHERE nombre_usuario = :usu OR correo = :usu";
    $stmt = $conexion->prepare($sql);
    $stmt->execute(['usu' => $usuario_ingresado]);
    $usuario = $stmt->fetch(PDO::FETCH_ASSOC);

    if ($usuario && password_verify($password_ingresado, $usuario['password'])) {
        // Guardamos los datos clave en la sesión del servidor
        $_SESSION['id'] = $usuario['id'];
        $_SESSION['nombre_usuario'] = $usuario['nombre_usuario'];
        $_SESSION['rol'] = $usuario['rol'];

        // Respondemos a JS con éxito (ya no hace falta mandar el rol aquí si index.php lo lee directo de $_SESSION)
        echo json_encode(['exito' => true]);
    } else {
        echo json_encode(['exito' => false, 'mensaje' => 'Usuario o contraseña incorrectos.']);
    }

} catch (PDOException $e) {
    echo json_encode(['exito' => false, 'mensaje' => 'Error de conexión a la base de datos.']);
}
?>