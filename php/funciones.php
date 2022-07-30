<script src="https://ajax.googleapis.com/ajax/libs/jquery/2.1.1/jquery.min.js"></script>
<script src='https://kit.fontawesome.com/a076d05399.js' crossorigin='anonymous'></script>
<script>$.ajaxPrefilter(function( options, originalOptions, jqXHR ) {    options.async = true; });</script>
<script type="text/javascript" src="../js/funciones.js"></script>

<?php

    //Funcion que verifica si existeel archivo de conexion de la base de datos
function existencia_de_la_conexion(){
    try {
        //Verificar si existe el archivo de conexion
        if(!file_exists('../PHP/conexion.php')){
            throw new Exception ('PHP: File  -conexion-  no existe',1);  //NO existe, captura excepcion
        }else{
            return true;
            //require_once("../PHP/conexion.php");                //SI Existe, continuar y realizar la conexion
        }
    
    } catch (Exception $excepcion) {
        //Captura de excepcion y su respectivo codigo
        echo 'Capture: ' .  $excepcion->getMessage(), "<br>";
        echo 'Código: ' . $excepcion->getCode(), "<br>";
    }
}
///////////////////////////////////////////////////////////////////////////////////////////////////////////////

function iniciar_sesion($usuario, $clave){
        
    if(existencia_de_la_conexion()){
        require_once("../PHP/conexion.php");    //Hacer conexion con la base de datos
    }
    $conexion = conectar();                     //Obtenemos la conexion
    
    //Consulta a la base de datos en la tabla login
    $consulta = mysqli_query($conexion, "SELECT `user_pers`, `pass_pers`, `tipo_usuario_pers` FROM `personal` WHERE `estado` ='activo'")
    or die ("Error al iniciar sesión: ");

    $encontrado = false;
    while (($fila = mysqli_fetch_array($consulta))!=NULL){

    //Comprobamos la existencia del usuario y contraseña del formulario en los resulatdos de la bases de datos
        if($usuario == $fila['user_pers'] && $clave == $fila['pass_pers']){
            //Existe en la base de datos y es conrrecto los datos
            $tipo_de_cuenta = $fila['tipo_usuario_pers']; //Obtenemos su tipo de cuenta
            echo "<div class='usuario'>".$fila['user_pers'];
            $encontrado = true;
            mysqli_free_result($consulta); //Liberar espacio de consulta cuando ya no es necesario
            mysqli_close($conexion);     //---------------------- Cerrar conexion ------------------
            break;
        }
    }
    if($encontrado==false){
        mysqli_free_result($consulta); //Liberar espacio de consulta cuando ya no es necesario
        mysqli_close($conexion);     //---------------------- Cerrar conexion ------------------
        //Si no se encontró registro alguno, regresamos al index de inicio de sesión
        ?>
        <script type="text/javascript">
            window.history.back();
        </script>
        <?php
    }
    return $tipo_de_cuenta;
}
//////////////////////////////////////////////////////////////////////////////////////////////////
function iniciar_sesion2($usuario, $clave){
    
    if(existencia_de_la_conexion()){
        require_once("../PHP/conexion.php");    //Hacer conexion con la base de datos
    }
    $conexion = conectar();                     //Obtenemos la conexion
    
    //Consulta a la base de datos en la tabla login
    $consulta = mysqli_query($conexion, "SELECT `nombre_proveedor`, `user`, `pass`, `estado` FROM `proveedor` WHERE `estado` = 'activo'")
    or die ("Error al iniciar sesión: ");

    $encontrado = false;
    while (($fila = mysqli_fetch_array($consulta))!=NULL){

    //Comprobamos la existencia del usuario y contraseña del formulario en los resulatdos de la bases de datos
        if($usuario == $fila['user'] && $clave == $fila['pass']){
            //Existe en la base de datos y es conrrecto los datos
            $nombre_proveedor = $fila['nombre_proveedor']; //Obtenemos su tipo de cuenta
            echo "<div class='usuario'>".$fila['user'];
            $encontrado = true;
            mysqli_free_result($consulta); //Liberar espacio de consulta cuando ya no es necesario
            mysqli_close($conexion);     //---------------------- Cerrar conexion ------------------
            break;
        }
    }
    if($encontrado==false){
        mysqli_free_result($consulta); //Liberar espacio de consulta cuando ya no es necesario
        mysqli_close($conexion);     //---------------------- Cerrar conexion ------------------
        //Si no se encontró registro alguno, regresamos al index de inicio de sesión
        ?>
        <script type="text/javascript">
            window.history.back();
        </script>
        <?php
    }
    return $nombre_proveedor;
}
////////////////////////////////////////////////////////////////////////////////////////////////////////
//Funcion que verifica si existeel archivo de conexion de la base de datos
function crear_sugerido($usuario){
    if(existencia_de_la_conexion()){
        require_once("../PHP/conexion.php");    //Hacer conexion con la base de datos
    }
    $conexion = conectar();                     //Obtenemos la conexion
    ?>
    <input type="text" id="myInput" onkeyup="myFunctionTabla()" placeholder="Nombrel del proveedor.." title="Type in a name">

    <form name="form_seleccionar_prove" id="form_seleccionar_prove" method='post'>

        <input type="hidden" name="nombre" id="prove_sugerido"/>
        <input type="hidden" name="usuario" value="<?php echo $usuario ?>"/>
        <table class="table_sugerido" id="myTable">
        <tr>
            <th>#</th>
            <th>Nombre</th>
            <th></th>
            <th></th>
        </tr>

        <?php
        $consulta = mysqli_query($conexion, "SELECT `id_proveedor`,`nombre_proveedor` 
        FROM `proveedor` 
        WHERE `estado` = 'activo'") or die ("Error al consultar: proveedores");

        
        $nombre_provee = array();
        while (($fila = mysqli_fetch_array($consulta))!=NULL){
            
            array_push($nombre_provee , $fila['nombre_proveedor']);
            
        }
        mysqli_free_result($consulta); //Liberar espacio de consulta cuando ya no es necesario

        $contador = 0;
        for ($i=0; $i < count($nombre_provee); $i++) { 
            $contador++;
            ?>
            <tr id="<?php echo $contador ?>">
                <td><?php echo $contador ?></td>
                <td class="row-data"><?php echo $nombre_provee[$i] ?></td>
                <td><input type="button" class="w3-btn w3-green" value="Nuevo" onclick="show1_3();" ><td>
                <td><input type="button" class="w3-btn w3-teal" value="Continuar" onclick="show1()"/></td>
            </tr>
            <?php
        }
        

        ?>
    <form>
    </table>
    <a class="w3-bar-item w3-button w3-red w3-hover-red active salir" onclick="document.getElementById('cont1_1').style.display='none'">X</a>
    <button type="button" id="enviar1" onclick="document.getElementById('respuesta1').style.display='block'" style="display: none;"></button>
    <button type="button" id="enviar1_3" style="display: none;"></button>
            
    <div id="respuesta1" class="ventana">


        

    </div>
    <script>
        $('#enviar1').click(function(){
            $.ajax({
                url:'../php/consulta1.php',
                type:'POST',
                data: $('#form_seleccionar_prove').serialize(),
                success: function(res){
                    $('#respuesta1').html(res);
                },
                error: function(res){
                    alert("Problemas al tratar de enviar el formulario");
                }
            });
        });
        $('#enviar1_3').click(function(){
            $.ajax({
                url:'../php/consulta1_3.php',
                type:'POST',
                data: $('#form_seleccionar_prove').serialize(),
                success: function(res){
                    $('#enviar1').trigger('click');
                },
                error: function(res){
                    alert("Problemas al tratar de enviar el formulario");
                }
            });
        });
    </script>
    <style>

        #myInput {
        background-image: url('/css/searchicon.png');
        background-position: 10px 10px;
        background-repeat: no-repeat;
        width: 90%;
        font-size: 16px;
        padding: 12px 20px 12px 40px;
        border: 1px solid #ddd;
        margin-bottom: 12px;
        }

        #myTable {
        border-collapse: collapse;
        width: 100%;
        border: 1px solid #ddd;
        font-size: 18px;
        }

        #myTable th, #myTable td {
        text-align: left;
        padding: 12px;
        }

        #myTable tr {
        border-bottom: 1px solid #ddd;
        }

        #myTable tr.header, #myTable tr:hover {
        background-color: #f1f1f1;
        }
    </style>
    </div>
