<?php
session_start();

// 🔐 SOLO ADMIN
if (!isset($_SESSION["rol"]) || $_SESSION["rol"] != "admin") {
    header("Location: ../Auth/login.php");
    exit();
}

require_once "../../../config/db.php";
$conn = Database::Conectar();

// ── TOTALES GENERALES ──────────────────────────────────────────────
$totalIngresos = $conn->query("
    SELECT COALESCE(SUM(d.subtotal), 0) AS total
    FROM facturas f
    JOIN detallefactura d ON f.id_detallefactura = d.id_detallefactura
    WHERE f.estado = 'pagada'
")->fetch_assoc()['total'];

$totalPendiente = $conn->query("
    SELECT COALESCE(SUM(d.subtotal), 0) AS total
    FROM facturas f
    JOIN detallefactura d ON f.id_detallefactura = d.id_detallefactura
    WHERE f.estado = 'pendiente'
")->fetch_assoc()['total'];

$totalFacturas = $conn->query("SELECT COUNT(*) AS total FROM facturas")->fetch_assoc()['total'];

$totalClientes = $conn->query("SELECT COUNT(*) AS total FROM clientes")->fetch_assoc()['total'];

// ── INGRESOS POR MES (último año) ─────────────────────────────────
$ingresosMes = $conn->query("
    SELECT 
        DATE_FORMAT(f.fecha, '%Y-%m') AS mes,
        DATE_FORMAT(f.fecha, '%b %Y') AS mes_label,
        COALESCE(SUM(d.subtotal), 0) AS total
    FROM facturas f
    JOIN detallefactura d ON f.id_detallefactura = d.id_detallefactura
    WHERE f.estado = 'pagada'
      AND f.fecha >= DATE_SUB(CURDATE(), INTERVAL 12 MONTH)
    GROUP BY DATE_FORMAT(f.fecha, '%Y-%m')
    ORDER BY mes ASC
");

$meses = [];
$totalesMes = [];
while ($row = $ingresosMes->fetch_assoc()) {
    $meses[] = $row['mes_label'];
    $totalesMes[] = (float)$row['total'];
}

// ── VENTAS POR ESTADO ─────────────────────────────────────────────
$estadosRes = $conn->query("
    SELECT estado, COUNT(*) AS cantidad
    FROM facturas
    GROUP BY estado
");
$estadosLabels = [];
$estadosCantidad = [];
while ($row = $estadosRes->fetch_assoc()) {
    $estadosLabels[] = ucfirst($row['estado']);
    $estadosCantidad[] = (int)$row['cantidad'];
}

// ── TOP 5 PRODUCTOS MÁS VENDIDOS ──────────────────────────────────
$topProductos = $conn->query("
    SELECT p.nombre, SUM(d.cantidad) AS total_vendido, SUM(d.subtotal) AS total_ingresos
    FROM detallefactura d
    JOIN productos p ON d.id_productos = p.id_productos
    JOIN facturas f ON d.id_facturas = f.id_facturas
    WHERE f.estado = 'pagada'
    GROUP BY d.id_productos
    ORDER BY total_vendido DESC
    LIMIT 5
");

// ── ÚLTIMAS 8 FACTURAS ────────────────────────────────────────────
$ultimasFacturas = $conn->query("
    SELECT f.id_facturas, f.estado, f.fecha,
           c.nombre AS nombre_cliente,
           p.nombre AS nombre_producto,
           d.cantidad, d.subtotal
    FROM facturas f
    JOIN clientes c ON f.id_clientes = c.id_clientes
    JOIN detallefactura d ON f.id_detallefactura = d.id_detallefactura
    JOIN productos p ON d.id_productos = p.id_productos
    ORDER BY f.fecha DESC
    LIMIT 8
");

// 📋 DATOS PARA EL MENÚ
$nombre_usuario = isset($_SESSION["nombre"]) ? $_SESSION["nombre"] : "Administrador";
$rol_usuario    = $_SESSION["rol"];
$base_url = "/MVC-PRU/views/Admin/";
?>

<!DOCTYPE html>
<html lang="es">
<head>
<meta charset="UTF-8">
<meta name="viewport" content="width=device-width, initial-scale=1.0">
<title>Ingresos – InvoicePro</title>

<!-- 🎨 ESTILOS DEL MENÚ Y ESTRUCTURA GENERAL -->
<script src="https://cdn.tailwindcss.com"></script>
<link rel="preconnect" href="https://fonts.googleapis.com">
<link href="https://fonts.googleapis.com/css2?family=Inter:wght@300;400;500;600;700&display=swap" rel="stylesheet">
<link href="https://cdn.jsdelivr.net/npm/font-awesome@4.7.0/css/font-awesome.min.css" rel="stylesheet">
<script src="https://cdn.jsdelivr.net/npm/chart.js"></script>

<script>
    tailwind.config = {
        theme: {
            extend: {
                colors: {
                    sidebar: '#232c3b',
                    primary: '#fcd100',
                    secondary: '#1e293b',
                    azul: '#17345f', // Tu color principal
                    gris: '#f8fafc',
                    activo: '#334155',
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
        .menu-item { @apply flex items-center gap-3 px-3 py-2.5 rounded-lg transition-all text-gray-300 hover:bg-activo hover:text-white; }
    }
</style>

<!-- ✅ TUS ESTILOS ORIGINALES (ASEGURADOS Y CORREGIDOS) -->
<style>
/* REEMPLAZA TODO TU <style> POR ESTE */

*,
*::before,
*::after{
    box-sizing:border-box;
    margin:0;
    padding:0;
}

:root{

    --bg:#f4f6f9 !important;
    --surface:#ffffff !important;
    --surface2:#f8fafc !important;
    --border:#e2e8f0 !important;

    --primary:#17345f !important;
    --primary-hover:#264c83 !important;

    --green:#16a34a !important;
    --green-bg:#dcfce7 !important;

    --blue:#2563eb !important;
    --blue-bg:#dbeafe !important;

    --orange:#ea580c !important;
    --orange-bg:#ffedd5 !important;

    --red:#dc2626 !important;
    --red-bg:#fee2e2 !important;

    --text:#0f172a !important;
    --muted:#64748b !important;

    --font:'Inter', sans-serif !important;
}

body{
    font-family:var(--font) !important;
    background:var(--bg) !important;
    color:var(--text) !important;
    min-height:100vh !important;
}

/* HEADER */

.header{
    display:flex !important;
    justify-content:space-between !important;
    align-items:center !important;
    margin-bottom:32px !important;
    flex-wrap:wrap !important;
    gap:16px !important;
}

.header-left h1{
    font-size:34px !important;
    font-weight:700 !important;
    color:var(--text) !important;
}

.header-left p{
    color:var(--muted) !important;
    margin-top:6px !important;
    font-size:15px !important;
}

.header-right{
    display:flex !important;
    gap:12px !important;
    align-items:center !important;
}

.badge{
    background:white !important;
    border:1px solid var(--border) !important;
    color:var(--muted) !important;
    padding:12px 16px !important;
    border-radius:14px !important;
    font-size:13px !important;
    font-weight:600 !important;
    box-shadow:0 2px 10px rgba(0,0,0,.04) !important;
}

.btn-back{
    background:var(--primary) !important;
    color:white !important;
    padding:12px 18px !important;
    border-radius:14px !important;
    text-decoration:none !important;
    font-size:14px !important;
    font-weight:600 !important;
    transition:.3s !important;
    display:inline-block !important;
}

.btn-back:hover{
    background:var(--primary-hover) !important;
    color:white !important;
}

/* KPIs */

.kpis{
    display:grid !important;
    grid-template-columns:repeat(4,1fr) !important;
    gap:18px !important;
    margin-bottom:24px !important;
}

.kpi{
    background:var(--surface) !important;
    border:1px solid var(--border) !important;
    border-radius:24px !important;
    padding:26px !important;
    box-shadow:0 4px 18px rgba(0,0,0,.04) !important;
    transition:.3s !important;
    position:relative !important;
    overflow:hidden !important;
}

.kpi:hover{
    transform:translateY(-3px) !important;
}

.kpi::before{
    content:'' !important;
    position:absolute !important;
    top:0 !important;
    left:0 !important;
    width:100% !important;
    height:5px !important;
}

.kpi.green::before{
    background:var(--green) !important;
}

.kpi.yellow::before{
    background:var(--orange) !important;
}

.kpi.blue::before{
    background:var(--blue) !important;
}

.kpi.red::before{
    background:var(--red) !important;
}

.kpi-icon{
    font-size:28px !important;
    margin-bottom:16px !important;
}

.kpi-label{
    font-size:13px !important;
    color:var(--muted) !important;
    text-transform:uppercase !important;
    font-weight:600 !important;
    letter-spacing:.5px !important;
    margin-bottom:8px !important;
}

.kpi-value{
    font-size:34px !important;
    font-weight:700 !important;
}

.kpi.green .kpi-value{
    color:var(--green) !important;
}

.kpi.yellow .kpi-value{
    color:var(--orange) !important;
}

.kpi.blue .kpi-value{
    color:var(--blue) !important;
}

.kpi.red .kpi-value{
    color:var(--red) !important;
}

/* CHARTS */

.charts-grid{
    display:grid !important;
    grid-template-columns:2fr 1fr !important;
    gap:18px !important;
    margin-bottom:24px !important;
}

.card{
    background:var(--surface) !important;
    border:1px solid var(--border) !important;
    border-radius:24px !important;
    padding:26px !important;
    box-shadow:0 4px 18px rgba(0,0,0,.04) !important;
}

.card-title{
    font-size:16px !important;
    font-weight:700 !important;
    margin-bottom:24px !important;
    color:var(--text) !important;
    display:flex !important;
    align-items:center !important;
    gap:10px !important;
}

/* BOTTOM GRID */

.bottom-grid{
    display:grid !important;
    grid-template-columns:1fr 1fr !important;
    gap:18px !important;
}

/* TABLE */

table{
    width:100% !important;
    border-collapse:collapse !important;
}

thead tr{
    border-bottom:1px solid var(--border) !important;
}

th{
    padding-bottom:14px !important;
    text-align:left !important;
    font-size:12px !important;
    color:var(--muted) !important;
    text-transform:uppercase !important;
    letter-spacing:.5px !important;
}

td{
    padding:16px 0 !important;
    border-bottom:1px solid var(--border) !important;
    font-size:14px !important;
    color:#334155 !important;
    vertical-align: middle !important;
}

tr:last-child td{
    border-bottom:none !important;
}

tbody tr:hover td{
    color:var(--primary) !important;
}

/* BADGES */

.estado-badge{
    padding:7px 12px !important;
    border-radius:999px !important;
    font-size:12px !important;
    font-weight:600 !important;
}

.pagada{
    background:var(--green-bg) !important;
    color:var(--green) !important;
}

.pendiente{
    background:#fef3c7 !important;
    color:#d97706 !important;
}

.anulada{
    background:var(--red-bg) !important;
    color:var(--red) !important;
}

/* PRODUCTOS */

.producto-row{
    display:flex !important;
    align-items:center !important;
    gap:14px !important;
    padding:14px 0 !important;
    border-bottom:1px solid var(--border) !important;
}

.producto-row:last-child{
    border-bottom:none !important;
}

.producto-rank{
    width:34px !important;
    height:34px !important;
    background:var(--surface2) !important;
    border-radius:10px !important;
    display:flex !important;
    align-items:center !important;
    justify-content:center !important;
    font-weight:700 !important;
    color:var(--primary) !important;
    font-size:13px !important;
}

.producto-info{
    flex:1 !important;
}

.producto-nombre{
    font-size:14px !important;
    font-weight:600 !important;
    color:var(--text) !important;
}

.producto-vendido{
    font-size:12px !important;
    color:var(--muted) !important;
    margin-top:4px !important;
}

.producto-total{
    font-size:14px !important;
    font-weight:700 !important;
    color:var(--green) !important;
}

.bar-container{
    height:6px !important;
    background:#e2e8f0 !important;
    border-radius:999px !important;
    margin-top:10px !important;
    overflow:hidden !important;
}

.bar-fill{
    height:100% !important;
    background:linear-gradient(90deg,#17345f,#3b82f6) !important;
    border-radius:999px !important;
}

/* RESPONSIVE */

@media(max-width:1100px){

    .kpis{
        grid-template-columns:repeat(2,1fr) !important;
    }

    .charts-grid{
        grid-template-columns:1fr !important;
    }

    .bottom-grid{
        grid-template-columns:1fr !important;
    }
}

@media(max-width:700px){

    body{
        padding:20px !important;
    }

    .header{
        flex-direction:column !important;
        align-items:flex-start !important;
    }

    .kpis{
        grid-template-columns:1fr !important;
    }

    .kpi{
        padding:22px !important;
    }

    .kpi-value{
        font-size:28px !important;
    }

    .header-left h1{
        font-size:28px !important;
    }

    .card{
        padding:20px !important;
    }
}
</style>
</head>
<body class="bg-gris font-inter text-secondary min-h-screen flex overflow-hidden">

    <!-- 🟦 MENÚ LATERAL -->
    <aside class="w-[220px] bg-[#232c3b] text-white h-screen sticky top-0 flex flex-col shadow-lg">
        
        <!-- Logo -->
        <div class="px-4 py-5 border-b border-white/10">
            <h1 class="text-[22px] font-bold text-[#fcd100] flex items-center gap-2">
                <i class="fa fa-file-text-o"></i> InvoicePro
            </h1>
        </div>

        <!-- Usuario -->
        <div class="px-4 py-4 border-b border-white/10 flex items-center gap-3">
            <div class="w-9 h-9 rounded-full bg-white/20 flex items-center justify-center">
                <i class="fa fa-user-circle-o text-lg"></i>
            </div>
            <div>
                <p class="text-sm font-medium"><?= htmlspecialchars($nombre_usuario) ?> 👑</p>
                <p class="text-xs text-gray-400"><?= ucfirst($rol_usuario) ?></p>
            </div>
        </div>

        <!-- MENÚ DE NAVEGACIÓN -->
        <nav class="flex-1 overflow-y-auto scrollbar-hide py-4 px-2">
            <p class="text-[10px] uppercase text-gray-500 font-semibold px-3 py-1 tracking-wider">Gestiona tu negocio</p>
            
            <ul class="space-y-1 mt-2">
                <!-- Dashboard -->
                <li>
                    <a href="<?= $base_url ?>dashboard_admin.php" 
                       class="menu-item <?= basename($_SERVER['PHP_SELF']) == 'dashboard_admin.php' ? 'menu-activo' : '' ?>">
                        <i class="fa fa-tachometer w-5 text-center"></i>
                        <span>Dashboard</span>
                    </a>
                </li>
                <!-- Facturas -->
                <li>
                    <a href="<?= $base_url ?>facturas/facturas.php" 
                       class="menu-item <?= in_array(basename($_SERVER['PHP_SELF']), ['facturas.php','crear_factura.php','editar_factura.php']) ? 'menu-activo' : '' ?>">
                        <i class="fa fa-file-text-o w-5 text-center"></i>
                        <span>Facturas</span>
                    </a>
                </li>
                <!-- Inventario / Productos -->
                <li>
                    <a href="<?= $base_url ?>productos/inventario.php" 
                       class="menu-item <?= in_array(basename($_SERVER['PHP_SELF']), ['inventario.php','agregar_producto.php','editar_producto.php']) ? 'menu-activo' : '' ?>">
                        <i class="fa fa-cube w-5 text-center"></i>
                        <span>Inventario</span>
                    </a>
                </li>
                <!-- Garantías -->
                <li>
                    <a href="<?= $base_url ?>garantias/ver_garantia.php" 
                       class="menu-item <?= in_array(basename($_SERVER['PHP_SELF']), ['ver_garantia.php','crear_garantia.php','editar_garantia.php']) ? 'menu-activo' : '' ?>">
                        <i class="fa fa-wrench w-5 text-center"></i>
                        <span>Garantías</span>
                    </a>
                </li>
                <!-- Clientes -->
                <li>
                    <a href="<?= $base_url ?>clientes/clientes.php" 
                       class="menu-item <?= in_array(basename($_SERVER['PHP_SELF']), ['clientes.php','crear_cliente.php','editar_cliente.php']) ? 'menu-activo' : '' ?>">
                        <i class="fa fa-users w-5 text-center"></i>
                        <span>Clientes</span>
                    </a>
                </li>
                <!-- Compras -->
                <li>
                    <a href="<?= $base_url ?>compra/compras.php" 
                       class="menu-item <?= in_array(basename($_SERVER['PHP_SELF']), ['compras.php','crear_compra.php','editar_compra.php']) ? 'menu-activo' : '' ?>">
                        <i class="fa fa-shopping-cart w-5 text-center"></i>
                        <span>Compras</span>
                    </a>
                </li>
                <!-- Proveedores -->
                <li>
                    <a href="<?= $base_url ?>proveedores/proveedores.php" 
                       class="menu-item <?= in_array(basename($_SERVER['PHP_SELF']), ['proveedores.php','crear_proveedor.php','editar_proveedor.php']) ? 'menu-activo' : '' ?>">
                        <i class="fa fa-truck w-5 text-center"></i>
                        <span>Proveedores</span>
                    </a>
                </li>
                <!-- Ingresos -->
                <li>
                    <a href="<?= $base_url ?>ingresos/ingresos.php" 
                       class="menu-item <?= basename($_SERVER['PHP_SELF']) == 'ingresos.php' ? 'menu-activo' : '' ?>">
                        <i class="fa fa-line-chart w-5 text-center"></i>
                        <span>Ingresos</span>
                    </a>
                </li>
                <!-- Usuarios -->
                <li>
                    <a href="<?= $base_url ?>usuario/usuarios.php" 
                       class="menu-item <?= in_array(basename($_SERVER['PHP_SELF']), ['usuarios.php','crear.php','editar.php']) ? 'menu-activo' : '' ?>">
                        <i class="fa fa-user-secret w-5 text-center"></i>
                        <span>Usuarios</span>
                    </a>
                </li>
            </ul>
        </nav>

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
                <h2 class="text-[26px] font-bold text-secondary">Ingresos</h2>
                <p class="text-gray-500 text-sm">Panel de Administrador • Bienvenido, <span class="font-medium text-azul"><?= htmlspecialchars($nombre_usuario) ?></span> 👑</p>
            </div>
            <!-- 🔍 BARRA DE BÚSQUEDA DE MÓDULOS -->
            <div class="relative">
                <input type="text" id="buscarModulos" placeholder="Buscar módulo..." 
                       class="pl-10 pr-4 py-2 w-[260px] bg-gris rounded-lg border border-gray-200 focus:outline-none focus:ring-2 focus:ring-azul/20 focus:border-azul transition-all">
                <i class="fa fa-search absolute left-3.5 top-1/2 -translate-y-1/2 text-gray-400"></i>
            </div>
        </header>

        <div class="px-8 py-6">

            <!-- ✅ TU CÓDIGO DE INGRESOS AQUÍ -->
            <!-- HEADER -->
            <div class="header">
                <div class="header-left">
                    <h1>📊 Ingresos</h1>
                    <p>Resumen financiero de InvoicePro</p>
                </div>
                <div class="header-right">
                    <span class="badge"><?= date('d M Y') ?></span>
                    <a href="../dashboard_admin.php" class="btn-back">← Volver</a>
                </div>
            </div>

            <!-- KPIs -->
            <div class="kpis">
                <div class="kpi green">
                    <div class="kpi-icon">💰</div>
                    <div class="kpi-label">Ingresos Totales</div>
                    <div class="kpi-value">$<?= number_format($totalIngresos, 0, ',', '.') ?></div>
                </div>
                <div class="kpi yellow">
                    <div class="kpi-icon">⏳</div>
                    <div class="kpi-label">Por Cobrar</div>
                    <div class="kpi-value">$<?= number_format($totalPendiente, 0, ',', '.') ?></div>
                </div>
                <div class="kpi blue">
                    <div class="kpi-icon">🧾</div>
                    <div class="kpi-label">Total Facturas</div>
                    <div class="kpi-value"><?= number_format($totalFacturas, 0, ',', '.') ?></div>
                </div>
                <div class="kpi red">
                    <div class="kpi-icon">👥</div>
                    <div class="kpi-label">Clientes</div>
                    <div class="kpi-value"><?= number_format($totalClientes, 0, ',', '.') ?></div>
                </div>
            </div>

            <!-- GRÁFICOS PRINCIPALES -->
            <div class="charts-grid">
                <div class="card">
                    <div class="card-title">📈 <span>Ingresos por mes</span></div>
                    <canvas id="chartMeses" height="100"></canvas>
                </div>
                <div class="card">
                    <div class="card-title">🥧 <span>Facturas por estado</span></div>
                    <canvas id="chartEstados" height="200"></canvas>
                </div>
            </div>

            <!-- BOTTOM: TABLA + TOP PRODUCTOS -->
            <div class="bottom-grid">
                <div class="card">
                    <div class="card-title">🕐 <span>Últimas facturas</span></div>
                    <table>
                        <thead>
                            <tr>
                                <th>#</th>
                                <th>Cliente</th>
                                <th>Producto</th>
                                <th>Total</th>
                                <th>Estado</th>
                            </tr>
                        </thead>
                        <tbody>
                        <?php while($f = $ultimasFacturas->fetch_assoc()): ?>
                            <tr>
                                <td style="font-family:var(--mono); color:var(--muted)"><?= $f['id_facturas'] ?></td>
                                <td><?= htmlspecialchars($f['nombre_cliente']) ?></td>
                                <td style="color:var(--muted)"><?= htmlspecialchars($f['nombre_producto']) ?></td>
                                <td style="font-family:var(--mono); color:var(--accent2)">$<?= number_format($f['subtotal'], 0, ',', '.') ?></td>
                                <td><span class="estado-badge <?= $f['estado'] ?>"><?= ucfirst($f['estado']) ?></span></td>
                            </tr>
                        <?php endwhile; ?>
                        </tbody>
                    </table>
                </div>

                <div class="card">
                    <div class="card-title">🏆 <span>Top productos vendidos</span></div>
                    <?php
                    $topData = [];
                    while ($p = $topProductos->fetch_assoc()) $topData[] = $p;
                    $maxVendido = !empty($topData) ? max(array_column($topData, 'total_vendido')) : 1;
                    foreach ($topData as $i => $p):
                        $pct = $maxVendido > 0 ? ($p['total_vendido'] / $maxVendido) * 100 : 0;
                    ?>
                    <div class="producto-row">
                        <div class="producto-rank"><?= $i+1 ?></div>
                        <div class="producto-info">
                            <div class="producto-nombre"><?= htmlspecialchars($p['nombre']) ?></div>
                            <div class="producto-vendido"><?= $p['total_vendido'] ?> unidades vendidas</div>
                            <div class="bar-container">
                                <div class="bar-fill" style="width:<?= $pct ?>%"></div>
                            </div>
                        </div>
                        <div class="producto-total">$<?= number_format($p['total_ingresos'], 0, ',', '.') ?></div>
                    </div>
                    <?php endforeach; ?>
                    <?php if (empty($topData)): ?>
                        <p style="color:var(--muted); font-size:13px; text-align:center; padding:20px 0">No hay datos aún</p>
                    <?php endif; ?>
                </div>
            </div>

        </div> <!-- Cierra el contenedor principal -->
    </main>

    <!-- ✅ SCRIPTS -->
    <script>
        // ── GRÁFICO BARRAS: INGRESOS POR MES ──
        const meses = <?= json_encode($meses) ?>;
        const totalesMes = <?= json_encode($totalesMes) ?>;

        new Chart(document.getElementById('chartMeses'), {
            type: 'bar',
            data: {
                labels: meses,
                datasets: [{
                    label: 'Ingresos',
                    data: totalesMes,
                    backgroundColor: 'rgba(59,130,246,0.7)',
                    borderColor: '#3b82f6',
                    borderWidth: 2,
                    borderRadius: 6,
                    hoverBackgroundColor: '#10b981',
                }]
            },
            options: {
                responsive: true,
                plugins: {
                    legend: { display: false },
                    tooltip: {
                        callbacks: {
                            label: ctx => ' $' + ctx.parsed.y.toLocaleString('es-CO')
                        }
                    }
                },
                scales: {
                    x: {
                        ticks: { color: '#64748b', font: { size: 11 } },
                        grid: { color: '#1e2d45' }
                    },
                    y: {
                        ticks: {
                            color: '#64748b',
                            font: { size: 11 },
                            callback: v => '$' + v.toLocaleString('es-CO')
                        },
                        grid: { color: '#1e2d45' }
                    }
                }
            }
        });

        // ── GRÁFICO DONA: ESTADOS ──
        const estadosLabels = <?= json_encode($estadosLabels) ?>;
        const estadosCantidad = <?= json_encode($estadosCantidad) ?>;

        new Chart(document.getElementById('chartEstados'), {
            type: 'doughnut',
            data: {
                labels: estadosLabels,
                datasets: [{
                    data: estadosCantidad,
                    backgroundColor: ['#10b981', '#f59e0b', '#ef4444', '#64748b'],
                    borderColor: '#111827',
                    borderWidth: 3,
                    hoverOffset: 8
                }]
            },
            options: {
                responsive: true,
                plugins: {
                    legend: {
                        position: 'bottom',
                        labels: {
                            color: '#94a3b8',
                            font: { size: 12 },
                            padding: 16,
                            usePointStyle: true
                        }
                    }
                },
                cutout: '65%'
            }
        });

        // 🔎 BUSCADOR DE MÓDULOS
        const inputBusqueda = document.getElementById('buscarModulos');
        if(inputBusqueda){
        inputBusqueda.addEventListener('input', function() {
            const texto = this.value.toLowerCase().trim();
            const filas = document.querySelectorAll('tbody tr');
            filas.forEach(fila => {
                const contenido = fila.textContent.toLowerCase();
                fila.style.display = (contenido.includes(texto)) ? '' : 'none';
            });
        });
        }
    </script>

</body>
</html>