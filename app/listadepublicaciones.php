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
                <h3><img src='../Imágenes/Advice.png' width='25' alt='Advertencia'/> ¿Está usted seguro de que quiere eliminar esta noticia?</h3>
                <form action="<?php echo htmlentities($_SERVER["PHP_SELF"]);?>" method="get">
                    <input type="hidden" name="numnoticiaeliminar" id="numnoticiaeliminar"/><button id="cancelar">Cancelar</button><button name="aceptar">Aceptar</button>
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
                            <li><a href='listadetrabajadores.php'>Lista de trabajadores</a></li>
                            <li><a href='listadepeticiones.php'>Lista de peticiones</a></li>
                            <li>Lista de publicaciones</li>
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
                    if(isset($_POST["imagen"]) && isset($_POST["enlace"]) && isset($_POST["titulo"]) && isset($_POST["descripcion"]) && isset($_POST["guardardatos"])){
                        $publicarnoticia = publicarNoticia($_POST["imagen"], $_POST["enlace"], $_POST["titulo"], $_POST["descripcion"], $usuario);
                        if($publicarnoticia == true){
                            echo OK_NOTICE_POSTED;
                        } else if($publicarnoticia == false){
                            echo ERROR_NOTICE_NOT_POSTED;
                        }
                    } else if(isset($_POST["numnoticia"]) && isset($_POST["imagen"]) && isset($_POST["enlace"]) && isset($_POST["titulo"]) && isset($_POST["descripcion"]) && isset($_POST["guardarcambios"])) {
                        $actualizarnoticia = actualizarNoticia($_POST["numnoticia"], $_POST["imagen"], $_POST["enlace"], $_POST["titulo"], $_POST["descripcion"], $usuario);
                        if($actualizarnoticia == true){
                            echo OK_NOTICE_UPDATED;
                        } else if($actualizarnoticia == false){
                            echo ERROR_NOTICE_NOT_UPDATED;
                        }
                    }
                ?>
                <?php
                    if(isset($_GET["numnoticiaeliminar"]) && isset($_GET["aceptar"])){
                        $numnoticiaeliminar = $_GET["numnoticiaeliminar"];
                        $conexion = new mysqli("127.0.0.1", "root", "", "epulse");
                        if(!$conexion){
                            echo ERROR_CONNECT_DATA;
                        } else {
                            $listadenoticias = "SELECT * FROM noticias";
                            $resultados = $conexion->query($listadenoticias);
                            if($resultados->num_rows == 0){
                                echo ERROR_NOTICE_NOT_EXISTS;
                            } else {
                                for ($i = 1; $i <= $resultados->num_rows; $i++) { 
                                    if($i == $numnoticiaeliminar){
                                        $eliminarnoticia = eliminarNoticia($numnoticiaeliminar);
                                        if($eliminarnoticia == true){
                                            echo OK_NOTICE_DELETED;
                                        } else if($eliminarnoticia == false){
                                            echo ERROR_NOTICE_NOT_DELETED;
                                        }
                                    }
                                }
                            }
                        }
                    }
                ?>
            </div>
            <section class="buttons">
                <button class="btn-crud" id="registrar">¿En qué estás pensando, <?php $datosusuario = explode(" ", $usuario); echo $datosusuario[0];?>?</button>
                <button class="btn-crud" id="importarxml">IMPORTAR XML</button>
                <?php
                    if(isset($_POST["ficheroxml"]) && isset($_POST["importardatos"])){
                        importarXMLNoticias($_POST["ficheroxml"]);
                    }
                ?>
                <form id="importacion" action="<?php echo htmlentities($_SERVER["PHP_SELF"]);?>" method="post">
                    <div class="campo" id="cerrarregistro2">
                        <p>&#128473;</p>
                    </div>
                    <div class="campo">
                        <h1>IMPORTAR NOTICIAS DE TELEDIARIO DESDE XML</h1>
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
                <form id="registro" action="<?php echo htmlentities($_SERVER["PHP_SELF"]);?>" method="post">
                    <div class="campo" id="cerrarregistro">
                        <p>&#128473;</p>
                    </div>
                    <div class="campo">
                        <h1>PUBLICAR NUEVA NOTICIA</h1>
                    </div>
                    <div class="campo">
                        <label for="imagen">Imagen:</label>
                        <input type="file" name="imagen" id="seleccionArchivos" accept="image/*">
                        <br><br>
                        <!-- La imagen que vamos a usar para previsualizar lo que el usuario seleccione -->
                        <img id="imagenPrevisualizacion">
                        <script type="text/javascript" src="JS/subirImagen.js"></script>
                    </div>
                    <div class="campo">
                        <label for="enlace">Enlace:</label><br/>
                        <input type="url" name="enlace" required/>
                    </div>
                    <div class="campo">
                        <label for="titulo">Título:</label><br/>
                        <input type="text" name="titulo" required/>
                    </div>
                    <div class="campo">
                        <label for="descripcion">Descripción:</label><br/>
                        <textarea name="descripcion" required></textarea>
                    </div>
                    <div class="campo">
                        <button name="guardardatos">PUBLICAR</button>
                    </div>
                </form>
                <script>
                    let registrar = document.getElementById("registrar");
                    let registro = document.getElementById("registro");
                    let cerrarregistro = document.getElementById("cerrarregistro");
                    registrar.addEventListener("click", function(){
                        registrar.style.display = "none";
                        registro.style.animation = "aparecerFormularioRegistro 1s ease-in-out forwards";
                    });
                    cerrarregistro.addEventListener("click", function(){
                        registrar.style.display = "block";
                        registro.style.animation = "desaparecerFormularioRegistro 1s ease-in-out";
                    });
                </script>
                <form id="edicion" action="<?php echo htmlentities($_SERVER["PHP_SELF"]);?>" method="post">
                    <div class="campo" id="cerraredicion">
                        <p>&#128473;</p>
                    </div>
                    <div class="campo">
                        <h1>MODIFICAR PUBLICACIÓN</h1>
                    </div>
                    <div class="campo">
                        <input type="hidden" name="numnoticia" id="numnoticia"/>
                        <label for="imagen">Imagen:</label>
                        <input type="file" name="imagen" id="seleccionArchivos" accept="image/*">
                        <br><br>
                        <!-- La imagen que vamos a usar para previsualizar lo que el usuario seleccione -->
                        <img id="imagenPrevisualizacion">
                        <script type="text/javascript" src="JS/subirImagen.js"></script>
                    </div>
                    <div class="campo">
                        <label for="enlace">Enlace:</label><br/>
                        <input type="url" name="enlace" id="enlace" required/>
                    </div>
                    <div class="campo">
                        <label for="titulo">Título:</label><br/>
                        <input type="text" name="titulo" id="titulo" required/>
                    </div>
                    <div class="campo">
                        <label for="descripcion">Descripción:</label><br/>
                        <textarea name="descripcion" id="descripcion" required></textarea>
                    </div>
                    <div class="campo">
                        <button name="guardarcambios">GUARDAR CAMBIOS</button>
                    </div>
                </form>
            </section>
            <?php
                paginacionTablaPublicaciones(3, "SELECT COUNT(*) AS total FROM noticias");
                paginacionTablaPublicacionesMovil(2, "SELECT COUNT(*) AS total FROM noticias");
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