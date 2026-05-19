<?php
session_start();

if (!isset($_SESSION["rol"]) || $_SESSION["rol"] != "empleado") {
    header("Location: ../Auth/login.php");
    exit();
}
?>

<!DOCTYPE html>
<html lang="es">
<head>
<meta charset="UTF-8">
<meta name="viewport" content="width=device-width, initial-scale=1.0">

<title>Panel Empleado</title>

<link href="https://fonts.googleapis.com/css2?family=Inter:wght@400;500;600;700&display=swap" rel="stylesheet">

<style>

*{
    margin:0;
    padding:0;
    box-sizing:border-box;
}

:root{
    --bg:#f4f7fb;
    --sidebar:#0f172a;
    --card:#ffffff;
    --primary:#2563eb;
    --primary-hover:#1d4ed8;
    --text:#0f172a;
    --muted:#64748b;
    --border:#e2e8f0;
    --shadow:0 10px 30px rgba(15,23,42,0.06);
}

body{
    font-family:'Inter', sans-serif;
    background:var(--bg);
    display:flex;
    min-height:100vh;
    color:var(--text);
}

/* SIDEBAR */

.sidebar{
    width:270px;
    background:var(--sidebar);
    padding:30px 22px;
    display:flex;
    flex-direction:column;
    gap:12px;
    position:fixed;
    height:100vh;
}

.sidebar h2{
    color:white;
    margin-bottom:25px;
    font-size:28px;
    font-weight:700;
}

.sidebar a{
    color:#cbd5e1;
    text-decoration:none;
    padding:14px 16px;
    border-radius:12px;
    transition:.3s;
    font-size:15px;
    font-weight:500;
    display:flex;
    align-items:center;
    gap:12px;
}

.sidebar a:hover{
    background:rgba(255,255,255,0.08);
    color:white;
    transform:translateX(4px);
}

/* MAIN */

.main{
    flex:1;
    margin-left:270px;
    padding:35px;
}

/* TOPBAR */

.topbar{
    display:flex;
    justify-content:space-between;
    align-items:center;
    margin-bottom:35px;
    flex-wrap:wrap;
    gap:15px;
}

.topbar h1{
    font-size:34px;
    color:var(--text);
}

.logout{
    background:#ef4444;
    color:white;
    text-decoration:none;
    padding:12px 18px;
    border-radius:10px;
    font-weight:600;
    transition:.3s;
}

.logout:hover{
    background:#dc2626;
    transform:translateY(-2px);
}

/* CARDS */

.cards{
    display:grid;
    grid-template-columns:repeat(auto-fit,minmax(240px,1fr));
    gap:22px;
}

.card{
    background:var(--card);
    border-radius:20px;
    padding:28px;
    text-decoration:none;
    color:var(--text);
    box-shadow:var(--shadow);
    transition:.3s;
    border:1px solid var(--border);
    display:flex;
    flex-direction:column;
    gap:12px;
}

.card:hover{
    transform:translateY(-6px);
    border-color:#bfdbfe;
}

.icon{
    width:65px;
    height:65px;
    border-radius:16px;
    background:#eff6ff;
    display:flex;
    align-items:center;
    justify-content:center;
    font-size:30px;
}

.card span{
    font-size:20px;
    font-weight:700;
}

.card small{
    color:var(--muted);
    font-size:14px;
    line-height:1.5;
}

/* BOTON CHAT */

