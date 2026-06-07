<?php
session_start();

if (!isset($_SESSION["rol"]) || $_SESSION["rol"] != "empleado") {
    header("Location: ../Auth/login.php");
    exit();
}

// 📋 DATOS PARA EL MENÚ
$nombre_usuario = isset($_SESSION["nombre"]) ? $_SESSION["nombre"] : "Empleado";
$rol_usuario    = $_SESSION["rol"];
$base_url = "/MVC-PRU/views/Empleado/";
?>

<!DOCTYPE html>
<html lang="es">
<head>
<meta charset="UTF-8">
<meta name="viewport" content="width=device-width, initial-scale=1.0">
<title>Panel Empleado - InvoicePro</title>

<!-- 🎨 RECURSOS -->
<script src="https://cdn.tailwindcss.com"></script>
<link rel="preconnect" href="https://fonts.googleapis.com">
<link href="https://fonts.googleapis.com/css2?family=Inter:wght@300;400;500;600;700&display=swap" rel="stylesheet">
<link href="https://cdn.jsdelivr.net/npm/font-awesome@4.7.0/css/font-awesome.min.css" rel="stylesheet">

<script>
    tailwind.config = {
        theme: {
            extend: {
                colors: {
                    sidebar: '#1E293B',  // Fondo oscuro igual a la imagen
                    sidebarHover: '#334155',
                    primary: '#FCD100',  // Amarillo del logo
                    azul: '#165DFF',
                    success: '#22C55E',
                    danger: '#EF4444',
                    warning: '#F59E0B',
                    purple: '#8B5CF6',
                    gris: '#F8FAFC',
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
        .card-shadow { box-shadow: 0 1px 3px 0 rgba(0, 0, 0, 0.1), 0 1px 2px 0 rgba(0, 0, 0, 0.06); }
    }
</style>

<style>
    * { margin: 0; padding: 0; box-sizing: border-box; }
    body { font-family: 'Inter', sans-serif !important; background: #F8FAFC !important; }
</style>

</head>
<body class="min-h-screen flex overflow-hidden">

    <!-- 🟦 MENÚ LATERAL (IGUAL A LA IMAGEN) -->
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
                    <a href="dashboard_empleado.php" 
                       class="menu-item <?= basename($_SERVER['PHP_SELF']) == 'dashboard_empleado.php' ? 'menu-activo' : '' ?>">
                        <i class="fa fa-tachometer w-5 text-center"></i>
                        <span>Dashboard</span>
                    </a>
                </li>
                <!-- Facturas -->
                <li>
                    <a href="facturas/facturas.php" class="menu-item">
                        <i class="fa fa-file-text-o w-5 text-center"></i>
                        <span>Facturas</span>
                    </a>
                </li>
                <!-- Inventario -->
                <li>
                    <a href="inventario/inventario.php" class="menu-item">
                        <i class="fa fa-cube w-5 text-center"></i>
                        <span>Inventario</span>
                    </a>
                </li>
                <!-- Garantías -->
                <li>
                    <a href="garantias/iniciogarantias.php" class="menu-item">
                        <i class="fa fa-wrench w-5 text-center"></i>
                        <span>Garantías</span>
                    </a>
                </li>
                <!-- Clientes -->
                <li>
                    <a href="clientes/clientesinicio.php" class="menu-item">
                        <i class="fa fa-users w-5 text-center"></i>
                        <span>Clientes</span>
                    </a>
                </li>
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

    <!-- 🟩 CONTENIDO PRINCIPAL -->
    <main class="flex-1 h-screen overflow-y-auto p-6">

        <!-- Encabezado superior -->
        <header class="mb-8 flex items-center justify-between">
            <div>
                <h2 class="text-[28px] font-bold text-gray-800">Dashboard</h2>
                <p class="text-gray-500 text-sm mt-1">Panel de Empleado • Bienvenido, <span class="font-medium text-azul"><?= htmlspecialchars($nombre_usuario) ?></span></p>
            </div>
            <!-- 🔍 BARRA DE BÚSQUEDA -->
            <div class="relative">
                <input type="text" id="buscarModulos" placeholder="Buscar módulo..." 
                       class="pl-10 pr-4 py-2 w-[260px] bg-white rounded-lg border border-gray-200 focus:outline-none focus:ring-2 focus:ring-azul/20 focus:border-azul transition-all">
                <i class="fa fa-search absolute left-3.5 top-1/2 -translate-y-1/2 text-gray-400"></i>
            </div>
        </header>

        <!-- 📅 FECHA -->
        <div class="mb-8">
            <span class="bg-white px-4 py-2 rounded-lg border border-gray-200 text-sm text-gray-600 shadow-sm">
                <i class="fa fa-calendar-o mr-2"></i>
                <?php echo date('F Y'); ?>
            </span>
        </div>

        <!-- 📊 TARJETAS DE BALANCE (IGUAL QUE EN LA IMAGEN) -->
        <div class="grid grid-cols-3 gap-6 mb-10">
            <div class="bg-white p-6 rounded-xl card-shadow flex items-center justify-between">
                <div>
                    <p class="text-sm text-gray-500 font-medium">Balance</p>
                    <h3 class="text-[24px] font-bold text-gray-800 mt-1">$ 150.000</h3>
                </div>
                <div class="w-12 h-12 rounded-full bg-green-100 text-success flex items-center justify-center text-xl">
                    <i class="fa fa-line-chart"></i>
                </div>
            </div>

            <div class="bg-white p-6 rounded-xl card-shadow flex items-center justify-between">
                <div>
                    <p class="text-sm text-gray-500 font-medium">Ventas totales</p>
                    <h3 class="text-[24px] font-bold text-gray-800 mt-1">$ 150.000</h3>
                </div>
                <div class="w-12 h-12 rounded-full bg-green-100 text-success flex items-center justify-center text-xl">
                    <i class="fa fa-money"></i>
                </div>
            </div>

            <div class="bg-white p-6 rounded-xl card-shadow flex items-center justify-between">
                <div>
                    <p class="text-sm text-gray-500 font-medium">Gastos totales</p>
                    <h3 class="text-[24px] font-bold text-gray-800 mt-1">$ 0</h3>
                </div>
                <div class="w-12 h-12 rounded-full bg-red-100 text-danger flex items-center justify-center text-xl">
                    <i class="fa fa-credit-card"></i>
                </div>
            </div>
        </div>

        <!-- ⚡ ACCESOS RÁPIDOS -->
        <div class="mb-6">
            <h3 class="text-lg font-semibold text-gray-700 mb-4">Accesos rápidos</h3>
            
            <div class="grid grid-cols-4 gap-6">

                <!-- INVENTARIO -->
                <div class="bg-white p-6 rounded-xl card-shadow flex flex-col justify-between">
                    <div>
                        <div class="w-10 h-10 rounded-lg bg-blue-100 text-azul flex items-center justify-center text-lg mb-3">
                            <i class="fa fa-cube"></i>
                        </div>
                        <h4 class="font-bold text-gray-800 text-lg mb-1">Inventario</h4>
                        <p class="text-sm text-gray-500 mb-4">Gestiona productos y controla el stock fácilmente.</p>
                    </div>
                    <a href="inventario/inventario.php" class="block w-full text-center py-2 rounded-lg bg-blue-50 text-azul font-medium text-sm hover:bg-blue-100 transition">
                        Administrar
                    </a>
                </div>

                <!-- FACTURACIÓN -->
                <div class="bg-white p-6 rounded-xl card-shadow flex flex-col justify-between">
                    <div>
                        <div class="w-10 h-10 rounded-lg bg-green-100 text-success flex items-center justify-center text-lg mb-3">
                            <i class="fa fa-file-text-o"></i>
                        </div>
                        <h4 class="font-bold text-gray-800 text-lg mb-1">Facturación</h4>
                        <p class="text-sm text-gray-500 mb-4">Crea facturas y administra ventas del sistema.</p>
                    </div>
                    <a href="facturas/facturas.php" class="block w-full text-center py-2 rounded-lg bg-green-50 text-success font-medium text-sm hover:bg-green-100 transition">
                        Ir a facturación
                    </a>
                </div>

                <!-- GARANTÍAS -->
                <div class="bg-white p-6 rounded-xl card-shadow flex flex-col justify-between">
                    <div>
                        <div class="w-10 h-10 rounded-lg bg-yellow-100 text-warning flex items-center justify-center text-lg mb-3">
                            <i class="fa fa-wrench"></i>
                        </div>
                        <h4 class="font-bold text-gray-800 text-lg mb-1">Garantías</h4>
                        <p class="text-sm text-gray-500 mb-4">Seguimiento y estado de garantías de productos.</p>
                    </div>
                    <a href="garantias/garantias.php" class="block w-full text-center py-2 rounded-lg bg-yellow-50 text-warning font-medium text-sm hover:bg-yellow-100 transition">
                        Ver gestión
                    </a>
                </div>

                <!-- CLIENTES -->
                <div class="bg-white p-6 rounded-xl card-shadow flex flex-col justify-between">
                    <div>
                        <div class="w-10 h-10 rounded-lg bg-purple-100 text-purple flex items-center justify-center text-lg mb-3">
                            <i class="fa fa-users"></i>
                        </div>
                        <h4 class="font-bold text-gray-800 text-lg mb-1">Clientes</h4>
                        <p class="text-sm text-gray-500 mb-4">Administra y consulta clientes registrados.</p>
                    </div>
                    <a href="clientes/clientesinicio.php" class="block w-full text-center py-2 rounded-lg bg-purple-50 text-purple font-medium text-sm hover:bg-purple-100 transition">
                        Administrar
                    </a>
                </div>

            </div>
        </div>

    </main>

<!-- ✅ SCRIPTS -->
<script>
    // 🔎 BUSCADOR
    const inputBusqueda = document.getElementById('buscarModulos');
    if(inputBusqueda){
    inputBusqueda.addEventListener('input', function() {
        const texto = this.value.toLowerCase().trim();
        const tarjetas = document.querySelectorAll('.grid > div');
        tarjetas.forEach(tarjeta => {
            const contenido = tarjeta.textContent.toLowerCase();
            tarjeta.style.display = (contenido.includes(texto)) ? '' : 'none';
        });
    });
    }
</script>

<!-- 🤖 TU CHATBOT SIGUE AQUÍ, ABAJO DE TODO -->
<div class="boton-chat" onclick="abrirChat()" style="position:fixed; bottom:25px; right:25px; width:70px; height:70px; border-radius:50%; background:linear-gradient(135deg,#60a5fa,#2563eb); display:flex; justify-content:center; align-items:center; font-size:34px; cursor:pointer; color:white; box-shadow:0 10px 25px rgba(37,99,235,0.3); z-index:1000;">🤖</div>

<div class="chat-container" id="chatContainer" style="position:fixed; bottom:110px; right:25px; width:360px; height:520px; background:white; border-radius:22px; overflow:hidden; display:none; flex-direction:column; box-shadow:0 20px 45px rgba(0,0,0,0.12); border:1px solid #dbeafe; z-index:1000;">
    <div class="chat-header" style="background:linear-gradient(135deg,#60a5fa,#2563eb); color:white; padding:18px; display:flex; justify-content:space-between; align-items:center; font-weight:700; font-size:18px;">
        🤖 Chatbot IA
        <span onclick="cerrarChat()" style="cursor:pointer;">✖</span>
    </div>
    <div id="chat" style="flex:1; overflow-y:auto; padding:18px; background:#f8fbff;"></div>
    <div class="input-area" style="display:flex; gap:10px; padding:15px; border-top:1px solid #e2e8f0; background:white;">
        <input type="text" id="mensaje" placeholder="Escribe algo..." style="flex:1; border:none; background:#f1f5f9; padding:13px; border-radius:12px; outline:none; font-size:14px;">
        <button onclick="enviar()" style="border:none; background:#2563eb; color:white; padding:12px 18px; border-radius:12px; cursor:pointer; font-weight:600;">Enviar</button>
    </div>
</div>

<script src="../../public/js/chatbot.js"></script>

</body>
</html>