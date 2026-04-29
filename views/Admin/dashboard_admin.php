<?php
session_start();

// 🔐 SOLO ADMIN
if (!isset($_SESSION["rol"]) || $_SESSION["rol"] != "admin") {
    header("Location: /MVC-PRU/views/Auth/login.php");
    exit();
}

?>

<!DOCTYPE html>
<html lang="es">
<head>
<meta charset="UTF-8">
<title>Panel Administrador - InvoicePro</title>
<link rel="stylesheet" href="/MVC-PRU/public/css/admin.css">

<link rel="preconnect" href="https://fonts.googleapis.com">
<link rel="preconnect" href="https://fonts.gstatic.com" crossorigin>
<link href="https://fonts.googleapis.com/css2?family=Poppins:wght@300;400;500;600;700&display=swap" rel="stylesheet">

</head>

<body>
    <style>
        
/* BOTONES */

a{
    display:block;
    background: #001357c9;
    color:white;
    padding:14px;
    margin:12px 0;
    border-radius:12px;
    text-decoration:none;
    font-family:Arial;
    font-size:18px;
    font-weight:bold;
    transition:0.3s;
    box-shadow:0 8px 20px rgba(0, 35, 110, 0.3);
}

/* HOVER */

a:hover{
    background:linear-gradient(135deg,#3b82f6,#2563eb);
    transform:translateY(-3px);
    box-shadow:0 12px 25px rgba(0, 54, 169, 0.45);
}


    </style>
<div class="layout">

    <aside class="sidebar">

        <div>

            <div class="logo">
                <h2>InvoicePro</h2>
            </div>

            <div class="menu">

                <a href="facturas/facturas.php" class="menu-item">
                    <span>📄</span>
                    Facturas
                </a>

                <a href="productos/inventario.php" class="menu-item">
                    <span>📦</span>
                    Inventario
                </a>

                <a href="garantias/iniciogarantias.php" class="menu-item">
                    <span>🛠️</span>
                    Garantías
                </a>

                <a href="clientes/clientes.php" class="menu-item">
                    <span>👤</span>
                    Clientes
                </a>

                <a href="compra/compras.php" class="menu-item">
                    <span>🛒</span>
                    Compras
                </a>

                <a href="proveedores/proveedores.php" class="menu-item">
                    <span>🏭</span>
                    Proveedores
                </a>
                <a href="ingresos/ingresos.php">
                    <span>📊</span>
                    Ingresos
                </a>
                <a href="usuario/usuarios.php">
                    <span>👥</span>
                    Usuarios
                </a>

               </div class="logout-btn" >

        <a href="Auth/logout.php">
            Cerrar sesión
        </a>
            </div>
        </div>

    </aside>

</div>

</body>
</html>




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