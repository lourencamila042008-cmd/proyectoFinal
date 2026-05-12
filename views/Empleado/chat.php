<?php

include "../../config/db.php";


$conexion = Database::Conectar();


$mensaje = $_POST['mensaje'];

// SALUDO
if(strpos($mensaje,"hola") !== false){

echo "Hola 👋 Bienvenido al sistema ¿En qué puedo ayudarte?
<br>
Puedes escribir:
<br>
- buscar 1001
<br>
- listar clientes
<br>
- cuantos clientes";

}


// BUSCAR CLIENTE
elseif(strpos($mensaje,"buscar") !== false){

$documento = str_replace("buscar","",$mensaje);
$documento = trim($documento);

$sql = "SELECT * FROM clientes
        WHERE cedula='$documento'";

$resultado = mysqli_query($conexion,$sql);

if(mysqli_num_rows($resultado)>0){

$fila = mysqli_fetch_assoc($resultado);

echo "Cliente encontrado 👇 <br>";
echo "Nombre: ".$fila['nombre']."<br>";
echo "Teléfono: ".$fila['telefono']."<br>";

}else{

echo "Cliente no encontrado";

}

}


// LISTAR CLIENTES
elseif($mensaje == "listar clientes"){

$sql = "SELECT * FROM clientes";

$resultado = mysqli_query($conexion,$sql);

while($fila = mysqli_fetch_assoc($resultado)){

echo "Cédula: ".$fila['cedula']."<br>";
echo "Nombre: ".$fila['nombre']."<br>";
echo "-------------------<br>";

}

}


// CONTAR CLIENTES
elseif($mensaje == "cuantos clientes"){

$sql = "SELECT count(*) as total FROM clientes";

$resultado = mysqli_query($conexion,$sql);
$fila = mysqli_fetch_assoc($resultado);

echo "Total clientes: ".$fila['total'];

}


// DESPEDIDA
elseif(strpos($mensaje,"adios") !== false){

echo "Hasta luego 👋";

}


// DEFAULT
else{

echo "No entiendo tu solicitud 🤔
<br>
Puedes escribir:
<br>
- hola
<br>
- buscar 1001
<br>
- listar clientes";

}


?>