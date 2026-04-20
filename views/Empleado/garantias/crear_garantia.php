<?php
session_start();

if (!isset($_SESSION["rol"]) || $_SESSION["rol"] != "empleado") {
    header("Location: ../Auth/login.php");
    exit();
}

require_once "../../../config/db.php";
$conn = Database::Conectar();

$errores = [];

if ($_SERVER['REQUEST_METHOD'] == 'POST') {

    $id_facturas = intval($_POST['id_facturas']);
    $id_producto = intval($_POST['id_producto']);
    $motivo = $_POST['motivo'];
    $solucion = $_POST['solucion'];
    $fecha_inicio = $_POST['fecha_inicio'];
    $fecha_fin = $_POST['fecha_fin'];

    $estado = "pendiente";

    // 🔥 VALIDAR TIEMPO DESDE BACKEND (CLAVE)
    $stmt = $conn->prepare("SELECT fecha FROM facturas WHERE id_facturas=?");
    $stmt->bind_param("i", $id_facturas);
    $stmt->execute();
    $res = $stmt->get_result()->fetch_assoc();

    if($res){
        $fecha_factura = new DateTime($res['fecha']);
        $hoy = new DateTime();
        $dias = $fecha_factura->diff($hoy)->days;

        if($dias > 30){
            $errores[] = "❌ Garantía fuera de tiempo (más de 30 días)";
        }
    }

    // VALIDACIONES BÁSICAS
    if ($id_facturas <= 0) $errores[] = "Factura inválida";
    if ($id_producto <= 0) $errores[] = "Producto inválido";
    if ($fecha_fin < $fecha_inicio) $errores[] = "Fechas incorrectas";

    if(empty($errores)){

        $stmt = $conn->prepare("
            INSERT INTO garantias 
            (id_facturas, id_producto, motivo, solucion, estado, fecha_inicio, fecha_fin) 
            VALUES (?, ?, ?, ?, ?, ?, ?)
        ");

        $stmt->bind_param("iisssss",
            $id_facturas,
            $id_producto,
            $motivo,
            $solucion,
            $estado,
            $fecha_inicio,
            $fecha_fin
        );

        if($stmt->execute()){
            header("Location: iniciogarantias.php?msg=creado");
            exit;
        }
    }
}

// DATOS
$facturas = $conn->query("SELECT f.id_facturas, c.nombre FROM facturas f JOIN clientes c ON f.id_clientes = c.id_clientes");
?>

<!DOCTYPE html>
<html lang="es">
<head>
<meta charset="UTF-8">
<title>Crear Garantía</title>

<style>
body {
    margin: 0;
    font-family: 'Segoe UI', Arial, sans-serif;
    background: linear-gradient(135deg, #0f4c81, #3a7bd5);
}

/* CONTENEDOR */
.container {
    width: 420px;
    margin: 60px auto;
    background: white;
    padding: 30px;
    border-radius: 15px;
    box-shadow: 0 15px 40px rgba(0,0,0,0.2);
    animation: fadeIn 0.5s ease;
}

/* ANIMACIÓN */
@keyframes fadeIn {
    from { opacity: 0; transform: translateY(20px); }
    to   { opacity: 1; transform: translateY(0); }
}

/* TÍTULO */
h2 {
    text-align: center;
    margin-bottom: 20px;
    color: #0f4c81;
}

/* LABELS */
label {
    font-weight: 600;
    display: block;
    margin-top: 10px;
    margin-bottom: 5px;
}

/* INPUTS */
input, select {
    width: 100%;
    padding: 12px;
    border-radius: 8px;
    border: 1px solid #ccc;
    transition: 0.3s;
    font-size: 14px;
}

input:focus, select:focus {
    border-color: #0f4c81;
    outline: none;
    box-shadow: 0 0 5px rgba(15,76,129,0.3);
}

/* BOTÓN */
button {
    width: 100%;
    margin-top: 15px;
    padding: 12px;
    background: #0f4c81;
    color: white;
    border: none;
    border-radius: 10px;
    font-size: 15px;
    cursor: pointer;
    transition: 0.3s;
}

button:hover {
    background: #09365f;
}

/* BOTÓN DESACTIVADO */
button:disabled {
    background: #aaa;
    cursor: not-allowed;
}

/* ALERTAS */
.alert {
    background: #ffe5e5;
    color: #c0392b;
    padding: 10px;
    border-radius: 8px;
    margin-bottom: 10px;
}

/* INFO PRODUCTO */
#info {
    background: #f4f6f9;
    padding: 10px;
    border-radius: 8px;
    margin-top: 10px;
    font-size: 14px;
}

/* BLOQUEADO */
.bloqueado {
    color: #c0392b;
    font-weight: bold;
}

/* OK */
.ok {
    color: #28a745;
    font-weight: bold;
}
</style>
</head>

<body>

<div class="container">

<h2>Nueva Garantía</h2>

<?php foreach($errores as $e): ?>
<p class="alert"><?= $e ?></p>
<?php endforeach; ?>

<form method="POST">

<label>Factura</label>
<select name="id_facturas" id="factura" required>
<option value="">Seleccionar</option>
<?php while($f = $facturas->fetch_assoc()): ?>
<option value="<?= $f['id_facturas'] ?>">
Factura #<?= $f['id_facturas'] ?> - <?= $f['nombre'] ?>
</option>
<?php endwhile; ?>
</select>

<label>Producto</label>
<select name="id_producto" id="producto" required></select>

<div id="info"></div>

<label>Motivo</label>
<select name="motivo">
<option value="daño">Daño</option>
</select>

<label>Solución</label>
<select name="solucion">
<option value="cambio">Cambio</option>
<option value="reparacion">Reparación</option>
<option value="devolucion">Devolución</option>
</select>

<label>Fecha inicio</label>
<input type="date" name="fecha_inicio" required>

<label>Fecha fin</label>
<input type="date" name="fecha_fin" required>

<button type="submit" id="btnGuardar">Guardar</button>

</form>

</div>

<script>
let productos = [];

document.getElementById("factura").addEventListener("change", function(){

    fetch("obtener_productos.php?id_factura=" + this.value)
    .then(res => res.json())
    .then(data => {

        productos = data;
        let select = document.getElementById("producto");
        select.innerHTML = "";

        data.forEach(p=>{
            let opt = document.createElement("option");
            opt.value = p.id_productos;
            opt.textContent = p.nombre;
            select.appendChild(opt);
        });
    });
});

document.getElementById("producto").addEventListener("change", function(){

    let p = productos.find(x=>x.id_productos == this.value);
    let info = document.getElementById("info");
    let btn = document.getElementById("btnGuardar");

    if(p.fuera_tiempo){
        info.innerHTML = "<p class='bloqueado'>⚠ Producto fuera de garantía ("+p.dias+" días)</p>";
        btn.disabled = true;
    } else {
        info.innerHTML = "<p>✔ Dentro de garantía ("+p.dias+" días)</p>";
        btn.disabled = false;
    }
});
</script>

</body>
</html>