<?php
}

////////////////////////////////////////////////////////////////////////////////////////////////////////
//Funcion que verifica si existeel archivo de conexion de la base de datos
function crear_pedido($name_proveedor){
    ?>
    <div>
    <form id="form_crear_pedido" method="POST">
        <input type="hidden" name="name_proveedor" value="<?php echo $name_proveedor; ?>">
        <button type="button" id="enviar2" class="w3-btn w3-green" onclick="document.getElementById('respuesta2').style.display='block';" style="display:none;">Crear Pedido</button><br><br>
    </form>
            
    <div id="respuesta2"></div>
    <script>
        $('#enviar2').click(function(){
            $.ajax({
                url:'../php/consulta2.php',
                type:'POST',
                data: $('#form_crear_pedido').serialize(),
                success: function(res){
                    $('#respuesta2').html(res);
                },
                error: function(res){
                    alert("Problemas al tratar de enviar el formulario");
                }
            });
        });
    </script>
    </div>
<?php
}

////////////////////////////////////////////////////////////////////////////////////////////////////////
//Funcion que verifica si existeel archivo de conexion de la base de datos
function crear_pedido2($name_proveedor){
    ?>
    <div>
    <form id="form_confirmar_pedido" method="POST">
        <input type="hidden" name="name_proveedor" value="<?php echo $name_proveedor; ?>">
        <button type="button" id="enviar3" class="w3-btn w3-green" onclick="document.getElementById('respuesta3').style.display='block';" style="display:none;">Siguiente</button><br><br>
    </form>
            
    <div id="respuesta3"></div>
    <script>
        $('#enviar3').click(function(){
            $.ajax({
                url:'../php/consulta3.php',
                type:'POST',
                data: $('#form_confirmar_pedido').serialize(),
                success: function(res){
                    $('#respuesta3').html(res);
                },
                error: function(res){
                    alert("Problemas al tratar de enviar el formulario");
                }
            });
        });
    </script>
    </div>
<?php
}

