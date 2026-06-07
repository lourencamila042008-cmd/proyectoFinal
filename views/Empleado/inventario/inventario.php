<?php
session_start();

// 🔒 PERMITIR admin y empleado
if (!isset($_SESSION["rol"]) ||
   ($_SESSION["rol"] != "admin" && $_SESSION["rol"] != "empleado")) {

    header("Location: ../Auth/login.php");
    exit();
}

$esAdmin = $_SESSION["rol"] == "admin";

require_once __DIR__ . "/../../../config/db.php";

$conn = Database::Conectar();

$sql = "
    SELECT *
    FROM productos
    ORDER BY id_productos DESC
";

$result = $conn->query($sql);

// 📋 DATOS PARA EL MENÚ
$nombre_usuario = isset($_SESSION["nombre"]) ? $_SESSION["nombre"] : "Usuario";
$rol_usuario    = $_SESSION["rol"];
$base_url = "/MVC-PRU/views/" . ($esAdmin ? "Admin/" : "Empleado/");
?>

<!DOCTYPE html>
<html lang="es">

<head>

<meta charset="UTF-8">
<meta name="viewport" content="width=device-width, initial-scale=1.0">

<title>Inventario | InvoicePro</title>

<!-- 🎨 RECURSOS DEL MENÚ -->
<script src="https://cdn.tailwindcss.com"></script>
<link rel="preconnect" href="https://fonts.googleapis.com">
<link href="https://fonts.googleapis.com/css2?family=Inter:wght@300;400;500;600;700&display=swap" rel="stylesheet">
<link href="https://cdn.jsdelivr.net/npm/font-awesome@4.7.0/css/font-awesome.min.css" rel="stylesheet">

<script>
    tailwind.config = {
        theme: {
            extend: {
                colors: {
                    sidebar: '#1E293B',
                    sidebarHover: '#334155',
                    primary: '#FCD100',
                    azul: '#16396b', /* Tu color principal */
                    success: '#22C55E',
                    danger: '#EF4444',
                    warning: '#F59E0B',
                    purple: '#8B5CF6',
                    gris: '#F3F4F6',
                    border: '#E2E8F0',
                },
                fontFamily: { inter: ['Inter', 'sans-serif'] }
            }
        }
    }
</script>

<style type="text/tailwindcss">
    @layer utilities {
        .content-auto { content-visibility: auto; }
        .scrollbar-hide { -ms-overflow-style: none; scrollbar-width: none; }
        .scrollbar-hide::-webkit-scrollbar { display: none; }
        .menu-item { @apply flex items-center gap-3 px-3 py-2.5 rounded-lg transition-all text-gray-300 hover:bg-sidebarHover hover:text-white; }
        .menu-activo { @apply bg-sidebarHover text-white font-medium; }
    }
</style>

<!-- ✅ TUS ESTILOS ORIGINALES (SIN CAMBIOS) -->
<style>

*{
    margin:0;
    padding:0;
    box-sizing:border-box;
    font-family:'Segoe UI', sans-serif;
}

body{
    background:#f3f4f6 !important;
    color:#0f172a !important;
}

.contenedor-principal {
    padding:30px !important;
}

/* HEADER */

.header{
    display:flex !important;
    justify-content:space-between !important;
    align-items:center !important;
    gap:20px !important;
    margin-bottom:30px !important;
    flex-wrap:wrap !important;
}

.title h1{
    font-size:45px !important;
    font-weight:800 !important;
    margin-bottom:5px !important;
}

.title p{
    color:#64748b !important;
    font-size:18px !important;
}

/* ACTIONS */

.actions{
    display:flex !important;
    align-items:center !important;
    gap:15px !important;
    flex-wrap:wrap !important;
}

/* BUSCADOR */

.search{
    width:320px !important;
}

.search input{
    width:100% !important;
    border:none !important;
    outline:none !important;
    padding:16px 20px !important;
    border-radius:16px !important;
    background:white !important;
    font-size:15px !important;
    box-shadow:0 2px 10px rgba(0,0,0,0.05) !important;
}

/* BOTONES */

.btn{
    background:#16396b !important;
    color:white !important;
    text-decoration:none !important;
    padding:14px 22px !important;
    border-radius:14px !important;
    font-weight:600 !important;
    transition:.3s !important;
    display:inline-block !important;
    border:none !important;
    cursor:pointer !important;
}

.btn:hover{
    transform:translateY(-2px) !important;
    color:white !important;
}

.btn-back{
    background:#64748b !important;
}

.btn-back:hover{
    background:#475569 !important;
}

/* TABLA */

