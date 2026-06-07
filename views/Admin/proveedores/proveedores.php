<?php
session_start();

// 🔐 SOLO ADMIN
if (!isset($_SESSION["rol"]) || $_SESSION["rol"] != "admin") {
    header("Location: ../Auth/login.php");
    exit();
}

require_once "../../../config/db.php";
$conn = Database::conectar();

$data = $conn->query("SELECT * FROM proveedores ORDER BY id_proveedores DESC");

// 📋 DATOS PARA EL MENÚ
$nombre_usuario = isset($_SESSION["nombre"]) ? $_SESSION["nombre"] : "Administrador";
$rol_usuario    = $_SESSION["rol"];
$base_url = "/MVC-PRU/views/Admin/";
?>

<!DOCTYPE html>
<html lang="es">
<head>
<meta charset="UTF-8">
<title>Proveedores - InvoicePro</title>

<!-- 🎨 ESTILOS DEL MENÚ Y ESTRUCTURA GENERAL -->
<script src="https://cdn.tailwindcss.com"></script>
<link rel="preconnect" href="https://fonts.googleapis.com">
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
                    'azul-oscuro': '#0f4c81', // Tu color original
                },
                fontFamily: { 
                    inter: ['Inter', 'sans-serif'],
                    segoe: ['"Segoe UI"', 'sans-serif']
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
        .sombra-suave { box-shadow: 0 2px 12px rgba(0,0,0,0.05); }
        .menu-activo { @apply bg-activo text-white font-medium; }
        .menu-item { @apply flex items-center gap-3 px-3 py-2.5 rounded-lg transition-all text-gray-300 hover:bg-activo hover:text-white; }
    }
</style>

<!-- ✅ TUS ESTILOS ORIGINALES (ASEGURADOS Y CORREGIDOS) -->
<style>
    *{
    margin:0;
    padding:0;
    box-sizing:border-box;
}

body{
    font-family:'Segoe UI',sans-serif !important;
    background:#f4f7fb !important;
    color:#1e293b !important;
}

.container{
    max-width:1200px;
    margin:auto;
    background:white;
    padding:30px;
    border-radius:20px;
    box-shadow:0 10px 30px rgba(15,76,129,0.08);
}

/* TOPBAR */

.topbar{
    display:flex;
    justify-content:space-between;
    align-items:center;
    margin-bottom:30px;
    flex-wrap:wrap;
    gap:15px;
}

.topbar h1{
    font-size:30px;
    color:#0f4c81;
    font-weight:700;
}

.acciones-top{
    display:flex;
    gap:12px;
}

.btn{
    background:#0f4c81;
    color:white !important;
    text-decoration:none;
    padding:12px 18px;
    border-radius:12px;
    font-size:14px;
    font-weight:600;
    transition:0.2s;
    display:inline-block;
    text-align:center;
    border:none;
    cursor:pointer;
}

.btn:hover{
    background:#1565a9;
    transform:translateY(-2px);
    color:white !important;
}

.btn-secundario{
    background:#e2e8f0;
    color:#0f4c81 !important;
}

.btn-secundario:hover{
    background:#cbd5e1;
}

/* TABLA */

table{
    width:100%;
    border-collapse:collapse;
}

thead{
    background:#f8fafc;
}

th{
    padding:16px;
    text-align:left;
    color:#64748b;
    font-size:13px;
    text-transform:uppercase;
    letter-spacing:.5px;
    border-bottom:1px solid #e2e8f0;
}

td{
    padding:18px 16px;
    border-bottom:1px solid #edf2f7;
    font-size:14px;
    vertical-align: middle;
}

tr:hover{
    background:#f8fbff !important;
}

/* BOTONES TABLA */

.acciones{
    display:flex;
    gap:10px;
}

.btn-editar,
.btn-eliminar{
    text-decoration:none;
    padding:8px 12px;
    border-radius:10px;
    font-size:13px;
    font-weight:600;
    transition:.2s;
    border:none;
    cursor:pointer;
}

.btn-editar{
    background:#dbeafe;
    color:#2563eb !important;
}

.btn-editar:hover{
    background:#bfdbfe;
}

.btn-eliminar{
    background:#fee2e2;
    color:#dc2626 !important;
}

.btn-eliminar:hover{
    background:#fecaca;
}

.sin-datos{
    text-align:center;
    color:#94a3b8;
    padding:30px;
}

/* RESPONSIVE */

@media(max-width:768px){
    body{
        padding:20px;
    }
    .topbar{
        flex-direction:column;
        align-items:flex-start;
    }

    table{
        display:block;
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
                <h2 class="text-[26px] font-bold text-secondary">Proveedores</h2>
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

            <!-- ✅ TU CÓDIGO DE PROVEEDORES AQUÍ -->
            <div class="container">

                <div class="topbar">
                    <h1>Proveedores</h1>

                    <div class="acciones-top">
                        <a class="btn" href="crear_proveedor.php">+ Nuevo proveedor</a>
                        <a class="btn btn-secundario" href="../dashboard_admin.php">⬅ Volver</a>
                    </div>
                </div>

                <table>
                    <thead>
                        <tr>
                            <th>ID</th>
                            <th>Nombre</th>
                            <th>Teléfono</th>
                            <th>Correo</th>
                            <th>Acciones</th>
                        </tr>
                    </thead>

                    <tbody>

                    <?php if($data && $data->num_rows > 0){ ?>
                        <?php while($p = $data->fetch_assoc()){ ?>

                        <tr>
                            <td><?= $p['id_proveedores'] ?></td>
                            <td><?= htmlspecialchars($p['nombre']) ?></td>
                            <td><?= htmlspecialchars($p['telefono']) ?></td>
                            <td><?= htmlspecialchars($p['correo']) ?></td>
                            <td class="acciones">
                                <a class="btn-editar" href="editar_proveedor.php?id=<?= $p['id_proveedores'] ?>">✏️ Editar</a>
                                <a class="btn-eliminar" href="eliminar_proveedor.php?id=<?= $p['id_proveedores'] ?>" onclick="return confirm('¿Eliminar proveedor?')">🗑️ Eliminar</a>
                            </td>
                        </tr>

                        <?php } ?>
                    <?php } else { ?>

                        <tr>
                            <td colspan="5" class="sin-datos">
                                No hay proveedores registrados
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