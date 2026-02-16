<?php
session_start();

if (!isset($_SESSION["usuario"])) {
    header("Location: /MVC-PRU/");
    exit();
}
?>
<!DOCTYPE html>
<html>
<head>
    <title>Dashboard - InvoicePro</title>

    <style>
        body {
            margin: 0;
            font-family: Arial, sans-serif;
            display: flex;
        }

        /* Barra lateral */
        .sidebar {
            width: 220px;
            height: 100vh;
            background: #4e73df;
            color: white;
            padding-top: 20px;
            position: fixed;
        }

        .sidebar h2 {
            text-align: center;
            margin-bottom: 30px;
        }

        .sidebar a {
            display: block;
            color: white;
            padding: 15px;
            text-decoration: none;
            transition: 0.3s;
        }

        .sidebar a:hover {
            background: #2e59d9;
        }

        /* Contenido */
        .content {
            margin-left: 220px;
            padding: 30px;
            width: 100%;
            background: #f8f9fc;
            min-height: 100vh;
        }

        .card {
            background: white;
            padding: 20px;
            margin: 15px 0;
            border-radius: 10px;
            box-shadow: 0 5px 15px rgba(0,0,0,0.1);
        }

        .logout {
            position: absolute;
            bottom: 20px;
            width: 100%;
        }
    </style>
</head>

<body>

<!-- 🔹 MENÚ LATERAL -->
<div class="sidebar">
    <h2>InvoicePro</h2>

    <a href="?modulo=productos">📦 Productos</a>
    <a href="?modulo=facturas">🧾 Facturas</a>

    <div class="logout">
        <a href="logout.php">🚪 Cerrar sesión</a>
    </div>
</div>

<!-- 🔹 CONTENIDO -->
<div class="content">

<?php

$modulo = $_GET["modulo"] ?? "inicio";

if ($modulo == "productos") {
    echo "<h1>Módulo de Productos</h1>";
    echo "<div class='card'>Aquí podrás agregar, editar y eliminar productos.</div>";
}

elseif ($modulo == "facturas") {
    echo "<h1>Módulo de Facturas</h1>";
    echo "<div class='card'>Aquí podrás crear y gestionar facturas.</div>";
}

else {
    echo "<h1>Bienvenida, " . $_SESSION["usuario"] . " 👩‍💻</h1>";
    echo "<div class='card'>Selecciona un módulo en el menú.</div>";
}

?>

</div>

</body>
</html>
