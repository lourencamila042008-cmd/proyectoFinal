
<!DOCTYPE html>
<html lang="es">
<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <title>InvoicePro - Sistema de Facturación</title>
    <script src="https://cdn.tailwindcss.com"></script>
    <link href="https://fonts.googleapis.com/css2?family=Inter:wght@300;400;500;600;700;800&display=swap" rel="stylesheet">
    <style>
        * {
            font-family: 'Inter', sans-serif;
        }

        .gradient-bg {
            background: linear-gradient(135deg, #165DFF 0%, #3685FF 50%, #5AA2FF 100%);
        }

        .card-shadow {
            box-shadow: 0 20px 60px rgba(22, 93, 255, 0.15);
        }

        .btn-shadow {
            box-shadow: 0 8px 20px rgba(22, 93, 255, 0.25);
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

    </style>
</head>
<body class="bg-gray-50">

    <!-- Barra de navegación superior -->
    <nav class="bg-white/90 backdrop-blur-md fixed w-full z-50 shadow-sm">
        <div class="container mx-auto px-6 py-4 flex justify-between items-center">
            <!-- Logo -->
            <div class="flex items-center gap-2">
                <div class="w-9 h-9 rounded-lg bg-[#165DFF] flex items-center justify-center text-white font-bold text-lg">I</div>
                <span class="text-[#165DFF] font-bold text-2xl tracking-tight">InvoicePro</span>
            </div>

            <!-- Botones de acción -->
            <div class="hidden md:flex items-center gap-4">
                <a href="#" class="text-gray-700 font-medium hover:text-[#165DFF] transition-colors">Características</a>
                <a href="#" class="text-gray-700 font-medium hover:text-[#165DFF] transition-colors">Precios</a>
                <a href="#" class="text-gray-700 font-medium hover:text-[#165DFF] transition-colors">Soporte</a>
                <a href="views/Auth/login.php" class="ml-2 px-5 py-2.5 border border-[#165DFF] text-[#165DFF] rounded-xl font-semibold hover:bg-blue-50 transition-all">Iniciar Sesión</a>
                <a href="views/Auth/register.php" class="px-5 py-2.5 bg-[#165DFF] text-white rounded-xl font-semibold btn-shadow hover:translate-y-[-2px] transition-all">Registrarse</a>
            </div>

            <!-- Menú móvil -->
            <button class="md:hidden text-gray-700 text-2xl">☰</button>
        </div>
    </nav>

    <!-- Sección Principal -->
    <section class="gradient-bg min-h-screen flex flex-col md:flex-row items-center justify-center pt-20 pb-16 px-6 relative overflow-hidden">
        
        <!-- Elementos decorativos de fondo -->
        <div class="absolute top-0 left-0 w-full h-full overflow-hidden -z-0">
            <div class="absolute w-[500px] h-[500px] bg-white/5 rounded-full blur-3xl -top-40 -left-40"></div>
            <div class="absolute w-[400px] h-[400px] bg-white/5 rounded-full blur-3xl bottom-0 right-0"></div>
        </div>

        <!-- Texto e información -->
        <div class="w-full md:w-1/2 text-white text-center md:text-left mb-12 md:mb-0 relative z-10">
            <h1 class="text-[clamp(2.5rem,5vw,4rem)] font-extrabold leading-[1.15] tracking-tight mb-4">
                Sistema de Gestión y Facturación <br>
                <span class="text-yellow-300">Inteligente</span>
            </h1>
            <p class="text-blue-100 text-lg md:text-xl mb-8 max-w-xl mx-auto md:mx-0">
                Gestiona clientes, productos, inventario y garantías de forma rápida, segura y profesional. Lleva el control total de tu negocio desde un solo lugar.
            </p>
            <div class="flex flex-col sm:flex-row gap-4 justify-center md:justify-start">
                <a href="#" class="bg-white text-[#165DFF] font-semibold px-8 py-3.5 rounded-xl text-lg btn-shadow hover:translate-y-[-3px] transition-all">
                    Comenzar ahora
                </a>
                <a href="#" class="bg-transparent border-2 border-white/80 text-white font-semibold px-8 py-3.5 rounded-xl text-lg hover:bg-white/10 transition-all">
                    Ver demostración
                </a>
            </div>

            <!-- Características rápidas -->
            <div class="flex flex-wrap gap-6 mt-10 justify-center md:justify-start">
                <div class="flex items-center gap-2">
                    <span class="text-yellow-300 text-xl">✅</span>
                    <span class="text-blue-50">Fácil de usar</span>
                </div>
                <div class="flex items-center gap-2">
                    <span class="text-yellow-300 text-xl">✅</span>
                    <span class="text-blue-50">Seguro</span>
                </div>
                <div class="flex items-center gap-2">
                    <span class="text-yellow-300 text-xl">✅</span>
                    <span class="text-blue-50">Accesible</span>
                </div>
            </div>
        </div>

        <!-- Tarjeta / Vista previa -->
        <div class="w-full md:w-1/2 relative z-10">
            <div class="bg-white rounded-[24px] p-6 card-shadow max-w-lg mx-auto">
                <div class="bg-gradient-to-br from-blue-50 to-white rounded-2xl p-6">
                    <div class="w-full h-72 bg-white rounded-xl shadow-md overflow-hidden mb-4">
                        <!-- Simulación de pantalla -->
                        <div class="bg-[#165DFF] h-8 flex items-center px-3 gap-1.5">
                            <div class="w-2.5 h-2.5 rounded-full bg-red-400"></div>
                            <div class="w-2.5 h-2.5 rounded-full bg-yellow-300"></div>
                            <div class="w-2.5 h-2.5 rounded-full bg-green-400"></div>
                        </div>
                        <div class="p-4">
                            <div class="h-3 bg-gray-200 rounded w-3/4 mb-3"></div>
                            <div class="h-3 bg-gray-200 rounded w-1/2 mb-6"></div>
                            <div class="space-y-2">
                                <div class="h-4 bg-blue-100 rounded w-full"></div>
                                <div class="h-4 bg-blue-100 rounded w-full"></div>
                                <div class="h-4 bg-blue-100 rounded w-3/4"></div>
                            </div>
                        </div>
                    </div>
                    <h3 class="text-[#165DFF] font-bold text-xl mb-1">Panel de Control</h3>
                    <p class="text-gray-600 text-sm">Todo lo que necesitas al alcance de un clic</p>
                </div>
            </div>
        </div>
    </section>

    <!-- Sección de características (como en Treinta) -->
    <section class="py-20 bg-white">
        <div class="container mx-auto px-6">
            <div class="text-center mb-16">
                <h2 class="text-[clamp(1.8rem,3vw,2.8rem)] font-bold text-gray-800 mb-4">Todo lo que tu negocio necesita</h2>
                <p class="text-gray-500 text-lg max-w-2xl mx-auto">Diseñado para que cualquier persona pueda usarlo, sin importar su experiencia tecnológica.</p>
            </div>

            <div class="grid md:grid-cols-3 gap-8">
                <!-- Tarjeta 1 -->
                <div class="bg-gray-50 rounded-2xl p-8 hover:shadow-lg transition-shadow">
                    <div class="w-14 h-14 bg-blue-100 text-[#165DFF] rounded-xl flex items-center justify-center text-2xl mb-6">💳</div>
                    <h3 class="text-xl font-bold text-gray-800 mb-3">Facturación Rápida</h3>
                    <p class="text-gray-600">Crea y envía facturas en segundos. Personaliza tus documentos y cumple con todas las normativas legales.</p>
                </div>

                <!-- Tarjeta 2 -->
                <div class="bg-gray-50 rounded-2xl p-8 hover:shadow-lg transition-shadow">
                    <div class="w-14 h-14 bg-blue-100 text-[#165DFF] rounded-xl flex items-center justify-center text-2xl mb-6">📦</div>
                    <h3 class="text-xl font-bold text-gray-800 mb-3">Control de Inventario</h3>
                    <p class="text-gray-600">Sabe qué tienes, qué vendes y cuándo reabastecer. Control total de tus productos y existencias.</p>
                </div>

                <!-- Tarjeta 3 -->
                <div class="bg-gray-50 rounded-2xl p-8 hover:shadow-lg transition-shadow">
                    <div class="w-14 h-14 bg-blue-100 text-[#165DFF] rounded-xl flex items-center justify-center text-2xl mb-6">🛡️</div>
                    <h3 class="text-xl font-bold text-gray-800 mb-3">Gestión de Garantías</h3>
                    <p class="text-gray-600">Registra, da seguimiento y resuelve garantías de forma organizada. Nunca pierdas el control de un reclamo.</p>
                </div>
            </div>
        </div>
    </section>
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