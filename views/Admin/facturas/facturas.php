<?php
session_start();

// 🔐 SOLO ADMIN
if (!isset($_SESSION["rol"]) || $_SESSION["rol"] != "admin") {
    header("Location: ../Auth/login.php");
    exit();
}

require_once "../../../config/db.php";
$conn = Database::Conectar();

// JOIN para traer nombre del cliente y productos del detalle
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
$nombre_usuario = isset($_SESSION["nombre"]) ? $_SESSION["nombre"] : "Administrador";
$rol_usuario    = $_SESSION["rol"];
$base_url = "/MVC-PRU/views/Admin/";
?>

<!DOCTYPE html>
<html lang="es">
<head>
<meta charset="UTF-8">
<meta name="viewport" content="width=device-width, initial-scale=1.0">
<title>Facturas - InvoicePro</title>

<!-- 🎨 ESTILOS DEL MENÚ Y ESTRUCTURA GENERAL -->
<script src="https://cdn.tailwindcss.com"></script>
<link href="https://fonts.googleapis.com/css2?family=Inter:wght@300;400;500;600;700&display=swap" rel="stylesheet">
<link href="https://cdn.jsdelivr.net/npm/font-awesome@4.7.0/css/font-awesome.min.css" rel="stylesheet">

<script>
    tailwind.config = {
        theme: {
            extend: {
                colors: {
                    sidebar: '#232c3b',
                    primary: '#fcd100',
                    secondary: '#1e293b',
                    azul: '#165DFF',
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

<!-- 🎨 TUS ESTILOS ORIGINALES (OPTIMIZADOS Y ARMONIZADOS) -->
<style>

*{
    margin:0;
    padding:0;
    box-sizing:border-box;
    font-family:'Inter', sans-serif;
}

body{
    background:#f4f6f9;
    color:#1e293b;
}

.topbar{
    display:flex;
    justify-content:space-between;
    align-items:center;
    flex-wrap:wrap;
    gap:15px;
    margin-bottom:30px;
}

.topbar h1{
    font-size:34px;
    color:#0f172a;
}

.actions{
    display:flex;
    gap:12px;
    flex-wrap:wrap;
}

.btn{
    border:none;
    background:#17345f;
    color:white;
    padding:14px 20px;
    border-radius:14px;
    cursor:pointer;
    text-decoration:none;
    font-weight:600;
    transition:.3s;
    display:inline-block;
    text-align:center;
}

.btn:hover{
    background:#264c83;
    color:white;
}

.btn-secondary{
    background:#64748b;
}

.btn-secondary:hover{
    background:#475569;
}

/* TABLE */

.table-container{
    background:white;
    border-radius:24px;
    overflow:hidden;
    box-shadow:0 4px 18px rgba(0,0,0,.05);
    border:1px solid #e2e8f0;
}

table{
    width:100%;
    border-collapse:collapse;
}

thead{
    background:#f8fafc;
}

thead th{
    padding:20px;
    text-align:left;
    color:#0f172a;
    font-size:15px;
    font-weight:600;
}

tbody td{
    padding:18px 20px;
    border-top:1px solid #e2e8f0;
    color:#475569;
    font-size:14px;
    vertical-align: middle;
}

tbody tr:hover{
    background:#f8fafc;
}

/* ESTADOS */

.estado{
    padding:8px 14px;
    border-radius:999px;
    font-size:13px;
    font-weight:600;
    display:inline-block;
}

.pagada{
    background:#dcfce7;
    color:#16a34a;
}

.pendiente{
    background:#fef3c7;
    color:#d97706;
}

.anulada{
    background:#fee2e2;
    color:#dc2626;
}

/* ACCIONES */

.acciones{
    display:flex;
    gap:12px;
}

.acciones a{
    text-decoration:none;
    font-size:18px;
    transition:.2s;
}

.acciones a:hover{
    transform:scale(1.15);
}

@media(max-width:900px){
    .topbar{
        flex-direction:column;
        align-items:flex-start;
    }
    .table-container{
        overflow-x:auto;
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
                <h2 class="text-[26px] font-bold text-secondary">Facturas</h2>
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

            <!-- ✅ TU CÓDIGO DE FACTURAS AQUÍ -->
            <div class="topbar">

                <h1>Facturas</h1>

                <div class="actions">

                    <a class="btn btn-secondary"
                    href="../dashboard_admin.php">
                        ⬅ Volver
                    </a>

                    <a class="btn"
                    href="crear_factura.php">
                        ➕ Nueva Factura
                    </a>

                </div>

            </div>

            <div class="table-container">

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

            <td>$<?= number_format($f['precio'], 0, ',', '.') ?></td>

            <td>$<?= number_format($f['subtotal'], 0, ',', '.') ?></td>

            <td>
                <span class="estado <?= $f['estado'] ?>">
                    <?= ucfirst($f['estado']) ?>
                </span>
            </td>

            <td><?= $f['fecha'] ?></td>

            <td class="acciones">

                <a href="editar_factura.php?id=<?= $f['id_facturas'] ?>">
                    ✏️
                </a>

                <a href="eliminar_factura.php?id=<?= $f['id_facturas'] ?>"
                onclick="return confirm('¿Eliminar factura?')">
                    🗑️
                </a>

                <a href="pdf_factura.php?id=<?= $f['id_facturas'] ?>">
                    📄
                </a>
                <a href="enviar_correo.php?id=<?= $f['id_facturas'] ?>">
                    📧
                </a>
            </td>

            </tr>

            <?php } ?>

            </tbody>

            </table>

            </div>

        </div> <!-- Cierra el contenedor principal -->
    </main>

    <!-- ✅ SCRIPTS -->
    <script>
        // 🔎 BUSCADOR DE MÓDULOS
        const inputBusqueda = document.getElementById('buscarModulos');
        const modulos = document.querySelectorAll('.modulo');
        if(inputBusqueda){
        inputBusqueda.addEventListener('input', function() {
            const texto = this.value.toLowerCase().trim();
            modulos.forEach(modulo => {
                const nombre = modulo.getAttribute('data-nombre').toLowerCase();
                modulo.style.display = (texto === '' || nombre.includes(texto)) ? 'block' : 'none';
            });
        });
        }
    </script>

</body>
</html>