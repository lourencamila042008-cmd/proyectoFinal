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
<link rel="stylesheet" href="../../../public/css/clientes/editar_clientes.css">
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