.boton-chat{
    position:fixed;
    bottom:25px;
    right:25px;
    width:70px;
    height:70px;
    border-radius:50%;
    background:linear-gradient(135deg,#60a5fa,#2563eb);
    display:flex;
    justify-content:center;
    align-items:center;
    font-size:34px;
    cursor:pointer;
    color:white;
    box-shadow:0 10px 25px rgba(37,99,235,0.3);
    transition:.3s;
    z-index:1000;
}

.boton-chat:hover{
    transform:scale(1.08);
}

/* CHAT */

.chat-container{
    position:fixed;
    bottom:110px;
    right:25px;
    width:360px;
    height:520px;
    background:white;
    border-radius:22px;
    overflow:hidden;
    display:none;
    flex-direction:column;
    box-shadow:0 20px 45px rgba(0,0,0,0.12);
    border:1px solid #dbeafe;
    z-index:1000;
}

.chat-header{
    background:linear-gradient(135deg,#60a5fa,#2563eb);
    color:white;
    padding:18px;
    display:flex;
    justify-content:space-between;
    align-items:center;
    font-weight:700;
    font-size:18px;
}

.chat-header span{
    cursor:pointer;
    font-size:18px;
}

#chat{
    flex:1;
    overflow-y:auto;
    padding:18px;
    background:#f8fbff;
}

/* MENSAJES */

.usuario{
    background:#2563eb;
    color:white;
    padding:12px 15px;
    border-radius:18px 18px 0 18px;
    margin:10px 0 10px auto;
    width:fit-content;
    max-width:80%;
    font-size:14px;
}

.bot{
    background:white;
    color:#1e293b;
    padding:12px 15px;
    border-radius:18px 18px 18px 0;
    margin:10px 0;
    width:fit-content;
    max-width:80%;
    border:1px solid #dbeafe;
    font-size:14px;
}

/* INPUT */

.input-area{
    display:flex;
    gap:10px;
    padding:15px;
    border-top:1px solid #e2e8f0;
    background:white;
}

#mensaje{
    flex:1;
    border:none;
    background:#f1f5f9;
    padding:13px;
    border-radius:12px;
    outline:none;
    font-size:14px;
}

button{
    border:none;
    background:#2563eb;
    color:white;
    padding:12px 18px;
    border-radius:12px;
    cursor:pointer;
    font-weight:600;
    transition:.3s;
}

button:hover{
    background:#1d4ed8;
}

/* SCROLL */

::-webkit-scrollbar{
    width:6px;
}

::-webkit-scrollbar-thumb{
    background:#cbd5e1;
    border-radius:10px;
}

/* RESPONSIVE */

@media(max-width:900px){

    .sidebar{
        width:100%;
        height:auto;
        position:relative;
    }

    .main{
        margin-left:0;
    }

    body{
        flex-direction:column;
    }
}

@media(max-width:600px){

    .main{
        padding:20px;
    }

    .cards{
        grid-template-columns:1fr;
    }

    .chat-container{
        width:95%;
        right:2.5%;
    }
}

</style>

</head>

<body>

<!-- SIDEBAR -->
<div class="sidebar">

    <h2>Empleado</h2>

    <a href="facturas/facturas.php">📄 Facturas</a>

    <a href="inventario/inventario.php">📦 Inventario</a>

    <a href="garantias/iniciogarantias.php">🛠 Garantías</a>

    <a href="clientes/clientesinicio.php">👤 Clientes</a>

</div>

<!-- MAIN -->
<div class="main">

    <div class="topbar">

        <h1>Dashboard</h1>

        <a href="../Auth/logout.php" class="logout">
            Cerrar sesión
        </a>

    </div>

    <div class="cards">

        <a href="facturas/facturas.php" class="card">

            <div class="icon">📄</div>

            <span>Facturas</span>

            <small>
                Gestiona ventas y comprobantes del sistema
            </small>

        </a>

        <a href="inventario/inventario.php" class="card">

            <div class="icon">📦</div>

            <span>Inventario</span>

            <small>
                Control y seguimiento de productos
            </small>

        </a>

        <a href="garantias/garantias.php" class="card">

            <div class="icon">🛠</div>

            <span>Garantías</span>

            <small>
                Seguimiento y estado de garantías
            </small>

        </a>

        <a href="clientes/clientesinicio.php" class="card">

            <div class="icon">👤</div>

            <span>Clientes</span>

            <small>
                Administración de clientes registrados
            </small>

        </a>

    </div>

</div>

<!-- BOTON CHAT -->

<div class="boton-chat" onclick="abrirChat()">
🤖
</div>

<!-- CHAT -->

<div class="chat-container" id="chatContainer">

    <div class="chat-header">
        🤖 Chatbot IA
        <span onclick="cerrarChat()">✖</span>
    </div>

    <div id="chat"></div>

    <div class="input-area">

        <input type="text" id="mensaje" placeholder="Escribe algo...">

        <button onclick="enviar()">
            Enviar
        </button>

    </div>

</div>

<script src="../../public/js/chatbot.js"></script>

</body>
</html>