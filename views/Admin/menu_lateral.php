<?php if (!isset($_SESSION)) session_start();
if (!isset($_SESSION["rol"]) || $_SESSION["rol"] != "admin") {
    header("Location: /MVC-PRU/views/Auth/login.php");
    exit();
}
$nombre_usuario = isset($_SESSION["nombre"]) ? $_SESSION["nombre"] : "Administrador";
$rol_usuario    = $_SESSION["rol"];
$base_url = "/MVC-PRU/views/Admin/";
require_once "../../../config/db.php";
$conn = Database::conectar();
?>
<!DOCTYPE html>
<html lang="es">
<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <title>InvoicePro - <?php echo isset($titulo_pagina) ? $titulo_pagina : 'Sistema'; ?></title>
    <script src="https://cdn.tailwindcss.com"></script>
    <link href="https://fonts.googleapis.com/css2?family=Inter:wght@300;400;500;600;700&display=swap" rel="stylesheet">
    <link href="https://cdn.jsdelivr.net/npm/font-awesome@4.7.0/css/font-awesome.min.css" rel="stylesheet">
    <script>
        tailwind.config = {
            theme: {
                extend: {
                    colors: {
                        sidebar: '#232c3b', primary: '#fcd100', secondary: '#1e293b', azul: '#165DFF', gris: '#f8fafc', activo: '#334155',
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
            .sombra-suave { box-shadow: 0 2px 12px rgba(0,0,0,0.05); }
            .menu-activo { @apply bg-activo text-white font-medium; }
        }
    </style>
</head>
<body class="bg-gris font-inter text-secondary min-h-screen flex overflow-hidden">
    <!-- 🟦 MENÚ LATERAL -->
    <aside class="w-[260px] bg-sidebar text-white h-screen sticky top-0 flex flex-col shadow-lg">
        <div class="px-6 py-5 border-b border-white/10 flex items-center justify-between">
            <h1 class="text-[22px] font-bold text-primary flex items-center gap-2"><i class="fa fa-file-text-o"></i> InvoicePro</h1>
        </div>
        <div class="px-6 py-4 border-b border-white/10 flex items-center gap-3">
            <div class="w-9 h-9 rounded-full bg-white/20 flex items-center justify-center"><i class="fa fa-user-circle-o text-lg"></i></div>
            <div><p class="text-sm font-medium"><?= htmlspecialchars($nombre_usuario) ?> 👑</p><p class="text-xs text-gray-400"><?= ucfirst($rol_usuario) ?></p></div>
        </div>
        <nav class="flex-1 overflow-y-auto scrollbar-hide py-4 px-3">
            <p class="text-[10px] uppercase text-gray-500 font-semibold px-3 py-2 tracking-wider">Gestiona tu negocio</p>
            <ul class="space-y-1">
                <li><a href="<?= $base_url ?>dashboard_admin.php" class="flex items-center gap-3 px-3 py-2.5 rounded-lg <?= basename($_SERVER['PHP_SELF']) == 'dashboard_admin.php' ? 'menu-activo' : 'text-gray-300 hover:bg-activo hover:text-white' ?> transition-all"><i class="fa fa-tachometer w-5 text-center"></i><span>Dashboard</span></a></li>
                <li><a href="<?= $base_url ?>facturas/facturas.php" class="flex items-center gap-3 px-3 py-2.5 rounded-lg <?= in_array(basename($_SERVER['PHP_SELF']), ['facturas.php','crear_factura.php','editar_factura.php']) ? 'menu-activo' : 'text-gray-300 hover:bg-activo hover:text-white' ?> transition-all"><i class="fa fa-file-text-o w-5 text-center"></i><span>Facturas</span></a></li>
                <li><a href="<?= $base_url ?>productos/inventario.php" class="flex items-center gap-3 px-3 py-2.5 rounded-lg <?= in_array(basename($_SERVER['PHP_SELF']), ['inventario.php','agregar_producto.php','editar_producto.php']) ? 'menu-activo' : 'text-gray-300 hover:bg-activo hover:text-white' ?> transition-all"><i class="fa fa-cube w-5 text-center"></i><span>Inventario</span></a></li>
                <li><a href="<?= $base_url ?>garantias/ver_garantia.php" class="flex items-center gap-3 px-3 py-2.5 rounded-lg <?= in_array(basename($_SERVER['PHP_SELF']), ['ver_garantia.php','crear_garantia.php','editar_garantia.php']) ? 'menu-activo' : 'text-gray-300 hover:bg-activo hover:text-white' ?> transition-all"><i class="fa fa-wrench w-5 text-center"></i><span>Garantías</span></a></li>
                <li><a href="<?= $base_url ?>clientes/clientes.php" class="flex items-center gap-3 px-3 py-2.5 rounded-lg <?= in_array(basename($_SERVER['PHP_SELF']), ['clientes.php','crear_cliente.php','editar_cliente.php']) ? 'menu-activo' : 'text-gray-300 hover:bg-activo hover:text-white' ?> transition-all"><i class="fa fa-users w-5 text-center"></i><span>Clientes</span></a></li>
                <li><a href="<?= $base_url ?>compra/compras.php" class="flex items-center gap-3 px-3 py-2.5 rounded-lg <?= in_array(basename($_SERVER['PHP_SELF']), ['compras.php','crear_compra.php','editar_compra.php']) ? 'menu-activo' : 'text-gray-300 hover:bg-activo hover:text-white' ?> transition-all"><i class="fa fa-shopping-cart w-5 text-center"></i><span>Compras</span></a></li>
                <li><a href="<?= $base_url ?>proveedores/proveedores.php" class="flex items-center gap-3 px-3 py-2.5 rounded-lg <?= in_array(basename($_SERVER['PHP_SELF']), ['proveedores.php','crear_proveedor.php','editar_proveedor.php']) ? 'menu-activo' : 'text-gray-300 hover:bg-activo hover:text-white' ?> transition-all"><i class="fa fa-truck w-5 text-center"></i><span>Proveedores</span></a></li>
                <li><a href="<?= $base_url ?>ingresos/ingresos.php" class="flex items-center gap-3 px-3 py-2.5 rounded-lg <?= basename($_SERVER['PHP_SELF']) == 'ingresos.php' ? 'menu-activo' : 'text-gray-300 hover:bg-activo hover:text-white' ?> transition-all"><i class="fa fa-line-chart w-5 text-center"></i><span>Ingresos</span></a></li>
                <li><a href="<?= $base_url ?>usuario/usuarios.php" class="flex items-center gap-3 px-3 py-2.5 rounded-lg <?= in_array(basename($_SERVER['PHP_SELF']), ['usuarios.php','crear.php','editar.php']) ? 'menu-activo' : 'text-gray-300 hover:bg-activo hover:text-white' ?> transition-all"><i class="fa fa-user-secret w-5 text-center"></i><span>Usuarios</span></a></li>
            </ul>
        </nav>
        <div class="p-3 border-t border-white/10">
            <a href="/MVC-PRU/views/Auth/logout.php" class="flex items-center gap-3 px-3 py-2.5 rounded-lg text-red-300 hover:bg-red-900/30 hover:text-red-200 transition-all">
                <i class="fa fa-sign-out w-5 text-center"></i><span>Cerrar sesión</span>
            </a>
        </div>
    </aside>
    <!-- 🟩 CONTENIDO PRINCIPAL -->
    <main class="flex-1 h-screen overflow-y-auto">
        <header class="bg-white px-8 py-4 border-b border-gray-200 flex items-center justify-between">
            <div>
                <h2 class="text-[26px] font-bold text-secondary"><?php echo isset($titulo_pagina) ? $titulo_pagina : 'Módulo'; ?></h2>
                <p class="text-gray-500 text-sm">Panel de Administrador • Bienvenido, <span class="font-medium text-azul"><?= htmlspecialchars($nombre_usuario) ?></span> 👑</p>
            </div>
            <div class="relative">
                <input type="text" id="buscarModulos" placeholder="Buscar módulo..." class="pl-10 pr-4 py-2 w-[260px] bg-gris rounded-lg border border-gray-200 focus:outline-none focus:ring-2 focus:ring-azul/20 focus:border-azul transition-all">
                <i class="fa fa-search absolute left-3.5 top-1/2 -translate-y-1/2 text-gray-400"></i>
            </div>
        </header>
        <div class="px-8 py-6"></div>