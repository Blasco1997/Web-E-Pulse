<?php
    define('CSSPATH', '');
    $css = "../CSS/disenoepulseservicios.css"."?v=".rand(1, 1000);
    include "funcionescrud.php";
?>
<!DOCTYPE html>
<html lang="es">
    <head>
        <meta charset="UTF-8">
        <meta name="viewport" content="width=device-width, initial-scale=1.0">
        <title>E-Pulse Servicios de Internet S.L. | Servicios</title>
        <link rel="stylesheet" type="text/css" href="<?php echo (CSSPATH . "$css" );?>"/>
    </head>
    <body>
        <header>
            <div class="logo"><img src="../Imágenes/Logo.png" width="100%" alt="Logo E-Pulse"></div>
            <h1 id='navegacionmovil'>&#9776;</h1>
            <h1 id='closenav' style="display: none;">&#9932;</h1>
            <nav class="navegacion" id="navegacion">
                <ul>
                    <li><a href='index.php'>Inicio</a></li>
                    <li class='seleccionado'>Servicios</li>
                    <li><a href='contacto.php'>Contacto</a></li>
                    <li><a href='iniciarsesion.php'>Login</a></li>
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
        <main class="servicios">
            <section class="titulo">
                <h1>NUESTROS SERVICIOS</h1>
            </section>
            <section class="sectores">
                <?php
                    $conexion = new mysqli("127.0.0.1", "dockeruser", "Docker@2025", "epulse");
                    if(!$conexion){
                        echo ERROR_CONNECT_DATA;
                    } else {
                        $listarservicios = "SELECT * FROM labores";
                        $resultados = $conexion->query($listarservicios);
                        if($resultados->num_rows == 0){
                            echo ERROR_TASK_CONSULTING_NOT_EXISTS;
                        } else {
                            $i = 1;
                            $t = 3;
                            $row = $resultados->fetch_assoc();
                            do {
                                echo "<div class='servicio' id='servicio$i'>
                                    <h2>{$row["Labor"]}</h2>
                                    <p>{$row["Descripción"]}</p>
                                </div>";
                                if($i % 2 == 0) {
                                    echo "<script>
                                    let servicio$i = document.getElementById('servicio$i');
                                    servicio$i.style.animation = 'aparecerServicioDerecha 1s ease-in-out {$t}s forwards';
                                    </script>";
                                } else {
                                    echo "<script>
                                    let servicio$i = document.getElementById('servicio$i');
                                    servicio$i.style.animation = 'aparecerServicioIzquierda 1s ease-in-out {$t}s forwards';
                                    </script>";
                                }
                                $i++;
                                $t++;
                                $row = $resultados->fetch_assoc();
                            } while ($row);
                        }
                    }
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