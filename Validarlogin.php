<?php
session_start();
include("DB.php");

$usuarioI = $_POST["usuario"];
$claveI = $_POST["clave"];

$sql = "SELECT * FROM usuarios WHERE usuario='$usuarioI'";
$consultar = mysqli_query($conexion, $sql);
$resultado = mysqli_fetch_assoc($consultar);

if ($resultado) {

    $claveBD = $resultado["clave"];

    if (password_verify($claveI, $claveBD) || $claveI == $claveBD) {

        $_SESSION["usuario"] = $usuarioI;

        header("Location: panel.php");
        exit;

    } 
    else 
        {

        echo "<script>
                alert('Contraseña incorrecta');
                window.location='index.php';
              </script>";
    }

} 
else 
{

    echo "<script>
            alert('El usuario es incorrecto o no existe');
            window.location='index.php';
          </script>";
}
?>
