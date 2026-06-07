<?php

// Cargo la conexión a la base de datos
require_once "config/db.php";

// Controlador para manejar todo lo relacionado con garantías
class GarantiasController{

    // 🔹 MOSTRAR LISTA DE GARANTÍAS
    // Carga la vista donde se ven todas las garantías registradas
    public function index(){
        require_once "views/Admin/garantias/index.php";
    }

    // 🔹 MOSTRAR FORMULARIO NUEVA GARANTÍA
    // Solo es una prueba para ver si entra a la función
    public function crear(){
        echo "SI ENTRA A CREAR";
        die();
    }


    // 🔹 GUARDAR GARANTÍA NUEVA
    // Recibe los datos del formulario y los guarda en la base
    public function guardar(){

        // Me conecto a la base
        $conn = Database::Conectar();

        // Recibo todos los datos que vienen por el formulario
        $id_facturas = $_POST["id_facturas"];
        $id_producto = $_POST["id_producto"];
        $motivo = $_POST["motivo"];
        $solucion = $_POST["solucion"];
        $estado = $_POST["estado"];
        $fecha_inicio = $_POST["fecha_inicio"];
        $fecha_fin = $_POST["fecha_fin"];

        // Inserto todo en la tabla garantías
        $sql = "INSERT INTO garantias 
        (id_facturas, id_producto, motivo, solucion, estado, fecha_inicio, fecha_fin)
        VALUES 
        ('$id_facturas','$id_producto','$motivo','$solucion','$estado','$fecha_inicio','$fecha_fin')";

        $conn->query($sql);

        // Regreso a la lista con mensaje de confirmación
        header("Location: /MVC-PRU/index.php?controller=Garantias&action=index&msg=creado");
        exit();
    }
}