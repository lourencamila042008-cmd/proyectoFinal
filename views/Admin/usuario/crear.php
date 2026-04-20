<?php
require_once "../../../config/db.php";
$conn = Database::Conectar();
mysqli_report(MYSQLI_REPORT_ERROR | MYSQLI_REPORT_STRICT);

$mensaje = "";

if ($_SERVER['REQUEST_METHOD'] == 'POST') {
    $nombre_negocio   = trim($_POST['nombre_negocio']);
    $nombre_usuario   = trim($_POST['nombre_usuario']);
    $apellido_usuario = trim($_POST['apellido_usuario']);
    $telefono         = trim($_POST['telefono']);
    $correo           = trim($_POST['correo']);
    $contrasena       = password_hash($_POST['contrasena'], PASSWORD_DEFAULT);

    if (empty($nombre_negocio) || empty($nombre_usuario) || empty($apellido_usuario)) {
        $mensaje = "Negocio, nombre y apellido son obligatorios.";
    } else {
        $telefono = intval($_POST['telefono']);

$stmt = $conn->prepare("INSERT INTO usuario 
    (nombre_negocio, nombre_usuario, apellido_usuario, telefono, correo, contrasena) 
    VALUES (?, ?, ?, ?, ?, ?)");
$stmt->bind_param("sssiss", $nombre_negocio, $nombre_usuario, $apellido_usuario, $telefono, $correo, $contrasena);
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
<style>
body {
    font-family: Arial;
    background: linear-gradient(135deg, #0f4c81, #3fa9f5);
    padding: 40px;
    min-height: 100vh;
    position: relative;
}

body::before, body::after {
    content: "";
    position: absolute;
    border-radius: 50%;
    background: rgba(255,255,255,0.1);
    filter: blur(120px);
}
body::before { width: 500px; height: 500px; top: -100px; left: -100px; }
body::after  { width: 400px; height: 400px; bottom: -100px; right: -100px; }

.contenedor {
    background: white;
    padding: 35px;
    border-radius: 15px;
    max-width: 600px;
    margin: auto;
    box-shadow: 0 10px 25px rgba(0,0,0,0.2);
    position: relative;
    z-index: 1;
}

h1 {
    text-align: center;
    color: #0f4c81;
    margin-bottom: 20px;
}

form {
    display: grid;
    grid-template-columns: 1fr 1fr;
    gap: 15px;
}

label {
    font-weight: bold;
    color: #333;
    font-size: 13px;
}

.campo {
    display: flex;
    flex-direction: column;
    gap: 5px;
}

.campo-full {
    grid-column: span 2;
    display: flex;
    flex-direction: column;
    gap: 5px;
}

input {
    padding: 12px;
    border-radius: 8px;
    border: 1px solid #ccc;
    font-size: 14px;
}

input:focus {
    border-color: #3fa9f5;
    outline: none;
    box-shadow: 0 0 6px rgba(63,169,245,0.5);
}

button {
    grid-column: span 2;
    background: #0f4c81;
    color: white;
    padding: 12px;
    border: none;
    border-radius: 8px;
    font-weight: bold;
    font-size: 15px;
    cursor: pointer;
}

button:hover { background: #09365c; }

.mensaje {
    grid-column: span 2;
    padding: 10px;
    border-radius: 8px;
    text-align: center;
    font-weight: bold;
    background: #f8d7da;
    color: #721c24;
}

.volver {
    grid-column: span 2;
    display: block;
    margin-top: 5px;
    text-align: center;
    text-decoration: none;
    color: #0f4c81;
    font-weight: bold;
}

.volver:hover { text-decoration: underline; }
</style>
</head>

<body>
<div class="contenedor">

    <h1>Crear Usuario</h1>

    <form method="POST" action="crear.php">

        <?php if ($mensaje != ""): ?>
            <div class="mensaje"><?= htmlspecialchars($mensaje) ?></div>
        <?php endif; ?>

        <div class="campo">
            <label>Negocio</label>
            <input type="text" name="nombre_negocio" placeholder="Nombre del negocio" required>
        </div>

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