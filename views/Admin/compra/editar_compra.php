<?php
require_once "../../../config/db.php";
$conn = Database::conectar();

if (!isset($_GET['id']) || empty($_GET['id'])) {
    header("Location: compras.php");
    exit;
}

$id = intval($_GET['id']);

// CONSULTA DE LA COMPRA
$stmt = $conn->prepare("
    SELECT *
    FROM compras
    WHERE id_compra = ?
");

$stmt->bind_param("i", $id);
$stmt->execute();

$compra = $stmt->get_result()->fetch_assoc();

$stmt->close();

if (!$compra) {
    header("Location: compras.php");
    exit;
}

// PROVEEDORES
$proveedores = $conn->query("
    SELECT * 
    FROM proveedores
    ORDER BY nombre ASC
");

// ACTUALIZAR
if ($_SERVER['REQUEST_METHOD'] == 'POST') {

    $id_proveedor = intval($_POST['id_proveedor']);
    $precio_total = floatval($_POST['precio_total']);
    $fecha        = $_POST['fecha'];

    $stmt = $conn->prepare("
        UPDATE compras
        SET 
            id_proveedor = ?,
            precio_total = ?,
            fecha = ?
        WHERE id_compra = ?
    ");

    $stmt->bind_param(
        "idsi",
        $id_proveedor,
        $precio_total,
        $fecha,
        $id
    );

    if ($stmt->execute()) {

        $stmt->close();

        echo "
        <script>
            alert('Compra actualizada correctamente');
            window.location='compras.php';
        </script>
        ";

        exit;

    } else {

        echo "
        <script>
            alert('Error al actualizar');
        </script>
        ";
    }
}
?>

<!DOCTYPE html>
<html lang="es">

<head>
<meta charset="UTF-8">
<title>Editar Compra</title>

<link rel="stylesheet" href="../../../public/css/compras/editar_compra.css">

</head>

<body>
<style>
    *{
    margin:0;
    padding:0;
    box-sizing:border-box;
}

body{
    font-family:'Segoe UI', sans-serif;
    background:#f4f7fb;
    padding:40px;
    color:#1e293b;
}

.container{
    max-width:600px;
    margin:auto;
    background:white;
    padding:35px;
    border-radius:18px;
    box-shadow:0 10px 30px rgba(0,0,0,0.05);
}

h1{
    text-align:center;
    margin-bottom:30px;
    color:#0f172a;
    font-size:30px;
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
}

input,
select{
    width:100%;
    padding:14px;
    border:1px solid #dbe2ea;
    border-radius:10px;
    font-size:14px;
    background:white;
    transition:.3s;
}

input:focus,
select:focus{
    outline:none;
    border-color:#2563eb;
    box-shadow:0 0 0 4px rgba(37,99,235,0.10);
}

button{
    background:#2563eb;
    color:white;
    border:none;
    padding:14px;
    border-radius:10px;
    font-size:15px;
    font-weight:600;
    cursor:pointer;
    transition:.3s;
    margin-top:10px;
}

button:hover{
    background:#1d4ed8;
    transform:translateY(-2px);
}

.volver{
    display:inline-block;
    margin-top:20px;
    text-decoration:none;
    color:#2563eb;
    font-weight:600;
}

.volver:hover{
    text-decoration:underline;
}

@media(max-width:768px){

    body{
        padding:20px;
    }

    .container{
        padding:25px;
    }

}
</style>
<div class="container">

    <h1>Editar Compra</h1>

    <form method="POST">

        <!-- PROVEEDOR -->
        <label>Proveedor</label>

        <select name="id_proveedor" required>

            <?php while($p = $proveedores->fetch_assoc()){ ?>

            <option 
                value="<?= $p['id_proveedores'] ?>"
                <?= $p['id_proveedores'] == $compra['id_proveedor'] ? 'selected' : '' ?>
            >

                <?= htmlspecialchars($p['nombre']) ?>

            </option>

            <?php } ?>

        </select>

        <!-- TOTAL -->
        <label>Total</label>

        <input
        type="number"
        name="precio_total"
        step="0.01"
        min="0"
        value="<?= $compra['precio_total'] ?>"
        required
        >

        <!-- FECHA -->
        <label>Fecha</label>

        <input
        type="date"
        name="fecha"
        value="<?= $compra['fecha'] ?>"
        required
        >

        <button type="submit">

            Guardar Cambios

        </button>

    </form>

    <a class="volver" href="compras.php">

        ⬅ Volver

    </a>

</div>

</body>
</html>