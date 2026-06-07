<?php

// Cargo la conexión a la base de datos
require_once __DIR__."/../config/db.php";

// Clase para manejar todo lo de los productos
class productos{

    // Variable para guardar la conexión
    private $db;

    // Al iniciar, conecta a la base de datos
    public function __construct(){
        $this->db = database::Conectar();
    }

    // 🔹 MOSTRAR TODOS LOS PRODUCTOS
    // Trae todos los registros de la tabla productos
    public function mostrar(){
        $sql = "SELECT * FROM productos";
        $result = $this->db->query($sql);

        // Si hay error en la consulta, lo muestra
        if(!$result){
            die("error en consulta: " . $this->db->error);
        }
        // Devuelve todos los datos en forma de arreglo
        return $result->fetch_all(MYSQL_ASSOC);
    }

    // 🔹 GUARDAR PRODUCTO NUEVO
    // Inserta un producto con todos sus datos: categoría, nombre, stock, precios, stock mínimo
    public function save($id_categoria, $nombre, $stock, $precio_compra, $precio_venta, $min_stock){
        $sql = "INSERT INTO productos(id_categoria, nombre, stock, precio_compra, precio_venta, min_stock) VALUES ('$id_categoria', '$nombre', $stock, $precio_compra, $precio_venta, $min_stock)";
        return $this->db->query($sql);
    }

    // 🔹 BUSCAR UN PRODUCTO POR ID
    // Obtiene los datos de un solo producto según su código
    public function GetById($id){
        $sql = "SELECT * FROM productos WHERE id_producto = $id";
        $result = $this->db->query($sql);
        return $result->fetch_assoc();
    }

    // 🔹 ACTUALIZAR PRODUCTO
    // Modifica todos los datos de un producto ya existente
    public function update($id_producto, $id_categoria, $nombre, $stock, $precio_compra, $precio_venta, $min_stock){
        $sql = "UPDATE productos SET id_categoria='$id_categoria', nombre='$nombre', stock='$stock', precio_compra='$precio_compra', precio_venta='$precio_venta', min_stock='$min_stock' WHERE id_producto=$id_producto";
        return $this->db->query($sql);
    }

    // 🔹 ELIMINAR PRODUCTO
    // Borra el registro de la base de datos
    public function delete($id_producto){
        $sql = "DELETE FROM productos WHERE id_producto = $id_producto";
        return $this->db->query($sql);
    }
}
?>