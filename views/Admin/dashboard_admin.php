<?php
session_start();

// 🔐 PROTEGER SOLO ADMIN
if (!isset($_SESSION['rol']) || $_SESSION['rol'] != 'Administrador') {
    header("Location: ../Auth/login.php");
    exit();
}
?>

<!DOCTYPE html>
<html lang="es">
<head>
<meta charset="UTF-8">
<meta name="viewport" content="width=device-width, initial-scale=1.0">
<title>Dashboard Admin - InvoicePro</title>

<link href="https://fonts.googleapis.com/css2?family=Poppins:wght@300;400;600&display=swap" rel="stylesheet">

<!-- 🔥 CSS EXTERNO -->
<link rel="stylesheet" href="../../public/css/dashboard_admin.css">

</head>

<body>

<!-- SIDEBAR -->
<div class="sidebar">
    <div class="logo">INVOICEPRO</div>

    <div class="menu">
        <a href="#">🏠 Inicio</a>
        <a href="../Productos/listar.php">📦 Productos</a>
        <a href="../Facturas/crear.php">🧾 Crear Factura</a>
        <a href="../Facturas/listar.php">📊 Historial Facturas</a>
    </div>

    <div class="logout">
        <a href="../Auth/logout.php">Cerrar sesión</a>
    </div>
</div>

<!-- MAIN -->
<div class="main">

    <div class="header">
        <div class="title">Dashboard Administrador</div>
    </div>

    <div class="cards">

        <div class="card">
            <h3>Gestionar Productos</h3>
            <p>Crear, editar y eliminar productos del sistema.</p>
            <a class="btn" href="../Productos/listar.php">Ir a Productos</a>
        </div>

        <div class="card">
            <h3>Crear Factura</h3>
            <p>Generar nuevas facturas para clientes.</p>
            <a class="btn" href="../Facturas/crear.php">Nueva Factura</a>
        </div>

        <div class="card">
            <h3>Reportes</h3>
            <p>Ver estadísticas y ventas del negocio.</p>
            <a class="btn" href="../Reportes/index.php">Ver Reportes</a>
        </div>

    </div>

</div>

</body>
</html>
