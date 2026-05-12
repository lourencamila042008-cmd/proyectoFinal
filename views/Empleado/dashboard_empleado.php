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
<title>Panel Empleado</title>
<link rel="stylesheet" href="../../public/css/empleado.css">

</head>

<body>
<style>

/* BOTON FLOTANTE */

.boton-chat{
    position:fixed;
    bottom:25px;
    right:25px;
    width:70px;
    height:70px;
    background:linear-gradient(135deg,#60a5fa,#2563eb);
    border-radius:50%;
    display:flex;
    justify-content:center;
    align-items:center;
    font-size:35px;
    cursor:pointer;
    box-shadow:0 8px 25px rgba(37,99,235,0.4);
    transition:0.3s;
    z-index:1000;
}

.boton-chat:hover{
    transform:scale(1.1);
}

/* CONTENEDOR */

.chat-container{
    position:fixed;
    bottom:110px;
    right:25px;
    width:360px;
    height:500px;
    background:white;
    border-radius:25px;
    overflow:hidden;
    box-shadow:0 15px 40px rgba(0,0,0,0.2);
    display:none;
    flex-direction:column;
    animation:aparecer .3s ease;
}

/* HEADER */

.chat-header{
    background:linear-gradient(135deg,#60a5fa,#2563eb);
    color:white;
    padding:18px;
    font-size:22px;
    font-weight:bold;
    display:flex;
    justify-content:space-between;
    align-items:center;
}

.chat-header span{
    cursor:pointer;
    font-size:18px;
}

/* CHAT */

#chat{
    flex:1;
    padding:15px;
    overflow-y:auto;
    background:#eff6ff;
}

/* MENSAJES */

.usuario{
    background:linear-gradient(135deg,#2563eb,#1d4ed8);
    color:white;
    padding:12px 15px;
    border-radius:18px 18px 0px 18px;
    margin:10px 0;
    width:fit-content;
    max-width:80%;
    margin-left:auto;
    box-shadow:0 4px 10px rgba(37,99,235,0.3);
}

.bot{
    background:white;
    color:#1e3a8a;
    padding:12px 15px;
    border-radius:18px 18px 18px 0px;
    margin:10px 0;
    width:fit-content;
    max-width:80%;
    border:1px solid #bfdbfe;
    box-shadow:0 4px 10px rgba(0,0,0,0.08);
}

/* INPUT */

.input-area{
    display:flex;
    padding:15px;
    gap:10px;
    background:white;
    border-top:1px solid #dbeafe;
}

#mensaje{
    flex:1;
    padding:12px;
    border:none;
    border-radius:15px;
    background:#eff6ff;
    outline:none;
    font-size:15px;
}

button{
    border:none;
    padding:12px 18px;
    border-radius:15px;
    background:linear-gradient(135deg,#60a5fa,#2563eb);
    color:white;
    cursor:pointer;
    font-weight:bold;
    transition:0.3s;
}

button:hover{
    transform:scale(1.05);
}

/* SCROLL */

::-webkit-scrollbar{
    width:6px;
}

::-webkit-scrollbar-thumb{
    background:#93c5fd;
    border-radius:10px;
}

/* ANIMACION */

@keyframes aparecer{
    from{
        transform:translateY(20px);
        opacity:0;
    }

    to{
        transform:translateY(0);
        opacity:1;
    }
}
</style>
<!-- SIDEBAR -->
<div class="sidebar">
    <h2>Empleado</h2>

    <a href="facturas/facturas.php">📄 Facturas</a>
    <a href="inventario/inventario.php">📦 Inventario</a>
    <a href="garantias/iniciogarantias.php">🛠 Garantías</a>
    <a href="clientes/clientesinicio.php">👤 Clientes</a>
</div>

<!-- CONTENIDO -->
<div class="main">

    <div class="topbar">
        <h1>Dashboard</h1>
        <a href="../Auth/logout.php" class="logout">Cerrar sesión</a>
    </div>

    <div class="cards">

        <a href="facturas/facturas.php" class="card">
            <div class="icon">📄</div>
            Facturas
            <small>Gestiona ventas</small>
        </a>

        <a href="inventario/inventario.php" class="card">
            <div class="icon">📦</div>
            Inventario
            <small>Control de productos</small>
        </a>

        <a href="garantias/garantias.php" class="card">
            <div class="icon">🛠</div>
            Garantías
            <small>Seguimiento</small>
        </a>

        <a href="clientes/clientesinicio.php" class="card">
            <div class="icon">👤</div>
            Clientes
            <small>Base de datos</small>
        </a>

    </div>

</div>

<!-- BOTON FLOTANTE -->
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
        <button onclick="enviar()">Enviar</button>
    </div>

</div>

<script src="../../public/js/chatbot.js"></script>

</body>
</html>