////////////////////////////////////////////////////////////////////////////////////////////////////////
//Funcion que verifica si existeel archivo de conexion de la base de datos
function ver_pedidos($usuario){
    ?>
    <div>
    <div id="respuesta4"></div>
    <form id="ver_pedidos" method="POST">
        <fieldset>
        <button type="button" id="enviar4" class="w3-btn w3-red" onclick="document.getElementById('respuesta4').style.display='block'">Ver fechas <i class='far fa-calendar-alt'></i></button>
        <input type="reset" value="Limpiar" class="w3-btn w3-red" onclick="document.getElementById('respuesta4').style.display='none'">
        </fieldset>
    </form>
    
    <script>
        $('#enviar4').click(function(){
            $.ajax({
                url:'../php/consulta4.php',
                type:'POST',
                data: $('#ver_pedidos').serialize(),
                success: function(res){
                    $('#respuesta4').html(res);
                },
                error: function(res){
                    alert("Problemas al tratar de enviar el formulario");
                }
            });
        });
    </script>
    </div>
<?php
}

///////////////////////////////////////////////////////////////////////////////////////////////////////
function control_domiciliario($usuario, $tipo_de_cuenta){

    if(existencia_de_la_conexion()){
        require_once("../PHP/conexion.php");    //Hacer conexion con la base de datos
    }
    $conexion = conectar();                     //Obtenemos la conexion

    if($tipo_de_cuenta == 1 || $tipo_de_cuenta == 2 || $tipo_de_cuenta == 3){
        ?>
        <form id="seleccion_vehiculo" method="POST">
            <input type="hidden" name="usuario" value="<?php echo $usuario; ?>">
            <input type="hidden" name="tipo_de_cuenta" value="<?php echo $tipo_de_cuenta; ?>">
            <fieldset><legend>Selecciona el vehículo:</legend>
            <input list="vehiculos" name="vehiculo" id="vehiculo"  required>
            <datalist id="vehiculos"  required>

            <?php
                //Consulta a la base de datos en la tabla provvedor
                $consulta = mysqli_query($conexion, "SELECT * FROM `vehiculo` WHERE `estado` = 'activo'") or die ("Error al consultar: proveedores");

                while (($fila = mysqli_fetch_array($consulta))!=NULL){
                    // traemos los proveedores existentes en la base de datos
                    echo "<option value=".$fila['placa']."></option>";
                }
                mysqli_free_result($consulta); //Liberar espacio de consulta cuando ya no es necesario
            ?>
            </datalist>

            <button type="button" id="enviar5" class="w3-btn w3-teal" onclick="document.getElementById('respuesta5').style.display='block'">Continuar</button>
            </fieldset>
        </form>

        <div id="respuesta5"></div>
        <script>
            $('#enviar5').click(function(){
                $.ajax({
                    url:'../php/consulta5.php',
                    type:'POST',
                    data: $('#seleccion_vehiculo').serialize(),
                    success: function(res){
                        $('#respuesta5').html(res);
                    },
                    error: function(res){
                        alert("Problemas al tratar de enviar el formulario");
                    }
                });
            });
        </script>
        </div>
        <?php

    }elseif($tipo_de_cuenta == 4){
        ?>
        <form id="seleccion_vehiculo" method="POST">
            <fieldset><legend>Selecciona el vehículo:</legend>
            <input list="vehiculos" name="vehiculo" id="vehiculo"  required>
            <datalist id="vehiculos"  required>
            <?php
                //Consulta a la base de datos en la tabla provvedor
                $consulta = mysqli_query($conexion, "SELECT * FROM `vehiculo` WHERE `estado` = 'activo'") or die ("Error al consultar: proveedores");

                while (($fila = mysqli_fetch_array($consulta))!=NULL){
                    // traemos los proveedores existentes en la base de datos
                    echo "<option value=".$fila['placa']."></option>";
                }
                mysqli_free_result($consulta); //Liberar espacio de consulta cuando ya no es necesario
            ?>
            </datalist>

            <button type="button" id="enviar5_2" class="w3-btn w3-teal" onclick="document.getElementById('respuesta5_2').style.display='block'; var intervalo_time = setInterval(myTimer2, 60000); setInterval(myTimer2, 60000)">Continuar</button>
            </fieldset>
        </form>

        <div id="respuesta5_2"></div>
        <script>
            $('#enviar5_2').click(function(){
                $.ajax({
                    url:'../php/consulta5_2.php',
                    type:'POST',
                    data: $('#seleccion_vehiculo').serialize(),
                    success: function(res){
                        $('#respuesta5_2').html(res);
                        
                    },
                    error: function(res){
                        alert("Problemas al tratar de enviar el formulario");
                    }
                });
            });
        </script>
        </div>
        <?php
    }
}

