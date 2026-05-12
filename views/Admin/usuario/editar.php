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
    $nombre_usuario   = trim($_POST['nombre_usuario']);
    $apellido_usuario = trim($_POST['apellido_usuario']);
    $telefono         = intval($_POST['telefono']);
    $correo           = trim($_POST['correo']);

    if (empty($nombre_usuario) || empty($apellido_usuario)) {
        $mensaje = "Nombre y apellido son obligatorios.";
    } else {
        // Si ingresó nueva contraseña la actualizamos, si no la dejamos igual
        if (!empty($_POST['contrasena'])) {
            $contrasena = password_hash($_POST['contrasena'], PASSWORD_DEFAULT);
            $stmt = $conn->prepare("UPDATE usuario SET nombre_usuario=?, apellido_usuario=?, telefono=?, correo=?, contrasena=? WHERE id_usuario=?");
            $stmt->bind_param("sssissi", $nombre_usuario, $apellido_usuario, $telefono, $correo, $contrasena, $id);
        } else {
            $stmt = $conn->prepare("UPDATE usuario SET nombre_usuario=?, apellido_usuario=?, telefono=?, correo=? WHERE id_usuario=?");
            $stmt->bind_param("sssisi", $nombre_usuario, $apellido_usuario, $telefono, $correo, $id);
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
<link rel="preconnect" href="https://fonts.googleapis.com">
<link href="https://fonts.googleapis.com/css2?family=Inter:wght@300;400;500;600;700&display=swap" rel="stylesheet">

</head>

<body>
    <style>
        *{
    margin:0;
    padding:0;
    box-sizing:border-box;
    font-family:'Inter', sans-serif;
}

body{
    background:#f4f6f9;
    display:flex;
    justify-content:center;
    align-items:center;
    min-height:100vh;
    padding:20px;
}

.contenedor{
    width:100%;
    max-width:550px;
    background:white;
    border-radius:28px;
    padding:40px;
    border:1px solid #e2e8f0;
    box-shadow:0 4px 18px rgba(0,0,0,.05);
}

h1{
    font-size:32px;
    margin-bottom:30px;
    color:#0f172a;
}

.mensaje{
    background:#fee2e2;
    color:#dc2626;
    padding:14px;
    border-radius:14px;
    margin-bottom:20px;
    font-size:14px;
}

.campo{
    margin-bottom:20px;
}

.campo label{
    display:block;
    margin-bottom:8px;
    color:#334155;
    font-weight:500;
}

.campo input{
    width:100%;
    padding:14px 16px;
    border:1px solid #dbe2ea;
    border-radius:14px;
    outline:none;
    background:#f8fafc;
    transition:.3s;
}

.campo input:focus{
    border-color:#17345f;
    background:white;
}

button{
    width:100%;
    margin-top:10px;
    border:none;
    background:#17345f;
    color:white;
    padding:15px;
    border-radius:14px;
    font-size:15px;
    font-weight:600;
    cursor:pointer;
    transition:.3s;
}

button:hover{
    background:#264c83;
}

.volver{
    display:inline-block;
    margin-top:20px;
    text-decoration:none;
    color:#64748b;
    font-weight:500;
}

    </style>
<div class="contenedor">

    <h1>Editar Usuario #<?= $id ?></h1>

    <form method="POST" action="editar.php?id=<?= $id ?>">

        <?php if ($mensaje != ""): ?>
            <div class="mensaje"><?= htmlspecialchars($mensaje) ?></div>
        <?php endif; ?>

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