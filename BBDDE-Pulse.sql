-- --------------------------------------------------------
-- Host:                         127.0.0.1
-- Versión del servidor:         10.4.32-MariaDB - mariadb.org binary distribution
-- SO del servidor:              Win64
-- HeidiSQL Versión:             12.8.0.6908
-- --------------------------------------------------------

/*!40101 SET @OLD_CHARACTER_SET_CLIENT=@@CHARACTER_SET_CLIENT */;
/*!40101 SET NAMES utf8 */;
/*!50503 SET NAMES utf8mb4 */;
/*!40103 SET @OLD_TIME_ZONE=@@TIME_ZONE */;
/*!40103 SET TIME_ZONE='+00:00' */;
/*!40014 SET @OLD_FOREIGN_KEY_CHECKS=@@FOREIGN_KEY_CHECKS, FOREIGN_KEY_CHECKS=0 */;
/*!40101 SET @OLD_SQL_MODE=@@SQL_MODE, SQL_MODE='NO_AUTO_VALUE_ON_ZERO' */;
/*!40111 SET @OLD_SQL_NOTES=@@SQL_NOTES, SQL_NOTES=0 */;


-- Volcando estructura de base de datos para epulse
DROP DATABASE IF EXISTS `epulse`;
CREATE DATABASE IF NOT EXISTS `epulse` /*!40100 DEFAULT CHARACTER SET utf8mb4 COLLATE utf8mb4_general_ci */;
USE `epulse`;

-- Volcando estructura para tabla epulse.clientes
DROP TABLE IF EXISTS `clientes`;
CREATE TABLE IF NOT EXISTS `clientes` (
  `Id_Cliente` smallint(5) unsigned NOT NULL AUTO_INCREMENT,
  `Nombre` varchar(100) NOT NULL,
  `Correo` varchar(100) NOT NULL,
  `Id_Labor` int(11) NOT NULL,
  `Mensaje` longtext NOT NULL,
  `Fecha_Consulta` date NOT NULL,
  `Id_Trabajador` smallint(5) unsigned NOT NULL,
  `Id_Progreso` smallint(5) unsigned NOT NULL,
  PRIMARY KEY (`Id_Cliente`),
  KEY `fk_idlabor1` (`Id_Labor`),
  KEY `fk_idprogreso1` (`Id_Progreso`),
  KEY `fk_idtrabajador2` (`Id_Trabajador`),
  CONSTRAINT `fk_idtrabajador2` FOREIGN KEY (`Id_Trabajador`) REFERENCES `trabajadores` (`Id_Trabajador`) ON DELETE CASCADE ON UPDATE CASCADE,
  CONSTRAINT `fk_idlabor1` FOREIGN KEY (`Id_Labor`) REFERENCES `labores` (`Id_Labor`) ON DELETE CASCADE ON UPDATE CASCADE,
  CONSTRAINT `fk_idprogreso1` FOREIGN KEY (`Id_Progreso`) REFERENCES `progresos` (`Id_Progreso`) ON DELETE CASCADE ON UPDATE CASCADE
) ENGINE=InnoDB AUTO_INCREMENT=2 DEFAULT CHARSET=utf8mb4 COLLATE=utf8mb4_general_ci;

-- Volcando datos para la tabla epulse.clientes: ~0 rows (aproximadamente)
REPLACE INTO `clientes` (`Id_Cliente`, `Nombre`, `Correo`, `Id_Labor`, `Mensaje`, `Fecha_Consulta`, `Id_Trabajador`, `Id_Progreso`) VALUES
	(1, 'María', 'maria@hotmail.com', 4, 'Buenos días, ¿alguien me pueder crear ydiseñar un AirBnb para mi proyecto de alquilar casas a los turistas?', '2024-12-05', 5, 1);

-- Volcando estructura para tabla epulse.labores
DROP TABLE IF EXISTS `labores`;
CREATE TABLE IF NOT EXISTS `labores` (
  `Id_Labor` int(11) NOT NULL AUTO_INCREMENT,
  `Labor` varchar(100) NOT NULL,
  `Descripción` longtext NOT NULL,
  `Id_Puesto` int(11) NOT NULL,
  PRIMARY KEY (`Id_Labor`),
  KEY `fk_idpuesto2` (`Id_Puesto`),
  CONSTRAINT `fk_idpuesto2` FOREIGN KEY (`Id_Puesto`) REFERENCES `puestos` (`Id_Puesto`) ON DELETE CASCADE ON UPDATE CASCADE
) ENGINE=InnoDB AUTO_INCREMENT=7 DEFAULT CHARSET=utf8mb4 COLLATE=utf8mb4_general_ci;

