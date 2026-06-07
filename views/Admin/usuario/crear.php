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
} elseif (!preg_match("/^[a-zA-ZÁÉÍÓÚáéíóúÑñ ]+$/", $nombre_usuario)) {
    $mensaje = "El nombre solo puede contener letras.";
} elseif (!preg_match("/^[a-zA-ZÁÉÍÓÚáéíóúÑñ ]+$/", $apellido_usuario)) {
    $mensaje = "El apellido solo puede contener letras.";
} elseif (!filter_var($correo, FILTER_VALIDATE_EMAIL)) {
    $mensaje = "El correo no es válido.";
} elseif (!preg_match('/^[0-9]+$/', $telefono)) {
    $mensaje = "El teléfono solo puede contener números.";
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
    </style>
<div class="contenedor">

    <h1>Crear Usuario</h1>

    <form method="POST" action="crear.php">

        <?php if ($mensaje != ""): ?>
            <div class="mensaje"><?= htmlspecialchars($mensaje) ?></div>
        <?php endif; ?>

        <div class="campo">
            <label>Nombre</label>
            <input type="text"
       name="nombre_usuario"
       placeholder="Nombre"
       pattern="[A-Za-zÁÉÍÓÚáéíóúÑñ ]+"
       title="Solo se permiten letras"
       required>
        </div>

        <div class="campo">
            <label>Apellido</label>
            <input type="text"
       name="apellido_usuario"
       placeholder="Apellido"
       pattern="[A-Za-zÁÉÍÓÚáéíóúÑñ ]+"
       title="Solo se permiten letras"
       required>
        </div>

        <div class="campo">
            <label>Teléfono</label>
            <input type="text"
       name="telefono"
       placeholder="Teléfono"
       pattern="[0-9]+"
       title="Solo números"
       required>
        </div>

        <div class="campo">
            <label>Correo</label>
            <input type="email" name="correo" placeholder="correo@ejemplo.com" pattern="[a-zA-Z0-9._%+-]+@[a-zA-Z0-9.-]+\.[a-zA-Z]{2,}$" title="Ingrese un correo válido" required>
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