<?php
session_start();

// 🔒 ROLES
if (!isset($_SESSION["rol"]) ||
   ($_SESSION["rol"] != "admin" && $_SESSION["rol"] != "empleado")) {
    header("Location: ../Auth/login.php");
    exit();
}

$esAdmin = $_SESSION["rol"] == "admin";

require_once "../../../config/db.php";
$conn = Database::Conectar();

// 🔄 ACTUALIZAR A VENCIDA
$conn->query("
    UPDATE garantias
    SET estado = 'vencida'
    WHERE fecha_fin < CURDATE()
    AND estado != 'vencida'
");

// 📊 CONTADORES
$vencidas = $conn->query("
    SELECT COUNT(*) as total
    FROM garantias
    WHERE estado = 'vencida'
")->fetch_assoc()['total'];

$porVencer = $conn->query("
    SELECT COUNT(*) as total
    FROM garantias
    WHERE fecha_fin BETWEEN CURDATE()
    AND DATE_ADD(CURDATE(), INTERVAL 3 DAY)
    AND estado != 'vencida'
")->fetch_assoc()['total'];

// 🔎 LISTA
$garantias = $conn->query("
    SELECT g.*, p.nombre AS nombre_producto
    FROM garantias g
    JOIN productos p ON g.id_producto = p.id_productos
    ORDER BY g.id_garantia DESC
");

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

<title>Garantías | InvoicePro</title>

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
                    gris: '#f3f4f6',
                    surface: '#ffffff',
                    border: '#e2e8f0',
                    muted: '#64748b',
                    danger: '#dc2626',
                    warning: '#ea580c',
                    pendienteBg: '#fef3c7',
                    pendienteText: '#92400e',
                    revisionBg: '#dbeafe',
                    revisionText: '#1d4ed8',
                    resueltoBg: '#dcfce7',
                    resueltoText: '#166534',
                    vencidaBg: '#e5e7eb',
                    vencidaText: '#374151',
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
    margin-bottom:30px !important;
    gap:20px !important;
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

/* TOP ACTIONS */

.actions{
    display:flex !important;
    align-items:center !important;
    gap:15px !important;
    flex-wrap:wrap !important;
}

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

.btn-back{
    background:#64748b !important;
}

.btn-back:hover{
    background:#475569 !important;
}

.btn{
    background:#16396b !important;
    color:white !important;
    text-decoration:none !important;
    padding:15px 22px !important;
    border-radius:14px !important;
    font-weight:600 !important;
    transition:.3s !important;
    display:inline-block !important;
}

.btn:hover{
    transform:translateY(-2px) !important;
    color:white !important;
}

/* STATS */

.stats{
    display:grid !important;
    grid-template-columns:repeat(auto-fit,minmax(240px,1fr)) !important;
    gap:20px !important;
    margin-bottom:30px !important;
}

.card-stat{
    background:white !important;
    padding:25px !important;
    border-radius:24px !important;
    box-shadow:0 2px 10px rgba(0,0,0,0.04) !important;
}

.card-stat h3{
    font-size:17px !important;
    color:#64748b !important;
    margin-bottom:10px !important;
}

.card-stat .number{
    font-size:38px !important;
    font-weight:700 !important;
}

.vencidas .number{
    color:#dc2626 !important;
}

.porvencer .number{
    color:#ea580c !important;
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

/* ESTADOS */

.estado{
    padding:8px 14px !important;
    border-radius:12px !important;
    font-size:13px !important;
    font-weight:600 !important;
    display:inline-block !important;
}

.estado-pendiente{
    background:#fef3c7 !important;
    color:#92400e !important;
}

.estado-revision{
    background:#dbeafe !important;
    color:#1d4ed8 !important;
}

.estado-resuelto{
    background:#dcfce7 !important;
    color:#166534 !important;
}

.estado-vencida{
    background:#e5e7eb !important;
    color:#374151 !important;
}

tr.vencida{
    opacity:.7 !important;
}

/* BOTON VER */

.btn-ver{
    background:#16396b !important;
    color:white !important;
    text-decoration:none !important;
    padding:10px 14px !important;
    border-radius:10px !important;
    font-size:14px !important;
    font-weight:600 !important;
    display:inline-block !important;
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

    .search{
        width:100% !important;
    }

    .title h1{
        font-size:35px !important;
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
                    <a href="<?= $base_url ?>inventario/inventario.php" class="menu-item">
                        <i class="fa fa-cube w-5 text-center"></i>
                        <span>Inventario</span>
                    </a>
                </li>
                <!-- Garantías -->
                <li>
                    <a href="<?= $base_url ?>garantias/iniciogarantias.php" class="menu-item menu-activo">
                        <i class="fa fa-wrench w-5 text-center"></i>
                        <span>Garantías</span>
                    </a>
                </li>
                <!-- Clientes -->
                <li>
                    <a href="<?= $base_url ?>clientes/clientes_empleado.php" class="menu-item">
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
                <h1>Garantías</h1>
                <p>Gestiona solicitudes y procesos de garantías.</p>
            </div>

            <div class="actions">

                <a class="btn btn-back" href="<?= $base_url . ($esAdmin ? 'dashboard_admin.php' : 'dashboard_empleado.php') ?>">
                    ← Volver
                </a>

                <div class="search">
                    <input type="text" id="buscar" placeholder="Buscar garantía...">
                </div>

                <a class="btn" href="crear_garantia.php">
                    + Nueva garantía
                </a>

            </div>
        </div>

        <!-- STATS -->
        <div class="stats">

            <div class="card-stat vencidas">
                <h3>Garantías vencidas</h3>
                <div class="number"><?= $vencidas ?></div>
            </div>

            <div class="card-stat porvencer">
                <h3>Por vencer (3 días)</h3>
                <div class="number"><?= $porVencer ?></div>
            </div>

        </div>

        <!-- TABLA -->
        <div class="table-box">

            <h2 class="table-title">Lista de garantías</h2>

            <table id="tablaGarantias">

                <thead>
                    <tr>
                        <th>ID</th>
                        <th>Factura</th>
                        <th>Producto</th>
                        <th>Motivo</th>
                        <th>Solución</th>
                        <th>Estado</th>
                        <th>Inicio</th>
                        <th>Fin</th>
                        <th>Acciones</th>
                    </tr>
                </thead>

                <tbody>
                <?php while($g = $garantias->fetch_assoc()){ ?>

                    <tr class="<?= $g['estado'] == 'vencida' ? 'vencida' : '' ?>">

                        <td>#<?= $g['id_garantia'] ?></td>
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
                        } elseif($g['estado'] == 'resuelto'){
                            echo "<span class='estado estado-resuelto'>Resuelto</span>";
                        } else {
                            echo "<span class='estado estado-vencida'>Vencida</span>";
                        }
                        ?>
                        </td>

                        <td><?= $g['fecha_inicio'] ?></td>
                        <td><?= $g['fecha_fin'] ?></td>

                        <td>
                            <a class="btn-ver" href="ver_garantia.php?id=<?= $g['id_garantia'] ?>">
                                Ver
                            </a>
                        </td>

                    </tr>

                <?php } ?>
                </tbody>

            </table>

        </div>

    </main>

<script>

// 🔎 BUSCADOR
document.getElementById("buscar").addEventListener("keyup", function(){
    let filtro = this.value.toLowerCase();
    let filas = document.querySelectorAll("#tablaGarantias tbody tr");
    filas.forEach(fila => {
        let texto = fila.textContent.toLowerCase();
        fila.style.display = texto.includes(filtro) ? "" : "none";
    });
});

</script>

</body>
</html>