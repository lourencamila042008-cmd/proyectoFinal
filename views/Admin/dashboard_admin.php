<?php
session_start();

// 🔐 SOLO ADMIN
if (!isset($_SESSION["rol"]) || $_SESSION["rol"] != "admin") {
    header("Location: /MVC-PRU/views/Auth/login.php");
    exit();
}
?>

<!DOCTYPE html>
<html lang="es">
<head>
<meta charset="UTF-8">
<meta name="viewport" content="width=device-width, initial-scale=1.0">
<title>Panel Administrador - InvoicePro</title>

<link rel="preconnect" href="https://fonts.googleapis.com">
<link href="https://fonts.googleapis.com/css2?family=Inter:wght@300;400;500;600;700&display=swap" rel="stylesheet">

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

.layout{
    display:flex;
    min-height:100vh;
}

/* SIDEBAR */

.sidebar{
    width:270px;
    background:#17345f;
    padding:25px 20px;
    display:flex;
    flex-direction:column;
    justify-content:space-between;
    position:fixed;
    height:100vh;
}

.logo{
    margin-bottom:40px;
}

.logo h2{
    color:white;
    font-size:28px;
    font-weight:700;
}

.menu{
    display:flex;
    flex-direction:column;
    gap:10px;
}

.menu a{
    text-decoration:none;
    color:#dbe6f5;
    padding:14px 16px;
    border-radius:12px;
    display:flex;
    align-items:center;
    gap:12px;
    transition:.3s;
    font-size:15px;
    font-weight:500;
}

.menu a:hover{
    background:#274a7a;
    color:white;
}

.menu a.active{
    background:#3b5b89;
    color:white;
}

.logout-btn{
    margin-top:40px;
}

.logout-btn a{
    display:block;
    text-decoration:none;
    background:#ffffff10;
    color:white;
    text-align:center;
    padding:14px;
    border-radius:12px;
    transition:.3s;
    font-weight:500;
}

.logout-btn a:hover{
    background:#ffffff20;
}

/* MAIN */

.main{
    margin-left:270px;
    width:calc(100% - 270px);
    padding:30px;
}

/* TOPBAR */

.topbar{
    display:flex;
    justify-content:space-between;
    align-items:center;
    margin-bottom:30px;
}

.topbar h1{
    font-size:32px;
    font-weight:700;
    color:#0f172a;
}

.search-box input{
    width:280px;
    padding:14px 18px;
    border:none;
    outline:none;
    border-radius:14px;
    background:white;
    box-shadow:0 2px 10px rgba(0,0,0,.05);
    font-size:14px;
}

/* WELCOME */

.welcome{
    margin-bottom:30px;
}

.welcome h2{
    font-size:34px;
    margin-bottom:8px;
    color:#0f172a;
}

.welcome p{
    color:#64748b;
    font-size:16px;
}

/* CARDS */

.cards{
    display:grid;
    grid-template-columns:repeat(auto-fit,minmax(250px,1fr));
    gap:20px;
    margin-bottom:30px;
}

.card{
    background:white;
    border-radius:22px;
    padding:25px;
    box-shadow:0 4px 18px rgba(0,0,0,.05);
    transition:.3s;
    border:1px solid #e2e8f0;
}

.card:hover{
    transform:translateY(-4px);
}

.card-icon{
    width:55px;
    height:55px;
    border-radius:16px;
    display:flex;
    align-items:center;
    justify-content:center;
    font-size:26px;
    margin-bottom:18px;
}

.blue{
    background:#dbeafe;
    color:#2563eb;
}

.green{
    background:#dcfce7;
    color:#16a34a;
}

.orange{
    background:#ffedd5;
    color:#ea580c;
}

.purple{
    background:#f3e8ff;
    color:#9333ea;
}

.card h3{
    font-size:22px;
    margin-bottom:10px;
    color:#0f172a;
}

.card p{
    color:#64748b;
    font-size:14px;
    margin-bottom:20px;
    line-height:1.5;
}

.card button{
    border:none;
    background:#17345f;
    color:white;
    padding:12px 18px;
    border-radius:12px;
    cursor:pointer;
    transition:.3s;
    font-weight:600;
}

.card button:hover{
    background:#254b83;
}

/* RESPONSIVE */

@media(max-width:900px){

    .sidebar{
        width:100px;
        padding:20px 10px;
    }

    .logo h2{
        font-size:18px;
        text-align:center;
    }

    .menu a{
        justify-content:center;
        font-size:0;
    }

    .menu a span{
        font-size:20px;
    }

    .main{
        margin-left:100px;
        width:calc(100% - 100px);
    }

    .topbar{
        flex-direction:column;
        align-items:flex-start;
        gap:15px;
    }

    .search-box input{
        width:100%;
    }
}

