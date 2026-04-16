<?php
require_once "config/db.php";

class GarantiasController{

    // 🔹 Mostrar lista
    public function index(){
        require_once "views/Admin/garantias/index.php";
    }

    // 🔹 Mostrar formulario

    public function crear(){
        echo "SI ENTRA A CREAR";
        die();
    }


    // 🔹 Guardar garantía
    public function guardar(){

        $conn = Database::Conectar();

        $id_facturas = $_POST["id_facturas"];
        $id_producto = $_POST["id_producto"];
        $motivo = $_POST["motivo"];
        $solucion = $_POST["solucion"];
        $estado = $_POST["estado"];
        $fecha_inicio = $_POST["fecha_inicio"];
        $fecha_fin = $_POST["fecha_fin"];

        $sql = "INSERT INTO garantias 
        (id_facturas, id_producto, motivo, solucion, estado, fecha_inicio, fecha_fin)
        VALUES 
        ('$id_facturas','$id_producto','$motivo','$solucion','$estado','$fecha_inicio','$fecha_fin')";

        $conn->query($sql);

        header("Location: /MVC-PRU/index.php?controller=Garantias&action=index&msg=creado");
        exit();
    }
}