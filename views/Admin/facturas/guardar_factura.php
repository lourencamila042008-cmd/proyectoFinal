<?php
require_once "../../../config/db.php";
$conn = Database::Conectar();

$conn->query("INSERT INTO facturas (id_clientes, estado, fecha)
VALUES ('$_POST[id_clientes]','$_POST[estado]','$_POST[fecha]')");

$id = $conn->insert_id;

$conn->query("INSERT INTO detallefactura 
(id_facturas, id_productos, cantidad, precio)
VALUES ($id, $_POST[id_productos], $_POST[cantidad], $_POST[precio])");

header("Location: index.php");