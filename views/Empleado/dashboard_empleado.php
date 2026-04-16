<?php
session_start();

if (!isset($_SESSION["rol"]) || $_SESSION["rol"] != "empleado") {
    header("Location: ../Auth/login.php");
    exit();
}
?>
<!DOCTYPE html>
<html lang="en">
<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <title>Document</title>
    <link rel="stylesheet" href="/MVC-PRU/public/css/empleado.css">

</head>
<body>
    

<div class="header">
  <h1>InvoicePro</h1>

  <div>
    </div>
</div>

<div class="menu">

<a href="../admin/inventario.php" class="card">
<h3>Inventario</h3>
<p>Consultar productos y agregar nuevos</p>
</a>

<a href="../admin/facturas.php" class="card">
<h3>Facturas</h3>
<p>Registrar ventas</p>
</a>

<a href="../admin/garantias/iniciogarantias.php" class="card">
<h3>Garantías</h3>
<p>Registrar garantías de productos</p>
</a>

</div>

<div class="logout">

<a href="../Auth/logout.php">Cerrar sesión</a>

</div>

</div>
</div>
</body>
</html>