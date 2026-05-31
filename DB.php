<?php
$host = "localhost";
$user = "root";
$pass = "";
$DB = "ProyectoEV";

$conexion = mysqli_connect($host, $user, $pass, $DB);

if (!$conexion) {
    die("Error de conexión: " . mysqli_connect_error());
}
?>
