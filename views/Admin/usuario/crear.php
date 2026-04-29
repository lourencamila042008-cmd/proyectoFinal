<?php
require_once "../../../config/db.php";
$conn = Database::Conectar();
mysqli_report(MYSQLI_REPORT_ERROR | MYSQLI_REPORT_STRICT);

$mensaje = "";

if ($_SERVER['REQUEST_METHOD'] == 'POST') {
    $nombre_usuario   = trim($_POST['nombre_usuario']);
    $apellido_usuario = trim($_POST['apellido_usuario']);
    $telefono         = trim($_POST['telefono']);
    $correo           = trim($_POST['correo']);
    $contrasena       = password_hash($_POST['contrasena'], PASSWORD_DEFAULT);

    if (empty($nombre_usuario) || empty($apellido_usuario)) {
        $mensaje = "Nombre y apellido son obligatorios.";
    } else {
        $telefono = intval($_POST['telefono']);

$stmt = $conn->prepare("INSERT INTO usuario 
    (nombre_usuario, apellido_usuario, telefono, correo, contrasena) 
    VALUES (?, ?, ?, ?, ?)");
$stmt->bind_param("ssiss", $nombre_usuario, $apellido_usuario, $telefono, $correo, $contrasena);
try {
    $stmt->execute();
    $stmt->close();
    echo "<script>alert('Usuario creado correctamente'); window.location='usuarios.php';</script>";
    exit;
} catch (Exception $e) {
    $mensaje = "Error: " . $e->getMessage();
    $stmt->close();
}
    }
}
?>

<!DOCTYPE html>
<html lang="es">
<head>
<meta charset="UTF-8">
<title>Crear Usuario</title>
<link rel="stylesheet" href="../../../public/css/usuarios/crear.css">

</head>

<body>
<div class="contenedor">

    <h1>Crear Usuario</h1>

    <form method="POST" action="crear.php">

        <?php if ($mensaje != ""): ?>
            <div class="mensaje"><?= htmlspecialchars($mensaje) ?></div>
        <?php endif; ?>

        <div class="campo">
            <label>Nombre</label>
            <input type="text" name="nombre_usuario" placeholder="Nombre" required>
        </div>

        <div class="campo">
            <label>Apellido</label>
            <input type="text" name="apellido_usuario" placeholder="Apellido" required>
        </div>

        <div class="campo">
            <label>Teléfono</label>
            <input type="text" name="telefono" placeholder="Teléfono">
        </div>

        <div class="campo">
            <label>Correo</label>
            <input type="email" name="correo" placeholder="correo@ejemplo.com">
        </div>

        <div class="campo">
            <label>Contraseña</label>
            <input type="password" name="contrasena" placeholder="Contraseña" required>
        </div>

        <button type="submit">Guardar Usuario</button>

        <a class="volver" href="usuarios.php">⬅ Volver</a>

    </form>

</div>
</body>
</html>