<?php

// Cargo la conexión a la base de datos
require_once __DIR__."/../config/db.php";

// Clase para manejar todo lo relacionado con facturas y ventas
class Factura{

    // Variable para guardar la conexión
    private $conn;

    // Al iniciar, conecta a la base
    public function __construct(){
        $this->conn = Database::Conectar();
    }

    // 🔹 OBTENER TODOS LOS PRODUCTOS
    // Trae la lista completa de productos para mostrar al vender
    public function obtenerProductos(){
        $sql = "SELECT * FROM productos";
        return $this->conn->query($sql);
    }

    // 🔹 CREAR REGISTRO DE VENTA
    // Guarda el total de la venta y devuelve el ID que se generó
    public function crearVenta($total){

        $stmt = $this->conn->prepare("INSERT INTO ventas(total) VALUES(?)");
        $stmt->bind_param("d",$total); // "d" porque es número con decimales
        $stmt->execute();

        return $this->conn->insert_id;
    }

    // 🔹 GUARDAR DETALLES DE LA VENTA
    // Guarda qué productos se compraron, cantidad y precio, ligado a la venta
    public function guardarDetalle($idVenta,$producto,$cantidad,$precio){

        $stmt = $this->conn->prepare("
        INSERT INTO detalleventa(id_venta,id_productos,cantidad,precio)
        VALUES(?,?,?,?)");

        $stmt->bind_param("iiid",$idVenta,$producto,$cantidad,$precio);
        $stmt->execute();
    }

    // 🔹 DESCONTAR DEL INVENTARIO
    // Resta la cantidad vendida del stock del producto
    public function descontarStock($producto,$cantidad){

        $stmt = $this->conn->prepare("
        UPDATE productos
        SET stock = stock - ?
        WHERE id_productos = ?");

        $stmt->bind_param("ii",$cantidad,$producto); // "i" porque son números enteros
        $stmt->execute();
    }

    // 🔹 CREAR LA FACTURA
    // Guarda la factura con su venta, estado 'pagada' y método de pago
    public function crearFactura($idVenta,$metodo){

        $stmt = $this->conn->prepare("
        INSERT INTO facturas(id_venta,estado,metodo_pago)
        VALUES(?, 'pagada', ?)");

        $stmt->bind_param("is",$idVenta,$metodo); // "i" número, "s" texto
        $stmt->execute();

        return $this->conn->insert_id; // Devuelve el ID de la factura nueva
    }

}
?>