-- Volcando datos para la tabla epulse.labores: ~6 rows (aproximadamente)
REPLACE INTO `labores` (`Id_Labor`, `Labor`, `Descripción`, `Id_Puesto`) VALUES
	(1, 'Tramitar o consultar un pedido', 'Disponemos de un servicio con la empresa colaboradora <a href="https://tienda.bricogeek.com/" target="_blank">BricoGeek</a> en el que usted puede realizar o consultar un pedido para luego empaquetarlo y enviártelo desde <a href="https://www.correos.es/es/es/particulares" target="_blank">Correos</a>.', 3),
	(2, 'Optimizar un sitio web para buscadores en Internet', 'Si tienes una página junto con un dominio asociado a ella y necesitas ayuda con el SEO y herramientas de Google para que tu web aparezca en las primeras filas del buscador, puedes contar con nosotros para que te realicemos ese proceso.', 2),
	(3, 'Adquirir un dominio en Internet', 'Si necesitas obtener un dominio para tu página web, no te lo sigas pensando ni buscando más ayuda: nosotros te buscaremos uno y te avisaremos para ver si te sirve y cobrarte lo que nos cueste el dominio.', 2),
	(4, 'Diseñar una página o un sitio web a medida', 'Si tienes un proyecto planteado y necesitas una página web, tenemos a un especialista en desarrollo web que te ayudará en lo que necesites sea desarrollo codificado o usando plantillas prediseñadas en Wix, GoDaddy o WordPress.', 4),
	(5, 'Notificar un problema a Recursos Humanos', 'Si tienes algún problema sea con tu página, o con Internet o con un pedido que hiciste, háznoslo saber en la sección de Contacto o a <a href="mailto:info@e-pulse.org">info@e-pulse.org</a>.', 1),
	(6, 'Otras labores o consultas', 'Si necesitas consultar otra cosa, envíanoslo en la sección de Contacto o a <a href="mailto:info@e-pulse.org">info@e-pulse.org</a>.', 1);

-- Volcando estructura para tabla epulse.noticias
DROP TABLE IF EXISTS `noticias`;
CREATE TABLE IF NOT EXISTS `noticias` (
  `Num_Noticia` smallint(5) unsigned NOT NULL AUTO_INCREMENT,
  `Imagen` varchar(100) NULL,
  `Título` varchar(100) NOT NULL,
  `Descripción` longtext NOT NULL,
  `Enlace` longtext NOT NULL,
  `Fecha_Publicación` date NOT NULL,
  `Id_Trabajador` smallint(5) unsigned NOT NULL,
  PRIMARY KEY (`Num_Noticia`),
  KEY `fk_idtrabajador1` (`Id_Trabajador`),
  CONSTRAINT `fk_idtrabajador1` FOREIGN KEY (`Id_Trabajador`) REFERENCES `trabajadores` (`Id_Trabajador`) ON DELETE CASCADE ON UPDATE CASCADE
) ENGINE=InnoDB AUTO_INCREMENT=11 DEFAULT CHARSET=utf8mb4 COLLATE=utf8mb4_general_ci;

