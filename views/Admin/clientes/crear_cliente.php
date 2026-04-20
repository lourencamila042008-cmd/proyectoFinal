<?php

require_once "../../../models/clientes.php";

$model = new Cliente();

$errores = [];

if($_SERVER['REQUEST_METHOD'] == 'POST'){

    $nombre = $_POST['nombre'];
    $cedula = $_POST['cedula'];
    $telefono = $_POST['telefono'];
 // VALIDACIONES

    // Nombre
    if (empty($nombre)) {
        $errores[] = "El nombre es obligatorio";
    } elseif (strlen($nombre) < 3) {
        $errores[] = "El nombre es muy corto";
    }

    // Cédula
    if (empty($cedula)) {
        $errores[] = "La cédula es obligatoria";
    } elseif (!ctype_digit($cedula)) {
        $errores[] = "La cédula solo debe contener números";
    } elseif (strlen($cedula) < 6 || strlen($cedula) > 15) {
        $errores[] = "La cédula no tiene un formato válido";
    }

    // Teléfono (opcional pero validado)
    if (!empty($telefono)) {
        if (!ctype_digit($telefono)) {
            $errores[] = "El teléfono solo debe contener números";
        } elseif (strlen($telefono) < 7 || strlen($telefono) > 15) {
            $errores[] = "El teléfono no es válido";
        }
    }

    // SI TODO ESTÁ BIEN
    if (empty($errores)) {

        $guardar = $model->crear($nombre, $cedula, $telefono);

        if($guardar){
            echo "<script>alert('Cliente guardado'); window.location='clientes.php';</script>";
        } else {
            $errores[] = "Error al guardar el cliente";
        }
    }
}
?>
<!DOCTYPE html>
<html lang="es">
<head>
<meta charset="UTF-8">
<title>Nuevo Cliente</title>
<link rel="stylesheet" href="../../../public/css/clientes/crear_cliente.css">
</head>


<body>

<div class="container">

<h1>Nuevo Cliente</h1>

<?php if (!empty($errores)): ?>
    <div style="color:red;">
        <?php foreach($errores as $e): ?>
            <p><?= htmlspecialchars($e) ?></p>
        <?php endforeach; ?>
    </div>
<?php endif; ?>

<form method="POST">

<input type="text" name="nombre" placeholder="Nombre completo" required>

<input type="text" name="cedula" placeholder="Cédula" required>

<input type="text" name="telefono" placeholder="Teléfono">

<button type="submit">Guardar Cliente</button>

</form>

<a class="volver" href="clientes.php">⬅ Volver</a>

</div>

</body>
</html>