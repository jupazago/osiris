<script type="text/javascript" src="../js/funciones.js"></script>
<?php
    //Incluir el archivo que contiene las funciones del lenguaje PHP
    require_once("../PHP/funciones.php");

    if(existencia_de_la_conexion()){
        require_once("../PHP/conexion.php");    //Hacer conexion con la base de datos
    }
    $conexion = conectar();                     //Obtenemos la conexion

    date_default_timezone_set('America/Bogota');
    $fecha        = date('Y-m-d', time());

    //Verificamos si el proveedor existe
    $existe_proveedor = false;

    $array_prove = array();

    $contador = 0;

    $provedores = array();

    $id_cuenta = array();
    $nombre = array();
    $factura = array();
    $fechas = array();
    $dias = array();
    $costo = array();
    $fechas_limite = array();
    
    //Consulta a la base de datos en la tabla provvedor
    $consulta = mysqli_query($conexion, "SELECT `nombre_proveedor` FROM `proveedor` WHERE `estado` = 'activo' ORDER BY `nombre_proveedor` ASC") or die ("Error al consultar: proveedores");

    while (($fila = mysqli_fetch_array($consulta))!=NULL){
        array_push($provedores, strval($fila['nombre_proveedor']));
    }
    mysqli_free_result($consulta); //Liberar espacio de consulta cuando ya no es necesario

    //Consulta a la base de datos en la tabla provvedor
    $consulta = mysqli_query($conexion, "SELECT * FROM `cuenta_cobro` 
    WHERE `estado` = 'activo' 
    ORDER BY `id_cuenta` ASC") or die ("Error al consultar: proveedores");

    ?>
    <form id="actualizar_cuentas_cobro" method="POST">
        <table border="1" id="tabla_sugerido" width="100%">
        <tr>
            <th>#</th>
            <th>Empresa</th>
            <th>Factura</th>
            <th>Fecha de Ingreso</th>
            <th>Plazo</th>
            <th>Fecha límite</th>
            <th>Estado</th>
            <th>Valor</th>
            <th></th>
            <th>Eliminar</th>
        </tr>
        <tr>
        <?php
        $costo_total = 0;
        while (($fila = mysqli_fetch_array($consulta))!=NULL){
            

            array_push($id_cuenta, $fila['id_cuenta']);
            array_push($nombre, $fila['nombre']);
            array_push($factura, $fila['factura']);
            array_push($fechas, $fila['fecha']);
            array_push($dias, $fila['dias']);
            array_push($costo, $fila['costo']);
            

            $fecha_limite = date("Y-m-d",strtotime($fila['fecha']." + ".$fila['dias']." days"));

            array_push($fechas_limite, $fecha_limite);

        }
        mysqli_free_result($consulta); //Liberar espacio de consulta cuando ya no es necesario


        for ($i = 0; $i < count($id_cuenta); $i++) { 
            $contador2 = $contador;
            $contador++;
            echo "<td>".$contador."</td>";

            ?>
            <input type="hidden" name="id_cuenta[]" value="<?php echo $id_cuenta[$i] ?>"/>
            <?php
                if($nombre[$i] != ''){
                    ?>



                    <td><input list="proveedores" name="proveedor[]" id="proveedor" value="<?php echo $nombre[$i] ?>"></th>
                    <datalist id="proveedores"  required>
                        
                    <?php
                    //Consulta a la base de datos en la tabla provvedor
                    $consulta = mysqli_query($conexion, "SELECT `nombre_proveedor` FROM `proveedor` WHERE `estado` = 'activo' ORDER BY `nombre_proveedor` ASC") or die ("Error al consultar: proveedores");

                    while (($fila = mysqli_fetch_array($consulta))!=NULL){
                        if($nombre[$i] != $fila['nombre_proveedor']){
                            ?>
                            <option value="<?php echo $fila['nombre_proveedor'] ?>"></option>";
                            <?php
                        }
                    }
                    mysqli_free_result($consulta); //Liberar espacio de consulta cuando ya no es necesario
                    ?>
                    </datalist>

                    <?php 
                }else{
                    ?>
                    <td><input list="proveedores" name="proveedor[]" id="proveedor"></th>
                    <datalist id="proveedores"  required>

                    <?php
                    //Consulta a la base de datos en la tabla provvedor
                    $consulta = mysqli_query($conexion, "SELECT `nombre_proveedor` FROM `proveedor` WHERE `estado` = 'activo' ORDER BY `nombre_proveedor` ASC") or die ("Error al consultar: proveedores");

                    while (($fila = mysqli_fetch_array($consulta))!=NULL){
                        ?>
                        <option value="<?php echo $fila['nombre_proveedor'] ?>"></option>";
                        <?php
                    }
                    mysqli_free_result($consulta); //Liberar espacio de consulta cuando ya no es necesario
                    ?>
                    </datalist>
                    <?php
                }             
            ?>

            <td><input type="text" name="factura[]" value="<?php echo $factura[$i] ?>"/></td>
            <td><input type="date" name="fecha[]" value="<?php echo $fechas[$i] ?>"/></td>
            <td><input type="text" name="dias[]" value="<?php echo $dias[$i] ?>" size="2"/></td>

            
            <?php
            $fecha1  = new DateTime($fecha);
            $fecha2  = new DateTime($fechas_limite[$i]);
            
            $intvl   = $fecha1->diff($fecha2);


            echo "<td>".date("Y-m-d",strtotime($fechas[$i]." + ".$dias[$i]." days"))."</td>";

            $f = date("Y-m-d",strtotime($fechas[$i]." + ".$dias[$i]." days"));

            if($fecha1 > $fecha2){
                echo "<td style='background-color:red;border: 2px solid white;text-align: center;color:white;'> - ".$intvl->days."</td>";

            }elseif($intvl->days < 7 && $intvl->days > 0){
                echo "<td style='background-color:red;border: 2px solid white;text-align: center;color:white;'>".$intvl->days."</td>";

            }elseif($intvl->days >= 7 && $intvl->days < 15){
                echo "<td style='background-color:yellow;border: 2px solid white;text-align: center;'>".$intvl->days."</td>";

            }elseif($intvl->days >= 15){
                echo "<td style='background-color:green;border: 2px solid white;text-align: center;color:white;'>".$intvl->days."</td>";

            }elseif($intvl->days == 0){
                echo "<td style='background-color:black;border: 2px solid white;text-align: center;color:white;'>".$intvl->days."</td>";

            }

            ?>

            <td><input type="text" name="costo[]" value="<?php echo $costo[$i] ?>" size="10"/></td>

            <?php
            $costo_total += $costo[$i];

            ?>
            <td><input type="radio" name="estado[<?php echo $contador ?>]" value="activo" style="visibility:hidden;" checked>
            <input type="radio" name="estado[<?php echo $contador ?>]" value="inactivo">
                Pagar<br></td>

            <?php
            if($nombre[$i] == ''){
                ?>
                <td class="w3-btn w3-red"><input type="radio" name="eliminar[<?php echo $contador ?>]" value="activo" style="visibility:hidden;" checked>
                <input type="radio" name="eliminar[<?php echo $contador ?>]" value="eliminar" id="eliminar[<?php echo $contador ?>]" onchange="$('#enviar6_2').trigger('click');">
                <label for="eliminar[<?php echo $contador ?>]">X</label><br></td> 
                <?php
            }else{
                ?>
                <td><input type="radio" name="eliminar[<?php echo $contador ?>]" value="activo" style="visibility:hidden;" checked>
                <input type="radio" name="eliminar[<?php echo $contador ?>]" value="eliminar" id="eliminar[<?php echo $contador ?>]" style="visibility:hidden;" onchange="$('#enviar6_2').trigger('click');"></td> 
                <?php
            }

            echo "</tr>";
        
        
        }
        ?>   
            
            
            
        <tr>
            <td></td>
            <td><button type="button" id="enviar6_3" class="w3-btn"><i class="fa fa-plus-circle" style="font-size:24px;color:#305490"></i></button></td>
            <td></td>
            <td><button type="button" id="enviar6_4" class="w3-btn" onclick="document.getElementById('respuesta6_4').style.display='block'" style="width:auto; background-color:#305490; color:white;">Ver cuentas canceladas</button></td>
        <?php
        echo "<td colspan='2'></td><td>Total</td><th>".number_format($costo_total, 0, ',', '.')."</th>";
        ?>
        <td><button type="button" id="enviar6_2" class="w3-btn" style="background-color: #478248;color:white;" onclick="document.getElementById('respuesta6_2').style.display='block'">Guardar <i class='fas fa-edit' style='font-size:24px;color:white'></button></td></tr>
        <?php
        
        ?>
    </tr>
    </table>

    <?php

    ?>
    </form>
    <div id="respuesta6_2"></div>
    <div id="respuesta6_4" style="position:absolute; top:0;left:0;background:white;width:100%;height: 100%;display:none;"></div>
    <script>
    $('#enviar6_2').click(function(){
        $.ajax({
            url:'../php/consulta6_2.php',
            type:'POST',
            data: $('#actualizar_cuentas_cobro').serialize(),
            success: function(res){
                Swal.fire(
                '¡Muy bien!',
                'Guardado Exitoso',
                'success'
                )
                $('#enviar6_1').trigger('click');
            },
            error: function(res){
                alert("Problemas al tratar de enviar el formulario");
            }
        });
    });

    $('#enviar6_3').click(function(){
        $.ajax({
            url:'../php/consulta6_3.php',
            success: function(res){
                $('#enviar6_1').trigger('click');
            },
            error: function(res){
                alert("Problemas al tratar de enviar el formulario");
            }
        });
    });
    $('#enviar6_4').click(function(){
        $.ajax({
            url:'../php/consulta6_4.php',
            success: function(res){
                $('#respuesta6_4').html(res);
            },
            error: function(res){
                alert("Problemas al tratar de enviar el formulario");
            }
        });

    });


    

    </script>
    
    <?php 
    mysqli_close($conexion);     //---------------------- Cerrar conexion ------------------
?>