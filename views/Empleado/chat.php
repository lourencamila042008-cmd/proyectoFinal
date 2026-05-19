<?php

include "../../config/db.php";

$conexion = Database::Conectar();

// RECIBIR MENSAJE
$mensaje = strtolower(trim($_POST['mensaje']));


// =========================
// SALUDO
// =========================

if(
    strpos($mensaje,"hola") !== false ||
    strpos($mensaje,"buenas") !== false ||
    strpos($mensaje,"hey") !== false
){

echo "
Hola 👋 Bienvenido a InvoicePro

<br><br>

Puedo ayudarte con:

<br><br>

📦 inventario
<br>
📄 facturas
<br>
🛠 garantias
<br>
👤 clientes
<br>
📍 ubicacion
<br>
🕒 horario

";

}

// =========================
// UBICACION
// =========================

elseif(

    strpos($mensaje,"ubicacion") !== false ||
    strpos($mensaje,"ubicación") !== false ||
    strpos($mensaje,"direccion") !== false ||
    strpos($mensaje,"dirección") !== false ||
    strpos($mensaje,"donde estan") !== false ||
    strpos($mensaje,"mapa") !== false

){

echo "

📍 Nuestra ubicación:

<br><br>

Carrera 4 y 5 entre calle 15, y 16
La Dorada, Caldas

<br><br>

<iframe
width='100%'
height='220'
style='border:0; border-radius:12px;'
loading='lazy'
allowfullscreen
src='https://maps.google.com/maps?q=manizales&t=&z=13&ie=UTF8&iwloc=&output=embed'>
</iframe>

<br><br>

<a href='https://maps.google.com/?q=manizales'
target='_blank'
style='
background:#2563eb;
color:white;
padding:10px 14px;
border-radius:10px;
text-decoration:none;
display:inline-block;
font-weight:bold;
'>

Abrir en Google Maps

</a>

";

}


// =========================
// HORARIO
// =========================

elseif(

    strpos($mensaje,"horario") !== false ||
    strpos($mensaje,"hora") !== false

){

echo "

🕒 Horario de atención

<br><br>

Lunes a viernes:
8:00 AM - 6:00 PM

<br><br>

Sábados:
8:00 AM - 1:00 PM

";

}
// =========================
// LISTAR PRODUCTOS
// =========================

elseif(

    $mensaje == "listar productos" ||
    $mensaje == "listar inventario"

){

$sql = "
SELECT *
FROM productos
ORDER BY id_productos DESC
LIMIT 10
";

$resultado = mysqli_query($conexion,$sql);

echo "📦 Últimos productos registrados

<br><br>";

while($fila = mysqli_fetch_assoc($resultado)){

echo "

📦 ".$fila['nombre']."

<br>

💰 Venta: $".$fila['precio_venta']."

<br>

📊 Stock: ".$fila['stock']."

<br><br>

";

}

}


// =========================
// CONTAR PRODUCTOS
// =========================

elseif(

    $mensaje == "cuantos productos" ||
    $mensaje == "cuántos productos" ||
    $mensaje == "cuanto inventario"

){

$sql = "
SELECT count(*) as total
FROM productos
";

$resultado = mysqli_query($conexion,$sql);

$fila = mysqli_fetch_assoc($resultado);

echo "

📦 Total productos registrados:

<b>".$fila['total']."</b>

";

}


// =========================
// INVENTARIO
// =========================

elseif(

    strpos($mensaje,"inventario") !== false ||
    strpos($mensaje,"productos") !== false

){


echo "

📦 Inventario
<br><br>

Puedes escribir:

<br>

- listar productos
<br>
- cuantos productos
<br>

";

}


// =========================
// CLIENTES
// =========================

elseif(

    strpos($mensaje,"clientes") !== false &&
    strpos($mensaje,"listar") === false &&
    strpos($mensaje,"cuantos") === false

){

echo "

👤 Módulo de clientes disponible.

<br><br>

Puedes escribir:

<br>

- listar clientes
<br>
- cuantos clientes
<br>
- buscar 1001

";

}


// =========================
// BUSCAR CLIENTE
// =========================

elseif(strpos($mensaje,"buscar") !== false){

$documento = str_replace("buscar","",$mensaje);

$documento = trim($documento);

// SEGURIDAD

$stmt = mysqli_prepare(
$conexion,
"SELECT * FROM clientes WHERE cedula=?"
);

mysqli_stmt_bind_param(
$stmt,
"s",
$documento
);

mysqli_stmt_execute($stmt);

$resultado = mysqli_stmt_get_result($stmt);

if(mysqli_num_rows($resultado)>0){

$fila = mysqli_fetch_assoc($resultado);

echo "

✅ Cliente encontrado

<br><br>

👤 Nombre:
".$fila['nombre']."

<br>

📞 Teléfono:
".$fila['telefono']."

";

}else{

echo "

❌ Cliente no encontrado

";

}

}


