<?php
session_start();

// 🔐 SOLO ADMIN
if (!isset($_SESSION["rol"]) || $_SESSION["rol"] != "admin") {
    header("Location: ../Auth/login.php");
    exit();
}

?>

<!DOCTYPE html>
<html lang="es">
<head>
<meta charset="UTF-8">
<title>Panel Administrador - InvoicePro</title>
<link rel="stylesheet" href="../../public/css/admin.css">
</head>

<body>

<div class="sidebar">

    <h2>InvoicePro</h2>

    <ul>
        <li>📦 Inventario</li>
        <li>🧾 Facturación</li>
        <li>💰 Ingresos</li>
        <li>👥 Usuarios</li>
    </ul>

    <a href="../Auth/logout.php" class="logout">Cerrar sesión</a>

</div>

<div class="main">

    <h1>Panel de Administrador</h1>
    <p>Bienvenido, <?php echo $_SESSION["usuario"]; ?> 👑</p>

    <div class="cards">

        <div class="card">
            <h3>Inventario</h3>
            <p>Gestiona productos y stock</p>
            <button onclick="location.href='inventario.php'">Administrar</button>
        </div>

        <div class="card">
            <h3>Facturación</h3>
            <p>Crear y ver facturas</p>
            <button onclick="location.href='facturas.php'">Ir a facturación</button>
        </div>

        <div class="card">
            <h3>Ingresos</h3>
            <p>Visualiza ganancias</p>
            <button>Ver reportes</button>
        </div>

        <div class="card">
            <h3>Usuarios</h3>
            <p>Gestiona roles y cuentas</p>
            <button>Administrar usuarios</button>
        </div>

         <div class="card">
            <h3>Garantias</h3>
            <p>Gestiona de garantias</p>
            <button onclick="location.href='garantias/iniciogarantias.php'">Administrar garantias</button>
        </div>


    </div>

</div>

</body>
</html>