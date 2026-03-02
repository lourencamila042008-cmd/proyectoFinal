<?php
session_start();

// 🔥 ELIMINAR TODAS LAS VARIABLES DE SESIÓN
session_unset();

// 🔥 DESTRUIR SESIÓN
session_destroy();

// 🚀 REDIRIGIR AL LOGIN
header("Location: login.php");
exit();