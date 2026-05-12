<?php
require_once "../../../config/db.php";
$conn = Database::Conectar();

$errores = [];
$mensaje = "";

// Procesar formulario
if ($_SERVER['REQUEST_METHOD'] == 'POST') {
    $id_facturas   = intval($_POST['id_facturas']);
    $id_producto   = intval($_POST['id_producto']);
    $motivo        = $_POST['motivo'];
    $solucion      = $_POST['solucion'];
    $estado        = $_POST['estado'];
    $fecha_inicio  = $_POST['fecha_inicio'];
    $fecha_fin     = $_POST['fecha_fin'];

     // VALIDACIONES

    // IDs
    if ($id_facturas <= 0) {
        $errores[] = "Debe seleccionar una factura válida";
    }

    if ($id_producto <= 0) {
        $errores[] = "Debe seleccionar un producto válido";
    }

    // Motivo
    $motivos_validos = ["ninguno", "daño"];
    if (!in_array($motivo, $motivos_validos)) {
        $errores[] = "Motivo no válido";
    }

    // Solución
    $soluciones_validas = ["cambio", "reparacion", "devolucion"];
    if (!in_array($solucion, $soluciones_validas)) {
        $errores[] = "Solución no válida";
    }

    // Estado
    $estados_validos = ["pendiente", "en_revision", "resuelto"];
    if (!in_array($estado, $estados_validos)) {
        $errores[] = "Estado no válido";
    }

    // Fechas
    if (empty($fecha_inicio) || empty($fecha_fin)) {
        $errores[] = "Las fechas son obligatorias";
    } else {
        if ($fecha_fin < $fecha_inicio) {
            $errores[] = "La fecha fin no puede ser menor que la fecha inicio";
        }
    }

    // SI TODO ESTÁ BIEN
    if (empty($errores)) {

        $stmt = $conn->prepare("INSERT INTO garantias 
        (id_facturas, id_producto, motivo, solucion, estado, fecha_inicio, fecha_fin) 
        VALUES (?, ?, ?, ?, ?, ?, ?)");

        $stmt->bind_param(
            "iisssss",
            $id_facturas,
            $id_producto,
            $motivo,
            $solucion,
            $estado,
            $fecha_inicio,
            $fecha_fin
        );

        if ($stmt->execute()) {
            $stmt->close();
            echo "<script>alert('Garantía registrada correctamente'); window.location='iniciogarantias.php';</script>";
            exit;
        } else {
            $errores[] = "Error al guardar la garantía";
            $stmt->close();
        }
    }
}

// CONSULTAS
$facturas  = $conn->query("SELECT f.id_facturas, c.nombre FROM facturas f JOIN clientes c ON f.id_clientes = c.id_clientes ORDER BY f.id_facturas DESC");
$productos = $conn->query("SELECT * FROM productos ORDER BY nombre ASC");
?>

<!DOCTYPE html>
<html lang="es">
<head>
<meta charset="UTF-8">
<title>Crear Garantía</title>
<link rel="stylesheet" href="../../../public/css/garantias/crear_garantia.css">

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
    display:flex;
    justify-content:center;
    align-items:center;
    min-height:100vh;
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
    margin-bottom:30px;
    color:#0f172a;
}

.mensaje{
    background:#fee2e2;
    color:#b91c1c;
    padding:14px;
    border-radius:12px;
    margin-bottom:20px;
    font-size:14px;
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

input,
select{
    width:100%;
    padding:14px;
    border:1px solid #dbe2ea;
    border-radius:14px;
    background:#f8fafc;
    font-size:14px;
    transition:.3s;
}

input:focus,
select:focus{
    outline:none;
    border-color:#2563eb;
    background:white;
}

.grid{
    display:grid;
    grid-template-columns:1fr 1fr;
    gap:18px;
}

button{
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

.volver{
    display:inline-block;
    margin-top:20px;
    text-decoration:none;
    color:#475569;
    font-size:14px;
    font-weight:500;
}

.volver:hover{
    color:#2563eb;
}

@media(max-width:700px){
    .grid{
        grid-template-columns:1fr;
    }

    .container{
        padding:25px;
    }
}
    </style>
<div class="container">

    <h1>Nueva Garantía</h1>

   <?php if (!empty($errores)): ?>
    <div class="mensaje" style="color:red;">
        <?php foreach($errores as $e): ?>
            <p><?= htmlspecialchars($e) ?></p>
        <?php endforeach; ?>
    </div>
<?php endif; ?>

    <form method="POST" action="crear_garantia.php">

        <label>Factura asociada</label>
        <select name="id_facturas" required>
            <option disabled selected>Seleccionar factura</option>
            <?php while($f = $facturas->fetch_assoc()){ ?>
                <option value="<?= $f['id_facturas'] ?>">
                    Factura #<?= $f['id_facturas'] ?> — <?= htmlspecialchars($f['nombre']) ?>
                </option>
            <?php } ?>
        </select>

        <label>Producto</label>
        <select name="id_producto" required>
            <option disabled selected>Seleccionar producto</option>
            <?php while($p = $productos->fetch_assoc()){ ?>
                <option value="<?= $p['id_productos'] ?>">
                    <?= htmlspecialchars($p['nombre']) ?>
                </option>
            <?php } ?>
        </select>

        <label>Motivo</label>
        <select name="motivo" required>
            <option disabled selected>Seleccionar motivo</option>
            <option value="ninguno">Ninguno</option>
            <option value="daño">Daño</option>
        </select>

        <label>Solución</label>
        <select name="solucion" required>
            <option disabled selected>Seleccionar solución</option>
            <option value="cambio">Cambio</option>
            <option value="reparacion">Reparación</option>
            <option value="devolucion">Devolución</option>
        </select>

        <label>Estado</label>
        <select name="estado" required>
            <option disabled selected>Seleccionar estado</option>
            <option value="pendiente">Pendiente</option>
            <option value="en_revision">En revisión</option>
            <option value="resuelto">Resuelto</option>
        </select>

        <div class="grid">
            <div>
                <label>Fecha inicio</label>
                <input type="date" name="fecha_inicio" required>
            </div>
            <div>
                <label>Fecha fin</label>
                <input type="date" name="fecha_fin" required>
            </div>
        </div>

        <button type="submit">Registrar Garantía</button>

    </form>

    <a class="volver" href="iniciogarantias.php">⬅ Volver</a>

</div>
</body>
</html>