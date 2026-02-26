<?php
require_once __DIR__."/../config/db.php";

class productos{
    private $db;

    public function __construct(){
        $this->db = database::Conectar();
    }

    public function mostrar(){
        $sql = "SELECT * FROM productos";
        $result = $this->db->query($sql);

        if(!$result){
            die("error en consulta: " . $this->db->error);

        }
        return $result->fetch_all(MYSQL_ASSOC);
    }

    public function save($id_categoria, $nombre, $stock, $precio_compra, $precio_venta, $min_stock){
        $sql = "INSERT INTO productos(id_categoria, nombre, stock, precio_compra, precio_venta, min_stock) VALUES ('$id_categoria', '$nombre', $stock, $precio_compra, $precio_venta, $min_stock)";
        return $this->db->query($sql);
        }
        public function GetById($id){
            $sql = "SELECT * FROM productos WHERE id_producto = $id";
            $result = $this->db->query($sql);
            return $result->fetch_assoc();
        }

        public function update($id_producto, $id_categoria, $nombre, $stock, $precio_compra, $precio_venta, $min_stock){
            $sql = "UPDATE productos SET id_categoria='$id_categoria', nombre='$nombre', stock='$stock', precio_compra='$precio_compra', precio_venta='$precio_venta', min_stock='$min_stock' WHERE id_producto=$id_producto";
            return $this->db->query($sql);
        }

        public function delete($id_producto){
            $sql = "DELETE FROM productos WHERE id_producto = $id_producto";
            return $this->db->query($sql);
        }
}
?>