-- Volcando datos para la tabla epulse.noticias: ~10 rows (aproximadamente)
REPLACE INTO `noticias` (`Num_Noticia`, `Imagen`, `Título`, `Descripción`, `Enlace`, `Fecha_Publicación`, `Id_Trabajador`) VALUES
	(1, 'Imágenes/Noticias/BateriaCasera.png', 'Cómo construir una batería casera con baterías 18650 y recarga solar', 'Las baterías 18650 tiene una muy buena relación de volumen, peso y capacidad y son ampliamente utilizadas en todo tipo de aparatos. Con ellas también \r\npuedes construir una batería con mucha más capacidad uniendo las celdas. Una caja impresa para dejarlo bien montado y junto con un cargador solar tienes un equipo totalmente autónomo.', 'https://blog.bricogeek.com/noticias/diy/como-construir-una-bateria-casera-con-baterias-18650-y-recarga-solar/', '2024-07-27', 2),
	(2, 'Imágenes/Noticias/CNCSencillaYBarataConArduino.png', 'Cómo construir una CNC sencilla y barata con Arduino', 'A todos nos gusta la maquinaria y aunque las impresoras 3D pululan como si no hubiese mañana, las máquinas CNC siguen teniendo luz propia y bastante protagonismo. Son máquinas normalmente \r\ncaras aunque también hay mucha casera. Si te planteas meterte en el mundo de las máquinas CNC pero no quieres gastar mucho dinero, una buena opción es montarte una sencilla para comenzar. \r\nPodrás cortar con precisión todo tipo de materiales como plásticos, acrílicos o madera. A continuación te dejo un estupendo tutorial que te enseña a montar una pequeña máquina CNC casera \r\ncontrolada con Arduino. No podrás cortar aluminio de 5 centímetros de espesor en una pasada, pero podrás conocer de más cerca éste maravilloso mundo.', 'https://blog.bricogeek.com/noticias/arduino/como-construir-una-cnc-sencilla-y-barata-con-arduino/', '2024-07-23', 2),
	(3, 'Imágenes/Noticias/ArduinoCaseroEnUnaPCB.png', 'Cómo fabricar tu propio Arduino casero en una PCB', 'Ya sea para ahorrar espacio, costes o simplemente para saber más sobre cómo funcionan los microcontroladores, es un muy buen ejercicio aprender a montar un Arduino desde cero. \r\nEn su esencia, la placa Arduino tiene muy poquitos componentes que se usan básicamente para alimentar y hacer funcionar el microcontrolador AVR. Es un ejercicio excelente para aprender \r\nmucho más acerca de su funcionamiento y os dejo una guía completa sobre cómo hacerlo a continuación. También te permitirá hacer montajes más completos ya que puedes incluso aprovechar para \r\nincluir más componentes como sensores, conectores, LEDs etc y así tener una placa mejorada y personalizada para tu proyecto. Aunque siempre es una buena idea apoyar el proyecto comprando \r\nalguna placa original de vez en cuando ;)', 'https://blog.bricogeek.com/noticias/arduino/como-fabricar-tu-propio-arduino-casero-en-una-pcb/', '2024-06-25', 2),
	(4, 'Imágenes/Noticias/WifiHackESP8266.png', 'Cómo hackear redes Wifi con ESP8266 (Wifi Jammer)', 'El pequeño módulo Wifi ESP8266 no deja de sorprendernos por todas las cosas que se pueden hacer con él. Una de ellas puede ser vacilar a tus amigos hackeando sus redes Wifi. No se trata \r\naquí de un ataque de fuerza bruta para aprovecharse de la conexión, sino que se puede trolear haciendo que el ESP8266 cree decenas de redes wifi con nombres de SSID con caracteres aleatorios, \r\no que envíe a una determinada red muchos paquetes DEAUTH para que se sature y de paso desconectar los dispositivos conectados a ella. Es un experimento interesante que muestra otro uso de \r\néste pequeño dispositivo. Si lo pruebas, no seas malo y usalo solo con tu propia red a modo de experimento para no molestar a nadie más ;)', 'https://blog.bricogeek.com/noticias/electronica/como-hackear-redes-wifi-con-esp8266-wifi-jammer/', '2024-06-17', 2),
	(5, 'Imágenes/Noticias/EnviarDatosAServidorMySQLESP8266.png', 'Cómo enviar datos a un servidor MySQL con ESP8266', 'Los amiguetes de Prometec han publicado una interesante guía donde explican en detalle cómo enviar datos como temperatura y humedad a un servidor MySQL desde un ESP8266. Ésto es muy útil \r\ny muy utilizado en todo tipo de proyectos y no siempre se explica en detalle ya que requiere por un lado preparar un servidor web y por otro programa la propia placa para realizar el envío \r\nde datos. Os recomiendo éste tutorial así como otros que tienen en prometec que son muy completos.', 'https://blog.bricogeek.com/noticias/electronica/como-enviar-datos-a-un-servidor-mysql-con-esp8266/', '2024-07-31', 2),
	(6, 'Imágenes/Noticias/CincoFormasDeImprimirTextoEImagenSobreMadera.png', 'Cinco formas de imprimir texto e imagen sobre madera', 'Si trabajas con madera para hacer cajas o cualquier otro proyecto, puede quedar interesante grabar algún texto o incluso una imagen sobre ella para que el resultado quede mucho más atractivo. \r\nEn el vídeo que os dejo a continuación se muestran cinco formas para imprimir sobre madera, cada una con mejor o peor resultado, algunas fáciles y otras que toman un poco más de tiempo.', 'https://blog.bricogeek.com/noticias/diy/cinco-formas-de-imprimir-texto-e-imagen-sobre-madera/', '2024-08-18', 2),
	(7, 'Imágenes/Noticias/DetectorMetalesArduino.png', 'Cómo hacer un detector de metales con Arduino', 'Si quieres sentirte comno un auténtico buscador de tesoros y a lo mejor sacarte un dinerito extra mientras paseas por la playa, necesitas un detector de metales como éste. Se basa en un \r\nArduino junto con una bobina que se encarga de detectar metales por el principio de la inducción de pulsos. Una pequeña pantalla mostrará la posición aproximada del objeto y quién sabe ¡igual \r\nencuentras un tesoro! Os dejo un par de vídeos así como el enlace con las instrucciones por si quieres hacerte uno.', 'https://blog.bricogeek.com/noticias/arduino/como-hacer-un-detector-de-metal-con-arduino/', '2024-08-26', 2),
	(8, 'Imágenes/Noticias/ComoEmparejarModulosBluetoothHC05.png', 'Cómo emparejar dos módulos bluetooth HC-05', 'Es muy común utilizar módulos bluetooth para realizar una comunicación inalámbrica en todo tipo de proyectos, uno de los más populares es el módulo bluetooth HC-05, que es económico y puede \r\nfuncionar tanto en modo maestro como esclavo. Con esto se puede por ejemplo emparejar un robot con un móvil y a esta alturas es muy fácil y seguramente ya lo has hecho varias veces. Sin \r\nembargo no es tan evidente emparejar dos módulos HC-05 entre ellos, por ejemplo para que dos placa Arduino se comuniquen entre ellas. En el vídeo que os dejo a continuación se explica cómo \r\nhacerlo paso a paso.', 'https://blog.bricogeek.com/noticias/electronica/como-emparejar-dos-modulos-bluetooth-hc-05/', '2024-09-12', 2),
	(9, 'Imágenes/Noticias/CortadoraLaserCO2Casera.png', 'Cómo hacer una cortadora láser CO2 desde cero', 'Me he enterado del proyecto de InventorsFactory que se han montado una enorme cortadora láser CO2 desde cero. La máquina es realmente impresionante con su tamaño de 120x115 centímetros. Es \r\ncontrolada mediante Match3 al igual que un router CNC y tiene todo un sistema de refrigeración por agua para el tubo CO2 de 50W, base Z motorizada y la estructura hecha con perfiles de \r\naluminio. Me parece un trabajo titánico y realmente espectacular. Os dejo los cuatro vídeos a continuación donde se muestran todos los detalles.', 'https://blog.bricogeek.com/noticias/diy/como-hacer-una-cortadora-laser-co2-desde-cero/', '2024-08-24', 2),
	(10, 'Imágenes/Noticias/ComoControlarUnaTiraDeLedRGBConElMovilUsandoBluetoothYArduino.png', 'Cómo controlar una tira de LED RGB con el móvil usando Bluetooth y Arduino', 'Controlar algo remotamente es algo que siempre mola, además si ese control lo hace un móvil ya mola mucho más. Como donde hay un LED hay alegría, os dejo por aquí un excelente vídeo de \r\nnuestros amiguetes de electronoobs que nos muestran cómo controlar una tira de LED RGB con el móvil usando Bluetooth y una placa Arduino. La aplicación para Android ha sido desarrolada con \r\nMIT App Inventor y el sistema puede ser también una base para controlar todo tipo de dispositivos sin cables.', 'https://blog.bricogeek.com/noticias/arduino/como-controlar-una-tira-de-led-rgb-con-el-movil-usando-bluetooth-y-arduino/', '2024-09-10', 2);

