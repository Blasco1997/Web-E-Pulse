<?php
    define('CSSPATH', '');
    $css = "../CSS/disenoepulselogin.css"."?v=".rand(1, 1000);
    include "funcionescrud.php";
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
                    <li><a href='index.php'>Inicio</a></li>
                    <li><a href='servicios.php'>Servicios</a></li>
                    <li><a href='contacto.php'>Contacto</a></li>
                    <li class='seleccionado'>Login</li>
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
        <main class="login">
            <section>
                <form action="<?php echo htmlentities($_SERVER["PHP_SELF"]);?>" method="post">
                    <div class="campo">
                        <?php
                            if(isset($_POST["usuario"]) && isset($_POST["clave"]) && isset($_POST["acceder"])){
                                iniciarSesion($_POST["usuario"], $_POST["clave"]);
                            }
                        ?>
                    </div>
                    <div class="campo">
                        <h1>INICIAR SESIÓN</h1>
                    </div>
                    <div class="campo">
                        <label for="usuario">Usuario:</label><br/>
                        <input type="text" name="usuario" required/>
                    </div>
                    <div class="campo">
                        <label for="clave">Clave:</label><br/>
                        <input type="password" name="clave" required/>
                    </div>
                    <div class="clear"></div>
                    <div class="campo">
                        <p><a href="resetearclave.php">¿Has olvidado tu clave?</a></p>
                    </div>
                    <div class="campo">
                        <button name="acceder">ACCEDER</button>
                    </div>
                </form>
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