<?php
session_start();

if (!isset($_SESSION["rol"]) || $_SESSION["rol"] != "admin") {
    header("Location: ../Auth/login.php");
    exit();
}

require_once __DIR__ . "/../../../config/db.php";
$conn = Database::Conectar();

$errores = [];

if($_SERVER["REQUEST_METHOD"] == "POST"){

    $nombre = $_POST["nombre"];
    $stock  = $_POST["stock"];
    $precio = $_POST["precio_venta"];
    $precio_compra = $_POST["precio_compra"];
    $min_stock = $_POST["min_stock"];

    // VALIDACIONES

   if (empty($nombre)) {
    $errores[] = "El nombre es obligatorio";
} elseif (!preg_match("/^[a-zA-ZáéíóúÁÉÍÓÚñÑ\s]+$/", $nombre)) {
    $errores[] = "El nombre solo puede contener letras y espacios";
}

    if (!is_numeric($stock) || $stock < 0) {
        $errores[] = "El stock debe ser numérico y positivo";
    }

    if (!is_numeric($precio) || $precio < 0) {
        $errores[] = "El precio de venta no puede ser negativo";
    }

    if (!is_numeric($precio_compra) || $precio_compra < 0) {
        $errores[] = "El precio de compra no puede ser negativo";
    }

    if (!is_numeric($min_stock) || $min_stock < 0) {
        $errores[] = "El stock mínimo no puede ser negativo";
    }

    // GUARDAR

    if (empty($errores)) {

        $stmt = $conn->prepare("INSERT INTO productos 
        (id_categoria, nombre, stock, precio_compra, precio_venta, min_stock)
        VALUES (?, ?, ?, ?, ?, ?)");

        $id_categoria = 1;

        $stmt->bind_param(
            "isidii",
            $id_categoria,
            $nombre,
            $stock,
            $precio_compra,
            $precio,
            $min_stock
        );

        if($stmt->execute()){

            header("Location: inventario.php");
            exit();

        } else {

            $errores[] = "Error al guardar el producto";

        }
    }
}
?>

<!DOCTYPE html>
<html lang="es">
<head>
<meta charset="UTF-8">
<meta name="viewport" content="width=device-width, initial-scale=1.0">
<title>Agregar Producto</title>

<link rel="preconnect" href="https://fonts.googleapis.com">
<link href="https://fonts.googleapis.com/css2?family=Inter:wght@300;400;500;600;700&display=swap" rel="stylesheet">

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

/* FORM */

.form-box{
    width:100%;
    max-width:520px;
    background:white;
    padding:40px;
    border-radius:28px;
    box-shadow:0 4px 18px rgba(0,0,0,.05);
    border:1px solid #e2e8f0;
}

.form-box h2{
    font-size:32px;
    margin-bottom:10px;
    color:#0f172a;
}

.subtitle{
    color:#64748b;
    margin-bottom:30px;
}

/* ALERT */

.alert{
    background:#fee2e2;
    color:#b91c1c;
    padding:14px;
    border-radius:14px;
    margin-bottom:20px;
    font-size:14px;
}

.alert p{
    margin-bottom:6px;
}

/* INPUTS */

.form-group{
    margin-bottom:20px;
}

.form-group label{
    display:block;
    margin-bottom:8px;
    color:#334155;
    font-weight:500;
}

.form-group input{
    width:100%;
    padding:14px 16px;
    border:1px solid #dbe2ea;
    border-radius:14px;
    outline:none;
    transition:.3s;
    font-size:15px;
    background:#f8fafc;
}

.form-group input:focus{
    border-color:#17345f;
    background:white;
}

/* BUTTONS */

.buttons{
    display:flex;
    gap:12px;
    margin-top:25px;
}

button{
    flex:1;
    border:none;
    padding:15px;
    border-radius:14px;
    cursor:pointer;
    font-size:15px;
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
    background:#e2e8f0;
    color:#334155;
}

.btn-back:hover{
    background:#cbd5e1;
}

a{
    text-decoration:none;
    color:inherit;
}

</style>
</head>

<body>

<div class="form-box">

    <h2>Agregar Producto</h2>
    <p class="subtitle">
        Completa la información del nuevo producto
    </p>

    <?php if (!empty($errores)): ?>

        <div class="alert">

            <?php foreach($errores as $error): ?>
                <p><?php echo $error; ?></p>
            <?php endforeach; ?>

        </div>

    <?php endif; ?>

    <form method="POST">

        <div class="form-group">
            <label>Nombre del producto</label>
            <input type="text" name="nombre" required>
        </div>

        <div class="form-group">
            <label>Stock</label>
            <input type="number" name="stock" required>
        </div>

        <div class="form-group">
            <label>Precio de venta</label>
            <input type="number" step="0.01"
            name="precio_venta" required>
        </div>

        <div class="form-group">
            <label>Precio de compra</label>
            <input type="number"
            name="precio_compra" required>
        </div>

        <div class="form-group">
            <label>Stock mínimo</label>
            <input type="number"
            name="min_stock" required>
        </div>

        <div class="buttons">

            <button type="button"
            class="btn-back"
            onclick="location.href='inventario.php'">
                Volver
            </button>

            <button type="submit"
            class="btn-save">
                Guardar Producto
            </button>

        </div>

    </form>

</div>

</body>
</html>