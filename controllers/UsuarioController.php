<?php

class UsuarioController{

    public function index(){
        echo "<h1>Bienvenida a InvoicePro 💙</h1>";
        echo "<a href='index.php?controller=auth&action=logout'>Cerrar sesión</a>";
    }
}