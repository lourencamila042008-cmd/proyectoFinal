<?php
session_start();

// 🔒 PERMITIR admin y empleado
if (!isset($_SESSION["rol"]) || 
   ($_SESSION["rol"] != "admin" && $_SESSION["rol"] != "empleado")) {
    header("Location: ../Auth/login.php");
    exit();
}

$esAdmin = $_SESSION["rol"] == "admin";

require_once "../../../config/db.php";
$conn = Database::Conectar();

$sql = "SELECT f.id_facturas, f.estado, f.fecha,
               c.nombre AS nombre_cliente,
               p.nombre AS nombre_producto,
               d.cantidad, d.precio, d.subtotal
        FROM facturas f
        JOIN clientes c ON f.id_clientes = c.id_clientes
        JOIN detallefactura d ON f.id_detallefactura = d.id_detallefactura
        JOIN productos p ON d.id_productos = p.id_productos
        ORDER BY f.id_facturas DESC";

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
<title>Facturas | InvoicePro</title>

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
                    azul: '#2563eb', /* Tu color principal */
                    success: '#10b981',
                    successBg: '#ecfdf5',
                    warning: '#f59e0b',
                    warningBg: '#fffbeb',
                    danger: '#ef4444',
                    dangerBg: '#fef2f2',
                    gris: '#f3f7fb',
                    surface: '#ffffff',
                    surface2: '#f8fafc',
                    border: '#dbe7f3',
                    muted: '#64748b',
                },
                fontFamily: { 
                    inter: ['Inter', 'sans-serif'],
                    dm: ['DM Sans', 'sans-serif'],
                    mono: ['DM Mono', 'mono']
                }
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
}

:root{
    --bg:#f3f7fb !important;
    --surface:#ffffff !important;
    --surface2:#f8fafc !important;

    --border:#dbe7f3 !important;

    --text:#0f172a !important;
    --muted:#64748b !important;

    --primary:#2563eb !important;
    --primary-light:#eff6ff !important;

    --success:#10b981 !important;
    --success-bg:#ecfdf5 !important;

    --warning:#f59e0b !important;
    --warning-bg:#fffbeb !important;

    --danger:#ef4444 !important;
    --danger-bg:#fef2f2 !important;

    --shadow:0 10px 30px rgba(15,23,42,.08) !important;

    --font:'DM Sans', sans-serif !important;
    --mono:'DM Mono', monospace !important;
}

body{
    background:var(--bg) !important;
    font-family:var(--font) !important;
    color:var(--text) !important;
}

.contenedor-principal {
    padding:32px 24px !important;
}

/* HEADER */

.topbar{
    display:flex !important;
    justify-content:space-between !important;
    align-items:center !important;
    margin-bottom:28px !important;
    flex-wrap:wrap !important;
    gap:16px !important;
}

.topbar h1{
    font-size:30px !important;
    font-weight:700 !important;
    letter-spacing:-1px !important;
}

.top-actions{
    display:flex !important;
    gap:12px !important;
    flex-wrap:wrap !important;
}

.btn{
    text-decoration:none !important;
    padding:12px 18px !important;
    border-radius:12px !important;
    font-size:14px !important;
    font-weight:600 !important;
    transition:.2s !important;
    border:1px solid transparent !important;
    display:inline-block !important;
}

.btn-primary{
    background:var(--primary) !important;
    color:white !important;
    box-shadow:0 10px 20px rgba(37,99,235,.15) !important;
}

.btn-primary:hover{
    transform:translateY(-2px) !important;
    color:white !important;
}

.btn-light{
    background:white !important;
    color:var(--text) !important;
    border-color:var(--border) !important;
}

.btn-light:hover{
    border-color:var(--primary) !important;
    color:var(--primary) !important;
}

/* TABLE CARD */

.table-card{
    background:var(--surface) !important;
    border-radius:24px !important;
    border:1px solid var(--border) !important;
    overflow:hidden !important;
    box-shadow:var(--shadow) !important;
}

/* TABLE */

.table-wrapper{
    overflow-x:auto !important;
}

table{
    width:100% !important;
    border-collapse:collapse !important;
    min-width:1000px !important;
}

thead{
    background:var(--surface2) !important;
}

th{
    text-align:left !important;
    padding:18px 20px !important;
    font-size:12px !important;
    text-transform:uppercase !important;
    letter-spacing:.08em !important;
    color:var(--muted) !important;
    font-weight:700 !important;
    border-bottom:1px solid var(--border) !important;
}

td{
    padding:18px 20px !important;
    border-bottom:1px solid #edf2f7 !important;
    font-size:14px !important;
}

