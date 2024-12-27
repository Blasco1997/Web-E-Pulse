<?php
    define('CSSPATH', '');
    $css = "../CSS/disenoepulsepeticiones.css"."?v=".rand(1, 1000);
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
            <nav class="navegacion">
                <ul>
                    <li><a href="index.php">Inicio</a></li>
                    <li class='seleccionado'>Consultas
                        <ul>
                            <li><a href='listadetrabajadores.php'>Lista de trabajadores</a></li>
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
                </script>
            </nav>
        </header>
        <main class="index">
            <?php
                $conexion = new mysqli("127.0.0.1", "dockeruser", "Docker@2025", "epulse");
                if(!$conexion){
                    echo ERROR_CONNECT_DATA;
                } else {
                    $numpeticiones = "SELECT COUNT(Id_Cliente) AS NumPeticiones FROM clientes";
                    $resultado = $conexion->query($numpeticiones);
                    if($resultado->num_rows == 0){
                        echo ERROR_NOT_ORDERS;
                    } else {
                        $row = $resultado->fetch_assoc();
                        $totalpeticiones = $row["NumPeticiones"];
                        for ($i = 1; $i <= $totalpeticiones; $i++) { 
                            if(isset($_GET["idcliente$i"]) && isset($_GET["verdetalles$i"])){
                                $idcliente = $_GET["idcliente$i"];
                                $verdetalles = "SELECT cl.Id_Cliente AS Id_Cliente, cl.Nombre AS Nombre, cl.Correo AS Correo, la.Labor AS Labor,
                                cl.Mensaje AS Mensaje, DATE_FORMAT(cl.Fecha_Consulta, '%d/%m/%Y') AS Fecha_Consulta, pu.Puesto AS Puesto, 
                                tr.Nombre AS Trabajador, pr.Progreso AS Progreso FROM clientes AS cl JOIN labores AS la JOIN puestos AS pu 
                                JOIN trabajadores AS tr JOIN progresos AS pr ON cl.Id_Labor = la.Id_Labor AND la.Id_Puesto = pu.Id_Puesto 
                                AND tr.Id_Puesto = pu.Id_Puesto AND cl.Id_Progreso = pr.Id_Progreso WHERE cl.Id_Cliente = $idcliente";
                                $resultado = $conexion->query($verdetalles);
                                if($resultado->num_rows == 0){
                                    echo ERROR_ORDER_NOT_EXISTS;
                                } else {
                                    $row = $resultado->fetch_assoc();
                                    echo "<section class='pedido'>
                                    <p id='volveratras'><a href='listadepeticiones.php'>&#8592; Volver atrás</a></p>
                                    <h1 id='detalles'>DETALLES DEL PEDIDO {$row["Id_Cliente"]}</h1>
                                    <div id='nombrecliente'><p>Nombre:<br/><b>{$row["Nombre"]}</b></p></div>
                                    <div id='correocliente'><p>Correo:<br/><a href='mailto:{$row["Correo"]}'><b>{$row["Correo"]}</b></a></p></div>
                                    <div id='fechadeconsulta'><p>Fecha de consulta:<br/><b>{$row["Fecha_Consulta"]}</b></p></div>
                                    <div id='asunto'><p>Asunto:<br/><b>{$row["Labor"]}</b></p></div>
                                    <div id='mensaje'><p>Mensaje:<br/>{$row["Mensaje"]}</p></div>
                                    <div id='sector'><p>Sector asignado:<br/><b>{$row["Puesto"]}</b></p></div>
                                    <div id='responsable'><p>Responsable:<br/><b>{$row["Trabajador"]}</b></p></div>";
                                    if($row["Progreso"] == "Pendiente de realizar"){
                                        echo "<div id='progreso'><p>Progreso:<br/><b style='color: red;'>{$row["Progreso"]}</b><br/><input type='range' min='1' max='4' value='1' disabled></p></div>";
                                    } else if($row["Progreso"] == "Iniciado"){
                                        echo "<div id='progreso'><p>Progreso:<br/><b  style='color: orange;'>{$row["Progreso"]}</b><br/><input type='range' min='1' max='4' value='2' disabled></p></div>";
                                    } else if($row["Progreso"] == "En progreso"){
                                        echo "<div id='progreso'><p>Progreso:<br/><b  style='color: rgb(211, 197, 0);'>{$row["Progreso"]}</b><br/><input type='range' min='1' max='4' value='3' disabled></p></div>";
                                    } else if($row["Progreso"] == "Terminado"){
                                        echo "<div id='progreso'><p>Progreso:<br/><b  style='color: green;'>{$row["Progreso"]}</b><br/><input type='range' min='1' max='4' value='4' disabled></p></div>";
                                    } 
                                    echo "</section>";
                                }
                            }
                        }
                    }
                }
                $conexion->close();
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