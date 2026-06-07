<?php
session_start();

// 🔒 PERMITIR admin y empleado
if (!isset($_SESSION["rol"]) || 
   ($_SESSION["rol"] != "admin" && $_SESSION["rol"] != "empleado")) {
    header("Location: ../Auth/login.php");
    exit();
}

$esAdmin = $_SESSION["rol"] == "admin";
$id_usuario = $_SESSION["id_usuario"]; // ID de la sesión

require_once "../../../config/db.php";
$conn = Database::Conectar();

// 🔍 Consulta con nombres CORRECTOS de tu tabla
$sql = "SELECT * 
        FROM usuario 
        WHERE id_usuario = ?";
$stmt = $conn->prepare($sql);
$stmt->bind_param("i", $id_usuario);
$stmt->execute();
$usuario = $stmt->get_result()->fetch_assoc();

// 📋 DATOS PARA EL MENÚ (CORREGIDO)
$nombre_usuario_completo = ($usuario['nombre_usuario'] ?? '') . ' ' . ($usuario['apellido_usuario'] ?? '');
$rol_usuario    = $_SESSION["rol"];
$base_url = "/MVC-PRU/views/" . ($esAdmin ? "Admin/" : "Empleado/");
?>

<!DOCTYPE html>
<html lang="es">
<head>
<meta charset="UTF-8">
<title>Mi Perfil | InvoicePro</title>

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
                    azul: '#16396b',
                    gris: '#f3f4f6',
                    surface: '#ffffff',
                    border: '#e2e8f0',
                    muted: '#64748b',
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

<style>
*{
    margin:0;
    padding:0;
    box-sizing:border-box;
    font-family:'Segoe UI', sans-serif;
}

body{
    background:#f3f4f6;
    color:#0f172a;
}

.contenedor-principal {
    padding:30px;
}

/* HEADER */
.header{
    display:flex;
    justify-content:space-between;
    align-items:center;
    margin-bottom:30px;
    gap:20px;
    flex-wrap:wrap;
}

.title h1{
    font-size:45px;
    font-weight:800;
    margin-bottom:5px;
}

.title p{
    color:#64748b;
    font-size:18px;
}

.btn{
    background:#16396b;
    color:white;
    text-decoration:none;
    padding:14px 22px;
    border-radius:14px;
    font-weight:600;
    transition:.3s;
    display:inline-block;
    border:none;
    cursor:pointer;
}

.btn:hover{
    transform:translateY(-2px);
    color:white;
}

.btn-back{
    background:#64748b;
}

.btn-back:hover{
    background:#475569;
}

/* TARJETA DE PERFIL */
.card-perfil{
    background:white;
    border-radius:28px;
    padding:40px;
    box-shadow:0 2px 10px rgba(0,0,0,0.05);
    max-width:800px;
    margin:auto;
}

.perfil-header{
    display:flex;
    align-items:center;
    gap:20px;
    margin-bottom:30px;
    padding-bottom:20px;
    border-bottom:1px solid #f1f5f9;
}

.perfil-icono{
    width:80px;
    height:80px;
    border-radius:50%;
    background:#16396b;
    color:white;
    display:flex;
    align-items:center;
    justify-content:center;
    font-size:35px;
}

.perfil-info h2{
    font-size:26px;
    font-weight:700;
    margin-bottom:5px;
}

.perfil-info .rol{
    background:#f3f4f6;
    color:#16396b;
    padding:6px 12px;
    border-radius:20px;
    font-size:13px;
    font-weight:600;
    text-transform:uppercase;
}

/* LISTA DE DATOS */
.datos-grid{
    display:grid;
    grid-template-columns:repeat(auto-fit, minmax(300px, 1fr));
    gap:25px;
}

.dato-item{
    display:flex;
    flex-direction:column;
    gap:4px;
}

.dato-label{
    font-size:13px;
    color:#64748b;
    font-weight:600;
    text-transform:uppercase;
    letter-spacing:0.5px;
}

.dato-valor{
    font-size:16px;
    font-weight:500;
    color:#0f172a;
    padding:10px 14px;
    background:#f8fafc;
    border-radius:10px;
    border-left:3px solid #16396b;
}

/* RESPONSIVE */
@media(max-width:768px){
    .contenedor-principal{
        padding:20px;
    }
    .title h1{
        font-size:35px;
    }
    .card-perfil{
        padding:25px;
    }
    .perfil-header{
        flex-direction:center;
        text-align:center;
        flex-direction:column;
    }
}
</style>

</head>

<body class="min-h-screen flex overflow-hidden">

    <!-- 🟦 MENÚ LATERAL (IGUAL AL RESTO) -->
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
                <!-- ✅ NOMBRE CORRECTO -->
                <p class="text-sm font-medium"><?= htmlspecialchars(trim($nombre_usuario_completo)) ?></p>
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
                    <a href="<?= $base_url ?>garantias/iniciogarantias.php" class="menu-item">
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

                <!-- 👤 OPCIÓN PERFIL (ACTIVA) -->
                <li>
                    <a href="<?= $base_url ?>perfil/perfil.php" class="menu-item menu-activo">
                        <i class="fa fa-id-card w-5 text-center"></i>
                        <span>Mi Perfil</span>
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
    <main class="flex-1 h-screen overflow-y-auto contenedor-principal">

        <!-- HEADER -->
        <div class="header">
            <div class="title">
                <h1>Mi Perfil</h1>
                <p>Tus datos de registro y cuenta.</p>
            </div>
            <a class="btn btn-back" href="<?= $base_url . ($esAdmin ? 'dashboard_admin.php' : 'dashboard_empleado.php') ?>">
                ← Volver al inicio
            </a>
        </div>

        <!-- TARJETA CON LOS DATOS -->
        <div class="card-perfil">
            
            <div class="perfil-header">
                <div class="perfil-icono">
                    <i class="fa fa-user"></i>
                </div>
                <div class="perfil-info">
                    <!-- ✅ NOMBRE Y APELLIDO CORRECTOS -->
                    <h2><?= htmlspecialchars($usuario['nombre_usuario'] . ' ' . $usuario['apellido_usuario']) ?></h2>
                    <span class="rol"><?= ucfirst($rol_usuario) ?></span>
                </div>
            </div>

            <div class="datos-grid">
                <!-- 🔽 TODOS LOS CAMPOS CON NOMBRES EXACTOS DE TU BD -->
                
                <div class="dato-item">
                    <span class="dato-label">ID de Usuario</span>
                    <span class="dato-valor">#<?= $usuario['id_usuario'] ?></span>
                </div>

                <div class="dato-item">
                    <span class="dato-label">Nombre</span>
                    <span class="dato-valor"><?= htmlspecialchars($usuario['nombre_usuario']) ?></span>
                </div>

                <div class="dato-item">
                    <span class="dato-label">Apellido</span>
                    <span class="dato-valor"><?= htmlspecialchars($usuario['apellido_usuario']) ?></span>
                </div>

                <div class="dato-item">
                    <span class="dato-label">Teléfono</span>
                    <span class="dato-valor"><?= htmlspecialchars($usuario['telefono']) ?></span>
                </div>

                <div class="dato-item">
                    <span class="dato-label">Correo Electrónico</span>
                    <span class="dato-valor"><?= htmlspecialchars($usuario['correo']) ?></span>
                </div>

                <div class="dato-item">
                    <span class="dato-label">Contraseña</span>
                    <span class="dato-valor">•••••••• (oculta por seguridad)</span>
                </div>

            </div>

        </div>

    </main>

</body>
</html>