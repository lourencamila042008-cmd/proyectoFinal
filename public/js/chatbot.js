function abrirChat(){

    document.getElementById("chatContainer")
    .style.display = "flex";

}

function cerrarChat(){

    document.getElementById("chatContainer")
    .style.display = "none";

}

function agregarMensaje(texto, clase){

    let chat = document.getElementById("chat");

    let div = document.createElement("div");

    div.className = clase;

    div.innerHTML = texto;

    chat.appendChild(div);

    chat.scrollTop = chat.scrollHeight;
}

function enviar(){

    let input = document.getElementById("mensaje");

    let texto = input.value.trim().toLowerCase();

    if(texto == "") return;

    agregarMensaje(input.value, "usuario");

    responder(texto);

    input.value = "";
}

function responder(texto){

    // INVENTARIO

    if(
        texto.includes("inventario") ||
        texto.includes("productos")
    ){

        agregarMensaje(
        "📦 Abriendo módulo de inventario...",
        "bot"
        );

        setTimeout(()=>{

            window.location =
            "inventario/inventario.php";

        },1000);

        return;
    }

    // FACTURAS

    if(
        texto.includes("factura") ||
        texto.includes("ventas")
    ){

        agregarMensaje(
        "📄 Abriendo facturación...",
        "bot"
        );

        setTimeout(()=>{

            window.location =
            "facturas/facturas.php";

        },1000);

        return;
    }

    // GARANTIAS

    if(
        texto.includes("garantia") ||
        texto.includes("garantías")
    ){

        agregarMensaje(
        "🛠 Entrando al módulo de garantías...",
        "bot"
        );

        setTimeout(()=>{

            window.location =
            "garantias/garantias.php";

        },1000);

        return;
    }

    // CLIENTES

    if(
        texto.includes("cliente")
    ){

        agregarMensaje(
        "👤 Abriendo clientes...",
        "bot"
        );

        setTimeout(()=>{

            window.location =
            "clientes/clientesinicio.php";

        },1000);

        return;
    }

    // UBICACION

    if(
        texto.includes("ubicacion") ||
        texto.includes("dirección") ||
        texto.includes("direccion") ||
        texto.includes("donde están")
    ){

        agregarMensaje(`
        📍 Estamos ubicados en:

        <br><br>

        Calle 10 #20-30
        Manizales, Colombia

        <br><br>

        <a href="https://maps.google.com/?q=manizales"
        target="_blank"
        style="
        color:#2563eb;
        font-weight:bold;
        ">
        Ver en Google Maps
        </a>
        `,
        "bot");

        return;
    }

    // HORARIO

    if(
        texto.includes("horario")
    ){

        agregarMensaje(
        `
        🕒 Horario de atención:

        <br><br>

        Lunes a viernes:
        8:00 AM - 6:00 PM

        <br>

        Sábados:
        8:00 AM - 1:00 PM
        `,
        "bot"
        );

        return;
    }

    // CONTACTO

    if(
        texto.includes("telefono") ||
        texto.includes("contacto")
    ){

        agregarMensaje(
        `
        📞 Teléfono:
        3200000000

        <br><br>

        ✉ correo:
        soporte@invoicepro.com
        `,
        "bot"
        );

        return;
    }

    // SALUDO

    if(
        texto.includes("hola") ||
        texto.includes("buenas")
    ){

        agregarMensaje(
        `
        👋 Hola.

        ¿En qué puedo ayudarte?

        <br><br>

        Puedes preguntar por:

        <br>

        📦 Inventario
        <br>
        📄 Facturas
        <br>
        🛠 Garantías
        <br>
        👤 Clientes
        <br>
        📍 Ubicación
        `,
        "bot"
        );

        return;
    }

    // DEFAULT

    agregarMensaje(
    `
    🤖 No entendí tu mensaje.

    <br><br>

    Intenta preguntar:

    <br>

    📦 Inventario
    <br>
    📄 Facturas
    <br>
    🛠 Garantías
    <br>
    👤 Clientes
    <br>
    📍 Ubicación
    `,
    "bot"
    );
}

// ENTER

document
.getElementById("mensaje")
.addEventListener("keypress", function(e){

    if(e.key === "Enter"){

        enviar();
    }

});