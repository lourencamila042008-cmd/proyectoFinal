<?php
session_start();

// 🔐 SOLO ADMIN PUEDE ENTRAR
if (!isset($_SESSION["rol"]) || $_SESSION["rol"] != "admin") {
    header("Location: /MVC-PRU/views/Auth/login.php");
    exit();
}

// 📋 DATOS DEL USUARIO ACTUAL
$nombre_usuario = isset($_SESSION["nombre"]) ? $_SESSION["nombre"] : "Administrador";
$rol_usuario    = $_SESSION["rol"];

// 📂 RUTA BASE DE TU PROYECTO
$base_url = "/MVC-PRU/views/Admin/";

// 📡 CONEXIÓN A BASE DE DATOS
require_once "../../config/db.php";
$conn = Database::conectar();

// 📅 OBTENER MES Y AÑO ACTUAL
$mes_actual = date('m');
$año_actual = date('Y');

// 💰 CONSULTA: VENTAS TOTALES DEL MES (solo facturas PAGADAS)
$sql_ventas = "SELECT SUM(df.subtotal) AS total_ventas 
               FROM facturas f
               JOIN detallefactura df ON f.id_facturas = df.id_facturas
               WHERE f.estado = 'pagada' AND MONTH(f.fecha) = ? AND YEAR(f.fecha) = ?";
$stmt = $conn->prepare($sql_ventas);
$stmt->bind_param("ii", $mes_actual, $año_actual);
$stmt->execute();
$res_ventas = $stmt->get_result()->fetch_assoc();
$ventas_totales = $res_ventas['total_ventas'] ?? 0;
$stmt->close();

// 💸 CONSULTA: GASTOS TOTALES DEL MES (PREPARADO)
$gastos_totales = 0; 

// ⚖️ BALANCE = VENTAS - GASTOS
$balance = $ventas_totales - $gastos_totales;

?>
<!DOCTYPE html>
<html lang="es">
<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <title>InvoicePro - Panel de Administración</title>
    <script src="https://cdn.tailwindcss.com"></script>
    <link href="https://fonts.googleapis.com/css2?family=Inter:wght@300;400;500;600;700&display=swap" rel="stylesheet">
    <link href="https://cdn.jsdelivr.net/npm/font-awesome@4.7.0/css/font-awesome.min.css" rel="stylesheet">
    
    <script>
        tailwind.config = {
            theme: {
                extend: {
                    colors: {
                        sidebar: '#232c3b',       // Color fondo menú
                        primary: '#fcd100',       // Color amarillo marca
                        secondary: '#1e293b',     // Color textos principales
                        azul: '#165DFF',          // Color botones/acciones
                        gris: '#f8fafc',          // Fondo general
                        activo: '#334155',        // Fondo menú activo
                    },
                    fontFamily: {
                        inter: ['Inter', 'sans-serif'],
                    },
                }
            }
        }
    </script>

    <style type="text/tailwindcss">
        @layer utilities {
            .content-auto {
                content-visibility: auto;
            }
            .scrollbar-hide {
                -ms-overflow-style: none;
                scrollbar-width: none;
            }
            .scrollbar-hide::-webkit-scrollbar {
                display: none;
            }
            .sombra-suave {
                box-shadow: 0 2px 12px rgba(0,0,0,0.05);
            }
            .menu-activo {
                @apply bg-activo text-white font-medium;
            }
        }
    </style>
