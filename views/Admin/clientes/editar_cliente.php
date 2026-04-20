<?php
require_once "../../../config/db.php";
$conn = Database::Conectar();

$mensaje = "";

// 1️⃣ Obtener el ID desde la URL
if (!isset($_GET['id']) || empty($_GET['id'])) {
    header("Location: clientes.php");
    exit;
}
$id = intval($_GET['id']);

// 2️⃣ Cargar datos actuales del cliente
$stmt = $conn->prepare("SELECT * FROM clientes WHERE id_clientes = ?");
$stmt->bind_param("i", $id);
$stmt->execute();
$cliente = $stmt->get_result()->fetch_assoc();
$stmt->close();

if (!$cliente) {
    header("Location: clientes.php");
    exit;
}

// 3️⃣ Procesar el formulario al enviar
if ($_SERVER['REQUEST_METHOD'] == 'POST') {
    $nombre   = trim($_POST['nombre']);
    $cedula   = trim($_POST['cedula']);
    $telefono = trim($_POST['telefono']);

    if (empty($nombre) || empty($cedula)) {
        $mensaje = "Nombre y cédula son obligatorios";
    } else {
        $stmt = $conn->prepare("UPDATE clientes SET nombre=?, cedula=?, telefono=? WHERE id_clientes=?");
        $stmt->bind_param("sssi", $nombre, $cedula, $telefono, $id);

        if ($stmt->execute()) {
            $stmt->close();
            echo "<script>alert('Cliente actualizado'); window.location='clientes.php';</script>";
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
<title>Editar Cliente</title>
<style>
body {
    font-family: Arial;
    background: linear-gradient(135deg, #0f4c81, #2f7bbd, #3fa9f5);
    min-height: 100vh;
    padding: 40px;
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

.container {
    background: white;
    padding: 35px;
    border-radius: 15px;
    max-width: 500px;
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
    display: flex;
    flex-direction: column;
    gap: 15px;
}

label {
    font-weight: bold;
    color: #333;
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
    background: #0f4c81;
    color: white;
    padding: 12px;
    border: none;
    border-radius: 8px;
    font-weight: bold;
    font-size: 15px;
    cursor: pointer;
}

button:hover {
    background: #09365c;
}

.mensaje {
    padding: 10px;
    border-radius: 8px;
    margin-bottom: 10px;
    text-align: center;
    font-weight: bold;
    background: #f8d7da;
    color: #721c24;
}

.volver {
    display: block;
    margin-top: 15px;
    text-align: center;
    text-decoration: none;
    color: #0f4c81;
    font-weight: bold;
}

.volver:hover {
    text-decoration: underline;
}
</style>
</head>

<body>
<div class="container">

    <h1>Editar Cliente</h1>

    <?php if ($mensaje != ""): ?>
        <div class="mensaje"><?= htmlspecialchars($mensaje) ?></div>
    <?php endif; ?>

    <form method="POST" action="editar_cliente.php?id=<?= $id ?>">

        <label>Nombre completo</label>
        <input type="text" name="nombre"
               value="<?= htmlspecialchars($cliente['nombre']) ?>"
               placeholder="Nombre completo" required>

        <label>Cédula</label>
        <input type="text" name="cedula"
               value="<?= htmlspecialchars($cliente['cedula']) ?>"
               placeholder="Cédula" required>

        <label>Teléfono</label>
        <input type="text" name="telefono"
               value="<?= htmlspecialchars($cliente['telefono']) ?>"
               placeholder="Teléfono">

        <button type="submit">Actualizar Cliente</button>

    </form>

    <a class="volver" href="clientes.php">⬅ Volver</a>

</div>
</body>
</html>