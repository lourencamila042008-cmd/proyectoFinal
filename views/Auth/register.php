<?php
require_once __DIR__ . "/../../config/db.php";

if($_SERVER["REQUEST_METHOD"] == "POST"){

$conn = Database::Conectar();

$nombre_negocio   = $_POST["nombre_negocio"];
$nombre_usuario   = $_POST["nombre_usuario"];
$apellido_usuario = $_POST["apellido_usuario"];
$telefono         = $_POST["telefono"];
$correo           = $_POST["email"];
$password         = $_POST["password"];

// 🔐 ENCRIPTAR CONTRASEÑA
$password_hash = password_hash($password, PASSWORD_DEFAULT);

// 🔹 Insertar usuario
$stmt = $conn->prepare("
INSERT INTO usuario
(nombre_negocio, nombre_usuario, apellido_usuario, telefono, correo, contraseña)
VALUES (?,?,?,?,?,?)
");

$stmt->bind_param("ssssss",
$nombre_negocio,
$nombre_usuario,
$apellido_usuario,
$telefono,
$correo,
$password_hash
);

if($stmt->execute()){

    $id_usuario = $conn->insert_id;

    // 🔎 BUSCAR ROL EMPLEADO
    $rolEmpleado = "empleado";

    $stmtRol = $conn->prepare("
        SELECT id_rol FROM rol WHERE tipo = ?
        LIMIT 1
    ");

    $stmtRol->bind_param("s", $rolEmpleado);
    $stmtRol->execute();
    $resRol = $stmtRol->get_result();

    if($resRol->num_rows > 0){

        $rol = $resRol->fetch_assoc();
        $id_rol = $rol["id_rol"];

        // 🔹 Asignar rol al usuario
        $stmtUserRol = $conn->prepare("
            INSERT INTO rol_user (id_rol, id_usuario)
            VALUES (?,?)
        ");

        $stmtUserRol->bind_param("ii", $id_rol, $id_usuario);
        $stmtUserRol->execute();

        echo "Usuario registrado correctamente 💙";

    } else {
        echo "Error: No existe el rol empleado";
    }

} else {
    echo "Error al registrar usuario";
}

}
?>
<!DOCTYPE html>
<html lang="es">
<head>
<meta charset="UTF-8">
<title>Registro</title>
<link rel="stylesheet" href="../../public/css/register.css">
</head>

<body>

<div class="register-box">
<div class="logo">INVOICEPRO</div>
<h2>Crear Cuenta</h2>

<form method="POST">

<input class="input" type="text" name="nombre_negocio" placeholder="Negocio" required>
<input class="input" type="text" name="nombre_usuario" placeholder="Nombre" required>
<input class="input" type="text" name="apellido_usuario" placeholder="Apellido" required>
<input class="input" type="text" name="telefono" placeholder="Teléfono" required>
<input class="input" type="email" name="email" placeholder="Correo" required>
<input class="input" type="password" name="password" placeholder="Contraseña" required>

<button type="submit" class="register-btn">Registrarse</button>

</form>

<a href="login.php" class="link">¿Ya tienes cuenta? Inicia sesión</a>
<a href="../../index.php" class="link">Volver al inicio</a>

</div>


</body>
</html>