.table-box{
    background:white !important;
    border-radius:28px !important;
    padding:25px !important;
    box-shadow:0 2px 10px rgba(0,0,0,0.05) !important;
    overflow-x:auto !important;
}

.table-title{
    font-size:24px !important;
    margin-bottom:20px !important;
}

table{
    width:100% !important;
    border-collapse:collapse !important;
}

th{
    text-align:left !important;
    padding:18px 15px !important;
    font-size:14px !important;
    color:#64748b !important;
    border-bottom:2px solid #f1f5f9 !important;
}

td{
    padding:18px 15px !important;
    border-bottom:1px solid #f1f5f9 !important;
    font-size:15px !important;
}

tr:hover{
    background:#f8fafc !important;
}

/* STOCK */

.agotado{
    background:#fee2e2 !important;
}

.agotado td{
    color:#b91c1c !important;
    font-weight:600 !important;
}

.bajo{
    background:#fef3c7 !important;
}

.bajo td{
    color:#92400e !important;
    font-weight:600 !important;
}

/* BADGES */

.badge{
    padding:6px 12px !important;
    border-radius:10px !important;
    font-size:12px !important;
    font-weight:700 !important;
    display:inline-block !important;
    margin-left:10px !important;
}

.badge-agotado{
    background:#dc2626 !important;
    color:white !important;
}

.badge-bajo{
    background:#f59e0b !important;
    color:white !important;
}

/* RESPONSIVE */

@media(max-width:768px){

    .contenedor-principal {
        padding:20px !important;
    }

    .header{
        flex-direction:column !important;
        align-items:flex-start !important;
    }

    .title h1{
        font-size:35px !important;
    }

    .search{
        width:100% !important;
    }

    .table-box{
        padding:15px !important;
    }
}

</style>

</head>

