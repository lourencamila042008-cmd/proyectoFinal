<?php
require_once __DIR__."../config/db.php/";

class productos{
    private $db;
    public function __construct() {
        $this->db=Database::Conectar();
    }

    public function mostrar(){
        $sql= "SELECT * FROM productos";
        $resul=$this->db->query($sql);
        return $resul->fetch_all(MYSQLI_ASSOC);
}

public function save($id_categoria,$nombre,$stock,$precio_compra,$precio_venta,$min_stock){
    $sql="INSERT INTO productos (id_categoria, nombre, stock, precio_compra, precio_venta, min_stock) VALUES ('$id_categoria','$nombre','$stock','$precio_compra','$precio_venta','$min_stock')";
    return $this->db->query($sql);
}

public function GetById($id){
    $sql= "SELECT * FROM productos WHERE id_producto=$id";
    $resul=$this->db->query($sql);
    return $resul->fetch_all(MYSQLI_ASSOC);
}

public function update($id,$id_categoria,$nombre,$stock,$precio_compra,$precio_venta,$min_stock){
    $sql="UPDATE productos SET id_categoria='id_categoria', nombre='$nombre', stock='$stock', precio_compra='$precio_compra', precio_venta='$precio_venta', min_stock='$min_stock' WHERE id_productos=$id";
    return $this->db->query($sql);
}
 
public function DELETE($id){
    $sql="DELETE FROM productos WHERE id_producto=$id";
    return $this->db->query($sql);
    
}
    
}

?>