/////////////////////////////////////////////////////////////////////////////////////////
function cuentas_por_pagar($usuario){
    ?>
    <button type="button" id="enviar6_1" class="w3-btn w3-red"  style="visibility:hidden;" onclick="document.getElementById('respuesta6_1').style.display='block'">Inscribir Cuenta</button>
    
    <div id="respuesta6_1"></div>

    <script>
        $('#enviar6_1').click(function(){
            $.ajax({
                url:'../php/consulta6_1.php',
                type:'POST',
                success: function(res){
                    $('#respuesta6_1').html(res);
                },
                error: function(res){
                    alert("Problemas al tratar de enviar el formulario");
                }
            });
        });
    </script>
    </div>
<?php
}

/////////////////////////////////////////////////////////////////////////////////////////

function menu_proveedor($usuario){
    ?>
    


    <div id="respuesta7_0" style="display:none; backgroung-color:white;"></div>
    <div id="respuesta7_1" style="display:none; backgroung-color:white;"></div>

    <br>
    <button type="button" id="enviar7_1" class="w3-btn w3-red" onclick="document.getElementById('respuesta7_1').style.display='block'"> Información</button>
    <button type="button" id="enviar7_0" class="w3-btn w3-red" onclick="document.getElementById('respuesta7_0').style.display='block'"> Acceso</button>
    
    <form id="mandar_user" method="POST">
        <input type="hidden" name="usuario" value="<?php echo $usuario ?>"/>
    </form>

    
    <script>
        $('#enviar7_0').click(function(){
            $.ajax({
                url:'../php/consulta7_0.php',
                success: function(res){
                    document.getElementById('respuesta7_1').style.display='none';

                    $('#respuesta7_0').html(res);
                },
                error: function(res){
                    alert("Problemas al tratar de enviar el formulario");
                }
            });
        });
        $('#enviar7_1').click(function(){
            $.ajax({
                url:'../php/consulta7_1.php',
                type:'POST',
                data: $('#mandar_user').serialize(),
                success: function(res){
                    document.getElementById('respuesta7_0').style.display='none';
                    $('#respuesta7_1').html(res);
                },
                error: function(res){
                    alert("Problemas al tratar de enviar el formulario");
                }
            });
        });
    </script>


<?php
}

