<?php
require_once __DIR__."/../config/db.php";

class Factura{

private $conn;

public function __construct(){
$this->conn = Database::Conectar();
}

public function obtenerProductos(){
$sql = "SELECT * FROM productos";
return $this->conn->query($sql);
}

public function crearVenta($total){

$stmt = $this->conn->prepare("INSERT INTO ventas(total) VALUES(?)");
$stmt->bind_param("d",$total);
$stmt->execute();

return $this->conn->insert_id;
}

public function guardarDetalle($idVenta,$producto,$cantidad,$precio){

$stmt = $this->conn->prepare("
INSERT INTO detalleventa(id_venta,id_productos,cantidad,precio)
VALUES(?,?,?,?)");

$stmt->bind_param("iiid",$idVenta,$producto,$cantidad,$precio);
$stmt->execute();

}

public function descontarStock($producto,$cantidad){

$stmt = $this->conn->prepare("
UPDATE productos
SET stock = stock - ?
WHERE id_productos = ?");

$stmt->bind_param("ii",$cantidad,$producto);
$stmt->execute();

}

public function crearFactura($idVenta,$metodo){

$stmt = $this->conn->prepare("
INSERT INTO facturas(id_venta,estado,metodo_pago)
VALUES(?, 'pagada', ?)");

$stmt->bind_param("is",$idVenta,$metodo);
$stmt->execute();

return $this->conn->insert_id;

}

}
?>