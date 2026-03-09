<?php

require_once "../config/db.php";

$conn = Database::Conectar();

$id_factura = $_POST["id_factura"];
$id_producto = $_POST["id_producto"];
$motivo = $_POST["motivo"];
$solucion = $_POST["solucion"];
$estado = $_POST["estado"];

$stmt = $conn->prepare("

INSERT INTO garantias
(id_factura,id_producto,motivo,solucion,estado)

VALUES (?,?,?,?,?)

");

$stmt->bind_param("iisss",$id_factura,$id_producto,$motivo,$solucion,$estado);

$stmt->execute();

header("Location: ../views/garantias/index.php");

?>