////////////////////////////////////////////////////////////////////////////////////////////////
function menu_producto($usuario){
    ?>
    <div>
    <form id="menu_productos" method="POST"> 
    <table class="tabla_sugerido">
    <tr>
        <th colspan="2">Datos Básicos</th>
        <th colspan="3">Información Tributaria</th>
    </tr>
    <tr>
        <td>Categoría</td>
        <td><input list="categorias" name="categoria" id="categoria"></th>
        <datalist id="categorias"  required>

        <?php
            if(existencia_de_la_conexion()){
                require_once("../PHP/conexion.php");    //Hacer conexion con la base de datos
            }
            $conexion = conectar();                     //Obtenemos la conexion
            
            //Consulta a la base de datos en la tabla provvedor
            $consulta = mysqli_query($conexion, "SELECT `categorias` FROM `categoria` WHERE `estado` = 'activo' ORDER BY `categorias` ASC") or die ("Error al consultar: proveedores");

            while (($fila = mysqli_fetch_array($consulta))!=NULL){
                // traemos los proveedores existentes en la base de datos
                echo "<option value=".$fila['categorias']."></option>";
            }
            mysqli_free_result($consulta); //Liberar espacio de consulta cuando ya no es necesario
        ?>
        </datalist></td>
        <td>Tarifas de IVA</td>
        <td><input type="text" id="t_iva" name=""/></td>
    </tr>
    <tr>
        <td>Proveedor</td>
        <td><input list="proveedores" name="proveedor" id="proveedor"></th>
        <datalist id="proveedores"  required>

        <?php
            if(existencia_de_la_conexion()){
                require_once("../PHP/conexion.php");    //Hacer conexion con la base de datos
            }
            $conexion = conectar();                     //Obtenemos la conexion
            
            //Consulta a la base de datos en la tabla provvedor
            $consulta = mysqli_query($conexion, "SELECT `nombre_proveedor` FROM `proveedor` WHERE `estado` = 'activo' ORDER BY `nombre_proveedor` ASC") or die ("Error al consultar: proveedores");

            while (($fila = mysqli_fetch_array($consulta))!=NULL){
                // traemos los proveedores existentes en la base de datos

                ?>
                <option value="<?php echo $fila['nombre_proveedor'] ?>">1</option>";
                <?php
            }
            mysqli_free_result($consulta); //Liberar espacio de consulta cuando ya no es necesario
        ?>
        </datalist></td>
        <td>Clasificación de IVA</td>
        <td><input type="radio" id="civa1" name="clasi_iva" value="gravado" checked>
                <label for="civa1">Gravado</label>
            <input type="radio" id="civa2" name="clasi_iva" value="incluido">
                <label for="civa2">Incluido</label>
            <input type="radio" id="civa3" name="clasi_iva" value="excluido">
                <label for="civa3">Excluido</label><br></td>
    </tr>
    <tr>
        <td>Descripción</td>
        <td><input type="text" name="descripcion"/></td>
        <td>Costo del producto</td>
        <td><input type="text" id="" name=""/></td>
    </tr>
    <tr>
        <td>Referencia</td>
        <td><input type="text" name="referencia"/></td>
        <td>Costo + Impuesto</td>
        <td><input type="text" id="" name=""/></td>
    </tr>
    <tr>
        <td>Código de barra</td>
        <td><input type="text" name="codigo_barras"/></td>
        <td>Flete</td>
        <td><input type="text" id="" name=""/></td>
    </tr>
    <tr>
        <td>Control Inventario</td>
        <td><input type="radio" id="c1" name="control_inventario" value="si" checked>
                <label for="c1">Si</label><br>
            <input type="radio" id="c2" name="control_inventario" value="no">
                <label for="c2">No</label><br></td>
        <td>Utilidad estimada</td>
        <td><input type="text" id="" name=""/></td>
    </tr>
    <tr>
        <td>Decimales en cantidad</td>
        <td><input type="radio" id="d1" name="decimales_en_cantidad" value="si" checked>
                <label for="d1">Si</label><br>
            <input type="radio" id="d2" name="decimales_en_cantidad" value="no">
                <label for="d2">No</label><br></td>



        <td>Precio Sugerido<br><input type="text" id="" name=""/></td>
        <td>Venta 2<br><input type="text" id="" name=""/></td>
        <td>Venta 3<br><input type="text" id="" name=""/></td>
    </tr>
    <tr>
        <td>Días rotación</td>
        <td><input type="number" name="codigo_barras" min="0" value="0"/></td>
    </tr>
    <tr>
        <td>Activo</td>
        <td><input type="radio" id="r1" name="estado" value="activo" checked>
                <label for="r1">Activo</label><br>
            <input type="radio" id="r2" name="estado" value="inactivo">
                <label for="r2">Inactivo</label><br></td>
        <td>Utilidad<br><input type="text" id="" name=""/></td>
        <td>Utilidad 2<br><input type="text" id="" name=""/></td>
        <td>Utilidad 3<br><input type="text" id="" name=""/></td>
    </tr>

    </form>
    </table>
    <button type="button" id="enviar8" class="w3-red" onclick="document.getElementById('respuesta8').style.display='block'"><i class='fas fa-edit' style='font-size:24px;color:white'></i></button>

    <div id="respuesta8"></div>
    

    <script>
        $('#enviar8').click(function(){
            $.ajax({
                url:'../php/consulta8.php',
                type:'POST',
                data: $('#menu_productos').serialize(),
                success: function(res){
                    $('#respuesta8').html(res);
                },
                error: function(res){
                    alert("Problemas al tratar de enviar el formulario");
                }
            });
        });
    </script>
    </div>
<?php
}

