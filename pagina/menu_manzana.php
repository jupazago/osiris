<!DOCTYPE html>
<html lang="es">
<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <title>Inicio de Sesión</title>
    <script type="text/javascript" src="../js/funciones.js"></script>
    <LINK REL=StyleSheet HREF="../CSS/estilos.css">

    <script>
       obtener_detalles_pedidos(); 
    </script>
</head>
<body>
<?php
    //Incluir el archivo que contiene las funciones del lenguaje PHP
    require_once("../PHP/funciones.php");
    //Desactivar Desactivar toda notificación de error
    error_reporting(0);
    $usuario     =      $_POST['u'];
    $clave       =      $_POST['p'];

    $tipo_de_cuenta = iniciar_sesion($usuario, $clave);

    echo " - Nivel de centa ".$tipo_de_cuenta;
    // Notificar todos los errores de PHP
    error_reporting(-1);
    
    //funciones
    crear_sugerido($usuario);
?>

<div class="menu">

</div>
</body>
</html>