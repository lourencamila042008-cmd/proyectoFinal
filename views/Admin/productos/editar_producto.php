
<?php
session_start();

if (!isset($_SESSION["rol"]) || $_SESSION["rol"] != "admin") {
    header("Location: ../Auth/login.php");
    exit();
}

require_once __DIR__ . "/../../../config/db.php";

$conn = Database::Conectar();

$id = isset($_GET['id']) ? intval($_GET['id']) : 0;

$stmt = $conn->prepare("
    SELECT *
    FROM productos
    WHERE id_productos = ?
");

$stmt->bind_param("i", $id);
$stmt->execute();

$result = $stmt->get_result();

if ($result->num_rows == 0) {
    die("Producto no encontrado");
}

$producto = $result->fetch_assoc();

$mensaje = "";
$errores = [];

if ($_SERVER["REQUEST_METHOD"] == "POST") {

    $nombre = trim($_POST["nombre"]);
    $stock = intval($_POST["stock"]);
    $precio_venta = floatval($_POST["precio_venta"]);
    $precio_compra = floatval($_POST["precio_compra"]);
    $min_stock = intval($_POST["min_stock"]);

    if (empty($nombre)) {
        $errores[] = "El nombre es obligatorio";
    } elseif (!preg_match("/^[a-zA-ZáéíóúÁÉÍÓÚñÑ0-9\s]+$/", $nombre)) {
        $errores[] = "El nombre contiene caracteres no válidos";
    }

    if ($stock < 0) {
        $errores[] = "El stock no puede ser negativo";
    }

    if ($precio_venta <= 0) {
        $errores[] = "El precio de venta debe ser mayor a cero";
    }

    if ($precio_compra <= 0) {
        $errores[] = "El precio de compra debe ser mayor a cero";
    }

    if ($min_stock < 0) {
        $errores[] = "El stock mínimo no puede ser negativo";
    }

    if (empty($errores)) {

        $update = $conn->prepare("
            UPDATE productos
            SET nombre = ?,
                stock = ?,
                precio_venta = ?,
                precio_compra = ?,
                min_stock = ?
            WHERE id_productos = ?
        ");

        $update->bind_param(
            "siddii",
            $nombre,
            $stock,
            $precio_venta,
            $precio_compra,
            $min_stock,
            $id
        );

        if ($update->execute()) {

            header("Location: inventario.php");
            exit();

        } else {

            $mensaje = "Error al actualizar el producto.";

        }
    }
}
?>

<!DOCTYPE html>
<html lang="es">
<head>

<meta charset="UTF-8">
<meta name="viewport" content="width=device-width, initial-scale=1.0">

<title>Editar Producto</title>

<link rel="preconnect" href="https://fonts.googleapis.com">
<link href="https://fonts.googleapis.com/css2?family=Inter:wght@300;400;500;600;700&display=swap" rel="stylesheet">

<style>

*{
    margin:0;
    padding:0;
    box-sizing:border-box;
    font-family:'Inter',sans-serif;
}

body{
    background:#f4f6f9;
    min-height:100vh;
    display:flex;
    justify-content:center;
    align-items:center;
    padding:30px;
}

.card{

    width:100%;
    max-width:700px;
    background:white;
    border-radius:24px;
    padding:35px;
    box-shadow:0 10px 25px rgba(0,0,0,.08);

}

h1{

    color:#17345f;
    margin-bottom:25px;
    text-align:center;

}

.form-group{

    margin-bottom:18px;

}

label{

    display:block;
    margin-bottom:8px;
    font-weight:600;
    color:#334155;

}

input{

    width:100%;
    padding:14px;
    border:1px solid #dbe3ee;
    border-radius:12px;
    font-size:14px;

}

input:focus{

    outline:none;
    border-color:#17345f;

}

.error{

    background:#fee2e2;
    color:#991b1b;
    padding:12px;
    border-radius:10px;
    margin-bottom:15px;

}

.btn-group{

    display:flex;
    gap:12px;
    margin-top:25px;

}

.btn{

    flex:1;
    border:none;
    padding:14px;
    border-radius:12px;
    cursor:pointer;
    font-weight:600;
    transition:.3s;

}

.btn-save{

    background:#17345f;
    color:white;

}

.btn-save:hover{

    background:#264c83;

}

.btn-back{

    background:#64748b;
    color:white;

}

.btn-back:hover{

    background:#475569;

}

</style>

</head>
<body>

<div class="card">

<h1>Editar Producto</h1>

<?php if(!empty($errores)): ?>

<div class="error">

<?php foreach($errores as $error): ?>
<div><?= $error ?></div>
<?php endforeach; ?>

</div>

<?php endif; ?>

<form method="POST">

<div class="form-group">
<label>Nombre</label>
<input
type="text"
name="nombre"
value="<?= htmlspecialchars($producto['nombre']) ?>"
required>
</div>

<div class="form-group">
<label>Stock</label>
<input
type="number"
name="stock"
value="<?= $producto['stock'] ?>"
required>
</div>

<div class="form-group">
<label>Precio Venta</label>
<input
type="number"
step="0.01"
name="precio_venta"
value="<?= $producto['precio_venta'] ?>"
required>
</div>

<div class="form-group">
<label>Precio Compra</label>
<input
type="number"
step="0.01"
name="precio_compra"
value="<?= $producto['precio_compra'] ?>"
required>
</div>

<div class="form-group">
<label>Stock Mínimo</label>
<input
type="number"
name="min_stock"
value="<?= $producto['min_stock'] ?>"
required>
</div>

<div class="btn-group">

<button type="submit" class="btn btn-save">
💾 Guardar cambios
</button>

<button
type="button"
class="btn btn-back"
onclick="window.location='inventario.php'">
⬅ Volver
</button>

</div>

</form>

</div>

</body>
</html>
```
