<?php
session_start();

// 🔐 SOLO EMPLEADOS
if (!isset($_SESSION["rol"]) || $_SESSION["rol"] != "empleado") {
    header("Location: ../Auth/login.php");
    exit();
}

require_once "../../../models/clientes.php";

$model = new Cliente();

$errores = [];

// 🔥 GUARDAR CLIENTE
if($_SERVER['REQUEST_METHOD'] == 'POST'){

    $nombre   = trim($_POST['nombre']);
    $cedula   = trim($_POST['cedula']);
    $telefono = trim($_POST['telefono']);

    // =========================
    // VALIDACIONES
    // =========================

    if(empty($nombre)){
        $errores[] = "El nombre es obligatorio";
    }

    if(strlen($nombre) < 3){
        $errores[] = "El nombre es demasiado corto";
    }

    if(empty($cedula)){
        $errores[] = "La cédula es obligatoria";
    }

    if(!is_numeric($cedula)){
        $errores[] = "La cédula debe ser numérica";
    }

    if(!empty($telefono) && !is_numeric($telefono)){
        $errores[] = "El teléfono debe ser numérico";
    }

    // =========================
    // VALIDAR DUPLICADO
    // =========================

    if(empty($errores)){

        require_once "../../../config/db.php";

        $conn = Database::Conectar();

        $stmt = $conn->prepare("
        SELECT id_clientes
        FROM clientes
        WHERE cedula = ?
        ");

        $stmt->bind_param("s", $cedula);

        $stmt->execute();

        $result = $stmt->get_result();

        if($result->num_rows > 0){
            $errores[] = "La cédula ya existe";
        }

        $stmt->close();
    }

    // =========================
    // GUARDAR
    // =========================

    if(empty($errores)){

        $guardar = $model->crear(
            $nombre,
            $cedula,
            $telefono
        );

        if($guardar){

            echo "
            <script>
            alert('Cliente guardado correctamente');
            window.location='clientes_empleado.php';
            </script>
            ";

            exit();

        } else {

            $errores[] = "Error al guardar cliente";
        }
    }
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

<title>Clientes | InvoicePro</title>

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
                    danger: '#b91c1c',
                    dangerLight: '#fee2e2',
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

/* CONTAINER */

.container{
    max-width:1200px !important;
    margin:auto !important;
}

/* HEADER */

.header{
    display:flex !important;
    justify-content:space-between !important;
    align-items:center !important;
    gap:20px !important;
    margin-bottom:30px !important;
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

/* BOTONES */

.actions{
    display:flex !important;
    gap:15px !important;
    flex-wrap:wrap !important;
}

.btn{
    background:#16396b !important;
    color:white !important;
    text-decoration:none !important;
    padding:14px 22px !important;
    border-radius:14px !important;
    font-weight:600 !important;
    transition:.3s !important;
    border:none !important;
    cursor:pointer !important;
    display:inline-block !important;
}

.btn:hover{
    transform:translateY(-2px) !important;
    color:white !important;
}

.btn-back{
    background:#64748b !important;
}

.btn-back:hover{
    background:#475569 !important;
}

/* CARD */

.card{
    background:white !important;
    border-radius:28px !important;
    padding:30px !important;
    box-shadow:0 2px 10px rgba(0,0,0,0.05) !important;
    margin-bottom:30px !important;
}

/* FORM */

form{
    display:grid !important;
    grid-template-columns:1fr 1fr !important;
    gap:20px !important;
}

.full{
    grid-column:1 / 3 !important;
}

/* INPUTS */

input{
    width:100% !important;
    padding:16px !important;
    border:none !important;
    border-radius:16px !important;
    background:#f8fafc !important;
    font-size:15px !important;
    outline:none !important;
    transition:.3s !important;
    border:2px solid transparent !important;
}

input:focus{
    border-color:#16396b !important;
    background:white !important;
}

/* ALERTAS */

.alert{
    background:#fee2e2 !important;
    color:#b91c1c !important;
    padding:14px !important;
    border-radius:12px !important;
    margin-bottom:15px !important;
    font-weight:500 !important;
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

/* RESPONSIVE */

@media(max-width:768px){

    .contenedor-principal {
        padding:20px !important;
    }

    .header{
        flex-direction:column !important;
        align-items:flex-start !important;
    }

    .title h1{
        font-size:35px !important;
    }

    form{
        grid-template-columns:1fr !important;
    }

    .full{
        grid-column:auto !important;
    }

    .card{
        padding:20px !important;
    }

    .table-box{
        padding:15px !important;
    }

    .actions{
        width:100% !important;
    }

    .actions a{
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
                    <a href="<?= $base_url ?>dashboard_empleado.php" class="menu-item">
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
                    <a href="<?= $base_url ?>clientes/clientes_empleado.php" class="menu-item menu-activo">
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

    <!-- 🟩 CONTENIDO PRINCIPAL (TU CÓDIGO AQUÍ DENTRO) -->
    <main class="flex-1 h-screen overflow-y-auto contenedor-principal">

        <div class="container">

            <!-- HEADER -->
            <div class="header">

                <div class="title">
                    <h1>Clientes</h1>
                    <p>Gestiona y registra clientes del sistema.</p>
                </div>

                <!-- BOTÓN AGREGAR CLIENTE -->
                <div class="actions">
                    <a href="<?= $base_url ?>dashboard_empleado.php" class="btn btn-back">
                        ← Volver
                    </a>
                    <a href="crearcliente.php" class="btn">
                        + Nuevo cliente
                    </a>
                </div>

            </div>


            <!-- MENSAJES DE ERROR -->
            <?php if(!empty($errores)): ?>
                <div class="card">
                    <?php foreach($errores as $e): ?>
                        <div class="alert"><?= $e ?></div>
                    <?php endforeach; ?>
                </div>
            <?php endif; ?>


            <!-- FORMULARIO DE REGISTRO -->
            <div class="card">

                <form method="POST" action="">

                    <div>
                        <label style="display:block; margin-bottom:8px; font-weight:600;">Nombre completo</label>
                        <input type="text" name="nombre" value="<?= isset($_POST['nombre']) ? htmlspecialchars($_POST['nombre']) : '' ?>" required>
                    </div>

                    <div>
                        <label style="display:block; margin-bottom:8px; font-weight:600;">Cédula / Identificación</label>
                        <input type="text" name="cedula" value="<?= isset($_POST['cedula']) ? htmlspecialchars($_POST['cedula']) : '' ?>" required>
                    </div>

                    <div class="full">
                        <label style="display:block; margin-bottom:8px; font-weight:600;">Teléfono (opcional)</label>
                        <input type="text" name="telefono" value="<?= isset($_POST['telefono']) ? htmlspecialchars($_POST['telefono']) : '' ?>">
                    </div>

                    <div class="full" style="text-align:right;">
                        <button type="submit" class="btn">Guardar cliente</button>
                    </div>

                </form>

            </div>


            <!-- TABLA -->
            <?php
            require_once "../../../config/db.php";
            $conn = Database::Conectar();
            $clientes = $conn->query("
            SELECT *
            FROM clientes
            ORDER BY id_clientes DESC
            ");
            ?>

            <div class="table-box">

                <h2 class="table-title">Lista de clientes</h2>

                <table>
                    <thead>
                        <tr>
                            <th>ID</th>
                            <th>Nombre</th>
                            <th>Cédula</th>
                            <th>Teléfono</th>
                        </tr>
                    </thead>

                    <tbody>
                    <?php while($c = $clientes->fetch_assoc()){ ?>
                        <tr>
                            <td>#<?= $c['id_clientes'] ?></td>
                            <td><?= htmlspecialchars($c['nombre']) ?></td>
                            <td><?= htmlspecialchars($c['cedula']) ?></td>
                            <td><?= htmlspecialchars($c['telefono']) ?></td>
                        </tr>
                    <?php } ?>
                    </tbody>
                </table>

            </div>

        </div>

    </main>

</body>
</html>