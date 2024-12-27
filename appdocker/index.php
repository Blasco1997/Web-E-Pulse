<?php
    define('CSSPATH', '');
    $css = "../CSS/disenoepulseindex.css"."?v=".rand(1, 1000);
    session_start();
    include "funcionescrud.php";
    include "paginaciones.php";
    if(isset($_SESSION["trabajador"])){
        $usuario = $_SESSION["trabajador"];
    }
?>
<!DOCTYPE html>
<html lang="es">
    <head>
        <meta charset="UTF-8">
        <meta name="viewport" content="width=device-width, initial-scale=1.0">
        <title>E-Pulse Servicios de Internet S.L. | Inicio</title>
        <link rel="stylesheet" type="text/css" href="<?php echo (CSSPATH . "$css" );?>"/>
    </head>
    <body>
        <header>
            <div class="logo"><img src="../Imágenes/Logo.png" width="100%" alt="Logo E-Pulse"></div>
            <h1 id='navegacionmovil'>&#9776;</h1>
            <h1 id='closenav' style="display: none;">&#9932;</h1>
            <nav class="navegacion" id="navegacion">
                <ul>
                    <?php
                        if(isset($usuario)){
                            echo "<li class='seleccionado'>Inicio</li>
                            <li><a href='#'>Consultas</a>
                                <ul>
                                    <li><a href='listadetrabajadores.php'>Lista de trabajadores</a></li>
                                    <li><a href='listadepeticiones.php'>Lista de peticiones</a></li>
                                    <li><a href='listadepublicaciones.php'>Lista de publicaciones</a></li>
                                </ul>
                            </li>
                            <li>Hola, $usuario <span id='puerta'><img src='Imágenes/PuertaCerrada.png' width='35' alt='Puerta cerrada'></span></li>";
                        } else {
                            echo "<li class='seleccionado'>Inicio</li>
                            <li><a href='servicios.php'>Servicios</a></li>
                            <li><a href='contacto.php'>Contacto</a></li>
                            <li><a href='iniciarsesion.php'>Login</a></li>";
                        }
                    ?>
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
        <main class="index">
            <h1 class="telediario">NUESTRO TELEDIARIO MAÑANERO</h1>
            <section class="noticias">
                <?php
                    paginacionIndex(3, "SELECT COUNT(*) AS total FROM noticias");
                ?>
            </section>
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