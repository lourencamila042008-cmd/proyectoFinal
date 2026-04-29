<?php
require_once "../../../config/db.php";
$conn = Database::conectar();

$id = $_GET['id'];

// eliminar detalle primero
$conn->query("DELETE FROM detalle_compra WHERE id_compra = $id");

// eliminar compra
$conn->query("DELETE FROM compras WHERE id_compra = $id");

header("Location: index_compras.php");