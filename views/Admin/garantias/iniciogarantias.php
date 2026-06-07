<?php
session_start();

// 🔐 SOLO ADMIN
if (!isset($_SESSION["rol"]) || $_SESSION["rol"] != "admin") {
    header("Location: ../Auth/login.php");
    exit();
}

require_once "../../../config/db.php";
$conn = Database::Conectar();

$garantias = $conn->query("
    SELECT g.*, p.nombre AS nombre_producto
    FROM garantias g
    JOIN productos p ON g.id_producto = p.id_productos
    ORDER BY g.id_garantia DESC
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
<title>Garantías - InvoicePro</title>

<!-- 🎨 ESTILOS DEL MENÚ Y ESTRUCTURA GENERAL -->
<script src="https://cdn.tailwindcss.com"></script>
<link href="https://fonts.googleapis.com/css2?family=Inter:wght@400;500;600;700&display=swap" rel="stylesheet">
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
    @import url('https://fonts.googleapis.com/css2?family=Inter:wght@400;500;600;700&display=swap');

*{
    margin:0;
    padding:0;
    box-sizing:border-box;
}

body{
    font-family:'Inter',sans-serif;
    background:#f4f7fb;
    color:#1e293b;
}

.container{
    max-width:100%;
    margin:auto;
}

.topbar{
    display:flex;
    justify-content:space-between;
    align-items:center;
    margin-bottom:30px;
    flex-wrap:wrap;
    gap:15px;
}

.topbar h1{
    font-size:32px;
    font-weight:700;
    color:#0f172a;
}

.btn{
    background:#1e3a5f;
    color:white;
    text-decoration:none;
    padding:12px 18px;
    border-radius:12px;
    font-size:14px;
    font-weight:600;
    transition:.3s;
    display:inline-block;
    text-align:center;
}

.btn:hover{
    background:#16304d;
    transform:translateY(-2px);
    color:white;
}

.alert{
    padding:14px 18px;
    border-radius:12px;
    margin-bottom:20px;
    font-size:14px;
    font-weight:500;
}

.success{
    background:#dcfce7;
    color:#166534;
}

table{
    width:100%;
    border-collapse:collapse;
    background:white;
    border-radius:20px;
    overflow:hidden;
    box-shadow:0 4px 20px rgba(15,23,42,.06);
}

thead{
    background:#f8fafc;
}

th{
    padding:18px;
    text-align:left;
    font-size:13px;
    color:#64748b;
    text-transform:uppercase;
    font-weight:600;
}

td{
    padding:18px;
    border-top:1px solid #e2e8f0;
    font-size:14px;
    vertical-align:middle;
}

tr:hover{
    background:#f8fafc;
}

.estado{
    padding:8px 14px;
    border-radius:30px;
    font-size:12px;
    font-weight:600;
    display:inline-block;
}

.estado-pendiente{
    background:#fef3c7;
    color:#92400e;
}

.estado-revision{
    background:#dbeafe;
    color:#1d4ed8;
}

.estado-resuelto{
    background:#dcfce7;
    color:#166534;
}

.btn-ver{
    text-decoration:none;
    background:#e0edff;
    color:#2563eb;
    padding:10px 14px;
    border-radius:10px;
    font-size:13px;
    font-weight:600;
    transition:.3s;
    margin-right:5px;
    display:inline-block;
}

.btn-ver:hover{
    background:#2563eb;
    color:white;
}

a{
    text-decoration:none;
    font-weight:500;
    transition:.2s;
}

a:hover{
    text-decoration:underline;
}

.action-buttons a{
    margin:0 3px;
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
                <h2 class="text-[26px] font-bold text-secondary">Garantías</h2>
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

            <!-- ✅ TU CÓDIGO DE GARANTÍAS AQUÍ -->
            <div class="container">

                <div class="topbar">
                    <h1>Garantías</h1>
                    <div class="flex gap-3">
                        <a class="btn" href="crear_garantia.php">+ Nueva garantía</a>
                        <a class="btn btn-secondary" href="../dashboard_admin.php">Volver al inicio</a>
                    </div>
                </div>

                <?php if(isset($_GET['msg']) && $_GET['msg'] == "creado"){ ?>
                    <div class="alert success">Garantía creada correctamente ✅</div>
                <?php } ?>

                <table>
                    <thead>
                        <tr>
                            <th>ID</th>
                            <th>Factura</th>
                            <th>Producto</th>
                            <th>Motivo</th>
                            <th>Solución</th>
                            <th>Estado</th>
                            <th>Fecha inicio</th>
                            <th>Fecha fin</th>
                            <th>Acción</th>
                        </tr>
                    </thead>
                    <tbody>
                    <?php while($g = $garantias->fetch_assoc()){ ?>
                        <tr>
                            <td><?= $g['id_garantia'] ?></td>
                            <td>#<?= $g['id_facturas'] ?></td>
                            <td><?= htmlspecialchars($g['nombre_producto']) ?></td>
                            <td><?= ucfirst($g['motivo']) ?></td>
                            <td><?= ucfirst($g['solucion']) ?></td>
                            <td>
                                <?php
                                if($g['estado'] == 'pendiente'){
                                    echo "<span class='estado estado-pendiente'>Pendiente</span>";
                                } elseif($g['estado'] == 'en_revision'){
                                    echo "<span class='estado estado-revision'>En revisión</span>";
                                } else {
                                    echo "<span class='estado estado-resuelto'>Resuelto</span>";
                                }
                                ?>
                            </td>
                            <td><?= $g['fecha_inicio'] ?></td>
                            <td><?= $g['fecha_fin'] ?></td>
                            <td class="action-buttons">
                                <a class="btn-ver" href="ver_garantia.php?id=<?= $g['id_garantia'] ?>">Ver</a>

                                <a href="editar_garantia.php?id=<?= $g['id_garantia'] ?>" style="color:#b45309;">Editar</a>

                                <a href="eliminar_garantia.php?id=<?= $g['id_garantia'] ?>" onclick="return confirm('¿Eliminar garantía?');" style="color:#dc2626;">Eliminar</a>
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