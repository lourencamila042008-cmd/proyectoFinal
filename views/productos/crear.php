<!DOCTYPE html>
<html lang="es">
<head>
    <meta charset="UTF-8">
    <title>Crear producto</title>
    <link rel="stylesheet" href="../../public/css/crear.css">
</head>
<body>

<div class="header">
    <h1>InvoicePro Dashboard</h1>
    <button class="btn" onclick="location.href='../../principal.php?controller=productos&action=index'">
        Regresar
    </button>
</div>

<br><br>

<div class="main">
    <div class="form-box">
        <h2>Crear productos</h2><br>

        <!-- 🔥 FORMULARIO CORRECTO -->
        <form action="../../principal.php?controller=productos&action=crear" method="post" class="form-group">

            <input type="text" name="id_categoria" placeholder="Nombre de la categoría"><br>

            <input type="text" name="nombre" placeholder="Nombre del producto"><br>

            <input type="number" name="stock" placeholder="Stock del producto"><br>

            <input type="number" name="precio_compra" placeholder="Precio de compra"><br>

            <input type="number" name="precio_venta" placeholder="Precio de venta"><br>

            <input type="number" name="min_stock" placeholder="Mínimo stock"><br>

            <button type="submit">Guardar</button>
        </form>
    </div>
</div>

</body>
</html>