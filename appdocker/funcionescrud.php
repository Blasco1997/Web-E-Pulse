<?php
    include "errores.php";
?>
<?php
    function registrarTrabajador($nombre, $clave, $puesto){
        $numcomillassimplesnombre = mb_substr_count($nombre, "'");
        $numcomillassimplesclave = mb_substr_count($clave, "'");
        if($numcomillassimplesnombre%2 == 1 || $numcomillassimplesclave%2 == 1){
            $salidaok = false;
        } else {
            $cifrada = password_hash($clave, PASSWORD_DEFAULT);
            $conexion = new mysqli("127.0.0.1", "dockeruser", "Docker@2025", "epulse");
            if(!$conexion){
                echo ERROR_CONNECT_DATA;
            } else {
                $existepuesto = "SELECT * FROM puestos WHERE Puesto = '$puesto'";
                $resultado = $conexion->query($existepuesto);
                if($resultado->num_rows == 0){
                    echo ERROR_UNKNOWN_EMPLOYEE;
                } else {
                    $row = $resultado->fetch_assoc();
                    $idpuesto = $row["Id_Puesto"];
                    $registrar = "INSERT INTO trabajadores(Nombre, Clave, Id_Puesto) VALUES('$nombre', '$cifrada', '$idpuesto')";
                    if($conexion->query($registrar)==true){
                        $salidaok = true;
                    } else if($conexion->query($registrar)==false){
                        $salidaok = false;
                    }
                }
            }
        }
        return $salidaok;
    }
    function modificarTrabajador($numtrabajador, $nombre, $usuario, $clave, $puesto){
        $numcomillassimplesnombre = mb_substr_count($nombre, "'");
        $numcomillassimplesusuario = mb_substr_count($usuario, "'");
        $numcomillassimplesclave = mb_substr_count($clave, "'");
        if($numcomillassimplesnombre%2 == 1 || $numcomillassimplesusuario%2 == 1 || $numcomillassimplesclave%2 == 1){
            $salidaok = false;
        } else {
            $cifrada = password_hash($clave, PASSWORD_DEFAULT);
            $conexion = new mysqli("127.0.0.1", "dockeruser", "Docker@2025", "epulse");
            if(!$conexion){
                echo ERROR_CONNECT_DATA;
            } else {
                $existetrabajador = "SELECT * FROM trabajadores WHERE Id_Trabajador = '$numtrabajador'";
                $resultado = $conexion->query($existetrabajador);
                if($resultado->num_rows == 0){
                    echo ERROR_WORKER_NOT_FOUND;
                } else {
                    $row = $resultado->fetch_assoc();
                    $idtrabajador = $row["Id_Trabajador"];
                    $existepuesto = "SELECT * FROM puestos WHERE Puesto = '$puesto'";
                    $resultado = $conexion->query($existepuesto);
                    if($resultado->num_rows == 0){
                        echo ERROR_UNKNOWN_EMPLOYEE;
                    } else {
                        $row = $resultado->fetch_assoc();
                        $idpuesto = $row["Id_Puesto"];
                        $modificardatos = "UPDATE trabajadores SET Nombre = '$nombre', Usuario = '$usuario', Clave = '$cifrada', Id_Puesto = '$idpuesto' WHERE Id_Trabajador = '$idtrabajador'";
                        if($conexion->query($modificardatos)==true){
                            $salidaok = true;
                        } else if($conexion->query($modificardatos)==false){
                            $salidaok = false;
                        }
                    }
                }
            }
        }
        return $salidaok;
    }
    function eliminarTrabajador($numtrabajador){
        $salidaok = true;
        $conexion = new mysqli("127.0.0.1", "dockeruser", "Docker@2025", "epulse");
        if(!$conexion){
            echo ERROR_CONNECT_DATA;
        } else {
            $datostrabajador = "SELECT * FROM trabajadores WHERE Id_Trabajador = '$numtrabajador'";
            $resultado = $conexion->query($datostrabajador);
            if($resultado->num_rows == 0){
                echo ERROR_WORKER_NOT_EXISTS;
            } else {
                $eliminardatos = "DELETE FROM trabajadores WHERE Id_Trabajador = '$numtrabajador'";
                if($conexion->query($eliminardatos)==true){
                    $salidaok = true;
                } else if($conexion->query($eliminardatos)==false){
                    $salidaok = false;
                }
            }
        }
        return $salidaok;
    }
    function publicarConsulta($nombre, $email, $consulta, $mensaje, $responsable) {
        $numcomillassimplesnombre = mb_substr_count($nombre, "'");
        $numcomillassimplesemail = mb_substr_count($email, "'");
        $numcomillassimplesmensaje = mb_substr_count($mensaje, "'");
        if($numcomillassimplesnombre%2 == 1 || $numcomillassimplesemail%2 == 1 || $numcomillassimplesmensaje%2 == 1){
            $salidaok = false;
        } else {
            $conexion = new mysqli("127.0.0.1", "dockeruser", "Docker@2025", "epulse");
            if(!$conexion){
                echo ERROR_CONNECT_DATA;
            } else {
                $existelabor = "SELECT * FROM labores WHERE Labor = '$consulta'";
                $resultado = $conexion->query($existelabor);
                if($resultado->num_rows == 0){
                    echo ERROR_TASK_CONSULTING_NOT_EXISTS;
                } else {
                    $row = $resultado->fetch_assoc();
                    $idlabor = $row["Id_Labor"];
                    $existetrabajador = "SELECT * FROM trabajadores WHERE Nombre = '$responsable'";
                    $resultado = $conexion->query($existetrabajador);
                    if($resultado->num_rows == 0){
                        echo ERROR_WORKER_NOT_EXISTS;
                    } else {
                        $row = $resultado->fetch_assoc();
                        $idtrabajador = $row["Id_trabajador"];
                        $publicarconsulta = "INSERT INTO clientes(Nombre, Correo, Id_Labor, Mensaje, Fecha_Consulta, Id_Trabajador, Id_Progreso) 
                        VALUES('$nombre', '$email', '$idlabor', '$mensaje', CURDATE(), '$idtrabajador', 1)";
                        if($conexion->query($publicarconsulta) == true){
                            $salidaok = true;
                        } else if($conexion->query($publicarconsulta) == false) {
                            $salidaok = false;
                        }
                    }
                }
            }
        }
        return $salidaok;
    }
    function iniciarSesion($usuario, $clave){
        $salidaok = true;
        $conexion = new mysqli("127.0.0.1", "dockeruser", "Docker@2025", "epulse");
        if(!$conexion){
            echo ERROR_CONNECT_DATA;
        } else {
            $existetrabajador = "SELECT * FROM trabajadores WHERE Usuario = '$usuario'";
            $resultado = $conexion->query($existetrabajador);
            if($resultado->num_rows == 0){
                echo ERROR_WORKER_NOT_EXISTS;
            } else {
                $row = $resultado->fetch_assoc();
                $hashgenerado = $row["Clave"];
                $claveverificada = password_verify($clave, $hashgenerado);
                if($row["Usuario"] == $usuario && $claveverificada == true){
                    session_start();
                    $_SESSION["trabajador"] = $row["Nombre"];
                    header("location: index.php");
                } else {
                    $salidaok = false;
                }
            }
        }
        if($salidaok == false){
            echo ERROR_ACCESS_LOGIN;
        }
        return $salidaok;
    }
    function resetearClave($usuario) {
        $salidaok = true;
        $conexion = new mysqli("127.0.0.1", "dockeruser", "Docker@2025", "epulse");
        if(!$conexion){
            echo ERROR_CONNECT_DATA;
        } else {
            $existetrabajador = "SELECT * FROM trabajadores WHERE Usuario = '$usuario'";
            $resultado = $conexion->query($existetrabajador);
            if($resultado->num_rows == 0){
                $salidaok = false;
            } else {
                $salidaok = true;
            }
        }
        if($salidaok == true){
            echo "<script>window.location.href = 'renovarclave.php';</script>";
        } else if($salidaok == false){
            echo ERROR_WORKER_NOT_EXISTS;
        }
        return $salidaok;
    }
    function renovarClave($usuario, $clave, $repetirclave) {
        $salidaok = true;
        $noexisteusuario = false;
        $clavesdiferentes = false;
        $problemasdeactualizarclave = false;
        $conexion = new mysqli("127.0.0.1", "dockeruser", "Docker@2025", "epulse");
        if(!$conexion){
            echo ERROR_CONNECT_DATA;
        } else {
            $existetrabajador = "SELECT * FROM trabajadores WHERE Usuario = '$usuario'";
            $resultado = $conexion->query($existetrabajador);
            if($resultado->num_rows == 0){
                $salidaok = false;
                $noexisteusuario = true;
            } else {
                $row = $resultado->fetch_assoc();
                $idtrabajador = $row["Id_Trabajador"];
                if($repetirclave != $clave){
                    $clavesdiferentes = true;
                } else {
                    $cifrada = password_hash($clave, PASSWORD_DEFAULT);
                    $actualizarclave = "UPDATE trabajadores SET Clave = '$cifrada' WHERE Id_Trabajador = '$idtrabajador'";
                    if($conexion->query($actualizarclave) == true) {
                        $salidaok = true;
                    } else if($conexion->query($actualizarclave) == false) {
                        $salidaok = false;
                        $problemasdeactualizarclave = true;
                    }
                }
            }
        }
        if($salidaok == true){
            echo OK_PASS_UPDATED;
        } else if($salidaok == false){
            echo "<p>No se pudo resetear la clave por esta razón:</p>";
        }
        if($noexisteusuario == true){
            echo ERROR_WORKER_NOT_EXISTS;
        } else if($clavesdiferentes == true){
            echo ERROR_DIFFERENT_PASS;
        } else if($problemasdeactualizarclave == true){
            echo ERROR_PASS_NOT_UPDATED;
        }
        return $salidaok;
    }
    function actualizarProgreso($idcliente, $progreso){
        $salidaok = true;
        $conexion = new mysqli("127.0.0.1", "dockeruser", "Docker@2025", "epulse");
        if(!$conexion){
            echo ERROR_CONNECT_DATA;
        } else {
            $existecliente = "SELECT * FROM clientes WHERE Id_Cliente = '$idcliente'";
            $resultado = $conexion->query($existecliente);
            if($resultado->num_rows == 0){
                echo ERROR_CLIENT_NOT_EXISTS;
            } else {
                $row = $resultado->fetch_assoc();
                $existeprogreso = "SELECT * FROM progresos WHERE Progreso = '$progreso'";
                $resultado = $conexion->query($existeprogreso);
                if($resultado->num_rows == 0){
                    echo ERROR_PROGRESS_NOT_UPDATED;
                } else {
                    $row = $resultado->fetch_assoc();
                    $idprogreso = $row["Id_Progreso"];
                    $actualizarprogreso = "UPDATE clientes SET Id_Progreso = '$idprogreso' WHERE Id_Cliente = '$idcliente'";
                    if($conexion->query($actualizarprogreso)==true){
                        $salidaok = true;
                    } else if($conexion->query($actualizarprogreso)==false){
                        $salidaok = false;
                    }
                }
            }
        }
        if($salidaok == true){
            echo OK_PROGRESS_UPDATED;
        } else if($salidaok == false){
            echo ERROR_PROGRESS_NOT_UPDATED;
        }
        return $salidaok;
    }
    function publicarNoticia($imagen, $enlace, $titulo, $descripcion, $usuario){
        $numcomillassimplesenlace = mb_substr_count($enlace, "'");
        $numcomillassimplestitulo = mb_substr_count($titulo, "'");
        $numcomillassimplesdescripcion = mb_substr_count($descripcion, "'");
        if($numcomillassimplesenlace%2 == 1 || $numcomillassimplestitulo%2 == 1 || $numcomillassimplesdescripcion%2 == 1){
            $salidaok = false;
        } else {
            $conexion = new mysqli("127.0.0.1", "dockeruser", "Docker@2025", "epulse");
            if(!$conexion){
                echo ERROR_CONNECT_DATA;
            } else {
                $existetrabajador = "SELECT * FROM trabajadores WHERE Nombre = '$usuario'";
                $resultado = $conexion->query($existetrabajador);
                if($resultado->num_rows == 0){
                    echo ERROR_WORKER_NOT_EXISTS;
                } else  {
                    $row=$resultado->fetch_assoc();
                    $idtrabajador = $row["Id_Trabajador"];
                    $publicarnoticia = "INSERT INTO noticias(Imagen, Título, Descripción, Enlace, Fecha_Publicación, Id_Trabajador)
                    VALUES('../Imágenes/Noticias/$imagen', '$titulo', '$descripcion', '$enlace', CURDATE(), '$idtrabajador')";
                    if($conexion->query($publicarnoticia)==true){
                        $salidaok = true;
                    } else if($conexion->query($publicarnoticia)==false){
                        $salidaok = false;
                    }
                }
            }
        }
        if($salidaok == true){
            echo OK_NOTICE_POSTED;
        } else if($salidaok == false){
            echo ERROR_NOTICE_NOT_POSTED;
        }
        return $salidaok;
    }
    function actualizarNoticia($numnoticia, $imagen, $enlace, $titulo, $descripcion, $usuario){
        $numcomillassimplesenlace = mb_substr_count($enlace, "'");
        $numcomillassimplestitulo = mb_substr_count($titulo, "'");
        $numcomillassimplesdescripcion = mb_substr_count($descripcion, "'");
        if($numcomillassimplesenlace%2 == 1 || $numcomillassimplestitulo%2 == 1 || $numcomillassimplesdescripcion%2 == 1){
            $salidaok = false;
        } else {
            $conexion = new mysqli("127.0.0.1", "dockeruser", "Docker@2025", "epulse");
            if(!$conexion){
                echo ERROR_CONNECT_DATA;
            } else {
                $existetrabajador = "SELECT * FROM trabajadores WHERE Nombre = '$usuario'";
                $resultado = $conexion->query($existetrabajador);
                if($resultado->num_rows == 0){
                    echo ERROR_WORKER_NOT_EXISTS;
                } else  {
                    $row=$resultado->fetch_assoc();
                    $idtrabajador = $row["Id_Trabajador"];
                    $existenoticia = "SELECT * FROM noticias WHERE Num_Noticia = '$numnoticia'";
                    $resultado = $conexion->query($existenoticia);
                    if($resultado->num_rows == 0){
                        echo ERROR_NOTICE_NOT_POSTED;
                    } else  {
                        $row=$resultado->fetch_assoc();
                        $idnoticia = $row["Num_Noticia"];
                        $actualizarnoticia = "UPDATE noticias SET Imagen = '../Imágenes/Noticias/$imagen', Título = '$titulo', Descripción = '$descripcion', 
                        Enlace = '$enlace', Fecha_Publicación = CURDATE(), Id_Trabajador = '$idtrabajador' WHERE Num_Noticia = '$idnoticia'";
                        if($conexion->query($actualizarnoticia)==true){
                            $salidaok = true;
                        } else if($conexion->query($actualizarnoticia)==false){
                            $salidaok = false;
                        }
                    }
                }
            }
        }
        if($salidaok == true){
            echo OK_NOTICE_UPDATED;
        } else if($salidaok == false){
            echo ERROR_NOTICE_NOT_UPDATED;
        }
        return $salidaok;
    }
    function eliminarNoticia($numnoticia){
        $salidaok = true;
        $conexion = new mysqli("127.0.0.1", "dockeruser", "Docker@2025", "epulse");
        if(!$conexion){
            echo ERROR_CONNECT_DATA;
        } else {
            $datosnoticia = "SELECT * FROM noticias WHERE Num_Noticia = '$numnoticia'";
            $resultado = $conexion->query($datosnoticia);
            if($resultado->num_rows == 0){
                echo ERROR_NOTICE_NOT_EXISTS;
            } else {
                $eliminarnoticia = "DELETE FROM noticias WHERE Num_Noticia = '$numnoticia'";
                if($conexion->query($eliminarnoticia)==true){
                    $salidaok = true;
                } else if($conexion->query($eliminarnoticia)==false){
                    $salidaok = false;
                }
            }
        }
        if($salidaok == true){
            echo OK_NOTICE_DELETED;
        } else if($salidaok == false){
            echo ERROR_NOTICE_NOT_DELETED;
        }
        return $salidaok;
    }
    function importarXMLTrabajadores($ficheroxml){
        $salidaok = true;
        if(file_exists("../XML/$ficheroxml")){
            $xml = simplexml_load_file("XML/$ficheroxml");
            $filasAfectadas = 0;
            $conexion = new mysqli("127.0.0.1", "dockeruser", "Docker@2025", "epulse");
            if(!$conexion){
                echo ERROR_CONNECT_DATA;
            } else {
                foreach ($xml->children() as $row) {
                    $nombre = $row->nombre;
                    $usuario = $row->usuario;
                    $clave = $row->clave;
                    $puesto = $row->puesto;
                    
                    $cifrada = password_hash($clave, PASSWORD_DEFAULT);
                    $existepuesto = "SELECT * FROM puestos WHERE Puesto = '$puesto'";
                    $resultado = $conexion->query($existepuesto);
                    if($resultado->num_rows == 0){
                        echo ERROR_POST_EMPLOYEE_NOT_FOUND;
                    } else {
                        $row = $resultado->fetch_assoc();
                        $idpuesto = $row["Id_Puesto"];
                        $registrarTrabajador = "INSERT INTO trabajadores(Nombre, Usuario, Clave, Id_Puesto) VALUES('$nombre','$usuario','$cifrada','$idpuesto')";
                        if($conexion->query($registrarTrabajador) == true){
                            $filasAfectadas++;
                        }
                    }
            }
            if($filasAfectadas != 0){
                    echo OK_IMPORT_WORKERS_FROM_XML;
                }
            }
        } else {
            $salidaok = false;
        }
        if($salidaok == false){
            echo ERROR_FILEXML_NOT_EXISTS . "XML/$ficheroxml</p>";
        }
        return $salidaok;
    }
    function importarXMLPeticiones($ficheroxml){
        $salidaok = true;
        if(file_exists("../XML/$ficheroxml")){
            $xml = simplexml_load_file("XML/$ficheroxml");
            $filasAfectadas = 0;
            $conexion = new mysqli("127.0.0.1", "dockeruser", "Docker@2025", "epulse");
            if(!$conexion){
                echo ERROR_CONNECT_DATA;
            } else {
                foreach ($xml->children() as $row) {
                    $nombre = $row->nombreCliente;
                    $correo = $row->correoCliente;
                    $tarea = $row->tituloPeticion;
                    $descripcion = $row->descripcionPeticion;
                    $fechaConsulta = $row->fechaPeticion;
                    $responsable = $row->responsable;
                    
                    $existetarea = "SELECT * FROM labores WHERE Labor = '$tarea'";
                    $resultado = $conexion->query($existetarea);
                    if($resultado->num_rows == 0){
                        echo ERROR_TASK_CONSULTING_NOT_EXISTS;
                    } else {
                        $row = $resultado->fetch_assoc();
                        $idlabor = $row["Id_Labor"];
                        $existetrabajador = "SELECT * FROM trabajadores WHERE Nombre = '$responsable'";
                        $resultado = $conexion->query($existetrabajador);
                        if($resultado->num_rows == 0){
                            echo ERROR_WORKER_NOT_EXISTS;
                        } else {
                            $row = $resultado->fetch_assoc();
                            $idtrabajador = $row["Id_Trabajador"];
                            $registrarTarea = "INSERT INTO clientes(Nombre, Correo, Id_Labor, Mensaje, Fecha_Consulta, Id_Trabajador, Id_Progreso) VALUES('$nombre','$correo','$idlabor','$descripcion', '$fechaConsulta', '$idtrabajador', 1)";
                            if($conexion->query($registrarTarea) == true){
                                $filasAfectadas++;
                            }
                        }
                    }
                    if($filasAfectadas != 0){
                            echo OK_IMPORT_TASKS_FROM_XML;
                    }
                }
            }
        } else {
            $salidaok = false;
        }
        if($salidaok == false){
            echo ERROR_FILEXML_NOT_EXISTS . "XML/$ficheroxml</p>";
        }
        return $salidaok;
    }
    function importarXMLNoticias($ficheroxml){
        $salidaok = true;
        if(file_exists("../XML/$ficheroxml")){
            $xml = simplexml_load_file("XML/$ficheroxml");
            $filasAfectadas = 0;
            $conexion = new mysqli("127.0.0.1", "dockeruser", "Docker@2025", "epulse");
            if(!$conexion){
                echo ERROR_CONNECT_DATA;
            } else {
                foreach ($xml->children() as $row) {
                    $titulo = $row->tituloNoticia;
                    $enlace = $row->enlaceNoticia;
                    $descripcion = $row->descripcionNoticia;
                    $fechaPublicacion = $row->fechaPublicacion;
                    $autor = $row->autor;
                    
                    $existetrabajador = "SELECT * FROM trabajadores WHERE Nombre = '$autor'";
                    $resultado = $conexion->query($existetrabajador);
                    if($resultado->num_rows == 0){
                        echo ERROR_WORKER_NOT_EXISTS;
                    } else {
                        $row = $resultado->fetch_assoc();
                        $idtrabajador = $row["Id_Trabajador"];
                        $publicarnoticia = "INSERT INTO noticias(Título, Descripción, Enlace, Fecha_Publicación, Id_Trabajador) VALUES('$titulo','$descripcion','$enlace','$fechaPublicacion', '$idtrabajador')";
                        if($conexion->query($publicarnoticia) == true){
                            $filasAfectadas++;
                        }
                    }
                }
                if($filasAfectadas != 0){
                        echo OK_IMPORT_NEWS_FROM_XML;
                }
            }
        } else {
            $salidaok = false;
        }
        if($salidaok == false){
            echo ERROR_FILEXML_NOT_EXISTS . "XML/$ficheroxml</p>";
        }
        return $salidaok;
    }
?>