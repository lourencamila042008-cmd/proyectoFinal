<?php

require_once __DIR__."/../models/Factura.php";

class FacturasController{

public function crear(){

$model = new Factura();
$productos = $model->obtenerProductos();

require_once __DIR__."/../views/Admin/crear_factura.php";

}

public function guardar(){

$model = new Factura();

$productos = $_POST["producto"];
$cantidades = $_POST["cantidad"];
$metodo = $_POST["metodo_pago"];

$total = 0;

for($i=0;$i<count($productos);$i++){

$precio = $_POST["precio"][$i];
$cantidad = $cantidades[$i];

$total += $precio * $cantidad;

}

$idVenta = $model->crearVenta($total);

for($i=0;$i<count($productos);$i++){

$model->guardarDetalle(
$idVenta,
$productos[$i],
$cantidades[$i],
$_POST["precio"][$i]
);

$model->descontarStock(
$productos[$i],
$cantidades[$i]
);

}

$model->crearFactura($idVenta,$metodo);

header("Location: index.php?controller=facturas&action=listar");

}

}