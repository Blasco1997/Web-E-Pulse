<?php
    function paginacionIndex($limitededatos, $totaldatos){
        // Conexión a la base de datos
        $conexion = new mysqli("127.0.0.1", "dockeruser", "Docker@2025", "epulse");
                    
        if ($conexion->connect_error) {
            die("Conexión fallida: " . $conexion->connect_error);
        }
        
        // Número de resultados por página
        $registros_por_pagina = $limitededatos;
        // Obtener el total de registros
        $totalnoticias = $totaldatos;
        $resultado = $conexion->query($totalnoticias);
        $row_total = $resultado->fetch_assoc();
        $total_registros = $row_total['total'];
        
        // Determinar el número de páginas
        $total_paginas = ceil($total_registros / $registros_por_pagina);
        
        // Obtener la página actual, por defecto es la página 1
        $pagina_actual = isset($_GET['pagina']) ? (int)$_GET['pagina'] : 1;
        
        // Asegurarse de que la página esté en un rango válido
        if ($pagina_actual < 1) {
            $pagina_actual = 1;
        } elseif ($pagina_actual > $total_paginas) {
            $pagina_actual = $total_paginas;
        }
        
        // Calcular el índice de inicio para la consulta SQL
        $inicio = ($pagina_actual - 1) * $registros_por_pagina;
        
        // Consultar las noticias para la página actual
        $telediario = "SELECT nt.Imagen AS Imagen, nt.Título AS Título, nt.Descripción AS Descripción, 
        nt.Enlace AS Enlace, DATE_FORMAT(nt.Fecha_Publicación, '%d/%m/%Y') AS Fecha_Publicación, tr.Nombre AS Nombre 
        FROM noticias AS nt JOIN trabajadores AS tr ON nt.Id_Trabajador = tr.Id_Trabajador ORDER BY nt.Fecha_Publicación DESC
        LIMIT $inicio, $registros_por_pagina";
        $resultados = $conexion->query($telediario);
        if($resultados->num_rows > 0) {
            $i = 1;
            $caracterest = 69;
            $caracteresd = 180;
            $row = $resultados->fetch_assoc();
            do {
                if(strlen($row["Título"]) > $caracterest){
                    echo "<div id='podcast$i'>
                    <img src='{$row["Imagen"]}' width='100%' alt='{$row["Título"]}'>
                    <h1>".substr("{$row["Título"]}", 0, $caracterest)."...</h1>
                    <p>".substr("{$row["Descripción"]}", 0, $caracteresd)."...</p>
                    <p class='enlace' onclick='window.open(\"{$row["Enlace"]}\", \"_blank\")'>Ver enlace</p>
                    <hr>
                    <p class='autor'><small>Publicado el día {$row["Fecha_Publicación"]} por <b>{$row["Nombre"]}</b></small></p>
                    </div>";
                } else {
                    echo "<div id='podcast$i'>
                    <img src='{$row["Imagen"]}' width='100%' alt='{$row["Título"]}'>
                    <h1>{$row["Título"]}</h1>
                    <p>".substr("{$row["Descripción"]}", 0, $caracteresd)."...</p>
                    <p class='enlace' onclick='window.open(\"{$row["Enlace"]}\", \"_blank\")'>Ver enlace</p>
                    <hr>
                    <p class='autor'><small>Publicado el día {$row["Fecha_Publicación"]} por <b>{$row["Nombre"]}</b></small></p>
                    </div>";
                }
                $i++;
                $row = $resultados->fetch_assoc();
            } while ($row);
        } else {
            echo ERROR_NO_NEWS;
        }
        
        // Mostrar enlaces de paginación
        echo "<div id='paginacion'>";
        if($pagina_actual > 1) {
            echo "<a href='?pagina=" . ($pagina_actual - 1) . "'>Anterior</a> | ";
        }
        
        for($i = 1; $i <= $total_paginas; $i++) {
            if ($i == $pagina_actual) {
                echo "<a href='#'>$i</a> | ";
            } else if ($i == $pagina_actual - 1 || $i == $pagina_actual + 1 ) {
                echo "<a href='?pagina=$i'>$i</a> | ";
            }
        }
        
        if($pagina_actual < $total_paginas) {
            echo "<a href='?pagina=" . ($pagina_actual + 1) . "'>Siguiente</a>";
        }
        echo "</div>";
        // Cerrar la conexión
        $conexion->close();
    }
    function paginacionTablaTrabajadores($limitededatos, $totaldatos, $usuario){
        echo "<section class='tablatrabajadores'><table>
        <tr>
            <th colspan='4'><h1>TRABAJADORES E-PULSE</h1></th>
        </tr>
        <tr>
            <th>Nombre</th>
            <th>Usuario</th>
            <th>Puesto</th>
            <th>Opciones</th>
        </tr>";
        // Conexión a la base de datos
        $conexion = new mysqli("127.0.0.1", "dockeruser", "Docker@2025", "epulse");
                    
        if ($conexion->connect_error) {
            die("Conexión fallida: " . $conexion->connect_error);
        }
        
        // Número de resultados por página
        $registros_por_pagina = $limitededatos;
        // Obtener el total de registros
        $totaltrabajadores = $totaldatos;
        $resultado = $conexion->query($totaltrabajadores);
        $row_total = $resultado->fetch_assoc();
        $total_registros = $row_total['total'];
        
        // Determinar el número de páginas
        $total_paginas = ceil($total_registros / $registros_por_pagina);
        
        // Obtener la página actual, por defecto es la página 1
        $pagina_actual = isset($_GET['pagina']) ? (int)$_GET['pagina'] : 1;
        
        // Asegurarse de que la página esté en un rango válido
        if ($pagina_actual < 1) {
            $pagina_actual = 1;
        } elseif ($pagina_actual > $total_paginas) {
            $pagina_actual = $total_paginas;
        }
        
        // Calcular el índice de inicio para la consulta SQL
        $inicio = ($pagina_actual - 1) * $registros_por_pagina;
        
        // Consultar los productos para la página actual
        $leerdatos = "SELECT tr.Id_Trabajador AS Id_Trabajador, tr.Nombre AS Nombre, tr.Usuario AS Usuario, tr.Clave AS Clave, pt.Puesto AS Puesto 
        FROM trabajadores AS tr JOIN puestos AS pt ON tr.Id_Puesto = pt.Id_Puesto LIMIT $inicio, $registros_por_pagina";
        $resultados = $conexion->query($leerdatos);
        if($resultados->num_rows == 0){
            echo "<tr><td colspan='4'><img src='Imágenes/Advice.png' width='15' alt='Advertencia'/> Sin datos que mostrar.</td></tr>";
        } else {
            $row = $resultados->fetch_assoc();
            $i = 1;
            do {
                if($row["Nombre"] == "Administrador"){
                    echo "<tr><td colspan='4'><img src='Imágenes/Advice.png' width='15' alt='Advertencia'/> La cuenta de {$row["Nombre"]} no se puede borrar ni modificar.</td></tr>";
                } else if($usuario == $row["Nombre"]){
                    echo "<tr><td colspan='4'><img src='Imágenes/Advice.png' width='15' alt='Advertencia'/> El trabajador {$row["Nombre"]} tiene la cuenta abierta.</td></tr>";
                } else {
                    echo "<tr><form action='".htmlentities($_SERVER["PHP_SELF"])."' method='get'>
                    <input type='hidden' name='idtrabajador$i' id='idtrabajador$i' value='{$row["Id_Trabajador"]}'/>
                    <td><input type='hidden' id='nombrerecogido$i' value='{$row["Nombre"]}'/>{$row["Nombre"]}</td>
                    <td><input type='hidden' id='usuariorecogido$i' value='{$row["Usuario"]}'/>{$row["Usuario"]}</td>
                    <input type='hidden' id='claverecogida$i' value='{$row["Clave"]}'/>
                    <td><input type='hidden' id='puestorecogido$i' value='{$row["Puesto"]}'/>{$row["Puesto"]}</td>
                    <td><input type='button' id='editar$i' value='EDITAR'/><input type='button' id='eliminar$i' value='ELIMINAR'/></td></form></tr>
                    <script>
                        let editar$i = document.getElementById('editar$i');
                        editar$i.addEventListener('click', function(){
                            let edicion = document.getElementById('edicion');
                            edicion.style.animation = 'aparecerFormularioEdicion 1s ease-in-out forwards';
                            let idtrabajador$i = document.getElementById('idtrabajador$i');
                            let numtrabajador = document.getElementById('numtrabajador');
                            let nombrerecogido$i = document.getElementById('nombrerecogido$i');
                            let nombre = document.getElementById('nombre');
                            let usuariorecogido$i = document.getElementById('usuariorecogido$i');
                            let usuario = document.getElementById('usuario');
                            let claveerecogida$i = document.getElementById('claverecogida$i');
                            let clave = document.getElementById('clave');
                            let puestorecogido$i = document.getElementById('puestorecogido$i');
                            let puesto = document.getElementById('puesto');
                            numtrabajador.value = idtrabajador$i.value;
                            nombre.value = nombrerecogido$i.value;
                            usuario.value = usuariorecogido$i.value
                            clave.value = claverecogida$i.value;
                            puesto.value = puestorecogido$i.value;
                            let cerraredicion = document.getElementById('cerraredicion');
                            cerraredicion.addEventListener('click', function(){
                                edicion.style.animation = 'desaparecerFormularioEdicion 1s ease-in-out';
                            });
                        });
                        let eliminar$i = document.getElementById('eliminar$i');
                        eliminar$i.addEventListener('click', function(){
                            let advice = document.getElementById('advice');
                            advice.style.display = 'block';
                            let idtrabajador$i = document.getElementById('idtrabajador$i');
                            let idtrabajadoreliminar = document.getElementById('idtrabajadoreliminar');
                            idtrabajadoreliminar.value = idtrabajador$i.value;
                        });
                    </script>";
                }
                $row = $resultados->fetch_assoc();
                $i++;
            } while ($row);
        }
        echo "</table></section>";
        // Mostrar enlaces de paginación
        echo "<div id='paginacion'>";
        if($pagina_actual > 1) {
            echo "<a href='?pagina=" . ($pagina_actual - 1) . "'>Anterior</a> | ";
        }
        
        for($i = 1; $i <= $total_paginas; $i++) {
            if ($i == $pagina_actual) {
                echo "<a href='#'>$i</a> | ";
            } else if($i == $pagina_actual - 1 || $i == $pagina_actual + 1 ) {
                echo "<a href='?pagina=$i'>$i</a> | ";
            }
        }
        
        if($pagina_actual < $total_paginas) {
            echo "<a href='?pagina=" . ($pagina_actual + 1) . "'>Siguiente</a>";
        }
        echo "</div>";
        // Cerrar la conexión
        $conexion->close();
    }
    function paginacionTablaTrabajadoresMovil($limitededatos, $totaldatos, $usuario){
        echo "<section class='tablatrabajadoresmovil'><table>
        <tr>
            <th colspan='2'><h1>TRABAJADORES E-PULSE</h1></th>
        </tr>";
        // Conexión a la base de datos
        $conexion = new mysqli("127.0.0.1", "dockeruser", "Docker@2025", "epulse");
                    
        if ($conexion->connect_error) {
            die("Conexión fallida: " . $conexion->connect_error);
        }
        
        // Número de resultados por página
        $registros_por_pagina = $limitededatos;
        // Obtener el total de registros
        $totaltrabajadores = $totaldatos;
        $resultado = $conexion->query($totaltrabajadores);
        $row_total = $resultado->fetch_assoc();
        $total_registros = $row_total['total'];
        
        // Determinar el número de páginas
        $total_paginas = ceil($total_registros / $registros_por_pagina);
        
        // Obtener la página actual, por defecto es la página 1
        $pagina_actual = isset($_GET['pagina']) ? (int)$_GET['pagina'] : 1;
        
        // Asegurarse de que la página esté en un rango válido
        if ($pagina_actual < 1) {
            $pagina_actual = 1;
        } elseif ($pagina_actual > $total_paginas) {
            $pagina_actual = $total_paginas;
        }
        
        // Calcular el índice de inicio para la consulta SQL
        $inicio = ($pagina_actual - 1) * $registros_por_pagina;
        
        // Consultar los productos para la página actual
        $leerdatos = "SELECT tr.Id_Trabajador AS Id_Trabajador, tr.Nombre AS Nombre, tr.Usuario AS Usuario, tr.Clave AS Clave, pt.Puesto AS Puesto 
        FROM trabajadores AS tr JOIN puestos AS pt ON tr.Id_Puesto = pt.Id_Puesto LIMIT $inicio, $registros_por_pagina";
        $resultados = $conexion->query($leerdatos);
        if($resultados->num_rows == 0){
            echo "<tr><td colspan='4'><img src='Imágenes/Advice.png' width='15' alt='Advertencia'/> Sin datos que mostrar.</td></tr>";
        } else {
            $row = $resultados->fetch_assoc();
            $i = 1;
            do {
                echo "<tr><th colspan='2'>TRABAJADOR Nº {$row["Id_Trabajador"]}</th></tr>";
                if($row["Nombre"] == "Administrador"){
                    echo "<tr><td colspan='2'><img src='Imágenes/Advice.png' width='15' alt='Advertencia'/> La cuenta de {$row["Nombre"]} no se puede borrar ni modificar.</td></tr>";
                } else if($usuario == $row["Nombre"]){
                    echo "<tr><td colspan='2'><img src='Imágenes/Advice.png' width='15' alt='Advertencia'/> El trabajador {$row["Nombre"]} tiene la cuenta abierta.</td></tr>";
                } else {
                    echo "<tr><form action='".htmlentities($_SERVER["PHP_SELF"])."' method='get'>
                    <input type='hidden' name='idtrabajador$i' id='idtrabajador$i' value='{$row["Id_Trabajador"]}'/>
                    <th>Nombre</th><td><input type='hidden' id='nombrerecogido$i' value='{$row["Nombre"]}'/>{$row["Nombre"]}</td></tr>
                    <tr><th>Usuario</th><td><input type='hidden' id='usuariorecogido$i' value='{$row["Usuario"]}'/>{$row["Usuario"]}</td></tr>
                    <input type='hidden' id='claverecogida$i' value='{$row["Clave"]}'/>
                    <tr><th>Puesto</th><td><input type='hidden' id='puestorecogido$i' value='{$row["Puesto"]}'/>{$row["Puesto"]}</td></tr>
                    <tr><th>Opciones</th><td><input type='button' id='editarmovil$i' value='EDITAR'/><input type='button' id='eliminarmovil$i' value='ELIMINAR'/></td></form></tr>
                    <script>
                        let editarmovil$i = document.getElementById('editarmovil$i');
                        editarmovil$i.addEventListener('click', function(){
                            let edicion = document.getElementById('edicion');
                            edicion.style.animation = 'aparecerFormularioEdicion 1s ease-in-out forwards';
                            let idtrabajador$i = document.getElementById('idtrabajador$i');
                            let numtrabajador = document.getElementById('numtrabajador');
                            let nombrerecogido$i = document.getElementById('nombrerecogido$i');
                            let nombre = document.getElementById('nombre');
                            let usuariorecogido$i = document.getElementById('usuariorecogido$i');
                            let usuario = document.getElementById('usuario');
                            let claveerecogida$i = document.getElementById('claverecogida$i');
                            let clave = document.getElementById('clave');
                            let puestorecogido$i = document.getElementById('puestorecogido$i');
                            let puesto = document.getElementById('puesto');
                            numtrabajador.value = idtrabajador$i.value;
                            nombre.value = nombrerecogido$i.value;
                            usuario.value = usuariorecogido$i.value
                            clave.value = claverecogida$i.value;
                            puesto.value = puestorecogido$i.value;
                            let cerraredicion = document.getElementById('cerraredicion');
                            cerraredicion.addEventListener('click', function(){
                                edicion.style.animation = 'desaparecerFormularioEdicion 1s ease-in-out';
                            });
                        });
                        let eliminarmovil$i = document.getElementById('eliminarmovil$i');
                        eliminarmovil$i.addEventListener('click', function(){
                            let advice = document.getElementById('advice');
                            advice.style.display = 'block';
                            let idtrabajador$i = document.getElementById('idtrabajador$i');
                            let idtrabajadoreliminar = document.getElementById('idtrabajadoreliminar');
                            idtrabajadoreliminar.value = idtrabajador$i.value;
                        });
                    </script>";
                }
                $row = $resultados->fetch_assoc();
                $i++;
            } while ($row);
        }
        echo "</table></section>";
        // Mostrar enlaces de paginación
        echo "<div id='paginacion2'>";
        if($pagina_actual > 1) {
            echo "<a href='?pagina=" . ($pagina_actual - 1) . "'>Anterior</a> | ";
        }
        
        for($i = 1; $i <= $total_paginas; $i++) {
            if ($i == $pagina_actual) {
                echo "<a href='#'>$i</a> | ";
            } else if($i == $pagina_actual - 1 || $i == $pagina_actual + 1 ) {
                echo "<a href='?pagina=$i'>$i</a> | ";
            }
        }
        
        if($pagina_actual < $total_paginas) {
            echo "<a href='?pagina=" . ($pagina_actual + 1) . "'>Siguiente</a>";
        }
        echo "</div>";
        // Cerrar la conexión
        $conexion->close();
    }
    function paginacionTablaPeticiones($limitededatos, $totaldatos){
        echo "<section class='tablapeticiones'><table>
        <tr>
            <th colspan='7'><h1>PETICIONES DE CLIENTES</h1></th>
        </tr>
        <tr>
            <th>Nombre</th>
            <th>Correo</th>
            <th>Petición</th>
            <th>Fecha</th>
            <th>Responsable</th>
            <th>Progreso</th>
            <th>Opciones</th>
        </tr>";
        // Conexión a la base de datos
        $conexion = new mysqli("127.0.0.1", "dockeruser", "Docker@2025", "epulse");
                    
        if ($conexion->connect_error) {
            die("Conexión fallida: " . $conexion->connect_error);
        }
        
        // Número de resultados por página
        $registros_por_pagina = $limitededatos;
        // Obtener el total de registros
        $totalpeticiones = $totaldatos;
        $resultado = $conexion->query($totalpeticiones);
        $row_total = $resultado->fetch_assoc();
        $total_registros = $row_total['total'];
        
        // Determinar el número de páginas
        $total_paginas = ceil($total_registros / $registros_por_pagina);
        
        // Obtener la página actual, por defecto es la página 1
        $pagina_actual = isset($_GET['pagina']) ? (int)$_GET['pagina'] : 1;
        
        // Asegurarse de que la página esté en un rango válido
        if ($pagina_actual < 1) {
            $pagina_actual = 1;
        } elseif ($pagina_actual > $total_paginas) {
            $pagina_actual = $total_paginas;
        }
        
        // Calcular el índice de inicio para la consulta SQL
        $inicio = ($pagina_actual - 1) * $registros_por_pagina;
        
        // Consultar las peticiones para la página actual
        //Aquí hago un try-cacth porque siempre me salta el típico error de consulta MySQL Uncaught:mysqli_sql_exception
        try {
            $verpeticiones = "SELECT cl.Id_Cliente AS Id_Cliente, cl.Nombre AS Nombre, cl.Correo AS Correo, la.Labor AS Labor,
            cl.Mensaje AS Mensaje, DATE_FORMAT(cl.Fecha_Consulta, '%d/%m/%Y') AS Fecha_Consulta, pu.Puesto AS Puesto, 
            tr.Nombre AS Trabajador, pr.Progreso AS Progreso FROM clientes AS cl JOIN labores AS la JOIN puestos AS pu 
            JOIN trabajadores AS tr JOIN progresos AS pr ON cl.Id_Labor = la.Id_Labor AND la.Id_Puesto = pu.Id_Puesto 
            AND tr.Id_Puesto = pu.Id_Puesto AND cl.Id_Progreso = pr.Id_Progreso AND cl.Id_Trabajador = tr.Id_Trabajador 
            ORDER BY cl.Fecha_Consulta DESC LIMIT $inicio, $registros_por_pagina";
            $resultados = $conexion->query($verpeticiones);
            if($resultados->num_rows == 0){
                echo "<tr><td colspan='7'>".ERROR_NOT_ORDERS."</td></tr>";
            } else {
                $row = $resultados->fetch_assoc();
                $i = 1;
                do {
                    echo "<tr><td>{$row["Nombre"]}</td><td><a href='mailto:{$row["Correo"]}'>{$row["Correo"]}</a></td><td>{$row["Labor"]}</td>
                    <td>{$row["Fecha_Consulta"]}</td><td>{$row["Trabajador"]}</td><td>{$row["Progreso"]}</td>
                    <td><form action='".htmlentities($_SERVER["PHP_SELF"])."' method='get'>
                    <input type='hidden' name='idcliente$i' id='idcliente$i' value='{$row["Id_Cliente"]}'/>
                    <input type='hidden' id='progresorecogido$i' name='progreso$i' value='{$row["Progreso"]}'/>
                    <input type='button' id='editarprogreso$i' value='EDITAR PROGRESO'/>
                    </form><form action='detallespeticion.php' method='get'>
                    <input type='hidden' name='idcliente$i' value='{$row["Id_Cliente"]}'/>
                    <button name='verdetalles$i'>VER DETALLES</button></form></td></tr>
                    <script>
                        let editarprogreso$i = document.getElementById('editarprogreso$i');
                        editarprogreso$i.addEventListener('click', function(){
                            let edicion = document.getElementById('edicion');
                            edicion.style.animation = 'aparecerFormularioEdicion 1s ease-in-out forwards';
                            let idcliente$i = document.getElementById('idcliente$i');
                            let numcliente = document.getElementById('numcliente');
                            let progresorecogido$i = document.getElementById('progresorecogido$i');
                            let progreso = document.getElementById('progreso');
                            numcliente.value = idcliente$i.value;
                            progreso.value = progresorecogido$i.value;
                            let cerraredicion = document.getElementById('cerraredicion');
                            cerraredicion.addEventListener('click', function(){
                                edicion.style.animation = 'desaparecerFormularioEdicion 1s ease-in-out';
                            });
                        });
                    </script>";
                    $row = $resultados->fetch_assoc();
                    $i++;
                } while ($row);
            }
            echo "</table></section>";
            // Mostrar enlaces de paginación
            echo "<div id='paginacion'>";
            if($pagina_actual > 1) {
                echo "<a href='?pagina=" . ($pagina_actual - 1) . "'>Anterior</a> | ";
            }
            
            for($i = 1; $i <= $total_paginas; $i++) {
                if ($i == $pagina_actual) {
                    echo "<a href='#'>$i</a> | ";
                } else if($i == $pagina_actual - 1 || $i == $pagina_actual + 1 ) {
                    echo "<a href='?pagina=$i'>$i</a> | ";
                }
            }
            
            if($pagina_actual < $total_paginas) {
                echo "<a href='?pagina=" . ($pagina_actual + 1) . "'>Siguiente</a>";
            }
            echo "</div>";
        } catch (mysqli_sql_exception $e) {
            $e = "<tr><td colspan='7'>".ERROR_NOT_ORDERS."</td></tr>";
            echo $e;
        }
        // Cerrar la conexión
        $conexion->close();
    }
    function paginacionTablaPeticionesMovil($limitededatos, $totaldatos){
        echo "<section class='tablapeticionesmovil'><table>
        <tr>
            <th colspan='2'><h1>PETICIONES DE CLIENTES</h1></th>
        </tr>";
        // Conexión a la base de datos
        $conexion = new mysqli("127.0.0.1", "dockeruser", "Docker@2025", "epulse");
                    
        if ($conexion->connect_error) {
            die("Conexión fallida: " . $conexion->connect_error);
        }
        
        // Número de resultados por página
        $registros_por_pagina = $limitededatos;
        // Obtener el total de registros
        $totalpeticiones = $totaldatos;
        $resultado = $conexion->query($totalpeticiones);
        $row_total = $resultado->fetch_assoc();
        $total_registros = $row_total['total'];
        
        // Determinar el número de páginas
        $total_paginas = ceil($total_registros / $registros_por_pagina);
        
        // Obtener la página actual, por defecto es la página 1
        $pagina_actual = isset($_GET['pagina']) ? (int)$_GET['pagina'] : 1;
        
        // Asegurarse de que la página esté en un rango válido
        if ($pagina_actual < 1) {
            $pagina_actual = 1;
        } elseif ($pagina_actual > $total_paginas) {
            $pagina_actual = $total_paginas;
        }
        
        // Calcular el índice de inicio para la consulta SQL
        $inicio = ($pagina_actual - 1) * $registros_por_pagina;
        
        // Consultar los productos para la página actual
        try {
            $verpeticiones = "SELECT cl.Id_Cliente AS Id_Cliente, cl.Nombre AS Nombre, cl.Correo AS Correo, la.Labor AS Labor,
            cl.Mensaje AS Mensaje, DATE_FORMAT(cl.Fecha_Consulta, '%d/%m/%Y') AS Fecha_Consulta, pu.Puesto AS Puesto, 
            tr.Nombre AS Trabajador, pr.Progreso AS Progreso FROM clientes AS cl JOIN labores AS la JOIN puestos AS pu 
            JOIN trabajadores AS tr JOIN progresos AS pr ON cl.Id_Labor = la.Id_Labor AND la.Id_Puesto = pu.Id_Puesto 
            AND tr.Id_Puesto = pu.Id_Puesto AND cl.Id_Progreso = pr.Id_Progreso ORDER BY cl.Fecha_Consulta DESC LIMIT $inicio, $registros_por_pagina";
            $resultados = $conexion->query($verpeticiones);
            if($resultados->num_rows == 0){
                echo "<tr><td colspan='2'>".ERROR_NOT_ORDERS."</td></tr>";
            } else {
                $row = $resultados->fetch_assoc();
                $i = 1;
                do {
                    echo "<tr><th colspan='2'>PETICIÓN Nº {$row["Id_Cliente"]}</th></tr>
                    <tr><th>Nombre</th><td>{$row["Nombre"]}</td></tr>
                    <tr><th>Correo</th><td><a href='mailto:{$row["Correo"]}'>{$row["Correo"]}</a></td></tr>
                    <tr><th>Petición</th><td>{$row["Labor"]}</td></tr>
                    <tr><th>Fecha de consulta</th><td>{$row["Fecha_Consulta"]}</td></tr>
                    <tr><th>Responsable</th><td>{$row["Trabajador"]}</td></tr>
                    <tr><th>Progreso</th><td>{$row["Progreso"]}</td></tr>
                    <tr><th>Opciones</th><td><form action='".htmlentities($_SERVER["PHP_SELF"])."' method='get'>
                    <input type='hidden' name='idcliente$i' id='idcliente$i' value='{$row["Id_Cliente"]}'/>
                    <input type='hidden' id='progresorecogido$i' name='progreso$i' value='{$row["Progreso"]}'/>
                    <input type='button' id='editarprogresomovil$i' value='EDITAR PROGRESO'/>
                    </form><form action='detallespeticion.php' method='get'>
                    <input type='hidden' name='idcliente$i' value='{$row["Id_Cliente"]}'/>
                    <button name='verdetalles$i'>VER DETALLES</button></form></td></tr>
                    <script>
                        let editarprogresomovil$i = document.getElementById('editarprogresomovil$i');
                        editarprogresomovil$i.addEventListener('click', function(){
                            let edicion = document.getElementById('edicion');
                            edicion.style.animation = 'aparecerFormularioEdicion 1s ease-in-out forwards';
                            let idcliente$i = document.getElementById('idcliente$i');
                            let numcliente = document.getElementById('numcliente');
                            let progresorecogido$i = document.getElementById('progresorecogido$i');
                            let progreso = document.getElementById('progreso');
                            numcliente.value = idcliente$i.value;
                            progreso.value = progresorecogido$i.value;
                            let cerraredicion = document.getElementById('cerraredicion');
                            cerraredicion.addEventListener('click', function(){
                                edicion.style.animation = 'desaparecerFormularioEdicion 1s ease-in-out';
                            });
                        });
                    </script>";
                    $row = $resultados->fetch_assoc();
                    $i++;
                } while ($row);
            }
            echo "</table></section>";
            // Mostrar enlaces de paginación
            echo "<div id='paginacion2'>";
            if($pagina_actual > 1) {
                echo "<a href='?pagina=" . ($pagina_actual - 1) . "'>Anterior</a> | ";
            }
            
            for($i = 1; $i <= $total_paginas; $i++) {
                if ($i == $pagina_actual) {
                    echo "<a href='#'>$i</a> | ";
                } else if($i == $pagina_actual - 1 || $i == $pagina_actual + 1 ) {
                    echo "<a href='?pagina=$i'>$i</a> | ";
                }
            }
            
            if($pagina_actual < $total_paginas) {
                echo "<a href='?pagina=" . ($pagina_actual + 1) . "'>Siguiente</a>";
            }
            echo "</div>";
        } catch (mysqli_sql_exception $e){
            $e = "<tr><td colspan='2'>".ERROR_NOT_ORDERS."</td></tr>";
            echo $e;
        }
        // Cerrar la conexión
        $conexion->close();
    }
    function paginacionTablaPublicaciones($limitededatos, $totaldatos){
        echo "<section class='tablapublicaciones'><table>
        <tr>
            <th colspan='6'><h1>BLOG DE NOTICIAS</h1></th>
        </tr>
        <tr>
            <th>Imagen</th>
            <th>Título</th>
            <th>Descripción</th>
            <th>Fecha de Publicación</th>
            <th>Publicado por</th>
            <th>Opciones</th>
        </tr>";
        // Conexión a la base de datos
        $conexion = new mysqli("127.0.0.1", "dockeruser", "Docker@2025", "epulse");
                    
        if ($conexion->connect_error) {
            die("Conexión fallida: " . $conexion->connect_error);
        }
        
        // Número de resultados por página
        $registros_por_pagina = $limitededatos;
        // Obtener el total de registros
        $totalnoticias = $totaldatos;
        $resultado = $conexion->query($totalnoticias);
        $row_total = $resultado->fetch_assoc();
        $total_registros = $row_total['total'];
        
        // Determinar el número de páginas
        $total_paginas = ceil($total_registros / $registros_por_pagina);
        
        // Obtener la página actual, por defecto es la página 1
        $pagina_actual = isset($_GET['pagina']) ? (int)$_GET['pagina'] : 1;
        
        // Asegurarse de que la página esté en un rango válido
        if ($pagina_actual < 1) {
            $pagina_actual = 1;
        } elseif ($pagina_actual > $total_paginas) {
            $pagina_actual = $total_paginas;
        }
        
        // Calcular el índice de inicio para la consulta SQL
        $inicio = ($pagina_actual - 1) * $registros_por_pagina;
        
        // Consultar los productos para la página actual
        $leerdatos = "SELECT nt.Num_Noticia AS Num_Noticia, nt.Imagen AS Imagen, nt.Título AS Título, nt.Descripción AS Descripción, nt.Enlace AS Enlace,
        DATE_FORMAT(nt.Fecha_Publicación, '%d/%m/%Y') AS Fecha_Publicación, tr.Nombre AS Trabajador FROM noticias AS nt JOIN trabajadores AS tr ON 
        nt.Id_Trabajador = tr.Id_Trabajador ORDER BY nt.Fecha_Publicación DESC LIMIT $inicio, $registros_por_pagina";
        $resultados = $conexion->query($leerdatos);
        if($resultados->num_rows == 0){
            echo "<tr><td colspan='6'><img src='Imágenes/Advice.png' width='15' alt='Advertencia'/> Sin datos que mostrar.</td></tr>";
        } else {
            $row = $resultados->fetch_assoc();
            $i = 1;
            $caracteresd = 500;
            do {
                echo "<tr><form action='".htmlentities($_SERVER["PHP_SELF"])."' method='get'>
                <input type='hidden' name='numnoticia$i' id='numnoticia$i' value='{$row["Num_Noticia"]}'/>
                <td><img src='{$row["Imagen"]}' width='100%' alt='{$row["Imagen"]}'/></td>
                <input type='hidden' id='enlacerecogido$i' value='{$row["Enlace"]}'/>
                <td><input type='hidden' id='titulorecogido$i' value='{$row["Título"]}'/><a href='{$row["Enlace"]}'><b>{$row["Título"]}</b></a></td>
                <td><input type='hidden' id='descripcionrecogida$i' value='{$row["Descripción"]}'/>".substr("{$row["Descripción"]}", 0, $caracteresd)."...</td>
                <td>{$row["Fecha_Publicación"]}</td><td>{$row["Trabajador"]}</td>
                <td><input type='button' id='editar$i' value='EDITAR'/><input type='button' id='eliminar$i' value='ELIMINAR'/></td></form></tr>
                <script>
                    let editar$i = document.getElementById('editar$i');
                    editar$i.addEventListener('click', function(){
                        let edicion = document.getElementById('edicion');
                        edicion.style.animation = 'aparecerFormularioEdicion 1s ease-in-out forwards';
                        let numnoticia$i = document.getElementById('numnoticia$i');
                        let numnoticia = document.getElementById('numnoticia');
                        let enlacerecogido$i = document.getElementById('enlacerecogido$i');
                        let enlace = document.getElementById('enlace');
                        let titulorecogido$i = document.getElementById('titulorecogido$i');
                        let titulo = document.getElementById('titulo');
                        let descripcionrecogida$i = document.getElementById('descripcionrecogida$i');
                        let descripcion = document.getElementById('descripcion');
                        numnoticia.value = numnoticia$i.value;
                        enlace.value = enlacerecogido$i.value
                        titulo.value = titulorecogido$i.value;
                        descripcion.value = descripcionrecogida$i.value;
                        let cerraredicion = document.getElementById('cerraredicion');
                        cerraredicion.addEventListener('click', function(){
                            edicion.style.animation = 'desaparecerFormularioEdicion 1s ease-in-out';
                        });
                    });
                    let eliminar$i = document.getElementById('eliminar$i');
                    eliminar$i.addEventListener('click', function(){
                        let advice = document.getElementById('advice');
                        advice.style.display = 'block';
                        let numnoticia$i = document.getElementById('numnoticia$i');
                        let numnoticiaeliminar = document.getElementById('numnoticiaeliminar');
                        numnoticiaeliminar.value = numnoticia$i.value;
                        });
                </script>";
                $row = $resultados->fetch_assoc();
                $i++;
            } while ($row);
        }
        echo "</table></section>";
        // Mostrar enlaces de paginación
        echo "<div id='paginacion'>";
        if($pagina_actual > 1) {
            echo "<a href='?pagina=" . ($pagina_actual - 1) . "'>Anterior</a> | ";
        }
        
        for($i = 1; $i <= $total_paginas; $i++) {
            if ($i == $pagina_actual) {
                echo "<a href='#'>$i</a> | ";
            } else if($i == $pagina_actual - 1 || $i == $pagina_actual + 1 ) {
                echo "<a href='?pagina=$i'>$i</a> | ";
            }
        }
        
        if($pagina_actual < $total_paginas) {
            echo "<a href='?pagina=" . ($pagina_actual + 1) . "'>Siguiente</a>";
        }
        echo "</div>";
        // Cerrar la conexión
        $conexion->close();
    }
    function paginacionTablaPublicacionesMovil($limitededatos, $totaldatos){
        echo "<section class='tablapublicacionesmovil'><table>
        <tr>
            <th colspan='2'><h1>BLOG DE NOTICIAS</h1></th>
        </tr>";
        // Conexión a la base de datos
        $conexion = new mysqli("127.0.0.1", "dockeruser", "Docker@2025", "epulse");
                    
        if ($conexion->connect_error) {
            die("Conexión fallida: " . $conexion->connect_error);
        }
        
        // Número de resultados por página
        $registros_por_pagina = $limitededatos;
        // Obtener el total de registros
        $totalnoticias = $totaldatos;
        $resultado = $conexion->query($totalnoticias);
        $row_total = $resultado->fetch_assoc();
        $total_registros = $row_total['total'];
        
        // Determinar el número de páginas
        $total_paginas = ceil($total_registros / $registros_por_pagina);
        
        // Obtener la página actual, por defecto es la página 1
        $pagina_actual = isset($_GET['pagina']) ? (int)$_GET['pagina'] : 1;
        
        // Asegurarse de que la página esté en un rango válido
        if ($pagina_actual < 1) {
            $pagina_actual = 1;
        } elseif ($pagina_actual > $total_paginas) {
            $pagina_actual = $total_paginas;
        }
        
        // Calcular el índice de inicio para la consulta SQL
        $inicio = ($pagina_actual - 1) * $registros_por_pagina;
        
        // Consultar los productos para la página actual
        $leerdatos = "SELECT nt.Num_Noticia AS Num_Noticia, nt.Imagen AS Imagen, nt.Título AS Título, nt.Descripción AS Descripción, nt.Enlace AS Enlace,
        DATE_FORMAT(nt.Fecha_Publicación, '%d/%m/%Y') AS Fecha_Publicación, tr.Nombre AS Trabajador FROM noticias AS nt JOIN trabajadores AS tr ON 
        nt.Id_Trabajador = tr.Id_Trabajador ORDER BY nt.Fecha_Publicación DESC LIMIT $inicio, $registros_por_pagina";
        $resultados = $conexion->query($leerdatos);
        if($resultados->num_rows == 0){
            echo "<tr><td colspan='6'><img src='Imágenes/Advice.png' width='15' alt='Advertencia'/> Sin datos que mostrar.</td></tr>";
        } else {
            $row = $resultados->fetch_assoc();
            $i = 1;
            $caracteresd = 500;
            do {
                echo "<tr><th colspan='2'>NOTICIA Nº {$row["Num_Noticia"]}</th></tr>
                <tr><form action='".htmlentities($_SERVER["PHP_SELF"])."' method='get'>
                <input type='hidden' name='numnoticia$i' id='numnoticiarecogido$i' value='{$row["Num_Noticia"]}'/>
                <th>Imagen</th><td><input type='hidden' id='imagenrecogida$i' value='{$row["Imagen"]}'/><img src='{$row["Imagen"]}' width='100%' alt='{$row["Imagen"]}'/></td></tr>
                <input type='hidden' id='enlacerecogido$i' value='{$row["Enlace"]}'/>
                <tr><th>Título</th><td><input type='hidden' id='titulorecogido$i' value='{$row["Título"]}'/><a href='{$row["Enlace"]}'><b>{$row["Título"]}</b></a></td></tr>
                <tr><th>Descripción</th><td><input type='hidden' id='descripcionrecogida$i' value='{$row["Descripción"]}'/>".substr("{$row["Descripción"]}", 0, $caracteresd)."...</td></tr>
                <tr><th>Fecha de publicación</th><td>{$row["Fecha_Publicación"]}</td></tr>
                <tr><th>Publicado por</th><td>{$row["Trabajador"]}</td></tr>
                <tr><th>Opciones</th><td><input type='button' id='editarmovil$i' value='EDITAR'/><input type='button' id='eliminarmovil$i' value='ELIMINAR'/></td></form></tr>
                <script>
                    let editarmovil$i = document.getElementById('editarmovil$i');
                    editarmovil$i.addEventListener('click', function(){
                        let edicion = document.getElementById('edicion');
                        edicion.style.animation = 'aparecerFormularioEdicion 1s ease-in-out forwards';
                        let numnoticia$i = document.getElementById('numnoticia$i');
                        let numnoticia = document.getElementById('numnoticia');
                        let enlacerecogido$i = document.getElementById('enlacerecogido$i');
                        let enlace = document.getElementById('enlace');
                        let titulorecogido$i = document.getElementById('titulorecogido$i');
                        let titulo = document.getElementById('titulo');
                        let descripcionrecogida$i = document.getElementById('descripcionrecogida$i');
                        let descripcion = document.getElementById('descripcion');
                        numnoticia.value = numnoticia$i.value;
                        enlace.value = enlacerecogido$i.value
                        titulo.value = titulorecogido$i.value;
                        descripcion.value = descripcionrecogida$i.value;
                        let cerraredicion = document.getElementById('cerraredicion');
                        cerraredicion.addEventListener('click', function(){
                            edicion.style.animation = 'desaparecerFormularioEdicion 1s ease-in-out';
                        });
                    });
                    let eliminarmovil$i = document.getElementById('eliminarmovil$i');
                    eliminarmovil$i.addEventListener('click', function(){
                        let advice = document.getElementById('advice');
                        advice.style.display = 'block';
                        let numnoticia$i = document.getElementById('numnoticia$i');
                        let numnoticiaeliminar = document.getElementById('numnoticiaeliminar');
                        numnoticiaeliminar.value = numnoticia$i.value;
                    });
                </script>";
                $row = $resultados->fetch_assoc();
                $i++;
            } while ($row);
        }
        echo "</table></section>";
        // Mostrar enlaces de paginación
        echo "<div id='paginacion2'>";
        if($pagina_actual > 1) {
            echo "<a href='?pagina=" . ($pagina_actual - 1) . "'>Anterior</a> | ";
        }
        
        for($i = 1; $i <= $total_paginas; $i++) {
            if ($i == $pagina_actual) {
                echo "<a href='#'>$i</a> | ";
            } else if($i == $pagina_actual - 1 || $i == $pagina_actual + 1 ) {
                echo "<a href='?pagina=$i'>$i</a> | ";
            }
        }
        
        if($pagina_actual < $total_paginas) {
            echo "<a href='?pagina=" . ($pagina_actual + 1) . "'>Siguiente</a>";
        }
        echo "</div>";
        // Cerrar la conexión
        $conexion->close();
    }
?>