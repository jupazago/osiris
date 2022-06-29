<script type="text/javascript" src="../js/funciones.js"></script>
<?php
    //Incluir el archivo que contiene las funciones del lenguaje PHP
    require_once("../PHP/funciones.php");

    if(existencia_de_la_conexion()){
        require_once("../PHP/conexion.php");    //Hacer conexion con la base de datos
    }
    $conexion = conectar();

    date_default_timezone_set('America/Bogota');
    $fecha = date('Y-m-d', time());
    $hoy = date("H:i");

    // Desactivar toda notificación de error
    error_reporting(0);

    //date('h:i'); //Fecha justo ahora
    $llegadass        = $_POST['llegadass'];

    

    
    foreach ($llegadass as $value) {
        if(count($llegadass) > 0){
            $consulta = mysqli_query($conexion, "SELECT `estado` FROM `domicilio` WHERE `id_domi` = '$value'") or die ("Error al consultar: domicilios");
            

            while (($fila = mysqli_fetch_array($consulta))!=NULL){
                $estado = $fila['estado'];
                mysqli_free_result($consulta); //Liberar espacio de consulta cuando ya no es necesario
                if($estado == "proceso"){

                    $consulta = mysqli_query($conexion, "UPDATE `domicilio` SET `tiempo_llegada`='$hoy', `estado`='inactivo' WHERE `estado` = 'proceso' AND `id_domi` = '$value'
                    ORDER BY `id_domi` ASC") or die ("Error al consultar: domicilios");
                }elseif($fila['estado'] == "activo"){
                    
                    ?>
                    <br>
                    <div class="alert warning">
                        <span class="closebtn">&times;</span>  
                        <strong>Información!</strong> Primero debes iniciar el domicilio
                    </div>
                    <?php
                }
                break;
            }
        }
    }
    // Notificar todos los errores de PHP
    error_reporting(-1);

?>