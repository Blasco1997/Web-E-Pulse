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
        <header>
            <div class="logo"><img src="../Imágenes/Logo.png" width="100%" alt="Logo E-Pulse"></div>
            <h1 id='navegacionmovil'>&#9776;</h1>
            <h1 id='closenav' style="display: none;">&#9932;</h1>
            <nav class="navegacion" id="navegacion">
                <ul>
                    <li><a href="index.php">Inicio</a></li>
                    <li class='seleccionado'>Consultas
                        <ul>
                            <li><a href='listadetrabajadores.php'>Lista de trabajadores</a></li>
                            <li>Lista de peticiones</li>
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
        <main class="listas" id="peticiones">
            <section class="buttons">
                <button class="btn-crud" id="importarxml">IMPORTAR XML</button>
                <?php
                    if(isset($_POST["ficheroxml"]) && isset($_POST["importardatos"])){
                        importarXMLPeticiones($_POST["ficheroxml"]);
                    }
                ?>
                <form id="importacion" action="<?php echo htmlentities($_SERVER["PHP_SELF"]);?>" method="post">
                    <div class="campo" id="cerrarregistro2">
                        <p>&#128473;</p>
                    </div>
                    <div class="campo">
                        <h1>IMPORTAR HOJA DE PETICIONES DESDE XML</h1>
                    </div>
                    <div class="clear"></div>
                    <div class="campo">
                        <label for="ficheroxml">Fichero a importar:</label><br/>
                        <input type="file" accept="text/xml" name="ficheroxml" required/>
                    </div>
                    <div class="campo">
                        <button name="importardatos">IMPORTAR DATOS</button>
                    </div>
                </form>
                <script>
                    let importarxml = document.getElementById("importarxml");
                    let importacion = document.getElementById("importacion");
                    let cerrarregistro2 = document.getElementById("cerrarregistro2");
                    importarxml.addEventListener("click", function(){
                        importarxml.style.display = "none";
                        importacion.style.animation = "aparecerFormularioRegistro 1s ease-in-out forwards";
                    });
                    cerrarregistro2.addEventListener("click", function(){
                        importarxml.style.display = "block";
                        importacion.style.animation = "desaparecerFormularioRegistro 1s ease-in-out";
                    });
                </script>
                <?php
                    if(isset($_POST["numcliente"]) && isset($_POST["progreso"]) && isset($_POST["guardarcambios"])){
                        $actualizarprogreso = actualizarProgreso($_POST["numcliente"], $_POST["progreso"]);
                        if($actualizarprogreso == true){
                            echo "<p>Progreso de la tarea {$_POST["numcliente"]} actualizado.</p>";
                        } else  if($actualizarprogreso == false){
                            echo "<p>Hubo problema al actualizar el progreso de la tarea {$_POST["numcliente"]}.</p>";
                        }
                    }
                ?>
                <form id="edicion" class="progreso" action="<?php echo htmlentities($_SERVER["PHP_SELF"]);?>" method="post">
                    <div class="campo" id="cerraredicion">
                        <p>&#128473;</p>
                    </div>
                    <div class="clear"></div>
                    <div class="campo">
                        <h1>MODIFICAR PROGRESO</h1>
                    </div>
                    <input type="hidden" name="numcliente" id="numcliente"/>
                    <div class="campo">
                        <label for="progreso">Progreso:</label><br/>
                        <select name="progreso" id="progreso" required>
                            <option selected="selected">Especifique el progreso de la tarea o petición</option>
                            <?php
                                $conexion = new mysqli("127.0.0.1", "dockeruser", "Docker@2025", "epulse");
                                if(!$conexion){
                                    echo ERROR_CONNECT_DATA_COMBOBOX;
                                } else {
                                    $tipoprogreso = "SELECT Progreso FROM progresos";
                                    $resultados = $conexion->query($tipoprogreso);
                                    if($resultados->num_rows == 0){
                                        echo ERROR_NO_DATA_COMBOBOX;
                                    } else {
                                        $row = $resultados->fetch_assoc();
                                        do {
                                            echo "<option>{$row["Progreso"]}</option>";
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
                paginacionTablaPeticiones(10, "SELECT COUNT(*) AS total FROM clientes");
                paginacionTablaPeticionesMovil(5, "SELECT COUNT(*) AS total FROM clientes");
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