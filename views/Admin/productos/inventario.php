<?php
session_start();

// 🔐 SOLO ADMIN
if (!isset($_SESSION["rol"]) || $_SESSION["rol"] != "admin") {
    header("Location: ../Auth/login.php");
    exit();
}

require_once __DIR__ . "/../../../config/db.php";
$conn = Database::Conectar();

// 🔎 OBTENER PRODUCTOS
$sql = "SELECT * FROM productos ORDER BY id_productos DESC";
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
<title>Inventario - InvoicePro</title>

<!-- 🎨 ESTILOS DEL MENÚ Y DISEÑO GENERAL -->
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

<!-- 🎨 TUS ESTILOS ORIGINALES (SE MANTIENEN TAL CUAL) -->
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

/* TOP */

.top-bar{
    display:flex;
    justify-content:space-between;
    align-items:center;
    flex-wrap:wrap;
    gap:15px;
    margin-bottom:30px;
}

.top-bar h1{
    font-size:34px;
    color:#0f172a;
}

.actions{
    display:flex;
    gap:12px;
    flex-wrap:wrap;
}

.top-bar input{
    width:280px;
    padding:14px 18px;
    border:none;
    outline:none;
    border-radius:14px;
    background:white;
    box-shadow:0 2px 10px rgba(0,0,0,.05);
    font-size:14px;
}

.btn{
    border:none;
    padding:14px 20px;
    border-radius:14px;
    cursor:pointer;
    font-weight:600;
    transition:.3s;
    color:white;
}

.btn-primary{
    background:#17345f;
}

.btn-primary:hover{
    background:#264c83;
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
    padding:20px;
    border-top:1px solid #e2e8f0;
    color:#475569;
    font-size:14px;
}

tbody tr{
    transition:.2s;
}

tbody tr:hover{
    background:#f8fafc;
}

/* STOCK COLORS */

.stock-ok{
    color:#16a34a;
    font-weight:600;
}

.stock-low{
    color:#dc2626;
    font-weight:600;
}

/* RESPONSIVE */

@media(max-width:900px){

    body{
        padding:20px;
    }

    .top-bar{
        flex-direction:column;
        align-items:flex-start;
    }

    .top-bar input{
        width:100%;
    }

    .actions{
        width:100%;
    }

    .btn{
        width:100%;
    }

    .table-container{
        overflow-x:auto;
    }
}
.action-buttons{
    display:flex;
    gap:8px;
}

.btn-edit{
    background:#2563eb;
    color:white;
    border:none;
    padding:8px 14px;
    border-radius:10px;
    cursor:pointer;
    font-size:13px;
    font-weight:600;
    transition:.3s;
}

.btn-edit:hover{
    background:#1d4ed8;
}

.btn-delete{
    background:#dc2626;
    color:white;
    border:none;
    padding:8px 14px;
    border-radius:10px;
    cursor:pointer;
    font-size:13px;
    font-weight:600;
    transition:.3s;
}

.btn-delete:hover{
    background:#b91c1c;
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

    <!-- 🟩 CONTENIDO PRINCIPAL (TU CÓDIGO ORIGINAL AQUÍ) -->
    <main class="flex-1 h-screen overflow-y-auto">

        <!-- Encabezado superior -->
        <header class="bg-white px-8 py-4 border-b border-gray-200 flex items-center justify-between">
            <div>
                <h2 class="text-[26px] font-bold text-secondary">Inventario</h2>
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

            <!-- ✅ AQUÍ EMPIEZA TU CÓDIGO DE INVENTARIO -->
            <div class="top-bar">

                <h1>Inventario</h1>

                <input type="text" id="buscar" placeholder="Buscar producto...">

                <div class="actions">

                    <button class="btn btn-secondary"
                    onclick="location.href='../dashboard_admin.php'">
                        ⬅ Volver
                    </button>

                    <button class="btn btn-primary"
                    onclick="location.href='agregar_producto.php'">
                        ➕ Agregar producto
                    </button>

                </div>

            </div>

            <div class="table-container">

            <table id="tablaProductos">

            <thead>
            <tr>
            <th>ID</th>
            <th>Nombre</th>
            <th>Stock</th>
            <th>Precio Venta</th>
            <th>Precio Compra</th>
            <th>Mínimo Stock</th>
            <th>Acciones</th>
            </tr>
            </thead>

            <tbody>

            <?php while($p = $result->fetch_assoc()): ?>

            <tr>

            <td><?= $p["id_productos"] ?></td>

            <td><?= $p["nombre"] ?></td>

            <td class="<?= $p["stock"] <= $p["min_stock"] ? 'stock-low' : 'stock-ok' ?>">
                <?= $p["stock"] ?>
            </td>

            <td>$<?= number_format($p["precio_venta"], 0, ',', '.') ?></td>

            <td>$<?= number_format($p["precio_compra"], 0, ',', '.') ?></td>

            <td><?= $p["min_stock"] ?></td>

            <td>
                <div class="action-buttons">

                    <button
                        class="btn-edit"
                        onclick="location.href='editar_producto.php?id=<?= $p['id_productos'] ?>'">
                        ✏ Editar
                    </button>

                    <button
                        class="btn-delete"
                        onclick="eliminarProducto(<?= $p['id_productos'] ?>)">
                        🗑 Eliminar
                    </button>

                </div>
            </td>

            </tr>

            <?php endwhile; ?>

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

        // 🔎 TU BUSCADOR DE PRODUCTOS
        document.getElementById("buscar").addEventListener("keyup", function() {
            let filtro = this.value.toLowerCase();
            let filas = document.querySelectorAll("#tablaProductos tbody tr");
            filas.forEach(fila => {
                let texto = fila.textContent.toLowerCase();
                fila.style.display = texto.includes(filtro) ? "" : "none";
            });
        });

        // 🗑 TU FUNCIÓN ELIMINAR
        function eliminarProducto(id){
            if(confirm("¿Está seguro de eliminar este producto?")){
                window.location.href = "eliminar_producto.php?id=" + id;
            }
        }
    </script>

</body>
</html>