<?php
require_once "../../../config/db.php";
$conn = Database::Conectar();

$mensaje = "";
$errores = [];

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
$stmt->close(); // ✅ Aquí se cierra solo la consulta de LECTURA

if (!$cliente) {
    header("Location: clientes.php");
    exit;
}

// 3️⃣ Procesar el formulario al enviar
if ($_SERVER['REQUEST_METHOD'] == 'POST') {
    $nombre   = trim($_POST['nombre']);
    $cedula   = trim($_POST['cedula']);
    $telefono = trim($_POST['telefono']);

    // VALIDACIONES
    if (empty($nombre)) {
        $errores[] = "El nombre es obligatorio";
    } elseif (!preg_match("/^[a-zA-ZáéíóúÁÉÍÓÚñÑ\s]+$/", $nombre)) {
        $errores[] = "El nombre solo puede contener letras y espacios";
    }

    if (empty($cedula)) {
        $errores[] = "La cédula es obligatoria";
    } elseif (!ctype_digit($cedula)) {
        $errores[] = "La cédula solo debe contener números";
    } elseif (strlen($cedula) < 6 || strlen($cedula) > 15) {
        $errores[] = "La cédula no tiene un formato válido";
    }

    if (!empty($telefono)) {
        if (!ctype_digit($telefono)) {
            $errores[] = "El teléfono solo debe contener números";
        } elseif (strlen($telefono) < 7 || strlen($telefono) > 15) {
            $errores[] = "El teléfono no es válido";
        }
    }

    // SI NO HAY ERRORES, ACTUALIZAR
    if (empty($errores)) {}
       // ✅ Agregamos 'motivo'
$stmt = $conn->prepare("UPDATE clientes SET nombre = ?, cedula = ?, telefono = ?, correo = ?, motivo = ? WHERE id_clientes = ?");
$stmt->bind_param("sssssi", $nombre, $cedula, $telefono, $correo, $motivo, $id);
        if ($stmt->execute()) {
            $stmt->close();
            echo "<script>alert('Cliente actualizado correctamente ✅'); window.location='clientes.php';</script>";
            exit;
        } else {
            $mensaje = "Error al actualizar: " . $conn->error;
        }
    } else {
        // Mostrar errores de validación
        $mensaje = implode("<br>", $errores);
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
    <style>
        @import url('https://fonts.googleapis.com/css2?family=Inter:wght@400;500;600;700&display=swap');

*{
    margin:0;
    padding:0;
    box-sizing:border-box;
}

body{
    background:#f4f7fb;
    font-family:'Inter',sans-serif;
    min-height:100vh;
    display:flex;
    justify-content:center;
    align-items:center;
    padding:30px;
}

.container{
    width:100%;
    max-width:700px;
    background:white;
    padding:40px;
    border-radius:24px;
    box-shadow:0 10px 30px rgba(15,23,42,.08);
}

h1{
    font-size:30px;
    margin-bottom:25px;
    color:#0f172a;
}

form{
    display:flex;
    flex-direction:column;
    gap:18px;
}

label{
    font-size:14px;
    font-weight:600;
    color:#334155;
    margin-bottom:6px;
}

input{
    width:100%;
    padding:15px;
    border:1px solid #dbe2ea;
    border-radius:14px;
    background:#f8fafc;
    font-size:14px;
    transition:.3s;
}

input:focus{
    outline:none;
    border-color:#2563eb;
    background:white;
}

button{
    margin-top:10px;
    background:#1e3a5f;
    color:white;
    border:none;
    padding:15px;
    border-radius:14px;
    font-size:15px;
    font-weight:600;
    cursor:pointer;
    transition:.3s;
}

button:hover{
    background:#16304d;
    transform:translateY(-2px);
}

.mensaje{
    background:#fee2e2;
    color:#b91c1c;
    padding:14px;
    border-radius:12px;
    margin-bottom:20px;
    font-size:14px;
}

.volver{
    display:inline-block;
    margin-top:22px;
    text-decoration:none;
    color:#475569;
    font-size:14px;
    font-weight:500;
}

.volver:hover{
    color:#2563eb;
}
    </style>
<div class="container">

    <h1>Editar Cliente</h1>

    <?php if ($mensaje != ""): ?>
        <div class="mensaje"><?= htmlspecialchars($mensaje, ENT_QUOTES, 'UTF-8') ?></div>
    <?php endif; ?>

    <form method="POST" action="editar_cliente.php?id=<?= $id ?>">

        <label>Nombre completo</label>
        <input type="text" name="nombre"
               value="<?= htmlspecialchars($cliente['nombre'], ENT_QUOTES, 'UTF-8') ?>"
               placeholder="Nombre completo" required>

        <label>Cédula</label>
        <input type="text" name="cedula"
               value="<?= htmlspecialchars($cliente['cedula'], ENT_QUOTES, 'UTF-8') ?>"
               placeholder="Cédula" required>

        <label>Teléfono</label>
        <input type="text" name="telefono"
               value="<?= htmlspecialchars($cliente['telefono'], ENT_QUOTES, 'UTF-8') ?>"
               placeholder="Teléfono">

   <label>Estado del cliente</label>
<select name="motivo" required>
    <option value="activo" <?= ($cliente['motivo'] == 'activo') ? 'selected' : '' ?>>✅ Activo</option>
    <option value="inactivo" <?= ($cliente['motivo'] == 'inactivo') ? 'selected' : '' ?>>❌ Inactivo</option>
</select>

<button type="submit">Actualizar Cliente</button>
    </form>

    <a class="volver" href="clientes.php">⬅ Volver</a>

</div>
</body>
</html>