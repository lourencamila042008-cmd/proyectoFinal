<?php
require_once "../../../config/db.php";
$conn = Database::Conectar();

$mensaje = "";

if (!isset($_GET['id']) || empty($_GET['id'])) {
    header("Location: usuarios.php");
    exit;
}
$id = intval($_GET['id']);

// Cargar datos del usuario
$stmt = $conn->prepare("SELECT * FROM usuario WHERE id_usuario = ?");
$stmt->bind_param("i", $id);
$stmt->execute();
$usuario = $stmt->get_result()->fetch_assoc();
$stmt->close();

if (!$usuario) {
    header("Location: usuarios.php");
    exit;
}

// Procesar formulario
if ($_SERVER['REQUEST_METHOD'] == 'POST') {
    $nombre_negocio   = trim($_POST['nombre_negocio']);
    $nombre_usuario   = trim($_POST['nombre_usuario']);
    $apellido_usuario = trim($_POST['apellido_usuario']);
    $telefono         = intval($_POST['telefono']);
    $correo           = trim($_POST['correo']);

    if (empty($nombre_negocio) || empty($nombre_usuario) || empty($apellido_usuario)) {
        $mensaje = "Negocio, nombre y apellido son obligatorios.";
    } else {
        // Si ingresó nueva contraseña la actualizamos, si no la dejamos igual
        if (!empty($_POST['contrasena'])) {
            $contrasena = password_hash($_POST['contrasena'], PASSWORD_DEFAULT);
            $stmt = $conn->prepare("UPDATE usuario SET nombre_negocio=?, nombre_usuario=?, apellido_usuario=?, telefono=?, correo=?, contrasena=? WHERE id_usuario=?");
            $stmt->bind_param("sssissi", $nombre_negocio, $nombre_usuario, $apellido_usuario, $telefono, $correo, $contrasena, $id);
        } else {
            $stmt = $conn->prepare("UPDATE usuario SET nombre_negocio=?, nombre_usuario=?, apellido_usuario=?, telefono=?, correo=? WHERE id_usuario=?");
            $stmt->bind_param("sssisi", $nombre_negocio, $nombre_usuario, $apellido_usuario, $telefono, $correo, $id);
        }

        if ($stmt->execute()) {
            $stmt->close();
            echo "<script>alert('Usuario actualizado correctamente'); window.location='usuarios.php';</script>";
            exit;
        } else {
            $mensaje = "Error al actualizar: " . $conn->error;
            $stmt->close();
        }
    }
}
?>

<!DOCTYPE html>
<html lang="es">
<head>
<meta charset="UTF-8">
<title>Editar Usuario</title>
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

h1 { text-align: center; color: #0f4c81; margin-bottom: 20px; }

form { display: grid; grid-template-columns: 1fr 1fr; gap: 15px; }

label { font-weight: bold; color: #333; font-size: 13px; }

.campo { display: flex; flex-direction: column; gap: 5px; }

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

    <h1>Editar Usuario #<?= $id ?></h1>

    <form method="POST" action="editar.php?id=<?= $id ?>">

        <?php if ($mensaje != ""): ?>
            <div class="mensaje"><?= htmlspecialchars($mensaje) ?></div>
        <?php endif; ?>

        <div class="campo">
            <label>Negocio</label>
            <input type="text" name="nombre_negocio"
                   value="<?= htmlspecialchars($usuario['nombre_negocio']) ?>" required>
        </div>

        <div class="campo">
            <label>Nombre</label>
            <input type="text" name="nombre_usuario"
                   value="<?= htmlspecialchars($usuario['nombre_usuario']) ?>" required>
        </div>

        <div class="campo">
            <label>Apellido</label>
            <input type="text" name="apellido_usuario"
                   value="<?= htmlspecialchars($usuario['apellido_usuario']) ?>" required>
        </div>

        <div class="campo">
            <label>Teléfono</label>
            <input type="text" name="telefono"
                   value="<?= $usuario['telefono'] ?>">
        </div>

        <div class="campo">
            <label>Correo</label>
            <input type="email" name="correo"
                   value="<?= htmlspecialchars($usuario['correo']) ?>">
        </div>

        <div class="campo">
            <label>Nueva contraseña (opcional)</label>
            <input type="password" name="contrasena" placeholder="Dejar vacío para no cambiar">
        </div>

        <button type="submit">Guardar Cambios</button>
        <a class="volver" href="usuarios.php">⬅ Volver</a>

    </form>

</div>
</body>
</html>