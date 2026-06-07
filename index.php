<!DOCTYPE html>
<html lang="es">
<head>
<meta charset="UTF-8">
<title>InvoicePro</title>
<link rel="stylesheet" href="public/css/index.css">
</head>

<body>
<style>
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
</style>
<div class="header">
  <h1>InvoicePro</h1>

  <div>
    <button class="btn" onclick="location.href='views/Auth/login.php'">
      Iniciar Sesión
    </button>

    <button class="btn" onclick="location.href='views/Auth/register.php'">
      Registrarse
    </button>
  </div>
</div>

<div class="hero">
  <div class="hero-box">

    <h2>Sistema de Facturación Inteligente</h2>

    <p>
      Gestiona clientes, productos y facturas de forma rápida,
      segura y profesional.
    </p>

    <button class="btn" onclick="location.href='views/Auth/login.php'">
      Comenzar
    </button>

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

<script src="public/js/chatbot.js"></script>

</body>
</html>

</body>
</html>