tbody tr{
    transition:.2s !important;
}

tbody tr:hover{
    background:#fafcff !important;
}

/* BADGES */

.estado{
    padding:7px 12px !important;
    border-radius:999px !important;
    font-size:12px !important;
    font-weight:700 !important;
    display:inline-flex !important;
    align-items:center !important;
    gap:6px !important;
}

.pagada{
    background:var(--success-bg) !important;
    color:var(--success) !important;
}

.pendiente{
    background:var(--warning-bg) !important;
    color:var(--warning) !important;
}

.anulada{
    background:var(--danger-bg) !important;
    color:var(--danger) !important;
}

/* MONEY */

.money{
    font-family:var(--mono) !important;
    font-weight:500 !important;
}

/* ACTIONS */

.acciones{
    display:flex !important;
    gap:10px !important;
}

.action-btn{
    width:38px !important;
    height:38px !important;
    border-radius:10px !important;
    display:flex !important;
    justify-content:center !important;
    align-items:center !important;
    text-decoration:none !important;
    font-size:16px !important;
    transition:.2s !important;
}

.action-btn:hover{
    transform:translateY(-2px) !important;
}

.edit{
    background:#eff6ff !important;
    color:var(--primary) !important;
}

.delete{
    background:#fef2f2 !important;
    color:var(--danger) !important;
}

.pdf{
    background:#ecfdf5 !important;
    color:var(--success) !important;
}

/* RESPONSIVE */

@media(max-width:768px){

    .contenedor-principal {
        padding:20px 14px !important;
    }

    .topbar{
        flex-direction:column !important;
        align-items:flex-start !important;
    }

    .topbar h1{
        font-size:24px !important;
    }

    .top-actions{
        width:100% !important;
    }

    .btn{
        width:100% !important;
        text-align:center !important;
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
                    <a href="<?= $base_url ?>facturas/facturas.php" class="menu-item menu-activo">
                        <i class="fa fa-file-text-o w-5 text-center"></i>
                        <span>Facturas</span>
                    </a>
                </li>
                <!-- Inventario -->
                <li>
                    <a href="<?= $base_url ?>inventario/inventario.php" class="menu-item">
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

        <div class="topbar">
            <h1>Facturas</h1>
            <div class="top-actions">
                <a class="btn btn-light" href="<?= $base_url . ($esAdmin ? 'dashboard_admin.php' : 'dashboard_empleado.php') ?>">
                    ← Volver al inicio
                </a>
                <a class="btn btn-primary" href="crear_factura.php">
                    + Nueva Factura
                </a>
            </div>
        </div>


        <div class="table-card">
            <div class="table-wrapper">
                <table>
                    <thead>
                        <tr>
                            <th>ID</th>
                            <th>Cliente</th>
                            <th>Producto</th>
                            <th>Cantidad</th>
                            <th>Precio</th>
                            <th>Subtotal</th>
                            <th>Estado</th>
                            <th>Fecha</th>
                            <th>Acciones</th>
                        </tr>
                    </thead>

                    <tbody>
                        <?php while($f = $result->fetch_assoc()){ ?>
                        <tr>
                            <td><?= $f['id_facturas'] ?></td>
                            <td><?= htmlspecialchars($f['nombre_cliente']) ?></td>
                            <td><?= htmlspecialchars($f['nombre_producto']) ?></td>
                            <td><?= $f['cantidad'] ?></td>
                            <td class="money">$<?= number_format($f['precio'], 0, ',', '.') ?></td>
                            <td class="money">$<?= number_format($f['subtotal'], 0, ',', '.') ?></td>

                            <td>
                                <span class="estado <?= $f['estado'] ?>">
                                    <?= ucfirst($f['estado']) ?>
                                </span>
                            </td>

                            <td><?= $f['fecha'] ?></td>

                            <td class="acciones">
                                <!-- 🔥 SOLO ADMIN -->
                                <?php if($esAdmin): ?>
                                    <a class="action-btn edit" href="editar_factura.php?id=<?= $f['id_facturas'] ?>" title="Editar">✏️</a>
                                    <a class="action-btn delete" href="eliminar_factura.php?id=<?= $f['id_facturas'] ?>"
                                       onclick="return confirm('¿Eliminar factura?')" title="Eliminar">🗑️</a>
                                <?php endif; ?>

                                <!-- TODOS -->
                                <a class="action-btn pdf" href="pdf.php?id=<?= $f['id_facturas'] ?>" title="Ver PDF">📄</a>
                            </td>
                        </tr>
                        <?php } ?>
                    </tbody>
                </table>
            </div>
        </div>

    </main>

</body>
</html>