<?php
    define('CSSPATH', '');
    $css = "../CSS/disenoepulsecontacto.css"."?v=".rand(1, 1000);
    include "funcionescrud.php";
?>
<!DOCTYPE html>
<html lang="es">
    <head>
        <meta charset="UTF-8">
        <meta name="viewport" content="width=device-width, initial-scale=1.0">
        <title>E-Pulse Servicios de Internet S.L. | Contacto</title>
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
                    <li class='seleccionado'>Contacto</li>
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
        <main class="consulta">
            <section class="mapa">
                <h1 class="ubicacion">NUESTRA UBICACIÓN</h1>
                <iframe src="https://www.google.com/maps/embed?pb=!1m18!1m12!1m3!1d7231.913597978799!2d-8.694282795325668!3d43.21136005068918!2m3!1f0!2f0!3f0!3m2!1i1024!2i768!4f13.1!3m3!1m2!1s0xd2e923837032c67%3A0xa42c96a6634171ee!2sE-Pulse%20Servicios%20de%20Internet%20S.L.!5e1!3m2!1ses!2ses!4v1731488244137!5m2!1ses!2ses" allowfullscreen="" loading="lazy" referrerpolicy="no-referrer-when-downgrade"></iframe><br/>
            </section>
            <section class="info">
                    <h1>CONTACTO</h1>
                    <p>&#x1F551; De Lunes a Viernes de 9:00 a 13:30 y de 15:30 a 19:00</p>
                    <p>&#128387; <a href="mailto:info@e-pulse.org">info@e-pulse.org</a></p>
                    <p>&#128379; 981 70 24 66</p>
            </section>
            <section class="contacto">
                <form id="contacto" action="<?php echo htmlentities($_SERVER["PHP_SELF"]);?>" method="post">
                    <div class="campo">
                        <?php
                            if(isset($_POST["nombre"]) && isset($_POST["email"]) && isset($_POST["consulta"]) && isset($_POST["mensaje"]) && isset($_POST["responsable"])){
                                $publicarconsulta = publicarConsulta($_POST["nombre"], $_POST["email"], $_POST["consulta"], $_POST["mensaje"], $_POST["responsable"]);
                                if($publicarconsulta == true){
                                    echo OK_TASK_CONSULTING_UPLOADED;
                                } else if($publicarconsulta == false){
                                    echo ERROR_TASK_CONSULTING_NOT_UPLOADED;
                                }
                            }
                        ?>
                    </div>
                    <div class="campo">
                        <h1>RELLENE LOS DATOS PARA SU CONSULTA</h1>
                    </div>
                    <div class="campo">
                        <label for="nombre">Nombre:</label><br/>
                        <input type="text" name="nombre" required/>
                    </div>
                    <div class="campo">
                        <label for="email">Correo:</label><br/>
                        <input type="email" name="email" required/>
                    </div>
                    <div class="campo">
                        <label for="consulta">Motivo de consulta:</label><br/>
                        <select name="consulta" required>
                            <option selected="selected">Seleccione motivo</option>
                            <?php
                                $conexion = new mysqli("127.0.0.1", "dockeruser", "Docker@2025", "epulse");
                                if(!$conexion){
                                    echo ERROR_CONNECT_DATA_COMBOBOX;
                                } else {
                                    $puestos = "SELECT Labor FROM labores";
                                    $resultados = $conexion->query($puestos);
                                    if($resultados->num_rows == 0){
                                        echo ERROR_NO_DATA_COMBOBOX;
                                    } else {
                                        $row = $resultados->fetch_assoc();
                                        do {
                                            echo "<option>{$row["Labor"]}</option>";
                                            $row = $resultados->fetch_assoc();
                                        } while ($row);
                                    }
                                }
                            ?>
                        </select>
                    </div>
                    <div class="campo">
                        <label for="responsable">Responsable:</label><br/>
                        <select name="responsable" required>
                            <option selected="selected">Elija al responsable</option>
                            <?php
                                $conexion = new mysqli("127.0.0.1", "dockeruser", "Docker@2025", "epulse");
                                if(!$conexion){
                                    echo ERROR_CONNECT_DATA_COMBOBOX;
                                } else {
                                    $trabajadores = "SELECT Nombre FROM trabajadores";
                                    $resultados = $conexion->query($trabajadores);
                                    if($resultados->num_rows == 0){
                                        echo ERROR_NO_DATA_COMBOBOX;
                                    } else {
                                        $row = $resultados->fetch_assoc();
                                        do {
                                            echo "<option>{$row["Nombre"]}</option>";
                                            $row = $resultados->fetch_assoc();
                                        } while ($row);
                                    }
                                }
                            ?>
                        </select>
                    </div>
                    <div class="campo">
                        <label for="textarea">Mensaje:</label><br/>
                        <textarea name="mensaje" required></textarea>
                    </div>
                    <div class="campo">
                        <button name="guardardatos">GUARDAR DATOS</button>
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