/////////////////////////////////////////////////////////////////////////////////////////

function menu_personal($usuario){
    ?>
    


    <div id="respuesta9_1" style="display:none; backgroung-color:white;"></div>
    <div id="respuesta9_2" style="display:none; backgroung-color:white;"></div>
    <div id="respuesta9_3" style="display:none; backgroung-color:white;"></div>
    <br>
    <button type="button" id="enviar9_1" class="w3-btn w3-red" onclick="document.getElementById('respuesta9_1').style.display='block'"> Información Laboral</button>
    <button type="button" id="enviar9_2" class="w3-btn w3-red" onclick="document.getElementById('respuesta9_2').style.display='block'"> Información Personal</button>
    <button type="button" id="enviar9_3" class="w3-btn w3-red" onclick="document.getElementById('respuesta9_3').style.display='block'"> Datos</button>
    <script>
        $('#enviar9_1').click(function(){
            $.ajax({
                url:'../php/consulta9_1.php',
                success: function(res){
                    document.getElementById('respuesta9_2').style.display='none';
                    document.getElementById('respuesta9_3').style.display='none';
                    $('#respuesta9_1').html(res);
                },
                error: function(res){
                    alert("Problemas al tratar de enviar el formulario");
                }
            });
        });
        $('#enviar9_2').click(function(){
            $.ajax({
                url:'../php/consulta9_2.php',
                success: function(res){
                    document.getElementById('respuesta9_1').style.display='none';
                    document.getElementById('respuesta9_3').style.display='none';
                    $('#respuesta9_2').html(res);
                },
                error: function(res){
                    alert("Problemas al tratar de enviar el formulario");
                }
            });
        });
        $('#enviar9_3').click(function(){
            $.ajax({
                url:'../php/consulta9_3.php',
                success: function(res){
                    document.getElementById('respuesta9_1').style.display='none';
                    document.getElementById('respuesta9_2').style.display='none';
                    $('#respuesta9_3').html(res);
                },
                error: function(res){
                    alert("Problemas al tratar de enviar el formulario");
                }
            });
        });
    </script>

<?php
}

