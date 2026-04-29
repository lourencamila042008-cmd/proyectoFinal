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
    <h2>admin</h2>

    <a href="facturas/facturas.php">📄 Facturas</a>
    <a href="productos/inventario.php">📦 Inventario</a>
    <a href="garantias/iniciogarantias.php">🛠 Garantías</a>
    <a href="clientes/clientes.php">👤 Clientes</a>
</div>

<div class="main">

    <h1>Panel de Administrador</h1>
    <p>Bienvenido, <?php echo $_SESSION["usuario"]; ?> 👑</p>

    <div class="cards">

        <div class="card">
            <h3>Inventario</h3>
            <p>Gestiona productos y stock</p>
            <button onclick="location.href='productos/inventario.php'">Administrar</button>
        </div>

        <div class="card">
            <h3>Facturación</h3>
            <p>Crear y ver facturas</p>
            <button onclick="location.href='facturas/facturas.php'">Ir a facturación</button>
        </div>

        <div class="card">
            <h3>Ingresos</h3>
            <p>Visualiza ganancias</p>
            <button onclick="location.href='ingresos/ingresos.php'">Ver reportes</button>
        </div>

        <div class="card">
            <h3>Usuarios</h3>
            <p>Gestiona roles y cuentas</p>
            <button onclick="location.href='usuario/usuarios.php'">Administrar usuarios</button>
        </div>

         <div class="card">
            <h3>Garantias</h3>
            <p>Gestiona de garantias</p>
            <button onclick="location.href='garantias/iniciogarantias.php'">Administrar garantias</button>
        </div>

         <div class="card">
            <h3>clientes</h3>
            <p>Gestiona de clientes</p>
            <button onclick="location.href='clientes/clientes.php'">Administrar clientes</button>
        </div>

             <div class="card">
            <h3>proveedores</h3>
            <p>Gestiona de proveedores</p>
            <button onclick="location.href='proveedores/proveedores.php'">Administrar proveedores</button>
        </div>

          <div class="card">
            <h3>compras</h3>
            <p>Gestiona de compras</p>
            <button onclick="location.href='compra/compras.php'">Administrar compras</button>
        </div>




    </div>

</div>

</body>
</html>