</style>
</head>

<body>

<div class="layout">

    <!-- SIDEBAR -->
    <aside class="sidebar">

        <div>

            <div class="logo">
                <h2>InvoicePro</h2>
            </div>

            <div class="menu">

                <a href="facturas/facturas.php">
                    <span>📄</span>
                    Facturas
                </a>

                <a href="productos/inventario.php">
                    <span>📦</span>
                    Inventario
                </a>

                <a href="garantias/iniciogarantias.php">
                    <span>🛠️</span>
                    Garantías
                </a>

                <a href="clientes/clientes.php" class="active">
                    <span>👤</span>
                    Clientes
                </a>

                <a href="compra/compras.php">
                    <span>🛒</span>
                    Compras
                </a>

                <a href="proveedores/proveedores.php">
                    <span>🏭</span>
                    Proveedores
                </a>

                <a href="ingresos/ingresos.php">
                    <span>📊</span>
                    Ingresos
                </a>

                <a href="usuario/usuarios.php">
                    <span>👥</span>
                    Usuarios
                </a>

            </div>

        </div>

        <div class="logout-btn">
            <a href="../Auth/logout.php">Cerrar sesión</a>
        </div>

    </aside>

    <!-- MAIN -->
    <main class="main">

        <div class="topbar">
            <h1>Dashboard</h1>

            <div class="search-box">
                <input type="text" id="buscar" placeholder="Buscar...">
            </div>
        </div>

        <div class="welcome">
            <h2>Panel de Administrador</h2>
            <p>Bienvenido, <?php echo $_SESSION["usuario"]; ?> 👑</p>
        </div>

        <div class="cards">

            <div class="card">
                <div class="card-icon blue">📦</div>
                <h3>Inventario</h3>
                <p>Gestiona productos y controla el stock fácilmente.</p>
                <button onclick="location.href='productos/inventario.php'">
                    Administrar
                </button>
            </div>

            <div class="card">
                <div class="card-icon green">📄</div>
                <h3>Facturación</h3>
                <p>Crea facturas y administra ventas del sistema.</p>
                <button onclick="location.href='facturas/facturas.php'">
                    Ir a facturación
                </button>
            </div>

            <div class="card">
                <div class="card-icon orange">📊</div>
                <h3>Ingresos</h3>
                <p>Visualiza estadísticas y ganancias de tu negocio.</p>
                <button onclick="location.href='ingresos/ingresos.php'">
                    Ver reportes
                </button>
            </div>

            <div class="card">
                <div class="card-icon purple">👥</div>
                <h3>Usuarios</h3>
                <p>Administra cuentas, permisos y roles.</p>
                <button onclick="location.href='usuario/usuarios.php'">
                    Administrar
                </button>
            </div>

            <div class="card">
                <div class="card-icon green">🛠️</div>
                <h3>Garantías</h3>
                <p>Gestiona solicitudes y procesos de garantías.</p>
                <button onclick="location.href='garantias/iniciogarantias.php'">
                    Administrar
                </button>
            </div>

            <div class="card">
                <div class="card-icon blue">👤</div>
                <h3>Clientes</h3>
                <p>Consulta y administra información de clientes.</p>
                <button onclick="location.href='clientes/clientes.php'">
                    Administrar
                </button>
            </div>

            <div class="card">
                <div class="card-icon orange">🏭</div>
                <h3>Proveedores</h3>
                <p>Controla tus proveedores y relaciones comerciales.</p>
                <button onclick="location.href='proveedores/proveedores.php'">
                    Administrar
                </button>
            </div>

            <div class="card">
                <div class="card-icon purple">🛒</div>
                <h3>Compras</h3>
                <p>Gestiona compras y movimientos del sistema.</p>
                <button onclick="location.href='compra/compras.php'">
                    Administrar
                </button>
            </div>

        </div>

    </main>

</div>
<script>

// 🔎 BUSCADOR EN TIEMPO REAL
document.getElementById("buscar").addEventListener("keyup", function() {

    let filtro = this.value.toLowerCase();
    let filas = document.querySelectorAll(".cards .card");

    filas.forEach(fila => {

        let texto = fila.textContent.toLowerCase();

        fila.style.display = texto.includes(filtro)
        ? ""
        : "none";

    });

});

</script>

</body>
</html>