<body class="min-h-screen flex overflow-hidden">

    <!-- 🟦 MENÚ LATERAL (IGUAL AL DISEÑO DE LA IMAGEN) -->
    <aside class="w-[240px] bg-sidebar text-white h-screen sticky top-0 flex flex-col shadow-lg">
        
        <!-- Logo -->
        <div class="px-4 py-5 border-b border-white/10">
            <h1 class="text-[22px] font-bold text-primary flex items-center gap-2">
                <i class="fa fa-file-text-o"></i> InvoicePro
            </h1>
        </div>

        <!-- Usuario -->
        <div class="px-4 py-4 border-b border-white/10 flex items-center gap-3">
            <div class="w-9 h-9 rounded-full bg-white/20 flex items-center justify-center">
                <i class="fa fa-user-circle-o text-lg"></i>
            </div>
            <div>
                <p class="text-sm font-medium"><?= htmlspecialchars($nombre_usuario) ?></p>
                <p class="text-xs text-gray-400"><?= ucfirst($rol_usuario) ?></p>
            </div>
        </div>

        <!-- MENÚ DE NAVEGACIÓN -->
        <nav class="flex-1 overflow-y-auto scrollbar-hide py-4 px-2">
            <p class="text-[10px] uppercase text-gray-500 font-semibold px-3 py-1 tracking-wider">Gestiona tu negocio</p>
            
            <ul class="space-y-1 mt-2">
                <!-- Dashboard -->
                <li>
                    <a href="<?= $base_url . ($esAdmin ? 'dashboard_admin.php' : 'dashboard_empleado.php') ?>" 
                       class="menu-item">
                        <i class="fa fa-tachometer w-5 text-center"></i>
                        <span>Dashboard</span>
                    </a>
                </li>
                <!-- Facturas -->
                <li>
                    <a href="<?= $base_url ?>facturas/facturas.php" class="menu-item">
                        <i class="fa fa-file-text-o w-5 text-center"></i>
                        <span>Facturas</span>
                    </a>
                </li>
                <!-- Inventario -->
                <li>
                    <a href="<?= $base_url ?>inventario/inventario.php" class="menu-item menu-activo">
                        <i class="fa fa-cube w-5 text-center"></i>
                        <span>Inventario</span>
                    </a>
                </li>
                <!-- Garantías -->
                <li>
                    <a href="<?= $base_url ?>garantias/iniciogarantias.php" class="menu-item">
                        <i class="fa fa-wrench w-5 text-center"></i>
                        <span>Garantías</span>
                    </a>
                </li>
                <!-- Clientes -->
                <li>
                    <a href="<?= $base_url ?>clientes/clientesinicio.php" class="menu-item">
                        <i class="fa fa-users w-5 text-center"></i>
                        <span>Clientes</span>
                    </a>
                </li>
                
                <?php if($esAdmin): ?>
                <!-- Compras (solo admin) -->
                <li>
                    <a href="<?= $base_url ?>compra/compras.php" class="menu-item">
                        <i class="fa fa-shopping-cart w-5 text-center"></i>
                        <span>Compras</span>
                    </a>
                </li>
                <!-- Proveedores (solo admin) -->
                <li>
                    <a href="<?= $base_url ?>proveedores/proveedores.php" class="menu-item">
                        <i class="fa fa-truck w-5 text-center"></i>
                        <span>Proveedores</span>
                    </a>
                </li>
                <!-- Ingresos (solo admin) -->
                <li>
                    <a href="<?= $base_url ?>ingresos/ingresos.php" class="menu-item">
                        <i class="fa fa-line-chart w-5 text-center"></i>
                        <span>Ingresos</span>
                    </a>
                </li>
                <!-- Usuarios (solo admin) -->
                <li>
                    <a href="<?= $base_url ?>usuario/usuarios.php" class="menu-item">
                        <i class="fa fa-user-secret w-5 text-center"></i>
                        <span>Usuarios</span>
                    </a>
                </li>
                <?php endif; ?>
            </ul>
        </nav>

        <!-- Cerrar Sesión -->
        <div class="p-3 border-t border-white/10">
            <a href="../Auth/logout.php" class="flex items-center gap-3 px-3 py-2.5 rounded-lg text-red-300 hover:bg-red-900/30 hover:text-red-200 transition-all">
                <i class="fa fa-sign-out w-5 text-center"></i>
                <span>Cerrar sesión</span>
            </a>
        </div>
    </aside>

    <!-- 🟩 CONTENIDO PRINCIPAL (TU CÓDIGO AQUÍ DENTRO) -->
    <main class="flex-1 h-screen overflow-y-auto contenedor-principal">

        <!-- HEADER -->
        <div class="header">

            <div class="title">
                <h1>Inventario</h1>
                <p>Gestiona productos y control de stock.</p>
            </div>

            <div class="actions">

                <!-- VOLVER -->
                <a class="btn btn-back" href="<?= $base_url . ($esAdmin ? 'dashboard_admin.php' : 'dashboard_empleado.php') ?>">
                    ← Volver
                </a>

                <!-- BUSCADOR -->
                <div class="search">
                    <input type="text" id="buscar" placeholder="Buscar producto...">
                </div>

                <!-- SOLO ADMIN -->
                <?php if($esAdmin): ?>
                <a class="btn" href="agregar_producto.php">
                    + Agregar producto
                </a>
                <?php endif; ?>

            </div>

        </div>

        <!-- TABLA -->
        <div class="table-box">

            <h2 class="table-title">Lista de productos</h2>

            <table id="tablaProductos">

                <thead>
                    <tr>
                        <th>ID</th>
                        <th>Nombre</th>
                        <th>Stock</th>
                        <th>Precio Venta</th>
                        <th>Precio Compra</th>
                        <th>Mínimo Stock</th>
                    </tr>
                </thead>

                <tbody>
                <?php while($p = $result->fetch_assoc()):

                    $clase = "";
                    if ($p["stock"] <= 0) {
                        $clase = "agotado";
                    } elseif ($p["stock"] <= $p["min_stock"]) {
                        $clase = "bajo";
                    }

                ?>

                <tr class="<?= $clase ?>">

                    <td>#<?= $p["id_productos"] ?></td>

                    <td><?= htmlspecialchars($p["nombre"]) ?></td>

                    <td>
                        <?= $p["stock"] ?>
                        <?php if($p["stock"] <= 0): ?>
                            <span class="badge badge-agotado">AGOTADO</span>
                        <?php elseif($p["stock"] <= $p["min_stock"]): ?>
                            <span class="badge badge-bajo">STOCK BAJO</span>
                        <?php endif; ?>
                    </td>

                    <td>$<?= number_format($p["precio_venta"], 0, ',', '.') ?></td>
                    <td>$<?= number_format($p["precio_compra"], 0, ',', '.') ?></td>
                    <td><?= $p["min_stock"] ?></td>

                </tr>

                <?php endwhile; ?>
                </tbody>

            </table>

        </div>

    </main>

<script>

// 🔎 BUSCADOR (TU CÓDIGO ORIGINAL)
document.getElementById("buscar")
.addEventListener("keyup", function(){

    let filtro =
    this.value.toLowerCase();

    let filas =
    document.querySelectorAll(
        "#tablaProductos tbody tr"
    );

    filas.forEach(fila => {

        let texto =
        fila.textContent.toLowerCase();

        fila.style.display =
        texto.includes(filtro)
        ? ""
        : "none";

    });

});

</script>

</body>
</html>