-- Volcando estructura para tabla epulse.progresos
DROP TABLE IF EXISTS `progresos`;
CREATE TABLE IF NOT EXISTS `progresos` (
  `Id_Progreso` smallint(5) unsigned NOT NULL AUTO_INCREMENT,
  `Progreso` varchar(100) NOT NULL,
  PRIMARY KEY (`Id_Progreso`)
) ENGINE=InnoDB AUTO_INCREMENT=5 DEFAULT CHARSET=utf8mb4 COLLATE=utf8mb4_general_ci;

-- Volcando datos para la tabla epulse.progresos: ~4 rows (aproximadamente)
REPLACE INTO `progresos` (`Id_Progreso`, `Progreso`) VALUES
	(1, 'Pendiente de realizar'),
	(2, 'Iniciado'),
	(3, 'En progreso'),
	(4, 'Terminado');

-- Volcando estructura para tabla epulse.puestos
DROP TABLE IF EXISTS `puestos`;
CREATE TABLE IF NOT EXISTS `puestos` (
  `Id_Puesto` int(11) NOT NULL AUTO_INCREMENT,
  `Puesto` varchar(100) NOT NULL,
  PRIMARY KEY (`Id_Puesto`)
) ENGINE=InnoDB AUTO_INCREMENT=5 DEFAULT CHARSET=utf8mb4 COLLATE=utf8mb4_general_ci;

