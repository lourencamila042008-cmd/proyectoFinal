<?php
require_once "../../../config/db.php";
$conn = Database::Conectar();

$id = $_GET["id"];

$conn->query("DELETE FROM detallefactura WHERE id_facturas=$id");
$conn->query("DELETE FROM facturas WHERE id_facturas=$id");

header("Location: index.php");