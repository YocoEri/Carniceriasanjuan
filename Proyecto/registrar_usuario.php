<?php
header('Content-Type: application/json');

$contenido = file_get_contents('php://input');
$datos = json_decode($contenido, true);

$usuario = trim($datos['usuario'] ?? '');
$correo = trim($datos['correo'] ?? '');
$password = $datos['password'] ?? '';

if (empty($usuario) || empty($correo) || empty($password)) {
    echo json_encode(['exito' => false, 'mensaje' => 'Por favor completa todos los campos.']);
    exit();
}

$servidor = "localhost";
$usuario_db = "root";
$password_db = "";
$base_datos = "carniceria_san_juan";

try {
    $conexion = new PDO("mysql:host=$servidor;dbname=$base_datos;charset=utf8", $usuario_db, $password_db);
    $conexion->setAttribute(PDO::ATTR_ERRMODE, PDO::ERRMODE_EXCEPTION);

    // Verificar si existe el usuario o correo
    $sqlExiste = "SELECT id FROM usuarios WHERE nombre_usuario = :usu OR correo = :correo";
    $stmtExiste = $conexion->prepare($sqlExiste);
    $stmtExiste->execute(['usu' => $usuario, 'correo' => $correo]);

    if ($stmtExiste->rowCount() > 0) {
        echo json_encode(['exito' => false, 'mensaje' => 'El usuario o el correo ya están registrados.']);
        exit();
    }

    $passwordHash = password_hash($password, PASSWORD_DEFAULT);

    $sqlInsert = "INSERT INTO usuarios (nombre_usuario, correo, password, rol) VALUES (:usu, :correo, :pass, 'usuario')";
    $stmtInsert = $conexion->prepare($sqlInsert);
    $stmtInsert->execute([
        'usu' => $usuario,
        'correo' => $correo,
        'pass' => $passwordHash
    ]);

    echo json_encode([
        'exito' => true,
        'mensaje' => '¡Cuenta creada con éxito! Redirigiendo al login...'
    ]);

} catch (PDOException $e) {
    echo json_encode([
        'exito' => false,
        'mensaje' => 'Error en la base de datos al registrar.'
    ]);
}
?>