-- Volcando datos para la tabla epulse.puestos: ~4 rows (aproximadamente)
REPLACE INTO `puestos` (`Id_Puesto`, `Puesto`) VALUES
	(1, 'Administrador'),
	(2, 'Consultor'),
	(3, 'Gestor de pedidos'),
	(4, 'Becario');

-- Volcando estructura para tabla epulse.trabajadores
DROP TABLE IF EXISTS `trabajadores`;
CREATE TABLE IF NOT EXISTS `trabajadores` (
  `Id_Trabajador` smallint(5) unsigned NOT NULL AUTO_INCREMENT,
  `Nombre` varchar(100) NOT NULL,
  `Usuario` varchar(50) NOT NULL,
  `Clave` varchar(100) NOT NULL,
  `Id_Puesto` int(11) NOT NULL,
  PRIMARY KEY (`Id_Trabajador`),
  UNIQUE KEY `Nombre` (`Nombre`),
  UNIQUE KEY `Usuario` (`Usuario`),
  KEY `fk_idpuesto1` (`Id_Puesto`),
  CONSTRAINT `fk_idpuesto1` FOREIGN KEY (`Id_Puesto`) REFERENCES `puestos` (`Id_Puesto`) ON DELETE CASCADE ON UPDATE CASCADE
) ENGINE=InnoDB AUTO_INCREMENT=6 DEFAULT CHARSET=utf8mb4 COLLATE=utf8mb4_general_ci;

-- Volcando datos para la tabla epulse.trabajadores: ~5 rows (aproximadamente)
REPLACE INTO `trabajadores` (`Id_Trabajador`, `Nombre`, `Usuario`, `Clave`, `Id_Puesto`) VALUES
	(1, 'Administrador', 'admin', '$2y$10$d5KQzRA4.viCsDlgJbcIAO6i1JJpTQUqQSKdROFYcnTLAcEDMLCmK', 1),
	(2, 'Óscar González', 'oscar', '$2y$10$d5KQzRA4.viCsDlgJbcIAO6i1JJpTQUqQSKdROFYcnTLAcEDMLCmK', 1),
	(3, 'Diego Fernández', 'diego', '$2y$10$d5KQzRA4.viCsDlgJbcIAO6i1JJpTQUqQSKdROFYcnTLAcEDMLCmK', 3),
	(4, 'Alejandro Mallo Souto', 'mallo', '$2y$10$PhQSY.D8I14sdF//IxCPuOxfbqljppuVzAv678.o2v.EwHY1SNExe', 2),
	(5, 'Blasco Rodríguez Porta', 'blasco', '$2y$10$d5KQzRA4.viCsDlgJbcIAO6i1JJpTQUqQSKdROFYcnTLAcEDMLCmK', 4);

/*!40103 SET TIME_ZONE=IFNULL(@OLD_TIME_ZONE, 'system') */;
/*!40101 SET SQL_MODE=IFNULL(@OLD_SQL_MODE, '') */;
/*!40014 SET FOREIGN_KEY_CHECKS=IFNULL(@OLD_FOREIGN_KEY_CHECKS, 1) */;
/*!40101 SET CHARACTER_SET_CLIENT=@OLD_CHARACTER_SET_CLIENT */;
/*!40111 SET SQL_NOTES=IFNULL(@OLD_SQL_NOTES, 1) */;
