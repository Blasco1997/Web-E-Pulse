<?php
   define('CSSPATH', '');
    $css = "../CSS/disenoepulsetablas.css"."?v=".rand(1, 1000);
    session_start();
    include "funcionescrud.php";
    include "paginaciones.php";
    if(!isset($_SESSION["trabajador"])){
        header("location: iniciarsesion.php");
    } else {
        $usuario = $_SESSION["trabajador"];
    }
?>
<!DOCTYPE html>
<html lang="es">
    <head>
        <meta charset="UTF-8">
        <meta name="viewport" content="width=device-width, initial-scale=1.0">
        <title>Trabajadores E-Pulse Servicios de Internet S.L.</title>
        <link rel="stylesheet" type="text/css" href="<?php echo (CSSPATH . "$css" );?>"/>
    </head>
    <body>
        <section id="advice">
            <div class="confirmar">
                <h3><img src='../Imágenes/Advice.png' width='25' alt='Advertencia'/> ¿Está usted seguro de que quiere eliminar a este trabajador?</h3>
                <form action="<?php echo htmlentities($_SERVER["PHP_SELF"]);?>" method="get">
                    <input type="hidden" name="idtrabajadoreliminar" id="idtrabajadoreliminar"/><button id="cancelar">Cancelar</button><button name="aceptar">Aceptar</button>
                </form>
                <script>
                    let cancelar = document.getElementById("cancelar");
                    cancelar.addEventListener("click", function(){
                        let advice = document.getElementById("advice");
                        advice.style.display = "none";
                    })
                </script>
            </div>
        </section>
        <header>
            <div class="logo"><img src="../Imágenes/Logo.png" width="100%" alt="Logo E-Pulse"></div>
            <h1 id='navegacionmovil'>&#9776;</h1>
            <h1 id='closenav' style="display: none;">&#9932;</h1>
            <nav class="navegacion" id="navegacion">
                <ul>
                    <li><a href="index.php">Inicio</a></li>
                    <li class='seleccionado'>Consultas
                        <ul>
                            <li>Lista de trabajadores</li>
                            <li><a href='listadepeticiones.php'>Lista de peticiones</a></li>
                            <li><a href='listadepublicaciones.php'>Lista de publicaciones</a></li>
                        </ul>
                    </li>
                    <li>Hola, <?php echo $usuario;?> <span id='puerta'><img src='../Imágenes/PuertaCerrada.png' width='35' alt='Puerta cerrada'></span></li>
                </ul>
                <script>
                    let puerta = document.getElementById("puerta");
                    puerta.addEventListener("click", function(){
                        window.location.href = "cerrarsesion.php";
                    });
                    puerta.addEventListener("mouseover", function(){
                        puerta.innerHTML = "<img src='../Imágenes/PuertaAbierta.png' width='35' alt='Puerta abierta'>";
                    });
                    puerta.addEventListener("mouseout", function(){
                        puerta.innerHTML = "<img src='../Imágenes/PuertaCerrada.png' width='35' alt='Puerta cerrada'>";
                    });
                    puerta.addEventListener("blur", function(){
                        puerta.innerHTML = "<img src='../Imágenes/PuertaCerrada.png' width='35' alt='Puerta cerrada'>";
                    });
                </script>
            </nav>
            <script>
                let navegacion = document.getElementById("navegacion");
                let navegacionmovil = document.getElementById("navegacionmovil");
                let closenav = document.getElementById("closenav");
                navegacionmovil.addEventListener('click', function(){
                        navegacionmovil.style.display = "none";
                        navegacion.style.display = "block";
                        closenav.style.display = "block";
                });
                closenav.addEventListener('click', function(){        
                        navegacionmovil.style.display = "block";
                        navegacion.style.display = "none";
                        closenav.style.display = "none";
                });
            </script>
        </header>
        <main class="listas">
            <div class="avisos">
                <?php
                    if(isset($_POST["nombre"]) && isset($_POST["usuario"]) && isset($_POST["clave"])  && isset($_POST["puesto"]) && isset($_POST["guardardatos"])){
                        $registrartrabajador = registrarTrabajador($_POST["nombre"], $_POST["usuario"], $_POST["clave"], $_POST["puesto"]);
                        if($registrartrabajador == true){
                            echo OK_CREATE_USERNAME;
                        } else if($registrartrabajador == false){
                            echo ERROR_NOT_CREATE_USERNAME;
                        }
                    } else if(isset($_POST["numtrabajador"]) && isset($_POST["nombre"]) && isset($_POST["usuario"]) && isset($_POST["clave"]) && isset($_POST["puesto"]) && isset($_POST["guardarcambios"])) {
                        $modificartrabajador = modificarTrabajador($_POST["numtrabajador"], $_POST["nombre"], $_POST["usuario"], $_POST["clave"], $_POST["puesto"]);
                        if($modificartrabajador == true){
                            echo OK_UPDATE_USERNAME;
                        } else if($modificartrabajador == false){
                            echo ERROR_NOT_UPDATE_USERNAME;
                        }
                    }
                ?>
                <?php
                    if(isset($_GET["idtrabajadoreliminar"]) && isset($_GET["aceptar"])){
                        $idtrabajadoreliminar = $_GET["idtrabajadoreliminar"];
                        $conexion = new mysqli("127.0.0.1", "root", "", "epulse");
                        if(!$conexion){
                            echo ERROR_CONNECT_DATA;
                        } else {
                            $listadetrabajadores = "SELECT * FROM trabajadores";
                            $resultados = $conexion->query($listadetrabajadores);
                            if($resultados->num_rows == 0){
                                echo ERROR_NOT_WORKERS;
                            } else {
                                for ($i = 1; $i <= $resultados->num_rows; $i++) { 
                                    if($i == $idtrabajadoreliminar){
                                        $eliminartrabajador = eliminarTrabajador($idtrabajadoreliminar);
                                        if($eliminartrabajador == true){
                                            echo OK_DELETE_USERNAME;
                                        } else if($eliminartrabajador == false){
                                            echo ERROR_NOT_DELETE_USERNAME;
                                        }
                                    }
                                }
                            }
                        }
                    }
                ?>
            </div>
            <section class="buttons">
                <button class="btn-crud" id="registrar">REGISTRAR</button>
                <button class="btn-crud" id="importarxml">IMPORTAR XML</button>
                <form id="registro" action="<?php echo htmlentities($_SERVER["PHP_SELF"]);?>" method="post">
                    <div class="campo" id="cerrarregistro">
                        <p>&#128473;</p>
                    </div>
                    <div class="clear"></div>
                    <div class="campo">
                        <h1>REGISTRAR TRABAJADOR</h1>
                    </div>
                    <div class="campo">
                        <label for="nombre">Nombre:</label><br/>
                        <input type="text" name="nombre" required/>
                    </div>
                    <div class="campo">
                        <label for="usuario">Usuario:</label><br/>
                        <input type="text" name="usuario" required/>
                    </div>
                    <div class="campo">
                        <label for="clave">Clave:</label><br/>
                        <input type="password" name="clave" required/>
                    </div>
                    <div class="campo">
                        <label for="puesto">Puesto:</label><br/>
                        <select name="puesto" required>
                            <option selected="selected">Seleccione puesto</option>
                            <?php
                                $conexion = new mysqli("127.0.0.1", "root", "", "epulse");
                                if(!$conexion){
                                    echo ERROR_CONNECT_DATA_COMBOBOX;
                                } else {
                                    $puestos = "SELECT Puesto FROM puestos";
                                    $resultados = $conexion->query($puestos);
                                    if($resultados->num_rows == 0){
                                        echo ERROR_NO_DATA_COMBOBOX;
                                    } else {
                                        $row = $resultados->fetch_assoc();
                                        do {
                                            echo "<option>{$row["Puesto"]}</option>";
                                            $row = $resultados->fetch_assoc();
                                        } while ($row);
                                    }
                                }
                            ?>
                        </select>
                    </div>
                    <div class="campo">
                        <button name="guardardatos">GUARDAR DATOS</button>
                    </div>
                </form>
                <?php
                    if(isset($_POST["ficheroxml"]) && isset($_POST["importardatos"])){
                        importarXMLTrabajadores($_POST["ficheroxml"]);
                    }
                ?>
                <form id="importacion" action="<?php echo htmlentities($_SERVER["PHP_SELF"]);?>" method="post">
                <div class="campo" id="cerrarregistro2">
                        <p>&#128473;</p>
                    </div>
                    <div class="clear"></div>
                    <div class="campo">
                        <h1>IMPORTAR HOJA DE TRABAJADORES DESDE XML</h1>
                    </div>
                    <div class="campo">
                        <label for="ficheroxml">Fichero a importar:</label><br/>
                        <input type="file" accept="text/xml" name="ficheroxml" required/>
                    </div>
                    <div class="campo">
                        <button name="importardatos">IMPORTAR DATOS</button>
                    </div>
                </form>
                <script>
                    let registrar = document.getElementById("registrar");
                    let registro = document.getElementById("registro");
                    let importarxml = document.getElementById("importarxml");
                    let importacion = document.getElementById("importacion");
                    let cerrarregistro = document.getElementById("cerrarregistro");
                    let cerrarregistro2 = document.getElementById("cerrarregistro2");
                    registrar.addEventListener("click", function(){
                        registrar.style.display = "none";
                        registro.style.animation = "aparecerFormularioRegistro 1s ease-in-out forwards";
                    });
                    cerrarregistro.addEventListener("click", function(){
                        registrar.style.display = "block";
                        registro.style.animation = "desaparecerFormularioRegistro 1s ease-in-out";
                    });
                    importarxml.addEventListener("click", function(){
                        importarxml.style.display = "none";
                        importacion.style.animation = "aparecerFormularioRegistro 1s ease-in-out forwards";
                    });
                    cerrarregistro2.addEventListener("click", function(){
                        importarxml.style.display = "block";
                        importacion.style.animation = "desaparecerFormularioRegistro 1s ease-in-out";
                    });
                </script>
                <form id="edicion" action="<?php echo htmlentities($_SERVER["PHP_SELF"]);?>" method="post">
                    <div class="campo" id="cerraredicion">
                        <p>&#128473;</p>
                    </div>
                    <div class="clear"></div>
                    <div class="campo">
                        <h1>MODIFICAR DATOS DEL TRABAJADOR</h1>
                    </div>
                    <div class="campo">
                        <input type="hidden" name="numtrabajador" id="numtrabajador"/>
                        <label for="nombre">Nombre:</label><br/>
                        <input type="text" name="nombre" id="nombre" required/>
                    </div>
                    <div class="campo">
                        <label for="usuario">Usuario:</label><br/>
                        <input type="text" name="usuario" id="usuario" required/>
                    </div>
                    <div class="campo">
                        <label for="clave">Clave:</label><br/>
                        <input type="password" name="clave" id="clave" required/>
                    </div>
                    <div class="campo">
                        <label for="puesto">Puesto:</label><br/>
                        <select name="puesto" id="puesto" required>
                            <option selected="selected">Seleccione puesto</option>
                            <?php
                                $conexion = new mysqli("127.0.0.1", "root", "", "epulse");
                                if(!$conexion){
                                    echo ERROR_CONNECT_DATA_COMBOBOX;
                                } else {
                                    $puestos = "SELECT Puesto FROM puestos";
                                    $resultados = $conexion->query($puestos);
                                    if($resultados->num_rows == 0){
                                        echo ERROR_NO_DATA_COMBOBOX;
                                    } else {
                                        $row = $resultados->fetch_assoc();
                                        do {
                                            echo "<option>{$row["Puesto"]}</option>";
                                            $row = $resultados->fetch_assoc();
                                        } while ($row);
                                    }
                                }
                            ?>
                        </select>
                    </div>
                    <div class="campo">
                        <button name="guardarcambios">GUARDAR CAMBIOS</button>
                    </div>
                </form>
            </section>
            <?php
                paginacionTablaTrabajadores(10, "SELECT COUNT(*) AS total FROM trabajadores", $usuario);
                paginacionTablaTrabajadoresMovil(5, "SELECT COUNT(*) AS total FROM trabajadores", $usuario);
            ?>
        </main>
        <footer>
            <div class="colaborador">
                <h3>EMPRESA COLABORADORA</h3>
                <a href="https://www.bricogeek.com/" target="_blank"><img src="../Imágenes/BricoGeek.PNG" width="100%" alt="BricoGeek"/></a>
            </div>
            <div class="copyright">
                <p>&copy;<?php echo date("Y");?>. Página hecha por Blasco</p>
            </div>
            <div class="correos">
                <h3>AGENTE DE IMPORTACIÓN Y EXPORTACIÓN DE PEDIDOS</h3>
                <a href="https://www.correosexpress.com/" target="_blank"><img src="../Imágenes/CorreosExpress.png" width="100%" alt="Correos Express"/></a>
            </div>
        </footer>
    </body>
</html>