// =========================
// LISTAR CLIENTES
// =========================

elseif($mensaje == "listar clientes"){

$sql = "
SELECT *
FROM clientes
ORDER BY id_clientes DESC
LIMIT 10
";

$resultado = mysqli_query($conexion,$sql);

echo "👥 Últimos clientes registrados

<br><br>";

while($fila = mysqli_fetch_assoc($resultado)){

echo "

👤 ".$fila['nombre']."

<br>

🪪 ".$fila['cedula']."

<br><br>

";

}

}


// =========================
// CONTAR CLIENTES
// =========================

elseif(

    $mensaje == "cuantos clientes" ||
    $mensaje == "cuántos clientes"

){

$sql = "
SELECT count(*) as total
FROM clientes
";

$resultado = mysqli_query($conexion,$sql);

$fila = mysqli_fetch_assoc($resultado);

echo "

👥 Total clientes registrados:

<b>".$fila['total']."</b>

";

}
// =========================
// LISTAR FACTURAS
// =========================

elseif(

    $mensaje == "listar facturas"

){

$sql = "
SELECT *
FROM facturas
ORDER BY id_facturas DESC

";

$resultado = mysqli_query($conexion,$sql);

echo "📄 Últimas facturas

<br><br>";

while($fila = mysqli_fetch_assoc($resultado)){

echo "

📄 Factura #".$fila['id_facturas']."

<br>

📅 Fecha: ".$fila['fecha']."

<br><br>

";

}

}


// =========================
// CONTAR FACTURAS
// =========================

elseif(

    $mensaje == "cuantas facturas" ||
    $mensaje == "cuántas facturas"

){

$sql = "
SELECT count(*) as total
FROM facturas
";

$resultado = mysqli_query($conexion,$sql);

$fila = mysqli_fetch_assoc($resultado);

echo "

📄 Total facturas registradas:

<b>".$fila['total']."</b>

";

}

// =========================
// FACTURAS
// =========================

elseif(

    strpos($mensaje,"factura") !== false ||
    strpos($mensaje,"ventas") !== false

){

echo "

📄 Módulo de facturación disponible.


<br><br>

Puedes escribir:

<br>

- listar facturas
<br>
- cuantas facturas
<br>

";

}


// =========================
// LISTAR GARANTIAS
// =========================

elseif(

    $mensaje == "listar garantias" ||
    $mensaje == "listar garantías"

){

$sql = "
SELECT *
FROM garantias
ORDER BY id_garantia DESC
LIMIT 10
";

$resultado = mysqli_query($conexion,$sql);

echo "🛠 Últimas garantías

<br><br>";

while($fila = mysqli_fetch_assoc($resultado)){

echo "

🛠 Garantía #".$fila['id_garantia']."

<br>

📄 Factura: ".$fila['id_facturas']."

<br>

📌 Estado: ".$fila['estado']."

<br><br>

";

}

}


// =========================
// CONTAR GARANTIAS
// =========================

elseif(

    $mensaje == "cuantas garantias" ||
    $mensaje == "cuántas garantías"

){

$sql = "
SELECT count(*) as total
FROM garantias
";

$resultado = mysqli_query($conexion,$sql);

$fila = mysqli_fetch_assoc($resultado);

echo "

🛠 Total garantías registradas:

<b>".$fila['total']."</b>

";

}


// =========================
// GARANTIAS
// =========================

elseif(

    strpos($mensaje,"garantia") !== false ||
    strpos($mensaje,"garantía") !== false

){

echo "

🛠 Sistema de garantías disponible.

<br><br>

Puedes escribir:

<br>

- lista garantias
<br>
- cuantas garantias
<br>


";

}

// =========================
// DESPEDIDA
// =========================

elseif(

    strpos($mensaje,"adios") !== false ||
    strpos($mensaje,"chao") !== false

){

echo "

Hasta luego 👋

";

}


// =========================
// DEFAULT
// =========================

else{

echo "

🤖 No entendí tu solicitud.

<br><br>

Prueba escribir:

<br><br>

- hola
<br>
- ubicacion
<br>
- horario
<br>
- inventario
<br>
- buscar 1001
<br>
- listar clientes
<br>
- cuantos clientes

";

}

?>