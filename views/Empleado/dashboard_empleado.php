<?php
session_start();

if (!isset($_SESSION["rol"]) || $_SESSION["rol"] != "empleado") {
    header("Location: ../Auth/login.php");
    exit();
}
?>

<!DOCTYPE html>
<html lang="es">
<head>
<meta charset="UTF-8">
<title>Panel Empleado</title>
<link rel="stylesheet" href="../../public/css/empleado.css">
<link rel="stylesheet" href="../../../public/css/empleado-dark.css">
</head>

<body>

<!-- SIDEBAR -->
<div class="sidebar">
    <h2>Empleado</h2>

    <a href="facturas/facturas.php">📄 Facturas</a>
    <a href="productos/inventario.php">📦 Inventario</a>
    <a href="garantias/iniciogarantias.php">🛠 Garantías</a>
    <a href="clientes/clientes.php">👤 Clientes</a>
</div>

<!-- CONTENIDO -->
<div class="main">

    <div class="topbar">
        <h1>Dashboard</h1>
        <a href="../Auth/logout.php" class="logout">Cerrar sesión</a>
    </div>

    <div class="cards">

        <a href="facturas/facturas.php" class="card">
            <div class="icon">📄</div>
            Facturas
            <small>Gestiona ventas</small>
        </a>

        <a href="inventario/inventario.php" class="card">
            <div class="icon">📦</div>
            Inventario
            <small>Control de productos</small>
        </a>

        <a href="garantias/garantias.php" class="card">
            <div class="icon">🛠</div>
            Garantías
            <small>Seguimiento</small>
        </a>

        <a href="../clientes/clientes.php" class="card">
            <div class="icon">👤</div>
            Clientes
            <small>Base de datos</small>
        </a>

    </div>

</div>

</body>
</html>