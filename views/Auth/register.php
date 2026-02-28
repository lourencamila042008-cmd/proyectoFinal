<?php
require_once __DIR__ . "/../../config/db.php";

if($_SERVER["REQUEST_METHOD"] == "POST"){

$conn = Database::Conectar();

$nombre_negocio = $_POST["nombre_negocio"];
$nombre_usuario = $_POST["nombre_usuario"];
$apellido_usuario = $_POST["apellido_usuario"];
$telefono = $_POST["telefono"];
$correo = $_POST["email"];
$password = $_POST["password"];
$rol = $_POST["rol"];

// 🔹 Insertar usuario
$sql = "INSERT INTO usuario
(nombre_negocio, nombre_usuario, apellido_usuario, telefono, correo, contraseña)
VALUES ('$nombre_negocio','$nombre_usuario','$apellido_usuario','$telefono','$correo','$password')";

if($conn->query($sql)){

    // 🔹 Obtener ID generado
    $id_usuario = $conn->insert_id;

    // 🔹 Insertar rol
    $sqlRol = "INSERT INTO rol_user (id_rol, id_usuario)
               VALUES ('$rol', '$id_usuario')";

    $conn->query($sqlRol);

    echo "Usuario registrado con rol 💙";
}else{
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

<input class="input" type="text" name="nombre_negocio" placeholder="Nombre del negocio" required>
<input class="input" type="text" name="nombre_usuario" placeholder="Nombre" required>
<input class="input" type="text" name="apellido_usuario" placeholder="Apellido" required>
<input class="input" type="text" name="telefono" placeholder="Teléfono" required>
<input class="input" type="email" name="email" placeholder="Correo" required>
<input class="input" type="password" name="password" placeholder="Contraseña" required>

<select class="input" name="rol" required>
  <option value="">Selecciona rol</option>
  <option value="1">Administrador</option>
  <option value="2">Empleado</option>
</select>

<button type="submit" class="register-btn">Registrarse</button>

</form>

<a href="login.php" class="link">¿Ya tienes cuenta? Inicia sesión</a>
<a href="../../index.php" class="link">Volver al inicio</a>

</div>

</body>
</html>