/////////////////////////////////////////////////////////////////////////////////////////
function ver_presupuestos($usuario){
    date_default_timezone_set('America/Bogota');
    $fecha        = date('m', time());
    ?>
    <form id="menu_presupuestos" method="POST" class="form-inline">
    <input type="hidden" name="user" value="<?php echo $usuario ?>">
    <table id="tabla_sugerido">
        <tr>
            <th colspan="6">Selección</th>
        </tr>
        <tr>
            <td>Mes</td>
            <td>
                <select name="mes" id="mes">

                    <option value="1" <?php if($fecha=='01'){?>selected <?php } ?> >Enero</option>
                    <option value="2" <?php if($fecha=='02'){?>selected <?php } ?> >Febrero</option>
                    <option value="3" <?php if($fecha=='03'){?>selected <?php } ?> >Marzo</option>
                    <option value="4" <?php if($fecha=='04'){?>selected <?php } ?> >Abril</option>
                    <option value="5" <?php if($fecha=='05'){?>selected <?php } ?> >Mayo</option>
                    <option value="6" <?php if($fecha=='06'){?>selected <?php } ?> >Junio</option>
                    <option value="7" <?php if($fecha=='07'){?>selected <?php } ?> >Julio</option>
                    <option value="8" <?php if($fecha=='08'){?>selected <?php } ?> >Agosto</option>
                    <option value="9" <?php if($fecha=='09'){?>selected <?php } ?> >Septiembre</option>
                    <option value="10" <?php if($fecha=='10'){?>selected <?php } ?> >Octubre</option>
                    <option value="11" <?php if($fecha=='11'){?>selected <?php } ?> >Noviembre</option>
                    <option value="12" <?php if($fecha=='12'){?>selected <?php } ?> >Diciembre</option>
                </select>
            </td>
            <td>Año</td>
            <td><input type="text" name="year" value="2022"></td>
            <td></td>
            <td><button type="button" id="enviar11" class="w3-btn" style="background-color: #478248;color:white;">Continuar <i class='fas fa-edit' style='font-size:24px;color:white'></button></td>
        </tr>
    </table>
    </form>
    <a class="w3-bar-item w3-button w3-red w3-hover-red active salir" onclick="document.getElementById('cont2_4').style.display='none'">X</a>
    <br>
    </div>
    <div id="respuesta11">
    <script>
        $('#enviar11').click(function(){
            $.ajax({
                url:'../php/consulta11.php',
                type:'POST',
                data: $('#menu_presupuestos').serialize(),
                success: function(res){
                    $('#respuesta11').html(res);
                    document.getElementById('form_presupuestos').style.display='block';
                },
                error: function(res){
                    alert("Problemas al tratar de enviar el formulario");
                }
            });
        });
    </script>
<?php
}

