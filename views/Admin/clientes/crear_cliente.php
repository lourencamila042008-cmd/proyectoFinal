<?php

require_once "../../../models/clientes.php";

$model = new Cliente();

if($_SERVER['REQUEST_METHOD'] == 'POST'){

    $nombre = $_POST['nombre'];
    $cedula = $_POST['cedula'];
    $telefono = $_POST['telefono'];

    $guardar = $model->crear($nombre, $cedula, $telefono);

    if($guardar){
        echo "<script>alert('Cliente guardado'); window.location='clientes.php';</script>";
    } else {
        echo "Error al guardar";
    }
}
?>
<!DOCTYPE html>
<html lang="es">
<head>
<meta charset="UTF-8">
<title>Nuevo Cliente</title>
<link rel="stylesheet" href="../../../public/css/clientes/crear_clientes.css">
</head>


<body>

<div class="container">

<h1>Nuevo Cliente</h1>

<form method="POST">

<input type="text" name="nombre" placeholder="Nombre completo" required>

<input type="text" name="cedula" placeholder="Cédula" required>

<input type="text" name="telefono" placeholder="Teléfono">

<button type="submit">Guardar Cliente</button>

</form>

<a class="volver" href="clientes.php">⬅ Volver</a>

</div>

</body>
</html>