</head>
<body class="bg-gris font-inter text-secondary min-h-screen flex overflow-hidden">

    <!-- 🟦 MENÚ LATERAL IZQUIERDO -->
    <aside class="w-[260px] bg-sidebar text-white h-screen sticky top-0 flex flex-col shadow-lg">
        
        <!-- Logo -->
        <div class="px-6 py-5 border-b border-white/10 flex items-center justify-between">
            <h1 class="text-[22px] font-bold text-primary flex items-center gap-2">
                <i class="fa fa-file-text-o"></i> InvoicePro
            </h1>
        </div>

        <!-- Usuario -->
        <div class="px-6 py-4 border-b border-white/10 flex items-center gap-3">
            <div class="w-9 h-9 rounded-full bg-white/20 flex items-center justify-center">
                <i class="fa fa-user-circle-o text-lg"></i>
            </div>
            <div>
                <p class="text-sm font-medium"><?= htmlspecialchars($nombre_usuario) ?> 👑</p>
                <p class="text-xs text-gray-400"><?= ucfirst($rol_usuario) ?></p>
            </div>
        </div>

        <!-- MENÚ DE NAVEGACIÓN -->
        <nav class="flex-1 overflow-y-auto scrollbar-hide py-4 px-3">
            <p class="text-[10px] uppercase text-gray-500 font-semibold px-3 py-2 tracking-wider">Gestiona tu negocio</p>
            
            <ul class="space-y-1">
                <!-- Facturas -->
                <li>
                    <a href="<?= $base_url ?>facturas/facturas.php" 
                       class="flex items-center gap-3 px-3 py-2.5 rounded-lg text-gray-300 hover:bg-activo hover:text-white transition-all <?= basename($_SERVER['PHP_SELF']) == 'facturas.php' ? 'menu-activo' : '' ?>">
                        <i class="fa fa-file-text-o w-5 text-center"></i>
                        <span>Facturas</span>
                    </a>
                </li>
                <!-- Inventario / Productos -->
                <li>
                    <a href="<?= $base_url ?>productos/inventario.php" 
                       class="flex items-center gap-3 px-3 py-2.5 rounded-lg text-gray-300 hover:bg-activo hover:text-white transition-all <?= basename($_SERVER['PHP_SELF']) == 'inventario.php' ? 'menu-activo' : '' ?>">
                        <i class="fa fa-cube w-5 text-center"></i>
                        <span>Inventario</span>
                    </a>
                </li>
                <!-- Garantías -->
                <li>
                    <a href="<?= $base_url ?>garantias/ver_garantia.php" 
                       class="flex items-center gap-3 px-3 py-2.5 rounded-lg text-gray-300 hover:bg-activo hover:text-white transition-all <?= basename($_SERVER['PHP_SELF']) == 'ver_garantia.php' ? 'menu-activo' : '' ?>">
                        <i class="fa fa-wrench w-5 text-center"></i>
                        <span>Garantías</span>
                    </a>
                </li>
                <!-- Clientes -->
                <li>
                    <a href="<?= $base_url ?>clientes/clientes.php" 
                       class="flex items-center gap-3 px-3 py-2.5 rounded-lg <?= basename($_SERVER['PHP_SELF']) == 'clientes.php' ? 'menu-activo' : 'text-gray-300 hover:bg-activo hover:text-white' ?> transition-all">
                        <i class="fa fa-users w-5 text-center"></i>
                        <span>Clientes</span>
                    </a>
                </li>
                <!-- Compras -->
                <li>
                    <a href="<?= $base_url ?>compra/compras.php" 
                       class="flex items-center gap-3 px-3 py-2.5 rounded-lg text-gray-300 hover:bg-activo hover:text-white transition-all <?= basename($_SERVER['PHP_SELF']) == 'compras.php' ? 'menu-activo' : '' ?>">
                        <i class="fa fa-shopping-cart w-5 text-center"></i>
                        <span>Compras</span>
                    </a>
                </li>
                <!-- Proveedores -->
                <li>
                    <a href="<?= $base_url ?>proveedores/proveedores.php" 
                       class="flex items-center gap-3 px-3 py-2.5 rounded-lg text-gray-300 hover:bg-activo hover:text-white transition-all <?= basename($_SERVER['PHP_SELF']) == 'proveedores.php' ? 'menu-activo' : '' ?>">
                        <i class="fa fa-truck w-5 text-center"></i>
                        <span>Proveedores</span>
                    </a>
                </li>
                <!-- Ingresos -->
                <li>
                    <a href="<?= $base_url ?>ingresos/ingresos.php" 
                       class="flex items-center gap-3 px-3 py-2.5 rounded-lg text-gray-300 hover:bg-activo hover:text-white transition-all <?= basename($_SERVER['PHP_SELF']) == 'ingresos.php' ? 'menu-activo' : '' ?>">
                        <i class="fa fa-line-chart w-5 text-center"></i>
                        <span>Ingresos</span>
                    </a>
                </li>
                <!-- Usuarios -->
                <li>
                    <a href="<?= $base_url ?>usuario/usuarios.php" 
                       class="flex items-center gap-3 px-3 py-2.5 rounded-lg text-gray-300 hover:bg-activo hover:text-white transition-all <?= basename($_SERVER['PHP_SELF']) == 'usuarios.php' ? 'menu-activo' : '' ?>">
                        <i class="fa fa-user-secret w-5 text-center"></i>
                        <span>Usuarios</span>
                    </a>
                </li>
            </ul>
        </nav>

                        <!-- 👤 MI PERFIL -->
                <li>
                    <a href="<?= $base_url ?>perfil/perfil.php" class="menu-item <?= basename($_SERVER['PHP_SELF']) == 'perfil.php' ? 'menu-activo' : '' ?>">
                        <i class="fa fa-id-card w-5 text-center"></i>
                        <span>Mi Perfil</span>
                    </a>
                </li>

        <!-- Cerrar Sesión -->
        <div class="p-3 border-t border-white/10">
            <a href="/MVC-PRU/views/Auth/logout.php" class="flex items-center gap-3 px-3 py-2.5 rounded-lg text-red-300 hover:bg-red-900/30 hover:text-red-200 transition-all">
                <i class="fa fa-sign-out w-5 text-center"></i>
                <span>Cerrar sesión</span>
            </a>
        </div>
    </aside>

    <!-- 🟩 CONTENIDO PRINCIPAL -->
    <main class="flex-1 h-screen overflow-y-auto">

        <!-- Encabezado superior -->
        <header class="bg-white px-8 py-4 border-b border-gray-200 flex items-center justify-between">
            <div>
                <h2 class="text-[26px] font-bold text-secondary">Dashboard</h2>
                <p class="text-gray-500 text-sm">Panel de Administrador • Bienvenido, <span class="font-medium text-azul"><?= htmlspecialchars($nombre_usuario) ?></span> 👑</p>
            </div>
            <!-- 🔍 BARRA DE BÚSQUEDA PARA FILTRAR MÓDULOS -->
            <div class="relative">
                <input type="text" id="buscarModulos" placeholder="Buscar módulo..." 
                       class="pl-10 pr-4 py-2 w-[260px] bg-gris rounded-lg border border-gray-200 focus:outline-none focus:ring-2 focus:ring-azul/20 focus:border-azul transition-all">
                <i class="fa fa-search absolute left-3.5 top-1/2 -translate-y-1/2 text-gray-400"></i>
            </div>
        </header>

        <!-- 📊 CONTENIDO Y TARJETAS -->
        <div class="px-8 py-6">
            
            <!-- Filtros y herramientas -->
    
                <button class="flex items-center gap-2 px-4 py-2 bg-white border border-gray-300 rounded-lg text-sm font-medium hover:bg-gris transition-all">
                    <i class="fa fa-calendar"></i> <?= date('F Y') ?>
                </button><br><br>
           

            <!-- Tarjetas de Resumen CON DATOS REALES -->
            <div class="grid grid-cols-1 md:grid-cols-3 gap-5 mb-10">
                <!-- BALANCE -->
                <div class="bg-white p-5 rounded-xl sombra-suave border border-gray-100 flex items-center gap-4">
                    <div class="w-12 h-12 rounded-full bg-green-100 text-green-600 flex items-center justify-center text-xl">
                        <i class="fa fa-line-chart"></i>
                    </div>
                    <div>
                        <p class="text-sm text-gray-500 font-medium">Balance</p>
                        <p class="text-2xl font-bold text-secondary">$ <?= number_format($balance, 0, ',', '.') ?></p>
                    </div>
                </div>
                <!-- VENTAS TOTALES -->
                <div class="bg-white p-5 rounded-xl sombra-suave border border-gray-100 flex items-center gap-4">
                    <div class="w-12 h-12 rounded-full bg-emerald-100 text-emerald-600 flex items-center justify-center text-xl">
                        <i class="fa fa-money"></i>
                    </div>
                    <div>
                        <p class="text-sm text-gray-500 font-medium">Ventas totales</p>
                        <p class="text-2xl font-bold text-secondary">$ <?= number_format($ventas_totales, 0, ',', '.') ?></p>
                    </div>
                </div>
                <!-- GASTOS TOTALES -->
                <div class="bg-white p-5 rounded-xl sombra-suave border border-gray-100 flex items-center gap-4">
                    <div class="w-12 h-12 rounded-full bg-rose-100 text-rose-600 flex items-center justify-center text-xl">
                        <i class="fa fa-credit-card"></i>
                    </div>
                    <div>
                        <p class="text-sm text-gray-500 font-medium">Gastos totales</p>
                        <p class="text-2xl font-bold text-secondary">$ <?= number_format($gastos_totales, 0, ',', '.') ?></p>
                    </div>
                </div>
            </div>

            <!-- 🟦 ACCESOS RÁPIDOS A TUS MÓDULOS - AQUÍ SE FILTRA -->
            <h3 class="text-lg font-semibold text-secondary mb-4">Accesos rápidos</h3>
            <div id="contenedorModulos" class="grid grid-cols-1 sm:grid-cols-2 lg:grid-cols-4 gap-6">
                
                <!-- Inventario -->
                <div class="modulo bg-white rounded-2xl p-6 border border-gray-100 sombra-suave hover:shadow-md transition-all" data-nombre="inventario productos stock">
                    <div class="w-12 h-12 bg-blue-50 text-azul rounded-xl flex items-center justify-center text-xl mb-4">
                        <i class="fa fa-cube"></i>
                    </div>
                    <h4 class="text-lg font-bold text-secondary mb-1">Inventario</h4>
                    <p class="text-gray-500 text-sm mb-5 leading-relaxed">Gestiona productos y controla el stock fácilmente.</p>
                    <a href="<?= $base_url ?>productos/inventario.php" class="inline-block w-full text-center bg-azul/10 text-azul font-medium py-2.5 rounded-lg hover:bg-azul/20 transition-all">Administrar</a>
                </div>

                <!-- Facturación -->
                <div class="modulo bg-white rounded-2xl p-6 border border-gray-100 sombra-suave hover:shadow-md transition-all" data-nombre="facturación facturas venta cobro">
                    <div class="w-12 h-12 bg-green-50 text-green-600 rounded-xl flex items-center justify-center text-xl mb-4">
                        <i class="fa fa-file-text-o"></i>
                    </div>
                    <h4 class="text-lg font-bold text-secondary mb-1">Facturación</h4>
                    <p class="text-gray-500 text-sm mb-5 leading-relaxed">Crea facturas y administra ventas del sistema.</p>
                    <a href="<?= $base_url ?>facturas/facturas.php" class="inline-block w-full text-center bg-green-50 text-green-700 font-medium py-2.5 rounded-lg hover:bg-green-100 transition-all">Ir a facturación</a>
                </div>

                <!-- Ingresos -->
                <div class="modulo bg-white rounded-2xl p-6 border border-gray-100 sombra-suave hover:shadow-md transition-all" data-nombre="ingresos ganancias reportes estadisticas">
                    <div class="w-12 h-12 bg-amber-50 text-amber-600 rounded-xl flex items-center justify-center text-xl mb-4">
                        <i class="fa fa-bar-chart"></i>
                    </div>
                    <h4 class="text-lg font-bold text-secondary mb-1">Ingresos</h4>
                    <p class="text-gray-500 text-sm mb-5 leading-relaxed">Visualiza estadísticas y ganancias de tu negocio.</p>
                    <a href="<?= $base_url ?>ingresos/ingresos.php" class="inline-block w-full text-center bg-amber-50 text-amber-700 font-medium py-2.5 rounded-lg hover:bg-amber-100 transition-all">Ver reportes</a>
                </div>

                <!-- Usuarios -->
                <div class="modulo bg-white rounded-2xl p-6 border border-gray-100 sombra-suave hover:shadow-md transition-all" data-nombre="usuarios cuentas roles permisos">
                    <div class="w-12 h-12 bg-purple-50 text-purple-600 rounded-xl flex items-center justify-center text-xl mb-4">
                        <i class="fa fa-users"></i>
                    </div>
                    <h4 class="text-lg font-bold text-secondary mb-1">Usuarios</h4>
                    <p class="text-gray-500 text-sm mb-5 leading-relaxed">Administra cuentas, permisos y roles.</p>
                    <a href="<?= $base_url ?>usuario/usuarios.php" class="inline-block w-full text-center bg-purple-50 text-purple-700 font-medium py-2.5 rounded-lg hover:bg-purple-100 transition-all">Administrar</a>
                </div>

                <!-- Garantías -->
                <div class="modulo bg-white rounded-2xl p-6 border border-gray-100 sombra-suave hover:shadow-md transition-all" data-nombre="garantías servicio reclamos">
                    <div class="w-12 h-12 bg-teal-50 text-teal-600 rounded-xl flex items-center justify-center text-xl mb-4">
                        <i class="fa fa-wrench"></i>
                    </div>
                    <h4 class="text-lg font-bold text-secondary mb-1">Garantías</h4>
                    <p class="text-gray-500 text-sm mb-5 leading-relaxed">Gestiona solicitudes y procesos de garantías.</p>
                    <a href="<?= $base_url ?>garantias/ver_garantia.php" class="inline-block w-full text-center bg-teal-50 text-teal-700 font-medium py-2.5 rounded-lg hover:bg-teal-100 transition-all">Administrar</a>
                </div>

                <!-- Clientes -->
                <div class="modulo bg-white rounded-2xl p-6 border border-gray-100 sombra-suave hover:shadow-md transition-all" data-nombre="clientes clientes">
                    <div class="w-12 h-12 bg-indigo-50 text-indigo-600 rounded-xl flex items-center justify-center text-xl mb-4">
                        <i class="fa fa-user-circle"></i>
                    </div>
                    <h4 class="text-lg font-bold text-secondary mb-1">Clientes</h4>
                    <p class="text-gray-500 text-sm mb-5 leading-relaxed">Consulta y administra información de clientes.</p>
                    <a href="<?= $base_url ?>clientes/clientes.php" class="inline-block w-full text-center bg-indigo-50 text-indigo-700 font-medium py-2.5 rounded-lg hover:bg-indigo-100 transition-all">Administrar</a>
                </div>

                <!-- Proveedores -->
                <div class="modulo bg-white rounded-2xl p-6 border border-gray-100 sombra-suave hover:shadow-md transition-all" data-nombre="proveedores proveedores comercio">
                    <div class="w-12 h-12 bg-orange-50 text-orange-600 rounded-xl flex items-center justify-center text-xl mb-4">
                        <i class="fa fa-truck"></i>
                    </div>
                    <h4 class="text-lg font-bold text-secondary mb-1">Proveedores</h4>
                    <p class="text-gray-500 text-sm mb-5 leading-relaxed">Controla tus proveedores y relaciones comerciales.</p>
                    <a href="<?= $base_url ?>proveedores/proveedores.php" class="inline-block w-full text-center bg-orange-50 text-orange-700 font-medium py-2.5 rounded-lg hover:bg-orange-100 transition-all">Administrar</a>
                </div>

                <!-- Compras -->
                <div class="modulo bg-white rounded-2xl p-6 border border-gray-100 sombra-suave hover:shadow-md transition-all" data-nombre="compras compras gastos">
                    <div class="w-12 h-12 bg-pink-50 text-pink-600 rounded-xl flex items-center justify-center text-xl mb-4">
                        <i class="fa fa-shopping-cart"></i>
                    </div>
                    <h4 class="text-lg font-bold text-secondary mb-1">Compras</h4>
                    <p class="text-gray-500 text-sm mb-5 leading-relaxed">Gestiona compras y movimientos del sistema.</p>
                    <a href="<?= $base_url ?>compra/compras.php" class="inline-block w-full text-center bg-pink-50 text-pink-700 font-medium py-2.5 rounded-lg hover:bg-pink-100 transition-all">Administrar</a>
                </div>

            </div>

        </div>
    </main>

    <!-- ✅ SCRIPT PARA FILTRAR LOS MÓDULOS -->
    <script>
        const inputBusqueda = document.getElementById('buscarModulos');
        const modulos = document.querySelectorAll('.modulo');

        inputBusqueda.addEventListener('input', function() {
            const texto = this.value.toLowerCase().trim();
            
            modulos.forEach(modulo => {
                const nombre = modulo.getAttribute('data-nombre').toLowerCase();
                
                // Si coincide o está vacío, mostrar; si no, ocultar
                if (texto === '' || nombre.includes(texto)) {
                    modulo.style.display = 'block';
                } else {
                    modulo.style.display = 'none';
                }
            });
        });
    </script>

</body>
</html>