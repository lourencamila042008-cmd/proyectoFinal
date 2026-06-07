<?php

// Cargo el modelo que tiene todas las funciones de facturas y ventas
require_once __DIR__."/../models/facturas.php";

// Controlador para manejar todo el proceso de facturación
class FacturasController{

    // 🔹 MOSTRAR FORMULARIO PARA CREAR FACTURA
    // Trae todos los productos para mostrarlos en la vista
    public function crear(){

        $model = new Factura();
        $productos = $model->obtenerProductos();

        // Carga la vista con el formulario
        require_once __DIR__."/../views/Admin/crear_factura.php";
    }

    // 🔹 GUARDAR LA FACTURA COMPLETA
    // Recibe los datos del formulario, calcula y guarda todo
    public function guardar(){

        $model = new Factura();

        // Recibo los datos que vienen del formulario
        $productos = $_POST["producto"];
        $cantidades = $_POST["cantidad"];
        $metodo = $_POST["metodo_pago"];

        $total = 0; // Inicio el total en 0

        // 🔹 CALCULAR EL TOTAL DE LA VENTA
        // Recorro cada producto y sumo su valor al total
        for($i=0;$i<count($productos);$i++){

            $precio = $_POST["precio"][$i];
            $cantidad = $cantidades[$i];

            $total += $precio * $cantidad;
        }

        // Guardo la venta y obtengo su ID
        $idVenta = $model->crearVenta($total);

        // 🔹 GUARDAR DETALLES Y ACTUALIZAR STOCK
        // Recorro otra vez para guardar cada producto vendido y descontar del inventario
        for($i=0;$i<count($productos);$i++){

            // Guarda qué producto, cantidad y precio se vendió
            $model->guardarDetalle(
                $idVenta,
                $productos[$i],
                $cantidades[$i],
                $_POST["precio"][$i]
            );

            // Resta la cantidad vendida del stock del producto
            $model->descontarStock(
                $productos[$i],
                $cantidades[$i]
            );
        }

        // Creo el registro de la factura con el método de pago
        $model->crearFactura($idVenta,$metodo);

        // Regreso a la lista de facturas
        header("Location: index.php?controller=facturas&action=listar");
    }
}

// 🔹 ELIMINAR FACTURA
// Si llega el parámetro 'eliminar' por la URL, borra ese registro
if(isset($_GET["eliminar"])){
    $id = $_GET["eliminar"];

    // Me conecto y ejecuto el borrado
    $conn = Database::Conectar();
    $conn->query("DELETE FROM facturas WHERE id_facturas = $id");

    // Vuelvo a la lista
    header("Location: ../views/Admin/facturas/index.php");
}