/////////////////////////////////////////////////////////////////////////////////////////
function menu_vehiculos($usuario){
    ?>
    <br>
    <button type="button" id="enviar10_1" class="w3-btn w3-red" onclick="document.getElementById('respuesta10_1').style.display='block'"> Vehículos</button>
    <button type="button" id="enviar10_2" class="w3-btn w3-red" onclick="document.getElementById('respuesta10_2').style.display='block'"> Generar reporte</button>

    <br>
    <br>
    <div id="respuesta10_1" style="display:none; backgroung-color:white;"></div>
    <div id="respuesta10_2" style="display:none; backgroung-color:white;"></div>

    <script>
        $('#enviar10_1').click(function(){
            $.ajax({
                url:'../php/consulta10_1.php',
                success: function(res){
                    document.getElementById('respuesta10_2').style.display='none';
                    $('#respuesta10_1').html(res);
                },
                error: function(res){
                    alert("Problemas al tratar de enviar el formulario");
                }
            });
        });
        $('#enviar10_2').click(function(){
            $.ajax({
                url:'../php/consulta10_2.php',
                success: function(res){
                    document.getElementById('respuesta10_1').style.display='none';
                    $('#respuesta10_2').html(res);
                },
                error: function(res){
                    alert("Problemas al tratar de enviar el formulario");
                }
            });
        });

    </script>

<?php
}

/////////////////////////////////////////////////////////////////////////////////////////
function resultados_operativos($usuario){
    ?>
    <form id="menu_roo" method="POST" class="form-inline">
    <table id="tabla_sugerido">
        <tr>
            <th colspan="6">Selección</th>
        </tr>
        <tr>
            <td>Año</td>
            <td><input type="text" name="year" value="2022"></td>
            <td></td>
            <td><button type="button" id="enviar12" class="w3-btn" style="background-color: #478248;color:white;">Continuar <i class='fas fa-edit' style='font-size:24px;color:white'></button></td>
        </tr>
    </table>
    <a class="w3-bar-item w3-button w3-red w3-hover-red active salir" onclick="document.getElementById('cont2_6').style.display='none'">X</a>
    </form>
    <br>
    </div>
    <div id="respuesta12">
    <script>
        $('#enviar12').click(function(){
            $.ajax({
                url:'../php/consulta12.php',
                type:'POST',
                data: $('#menu_roo').serialize(),
                success: function(res){
                    $('#respuesta12').html(res);
                    document.getElementById('form_ro').style.display='block';
                },
                error: function(res){
                    alert("Problemas al tratar de enviar el formulario");
                }
            });
        });
    </script>
<?php
}
/////////////////////////////////////////////////////////////////////////////////////////
function caja1($usuario){
    ?>
    <button type="button" id="enviarv1" class="w3-btn w3-red" onclick="document.getElementById('respuestav1').style.display='block'" style="display:none;"></button>

    <form id="usuario_caja1" method="POST">
        <input type="hidden" name="usuario" value="<?php echo $usuario ?>"/>
    </form>

    <div id="respuestav1" style="display:none; backgroung-color:white;overflow-y: scroll">
    </div>

    <script>
        $('#enviarv1').click(function(){
            $.ajax({
                url:'../php/consultav1.php',
                type:'POST',
                data: $('#usuario_caja1').serialize(),
                success: function(res){
                    $('#respuestav1').html(res);
                },
                error: function(res){
                    alert("Problemas al tratar de enviar el formulario");
                }
            });
        });
    </script>
<?php
}
?>