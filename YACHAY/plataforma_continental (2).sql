-- phpMyAdmin SQL Dump
-- version 5.2.1
-- https://www.phpmyadmin.net/
--
-- Servidor: 127.0.0.1
-- Tiempo de generación: 27-10-2025 a las 17:01:07
-- Versión del servidor: 10.4.32-MariaDB
-- Versión de PHP: 8.2.12

SET SQL_MODE = "NO_AUTO_VALUE_ON_ZERO";
START TRANSACTION;
SET time_zone = "+00:00";


/*!40101 SET @OLD_CHARACTER_SET_CLIENT=@@CHARACTER_SET_CLIENT */;
/*!40101 SET @OLD_CHARACTER_SET_RESULTS=@@CHARACTER_SET_RESULTS */;
/*!40101 SET @OLD_COLLATION_CONNECTION=@@COLLATION_CONNECTION */;
/*!40101 SET NAMES utf8mb4 */;

--
-- Base de datos: `plataforma_continental`
--

DELIMITER $$
--
-- Procedimientos
--
CREATE DEFINER=`root`@`localhost` PROCEDURE `sp_buscar_libros` (IN `p_termino` VARCHAR(255), IN `p_id_categoria` INT, IN `p_limite` INT)   BEGIN SELECT l.*, c.nombre_categoria, u.nombre_completo AS subido_por FROM libros l LEFT JOIN categorias_libros c ON l.id_categoria = c.id_categoria LEFT JOIN usuarios u ON l.id_usuario_subida = u.id_usuario WHERE l.estado_validacion = 'aprobado' AND (p_termino IS NULL OR MATCH(l.titulo, l.autor_libro, l.descripcion) AGAINST(p_termino IN NATURAL LANGUAGE MODE)) AND (p_id_categoria IS NULL OR l.id_categoria = p_id_categoria) ORDER BY l.fecha_subida DESC LIMIT p_limite; END$$

CREATE DEFINER=`root`@`localhost` PROCEDURE `sp_estadisticas_usuario` (IN `p_id_usuario` INT)   BEGIN SELECT u.nombre_completo, u.email, u.fecha_registro, COUNT(DISTINCT l.id_libro) AS libros_subidos, COUNT(DISTINCT a.id_apunte) AS apuntes_subidos, COALESCE(SUM(l.descargas), 0) + COALESCE(SUM(a.descargas), 0) AS total_descargas, COUNT(DISTINCT mg.id_grupo) AS grupos_participando, COUNT(DISTINCT mc.id_mensaje) AS mensajes_enviados FROM usuarios u LEFT JOIN libros l ON u.id_usuario = l.id_usuario_subida AND l.estado_validacion = 'aprobado' LEFT JOIN apuntes a ON u.id_usuario = a.id_usuario_subida AND a.estado_validacion = 'aprobado' LEFT JOIN miembros_grupo mg ON u.id_usuario = mg.id_usuario LEFT JOIN mensajes_chat mc ON u.id_usuario = mc.id_usuario WHERE u.id_usuario = p_id_usuario GROUP BY u.id_usuario; END$$

CREATE DEFINER=`root`@`localhost` PROCEDURE `sp_top_usuarios_activos` (IN `p_limite` INT)   BEGIN SELECT u.id_usuario, u.nombre_completo, u.email, COUNT(DISTINCT l.id_libro) + COUNT(DISTINCT a.id_apunte) AS contenido_subido, COALESCE(SUM(l.descargas), 0) + COALESCE(SUM(a.descargas), 0) AS total_descargas, COUNT(DISTINCT mc.id_mensaje) AS mensajes_enviados FROM usuarios u LEFT JOIN libros l ON u.id_usuario = l.id_usuario_subida AND l.estado_validacion = 'aprobado' LEFT JOIN apuntes a ON u.id_usuario = a.id_usuario_subida AND a.estado_validacion = 'aprobado' LEFT JOIN mensajes_chat mc ON u.id_usuario = mc.id_usuario WHERE u.estado = 'activo' GROUP BY u.id_usuario ORDER BY contenido_subido DESC, total_descargas DESC LIMIT p_limite; END$$

DELIMITER ;

-- --------------------------------------------------------

--
-- Estructura de tabla para la tabla `apuntes`
--

CREATE TABLE `apuntes` (
  `id_apunte` int(11) NOT NULL,
  `titulo` varchar(500) NOT NULL,
  `descripcion` text DEFAULT NULL,
  `id_curso` int(11) NOT NULL,
  `tipo_material` enum('apuntes','guia','ejercicios','examenes','proyecto','otro') NOT NULL,
  `url_drive` varchar(1000) NOT NULL,
  `id_usuario_subida` int(11) NOT NULL,
  `estado_validacion` enum('pendiente','aprobado','rechazado') DEFAULT 'pendiente',
  `id_validador` int(11) DEFAULT NULL,
  `fecha_validacion` timestamp NULL DEFAULT NULL,
  `comentario_validacion` text DEFAULT NULL,
  `vistas` int(11) DEFAULT 0,
  `descargas` int(11) DEFAULT 0,
  `fecha_subida` timestamp NOT NULL DEFAULT current_timestamp(),
  `fecha_actualizacion` timestamp NOT NULL DEFAULT current_timestamp() ON UPDATE current_timestamp()
) ENGINE=InnoDB DEFAULT CHARSET=utf8mb4 COLLATE=utf8mb4_unicode_ci;

--
-- Disparadores `apuntes`
--
DELIMITER $$
CREATE TRIGGER `trg_habilitar_descarga_apunte` AFTER INSERT ON `apuntes` FOR EACH ROW BEGIN UPDATE usuarios SET puede_descargar = TRUE WHERE id_usuario = NEW.id_usuario_subida AND puede_descargar = FALSE; END
$$
DELIMITER ;

-- --------------------------------------------------------

--
-- Estructura de tabla para la tabla `categorias_libros`
--

CREATE TABLE `categorias_libros` (
  `id_categoria` int(11) NOT NULL,
  `nombre_categoria` varchar(100) NOT NULL,
  `descripcion` text DEFAULT NULL,
  `icono` varchar(50) DEFAULT NULL,
  `fecha_creacion` timestamp NOT NULL DEFAULT current_timestamp()
) ENGINE=InnoDB DEFAULT CHARSET=utf8mb4 COLLATE=utf8mb4_unicode_ci;

--
-- Volcado de datos para la tabla `categorias_libros`
--

INSERT INTO `categorias_libros` (`id_categoria`, `nombre_categoria`, `descripcion`, `icono`, `fecha_creacion`) VALUES
(1, 'Programación', 'Libros sobre lenguajes de programación y desarrollo', NULL, '2025-10-27 14:53:49'),
(2, 'Base de Datos', 'Libros sobre gestión y diseño de bases de datos', NULL, '2025-10-27 14:53:49'),
(3, 'Redes', 'Libros sobre redes de computadoras y telecomunicaciones', NULL, '2025-10-27 14:53:49'),
(4, 'Matemáticas', 'Libros de cálculo, álgebra y matemática aplicada', NULL, '2025-10-27 14:53:49'),
(5, 'Ingeniería de Software', 'Libros sobre metodologías y procesos de desarrollo', NULL, '2025-10-27 14:53:49'),
(6, 'Inteligencia Artificial', 'Libros sobre IA, Machine Learning y Data Science', NULL, '2025-10-27 14:53:49'),
(7, 'Sistemas Operativos', 'Libros sobre arquitectura y administración de SO', NULL, '2025-10-27 14:53:49'),
(8, 'Seguridad Informática', 'Libros sobre ciberseguridad y protección de datos', NULL, '2025-10-27 14:53:49'),
(9, 'General', 'Libros de cultura general y desarrollo personal', NULL, '2025-10-27 14:53:49');

-- --------------------------------------------------------

--
-- Estructura de tabla para la tabla `comedores`
--

CREATE TABLE `comedores` (
  `id` int(11) NOT NULL,
  `nombre` varchar(255) NOT NULL,
  `descripcion` text DEFAULT NULL,
  `direccion` varchar(500) NOT NULL,
  `latitud` decimal(10,8) NOT NULL,
  `longitud` decimal(11,8) NOT NULL,
  `universidad_cercana` varchar(255) DEFAULT NULL,
  `precio_menu_min` decimal(10,2) NOT NULL,
  `precio_menu_max` decimal(10,2) DEFAULT NULL,
  `horario_apertura` time DEFAULT NULL,
  `horario_cierre` time DEFAULT NULL,
  `dias_atencion` varchar(255) DEFAULT NULL,
  `telefono` varchar(20) DEFAULT NULL,
  `tipo_comida` varchar(100) DEFAULT NULL,
  `menu_dia` text DEFAULT NULL,
  `foto` varchar(255) DEFAULT 'default-comedor.jpg',
  `activo` tinyint(1) DEFAULT 1,
  `valoracion_promedio` decimal(2,1) DEFAULT 0.0,
  `created_at` timestamp NOT NULL DEFAULT current_timestamp()
) ENGINE=InnoDB DEFAULT CHARSET=utf8mb4 COLLATE=utf8mb4_unicode_ci;

-- --------------------------------------------------------

--
-- Estructura de tabla para la tabla `cursos`
--

CREATE TABLE `cursos` (
  `id_curso` int(11) NOT NULL,
  `id_malla` int(11) NOT NULL,
  `codigo_curso` varchar(10) NOT NULL COMMENT 'Ej: C.3, C.4',
  `nombre_curso` varchar(255) NOT NULL,
  `ciclo` tinyint(4) NOT NULL COMMENT 'Ciclo del 1 al 10',
  `creditos` decimal(3,1) NOT NULL,
  `descripcion` text DEFAULT NULL
) ;

--
-- Volcado de datos para la tabla `cursos`
--

INSERT INTO `cursos` (`id_curso`, `id_malla`, `codigo_curso`, `nombre_curso`, `ciclo`, `creditos`, `descripcion`) VALUES
(1, 2, 'C.3', 'Comprensión y Producción de Textos 1', 1, 3.0, NULL),
(2, 2, 'C.3', 'Laboratorio de Liderazgo e Innovación', 1, 3.0, NULL),
(3, 2, 'C.4', 'Estrategias y Herramientas Digitales para el Aprendizaje', 1, 4.0, NULL),
(4, 2, 'C.4', 'Matemática Básica', 1, 4.0, NULL),
(5, 2, 'C.4', 'Matemática Discreta 1', 1, 4.0, NULL),
(6, 2, 'C.2', 'Técnicas de Programación', 1, 2.0, NULL),
(7, 2, 'C.3', 'Introducción a la Ingeniería de Sistemas e Informática', 1, 3.0, NULL),
(8, 2, 'C.4', 'Comprensión y Producción de Textos 2', 2, 4.0, NULL),
(9, 2, 'C.3', 'Electivo General 1', 2, 3.0, NULL),
(10, 2, 'C.4', 'Álgebra Lineal y Geometría Analítica', 2, 4.0, NULL),
(11, 2, 'C.3', 'Modelado de Negocios', 2, 3.0, NULL),
(12, 2, 'C.4', 'Matemática Superior', 2, 4.0, NULL),
(13, 2, 'C.4', 'Matemática Discreta 2', 2, 4.0, NULL),
(14, 2, 'C.2', 'Programación Orientada a Objetos', 2, 2.0, NULL),
(15, 2, 'C.4', 'Estadística y Probabilidades', 3, 4.0, NULL),
(16, 2, 'C.3', 'Electivo General 2', 3, 3.0, NULL),
(17, 2, 'C.2', 'Laboratorio de Liderazgo e Innovación Intermedio', 3, 2.0, NULL),
(18, 2, 'C.4', 'Cálculo Diferencial', 3, 4.0, NULL),
(19, 2, 'C.4', 'Física 1', 3, 4.0, NULL),
(20, 2, 'C.3', 'Base de Datos 1', 3, 3.0, NULL),
(21, 2, 'C.2', 'Diseño Web', 3, 2.0, NULL),
(22, 2, 'C.2', 'Estructura de Datos', 3, 2.0, NULL),
(23, 2, 'C.3', 'Electivo General 3', 4, 3.0, NULL),
(24, 2, 'C.3', 'Electivo General 4', 4, 3.0, NULL),
(25, 2, 'C.3', 'English Course 1', 4, 3.0, NULL),
(26, 2, 'C.4', 'Cálculo Integral', 4, 4.0, NULL),
(27, 2, 'C.4', 'Física Electromagnética', 4, 4.0, NULL),
(28, 2, 'C.4', 'Análisis y Diseño de Software', 4, 4.0, NULL),
(29, 2, 'C.2', 'Programación Web', 4, 2.0, NULL),
(30, 2, 'C.3', 'English Course 2', 5, 3.0, NULL),
(31, 2, 'C.3', 'Laboratorio de Liderazgo e Innovación Avanzado', 5, 3.0, NULL),
(32, 2, 'C.4', 'Ecuaciones Diferenciales', 5, 4.0, NULL),
(33, 2, 'C.3', 'Estadística para Ingeniería', 5, 3.0, NULL),
(34, 2, 'C.2', 'Desarrollo de Aplicaciones Web', 5, 2.0, NULL),
(35, 2, 'C.3', 'Base de Datos 2', 5, 3.0, NULL),
(36, 2, 'C.3', 'Arquitectura del Computador', 5, 3.0, NULL),
(37, 2, 'C.3', 'Investigación Académica', 6, 3.0, NULL),
(38, 2, 'C.3', 'English Course 3', 6, 3.0, NULL),
(39, 2, 'C.4', 'Investigación Operativa 1', 6, 4.0, NULL),
(40, 2, 'C.4', 'Métodos Numéricos', 6, 4.0, NULL),
(41, 2, 'C.4', 'Desarrollo de Videojuegos', 6, 4.0, NULL),
(42, 2, 'C.4', 'Sistemas Operativos', 6, 4.0, NULL),
(43, 2, 'C.3', 'English Course 4', 7, 3.0, NULL),
(44, 2, 'C.1', 'Electivo General 5', 7, 1.0, NULL),
(45, 2, 'C.3', 'Ingeniería Económica', 7, 3.0, NULL),
(46, 2, 'C.4', 'Redes de Computadoras', 7, 4.0, NULL),
(47, 2, 'C.4', 'Ingeniería de Software', 7, 4.0, NULL),
(48, 2, 'C.2', 'Proyectos de Innovación', 7, 2.0, NULL),
(49, 2, 'C.3', 'Fundamentos de Sistemas Dinámicos y Modelado', 7, 3.0, NULL),
(50, 2, 'C.4', 'Construcción y Pruebas de Software', 7, 4.0, NULL),
(51, 2, 'C.3', 'Simulación de Procesos', 8, 3.0, NULL),
(52, 2, 'C.3', 'Gestión de Proyectos en Ingeniería', 8, 3.0, NULL),
(53, 2, 'C.4', 'Conmutación y Enrutamiento', 8, 4.0, NULL),
(54, 2, 'C.4', 'Desarrollo de Aplicaciones Móviles', 8, 4.0, NULL),
(55, 2, 'C.3', 'Arquitectura de Software', 8, 3.0, NULL),
(56, 2, 'C.3', 'Metodologías Ágiles para el Desarrollo de Software', 8, 3.0, NULL),
(57, 2, 'C.3', 'Electivo Transversal o de Especialidad 1', 9, 3.0, NULL),
(58, 2, 'C.4', 'Taller de Investigación 1 en Ingeniería de Sistemas e Informática', 9, 4.0, NULL),
(59, 2, 'C.4', 'Proyectos de Diseño en Ingeniería de Sistemas e Informática', 9, 4.0, NULL),
(60, 2, 'C.4', 'Inteligencia de Negocios y Ciencia de Datos', 9, 4.0, NULL),
(61, 2, 'C.3', 'Seguridad de la Información', 9, 3.0, NULL),
(62, 2, 'C.2', 'Aplicaciones Cloud', 9, 2.0, NULL),
(63, 2, 'C.4', 'Taller de Investigación 2 en Ingeniería de Sistemas e Informática', 10, 4.0, NULL),
(64, 2, 'C.4', 'Proyectos de Diseño y Desarrollo en Ingeniería de Sistemas e Informática', 10, 4.0, NULL),
(65, 2, 'C.3', 'Auditoria de Sistemas', 10, 3.0, NULL),
(66, 2, 'C.2', 'Robótica y Machine Learning', 10, 2.0, NULL),
(67, 2, 'C.3', 'Planificación y Gestión de Tecnologías de la Información', 10, 3.0, NULL),
(68, 2, 'C.3', 'Electivo Transversal o de Especialidad 2', 10, 3.0, NULL),
(69, 1, 'C.4', 'Habilidades Comunicativas', 1, 4.0, NULL),
(70, 1, 'C.5', 'Matemática Superior', 1, 5.0, NULL),
(71, 1, 'C.2', 'Laboratorio de Liderazgo', 1, 2.0, NULL),
(72, 1, 'C.3', 'Gestión del Aprendizaje', 1, 3.0, NULL),
(73, 1, 'C.3', 'Química 1', 1, 3.0, NULL),
(74, 1, 'C.3', 'Introducción a la Ing. de Sistemas e Informática', 1, 3.0, NULL),
(75, 1, 'C.1', 'Herramientas Virtuales para el Aprendizaje', 1, 1.0, NULL),
(76, 1, 'C.3', 'Comunicación Efectiva', 2, 3.0, NULL),
(77, 1, 'C.4', 'Fundamentos del Cálculo', 2, 4.0, NULL),
(78, 1, 'C.3', 'Ética, Ciudadanía y Globalización', 2, 3.0, NULL),
(79, 1, 'C.4', 'Álgebra Matricial y Geometría Analítica', 2, 4.0, NULL),
(80, 1, 'C.3', 'Gestión Basada en Procesos', 2, 3.0, NULL),
(81, 1, 'C.4', 'Matemática Discreta', 2, 4.0, NULL),
(82, 1, 'C.1', 'Laboratorio de Innovación', 3, 1.0, NULL),
(83, 1, 'C.3', 'Estadística General', 3, 3.0, NULL),
(84, 1, 'C.5', 'Cálculo Diferencial', 3, 5.0, NULL),
(85, 1, 'C.4', 'Física 1', 3, 4.0, NULL),
(86, 1, 'C.4', 'Sistemas de Información', 3, 4.0, NULL),
(87, 1, 'C.4', 'Fundamentos de Programación', 3, 4.0, NULL),
(88, 1, 'C.3', 'Comunicación y Argumentación', 4, 3.0, NULL),
(89, 1, 'C.5', 'Cálculo Integral', 4, 5.0, NULL),
(90, 1, 'C.3', 'Estadística Aplicada', 4, 3.0, NULL),
(91, 1, 'C.3', 'Estructura de Datos', 4, 3.0, NULL),
(92, 1, 'C.3', 'Física 2', 4, 3.0, NULL),
(93, 1, 'C.4', 'Programación Orientada a Objetos', 4, 4.0, NULL),
(94, 1, 'C.1', 'Laboratorio Avanzado de Innovación y Liderazgo', 5, 1.0, NULL),
(95, 1, 'C.5', 'Ecuaciones Diferenciales', 5, 5.0, NULL),
(96, 1, 'C.4', 'Base de Datos', 5, 4.0, NULL),
(97, 1, 'C.4', 'Sistemas Digitales', 5, 4.0, NULL),
(98, 1, 'C.4', 'Análisis y Requerimiento de Software', 5, 4.0, NULL),
(99, 1, 'C.3', 'Electivo General 1', 5, 3.0, NULL),
(100, 1, 'C.4', 'Seminario de Investigación', 6, 4.0, NULL),
(101, 1, 'C.3', 'Investigación Operativa', 6, 3.0, NULL),
(102, 1, 'C.4', 'Arquitectura del Computador', 6, 4.0, NULL),
(103, 1, 'C.3', 'Sistemas Operativos', 6, 3.0, NULL),
(104, 1, 'C.4', 'Diseño de Software', 6, 4.0, NULL),
(105, 1, 'C.3', 'Administración de Base de Datos', 6, 3.0, NULL),
(106, 1, 'C.1', 'Gestión Profesional', 7, 1.0, NULL),
(107, 1, 'C.2', 'Innovación Social', 7, 2.0, NULL),
(108, 1, 'C.3', 'Ingeniería Económica', 7, 3.0, NULL),
(109, 1, 'C.5', 'Arquitectura Empresarial', 7, 5.0, NULL),
(110, 1, 'C.4', 'Redes de Computadoras', 7, 4.0, NULL),
(111, 1, 'C.5', 'Construcción de Software', 7, 5.0, NULL),
(112, 1, 'C.3', 'Conversación Class', 8, 3.0, NULL),
(113, 1, 'C.1', 'Supervisión de Recursos Empresariales y Gestión de Servicios de TI', 8, 1.0, NULL),
(114, 1, 'C.4', 'Dirección de Proyectos', 8, 4.0, NULL),
(115, 1, 'C.4', 'Pruebas y Calidad de Software', 8, 4.0, NULL),
(116, 1, 'C.4', 'Simulación', 8, 4.0, NULL),
(117, 1, 'C.4', 'Comunicación y Enrutamiento', 8, 4.0, NULL),
(118, 1, 'C.4', 'Desarrollo de Aplicaciones Móviles', 9, 4.0, NULL),
(119, 1, 'C.4', 'Taller de Investigación 1', 9, 4.0, NULL),
(120, 1, 'C.4', 'Taller de Proyectos 1', 9, 4.0, NULL),
(121, 1, 'C.3', 'Gestión de Servicios TI', 9, 3.0, NULL),
(122, 1, 'C.4', 'Ingeniería Web', 9, 4.0, NULL),
(123, 1, 'C.3', 'Electivo Específico 1', 9, 3.0, NULL),
(124, 1, 'C.4', 'Taller de Investigación 2', 10, 4.0, NULL),
(125, 1, 'C.4', 'Taller de Proyectos 2', 10, 4.0, NULL),
(126, 1, 'C.4', 'Auditoría de Sistemas', 10, 4.0, NULL),
(127, 1, 'C.3', 'Inteligencia de Negocios', 10, 3.0, NULL),
(128, 1, 'C.3', 'Cloud Computing', 10, 3.0, NULL),
(129, 1, 'C.3', 'Electivo Específico 2', 10, 3.0, NULL);

-- --------------------------------------------------------

--
-- Estructura de tabla para la tabla `estadisticas_diarias`
--

CREATE TABLE `estadisticas_diarias` (
  `id_estadistica` int(11) NOT NULL,
  `fecha` date NOT NULL,
  `usuarios_activos` int(11) DEFAULT 0,
  `libros_subidos` int(11) DEFAULT 0,
  `apuntes_subidos` int(11) DEFAULT 0,
  `descargas_totales` int(11) DEFAULT 0,
  `grupos_creados` int(11) DEFAULT 0,
  `mensajes_enviados` int(11) DEFAULT 0,
  `reportes_generados` int(11) DEFAULT 0,
  `fecha_calculo` timestamp NOT NULL DEFAULT current_timestamp()
) ENGINE=InnoDB DEFAULT CHARSET=utf8mb4 COLLATE=utf8mb4_unicode_ci;

-- --------------------------------------------------------

--
-- Estructura de tabla para la tabla `grupos_tutoria`
--

CREATE TABLE `grupos_tutoria` (
  `id_grupo` int(11) NOT NULL,
  `nombre_grupo` varchar(255) NOT NULL,
  `descripcion` text DEFAULT NULL,
  `id_curso` int(11) DEFAULT NULL COMMENT 'Curso relacionado (opcional)',
  `id_creador` int(11) NOT NULL,
  `tipo` enum('publico','privado') DEFAULT 'publico',
  `max_participantes` int(11) DEFAULT 50,
  `codigo_acceso` varchar(20) DEFAULT NULL COMMENT 'Para grupos privados',
  `esta_activo` tinyint(1) DEFAULT 1,
  `fecha_creacion` timestamp NOT NULL DEFAULT current_timestamp()
) ENGINE=InnoDB DEFAULT CHARSET=utf8mb4 COLLATE=utf8mb4_unicode_ci;

-- --------------------------------------------------------

--
-- Estructura de tabla para la tabla `historial_actividad`
--

CREATE TABLE `historial_actividad` (
  `id_actividad` bigint(20) NOT NULL,
  `id_usuario` int(11) NOT NULL,
  `tipo_accion` enum('login','subir_libro','subir_apunte','descargar','validar','reportar','unirse_grupo','crear_grupo','otro') NOT NULL,
  `descripcion` varchar(500) DEFAULT NULL,
  `ip_address` varchar(45) DEFAULT NULL,
  `user_agent` varchar(255) DEFAULT NULL,
  `fecha_accion` timestamp NOT NULL DEFAULT current_timestamp()
) ENGINE=InnoDB DEFAULT CHARSET=utf8mb4 COLLATE=utf8mb4_unicode_ci;

--
-- Disparadores `historial_actividad`
--
DELIMITER $$
CREATE TRIGGER `trg_incrementar_descargas_libro` AFTER INSERT ON `historial_actividad` FOR EACH ROW BEGIN IF NEW.tipo_accion = 'descargar' AND NEW.descripcion LIKE 'libro:%' THEN UPDATE libros SET descargas = descargas + 1 WHERE id_libro = CAST(SUBSTRING(NEW.descripcion, 7) AS UNSIGNED); END IF; END
$$
DELIMITER ;

-- --------------------------------------------------------

--
-- Estructura de tabla para la tabla `libros`
--

CREATE TABLE `libros` (
  `id_libro` int(11) NOT NULL,
  `titulo` varchar(500) NOT NULL,
  `autor_libro` varchar(255) DEFAULT NULL,
  `editorial` varchar(255) DEFAULT NULL,
  `anio_publicacion` year(4) DEFAULT NULL,
  `isbn` varchar(20) DEFAULT NULL,
  `id_categoria` int(11) DEFAULT NULL,
  `descripcion` text DEFAULT NULL,
  `url_drive` varchar(1000) NOT NULL,
  `id_usuario_subida` int(11) NOT NULL,
  `estado_validacion` enum('pendiente','aprobado','rechazado') DEFAULT 'pendiente',
  `id_validador` int(11) DEFAULT NULL,
  `fecha_validacion` timestamp NULL DEFAULT NULL,
  `comentario_validacion` text DEFAULT NULL,
  `vistas` int(11) DEFAULT 0,
  `descargas` int(11) DEFAULT 0,
  `fecha_subida` timestamp NOT NULL DEFAULT current_timestamp(),
  `fecha_actualizacion` timestamp NOT NULL DEFAULT current_timestamp() ON UPDATE current_timestamp()
) ENGINE=InnoDB DEFAULT CHARSET=utf8mb4 COLLATE=utf8mb4_unicode_ci;

--
-- Disparadores `libros`
--
DELIMITER $$
CREATE TRIGGER `trg_habilitar_descarga_libro` AFTER INSERT ON `libros` FOR EACH ROW BEGIN UPDATE usuarios SET puede_descargar = TRUE WHERE id_usuario = NEW.id_usuario_subida AND puede_descargar = FALSE; END
$$
DELIMITER ;

-- --------------------------------------------------------

--
-- Estructura de tabla para la tabla `mallas_curriculares`
--

CREATE TABLE `mallas_curriculares` (
  `id_malla` int(11) NOT NULL,
  `periodo` int(11) NOT NULL COMMENT 'Año de la malla: 2018, 2025',
  `nombre_malla` varchar(255) NOT NULL,
  `formato_material` enum('foto','pdf','mixto') NOT NULL,
  `esta_activa` tinyint(1) DEFAULT 1,
  `fecha_creacion` timestamp NOT NULL DEFAULT current_timestamp()
) ENGINE=InnoDB DEFAULT CHARSET=utf8mb4 COLLATE=utf8mb4_unicode_ci;

--
-- Volcado de datos para la tabla `mallas_curriculares`
--

INSERT INTO `mallas_curriculares` (`id_malla`, `periodo`, `nombre_malla`, `formato_material`, `esta_activa`, `fecha_creacion`) VALUES
(1, 2018, 'Ingeniería de Sistemas e Informática 2018', 'foto', 1, '2025-10-27 14:53:49'),
(2, 2025, 'Ingeniería de Sistemas e Informática 2025', 'pdf', 1, '2025-10-27 14:53:49');

-- --------------------------------------------------------

--
-- Estructura de tabla para la tabla `mensajes_chat`
--

CREATE TABLE `mensajes_chat` (
  `id_mensaje` bigint(20) NOT NULL,
  `id_grupo` int(11) NOT NULL,
  `id_usuario` int(11) NOT NULL,
  `mensaje` text NOT NULL,
  `tipo_mensaje` enum('texto','archivo','imagen','link') DEFAULT 'texto',
  `url_adjunto` varchar(1000) DEFAULT NULL,
  `editado` tinyint(1) DEFAULT 0,
  `fecha_envio` timestamp NOT NULL DEFAULT current_timestamp(),
  `fecha_edicion` timestamp NULL DEFAULT NULL
) ENGINE=InnoDB DEFAULT CHARSET=utf8mb4 COLLATE=utf8mb4_unicode_ci;

-- --------------------------------------------------------

--
-- Estructura de tabla para la tabla `miembros_grupo`
--

CREATE TABLE `miembros_grupo` (
  `id_miembro` int(11) NOT NULL,
  `id_grupo` int(11) NOT NULL,
  `id_usuario` int(11) NOT NULL,
  `rol_grupo` enum('admin','moderador','miembro') DEFAULT 'miembro',
  `fecha_union` timestamp NOT NULL DEFAULT current_timestamp()
) ENGINE=InnoDB DEFAULT CHARSET=utf8mb4 COLLATE=utf8mb4_unicode_ci;

-- --------------------------------------------------------

--
-- Estructura de tabla para la tabla `reportes`
--

CREATE TABLE `reportes` (
  `id_reporte` int(11) NOT NULL,
  `id_usuario_reporta` int(11) NOT NULL,
  `tipo_contenido` enum('libro','apunte','comentario','usuario') NOT NULL,
  `id_contenido` int(11) NOT NULL,
  `motivo` enum('contenido_inapropiado','spam','informacion_incorrecta','contenido_duplicado','derechos_autor','otro') NOT NULL,
  `descripcion` text NOT NULL,
  `estado` enum('pendiente','en_revision','resuelto','rechazado') DEFAULT 'pendiente',
  `id_moderador` int(11) DEFAULT NULL,
  `accion_tomada` text DEFAULT NULL,
  `fecha_reporte` timestamp NOT NULL DEFAULT current_timestamp(),
  `fecha_resolucion` timestamp NULL DEFAULT NULL
) ENGINE=InnoDB DEFAULT CHARSET=utf8mb4 COLLATE=utf8mb4_unicode_ci;

--
-- Disparadores `reportes`
--
DELIMITER $$
CREATE TRIGGER `trg_ocultar_contenido_reportado` AFTER INSERT ON `reportes` FOR EACH ROW BEGIN DECLARE total_reportes INT; IF NEW.tipo_contenido = 'libro' THEN SELECT COUNT(*) INTO total_reportes FROM reportes WHERE tipo_contenido = 'libro' AND id_contenido = NEW.id_contenido AND estado = 'pendiente'; IF total_reportes >= 3 THEN UPDATE libros SET estado_validacion = 'rechazado', comentario_validacion = 'Oculto automáticamente por reportes múltiples' WHERE id_libro = NEW.id_contenido; END IF; END IF; IF NEW.tipo_contenido = 'apunte' THEN SELECT COUNT(*) INTO total_reportes FROM reportes WHERE tipo_contenido = 'apunte' AND id_contenido = NEW.id_contenido AND estado = 'pendiente'; IF total_reportes >= 3 THEN UPDATE apuntes SET estado_validacion = 'rechazado', comentario_validacion = 'Oculto automáticamente por reportes múltiples' WHERE id_apunte = NEW.id_contenido; END IF; END IF; END
$$
DELIMITER ;

-- --------------------------------------------------------

--
-- Estructura de tabla para la tabla `resenas_comedores`
--

CREATE TABLE `resenas_comedores` (
  `id_resena` int(11) NOT NULL,
  `id_comedor` int(11) NOT NULL,
  `id_usuario` int(11) NOT NULL,
  `calificacion` tinyint(4) NOT NULL COMMENT 'De 1 a 5 estrellas',
  `comentario` text DEFAULT NULL,
  `fecha_visita` date DEFAULT NULL,
  `fecha_resena` timestamp NOT NULL DEFAULT current_timestamp()
) ;

-- --------------------------------------------------------

--
-- Estructura de tabla para la tabla `roles`
--

CREATE TABLE `roles` (
  `id_rol` int(11) NOT NULL,
  `nombre_rol` enum('admin','validador','estudiante','docente') NOT NULL,
  `descripcion` varchar(255) DEFAULT NULL,
  `fecha_creacion` timestamp NOT NULL DEFAULT current_timestamp()
) ENGINE=InnoDB DEFAULT CHARSET=utf8mb4 COLLATE=utf8mb4_unicode_ci;

--
-- Volcado de datos para la tabla `roles`
--

INSERT INTO `roles` (`id_rol`, `nombre_rol`, `descripcion`, `fecha_creacion`) VALUES
(1, 'admin', 'Administrador del sistema con acceso total', '2025-10-27 14:53:49'),
(2, 'validador', 'Usuario encargado de validar contenido subido', '2025-10-27 14:53:49'),
(3, 'estudiante', 'Estudiante regular de la Universidad Continental', '2025-10-27 14:53:49'),
(4, 'docente', 'Docente que puede sugerir mejoras en el contenido', '2025-10-27 14:53:49');

-- --------------------------------------------------------

--
-- Estructura de tabla para la tabla `sugerencias_docentes`
--

CREATE TABLE `sugerencias_docentes` (
  `id_sugerencia` int(11) NOT NULL,
  `id_docente` int(11) NOT NULL,
  `tipo_contenido` enum('libro','apunte') NOT NULL,
  `id_contenido` int(11) NOT NULL COMMENT 'ID del libro o apunte',
  `sugerencia` text NOT NULL,
  `estado` enum('pendiente','revisada','aplicada','descartada') DEFAULT 'pendiente',
  `respuesta` text DEFAULT NULL,
  `id_usuario_respuesta` int(11) DEFAULT NULL,
  `fecha_creacion` timestamp NOT NULL DEFAULT current_timestamp(),
  `fecha_respuesta` timestamp NULL DEFAULT NULL
) ENGINE=InnoDB DEFAULT CHARSET=utf8mb4 COLLATE=utf8mb4_unicode_ci;

-- --------------------------------------------------------

--
-- Estructura de tabla para la tabla `tutorias`
--

CREATE TABLE `tutorias` (
  `id` int(11) NOT NULL,
  `tutor_id` int(11) NOT NULL,
  `materia` varchar(255) NOT NULL,
  `descripcion` text DEFAULT NULL,
  `precio_hora` decimal(10,2) NOT NULL,
  `horario_disponible` text DEFAULT NULL,
  `modalidad` varchar(50) DEFAULT NULL COMMENT 'Presencial, Virtual, Mixta',
  `created_at` timestamp NOT NULL DEFAULT current_timestamp()
) ENGINE=InnoDB DEFAULT CHARSET=utf8mb4 COLLATE=utf8mb4_unicode_ci;

-- --------------------------------------------------------

--
-- Estructura de tabla para la tabla `usuarios`
--

CREATE TABLE `usuarios` (
  `id_usuario` int(11) NOT NULL,
  `google_id` varchar(255) NOT NULL,
  `email` varchar(255) NOT NULL,
  `password` varchar(255) DEFAULT NULL COMMENT 'Hash de la contraseña para login local',
  `nombre_completo` varchar(255) NOT NULL,
  `apellidos` varchar(255) DEFAULT NULL,
  `foto_perfil` varchar(500) DEFAULT NULL,
  `id_rol` int(11) NOT NULL DEFAULT 3,
  `estado` enum('activo','suspendido','inactivo') DEFAULT 'activo',
  `puede_descargar` tinyint(1) DEFAULT 0 COMMENT 'TRUE si ha subido al menos un libro',
  `fecha_registro` timestamp NOT NULL DEFAULT current_timestamp(),
  `ultimo_acceso` timestamp NULL DEFAULT NULL,
  `telefono` varchar(20) DEFAULT NULL,
  `universidad` varchar(255) DEFAULT NULL,
  `carrera` varchar(255) DEFAULT NULL,
  `ciclo` varchar(50) DEFAULT NULL
) ;

--
-- Volcado de datos para la tabla `usuarios`
--

INSERT INTO `usuarios` (`id_usuario`, `google_id`, `email`, `password`, `nombre_completo`, `apellidos`, `foto_perfil`, `id_rol`, `estado`, `puede_descargar`, `fecha_registro`, `ultimo_acceso`, `telefono`, `universidad`, `carrera`, `ciclo`) VALUES
(1, '', 'uwu@continental.edu.pe', '$2y$10$qrlfD.3jM.YAG691yi4b..kTzOfPRTVrvw6cyk2QtcA7q7GRaDvzW', 'Gary Jhoel', 'Jhoel', NULL, 3, 'activo', 0, '2025-10-27 15:33:20', NULL, '999999999', 'Universidad Continental', 'Ing. de Sistemas', '4');

--
-- Índices para tablas volcadas
--

--
-- Indices de la tabla `apuntes`
--
ALTER TABLE `apuntes`
  ADD PRIMARY KEY (`id_apunte`),
  ADD KEY `fk_apunte_usuario` (`id_usuario_subida`),
  ADD KEY `fk_apunte_validador` (`id_validador`),
  ADD KEY `idx_curso` (`id_curso`),
  ADD KEY `idx_estado` (`estado_validacion`),
  ADD KEY `idx_tipo` (`tipo_material`);
ALTER TABLE `apuntes` ADD FULLTEXT KEY `idx_busqueda` (`titulo`,`descripcion`);

--
-- Indices de la tabla `categorias_libros`
--
ALTER TABLE `categorias_libros`
  ADD PRIMARY KEY (`id_categoria`),
  ADD UNIQUE KEY `nombre_categoria` (`nombre_categoria`);

--
-- Indices de la tabla `comedores`
--
ALTER TABLE `comedores`
  ADD PRIMARY KEY (`id`);

--
-- Indices de la tabla `cursos`
--
ALTER TABLE `cursos`
  ADD PRIMARY KEY (`id_curso`),
  ADD UNIQUE KEY `uk_malla_nombre` (`id_malla`,`nombre_curso`);

--
-- Indices de la tabla `estadisticas_diarias`
--
ALTER TABLE `estadisticas_diarias`
  ADD PRIMARY KEY (`id_estadistica`),
  ADD UNIQUE KEY `fecha` (`fecha`);

--
-- Indices de la tabla `grupos_tutoria`
--
ALTER TABLE `grupos_tutoria`
  ADD PRIMARY KEY (`id_grupo`),
  ADD UNIQUE KEY `codigo_acceso` (`codigo_acceso`),
  ADD KEY `fk_grupo_curso` (`id_curso`),
  ADD KEY `fk_grupo_creador` (`id_creador`),
  ADD KEY `idx_activo` (`esta_activo`);

--
-- Indices de la tabla `historial_actividad`
--
ALTER TABLE `historial_actividad`
  ADD PRIMARY KEY (`id_actividad`),
  ADD KEY `idx_usuario_fecha` (`id_usuario`,`fecha_accion`),
  ADD KEY `idx_tipo` (`tipo_accion`);

--
-- Indices de la tabla `libros`
--
ALTER TABLE `libros`
  ADD PRIMARY KEY (`id_libro`),
  ADD KEY `fk_libro_categoria` (`id_categoria`),
  ADD KEY `fk_libro_usuario` (`id_usuario_subida`),
  ADD KEY `fk_libro_validador` (`id_validador`),
  ADD KEY `idx_estado` (`estado_validacion`),
  ADD KEY `idx_titulo` (`titulo`(100));
ALTER TABLE `libros` ADD FULLTEXT KEY `idx_busqueda` (`titulo`,`autor_libro`,`descripcion`);

--
-- Indices de la tabla `mallas_curriculares`
--
ALTER TABLE `mallas_curriculares`
  ADD PRIMARY KEY (`id_malla`),
  ADD UNIQUE KEY `periodo` (`periodo`);

--
-- Indices de la tabla `mensajes_chat`
--
ALTER TABLE `mensajes_chat`
  ADD PRIMARY KEY (`id_mensaje`),
  ADD KEY `fk_mensaje_usuario` (`id_usuario`),
  ADD KEY `idx_grupo_fecha` (`id_grupo`,`fecha_envio`);

--
-- Indices de la tabla `miembros_grupo`
--
ALTER TABLE `miembros_grupo`
  ADD PRIMARY KEY (`id_miembro`),
  ADD UNIQUE KEY `uk_grupo_usuario` (`id_grupo`,`id_usuario`),
  ADD KEY `fk_miembro_usuario` (`id_usuario`);

--
-- Indices de la tabla `reportes`
--
ALTER TABLE `reportes`
  ADD PRIMARY KEY (`id_reporte`),
  ADD KEY `fk_reporte_usuario` (`id_usuario_reporta`),
  ADD KEY `fk_reporte_moderador` (`id_moderador`),
  ADD KEY `idx_estado` (`estado`),
  ADD KEY `idx_tipo` (`tipo_contenido`,`id_contenido`);

--
-- Indices de la tabla `resenas_comedores`
--
ALTER TABLE `resenas_comedores`
  ADD PRIMARY KEY (`id_resena`),
  ADD UNIQUE KEY `uk_usuario_comedor` (`id_usuario`,`id_comedor`),
  ADD KEY `fk_resena_comedor` (`id_comedor`);

--
-- Indices de la tabla `roles`
--
ALTER TABLE `roles`
  ADD PRIMARY KEY (`id_rol`),
  ADD UNIQUE KEY `nombre_rol` (`nombre_rol`);

--
-- Indices de la tabla `sugerencias_docentes`
--
ALTER TABLE `sugerencias_docentes`
  ADD PRIMARY KEY (`id_sugerencia`),
  ADD KEY `fk_sugerencia_docente` (`id_docente`),
  ADD KEY `fk_sugerencia_respuesta` (`id_usuario_respuesta`),
  ADD KEY `idx_estado` (`estado`),
  ADD KEY `idx_tipo` (`tipo_contenido`,`id_contenido`);

--
-- Indices de la tabla `tutorias`
--
ALTER TABLE `tutorias`
  ADD PRIMARY KEY (`id`),
  ADD KEY `fk_tutoria_tutor` (`tutor_id`);

--
-- Indices de la tabla `usuarios`
--
ALTER TABLE `usuarios`
  ADD PRIMARY KEY (`id_usuario`),
  ADD UNIQUE KEY `google_id` (`google_id`),
  ADD UNIQUE KEY `email` (`email`),
  ADD KEY `fk_usuario_rol` (`id_rol`);

--
-- AUTO_INCREMENT de las tablas volcadas
--

--
-- AUTO_INCREMENT de la tabla `apuntes`
--
ALTER TABLE `apuntes`
  MODIFY `id_apunte` int(11) NOT NULL AUTO_INCREMENT;

--
-- AUTO_INCREMENT de la tabla `categorias_libros`
--
ALTER TABLE `categorias_libros`
  MODIFY `id_categoria` int(11) NOT NULL AUTO_INCREMENT, AUTO_INCREMENT=10;

--
-- AUTO_INCREMENT de la tabla `comedores`
--
ALTER TABLE `comedores`
  MODIFY `id` int(11) NOT NULL AUTO_INCREMENT;

--
-- AUTO_INCREMENT de la tabla `cursos`
--
ALTER TABLE `cursos`
  MODIFY `id_curso` int(11) NOT NULL AUTO_INCREMENT;

--
-- AUTO_INCREMENT de la tabla `estadisticas_diarias`
--
ALTER TABLE `estadisticas_diarias`
  MODIFY `id_estadistica` int(11) NOT NULL AUTO_INCREMENT;

--
-- AUTO_INCREMENT de la tabla `grupos_tutoria`
--
ALTER TABLE `grupos_tutoria`
  MODIFY `id_grupo` int(11) NOT NULL AUTO_INCREMENT;

--
-- AUTO_INCREMENT de la tabla `historial_actividad`
--
ALTER TABLE `historial_actividad`
  MODIFY `id_actividad` bigint(20) NOT NULL AUTO_INCREMENT;

--
-- AUTO_INCREMENT de la tabla `libros`
--
ALTER TABLE `libros`
  MODIFY `id_libro` int(11) NOT NULL AUTO_INCREMENT;

--
-- AUTO_INCREMENT de la tabla `mallas_curriculares`
--
ALTER TABLE `mallas_curriculares`
  MODIFY `id_malla` int(11) NOT NULL AUTO_INCREMENT, AUTO_INCREMENT=3;

--
-- AUTO_INCREMENT de la tabla `mensajes_chat`
--
ALTER TABLE `mensajes_chat`
  MODIFY `id_mensaje` bigint(20) NOT NULL AUTO_INCREMENT;

--
-- AUTO_INCREMENT de la tabla `miembros_grupo`
--
ALTER TABLE `miembros_grupo`
  MODIFY `id_miembro` int(11) NOT NULL AUTO_INCREMENT;

--
-- AUTO_INCREMENT de la tabla `reportes`
--
ALTER TABLE `reportes`
  MODIFY `id_reporte` int(11) NOT NULL AUTO_INCREMENT;

--
-- AUTO_INCREMENT de la tabla `resenas_comedores`
--
ALTER TABLE `resenas_comedores`
  MODIFY `id_resena` int(11) NOT NULL AUTO_INCREMENT;

--
-- AUTO_INCREMENT de la tabla `roles`
--
ALTER TABLE `roles`
  MODIFY `id_rol` int(11) NOT NULL AUTO_INCREMENT, AUTO_INCREMENT=5;

--
-- AUTO_INCREMENT de la tabla `sugerencias_docentes`
--
ALTER TABLE `sugerencias_docentes`
  MODIFY `id_sugerencia` int(11) NOT NULL AUTO_INCREMENT;

--
-- AUTO_INCREMENT de la tabla `tutorias`
--
ALTER TABLE `tutorias`
  MODIFY `id` int(11) NOT NULL AUTO_INCREMENT;

--
-- AUTO_INCREMENT de la tabla `usuarios`
--
ALTER TABLE `usuarios`
  MODIFY `id_usuario` int(11) NOT NULL AUTO_INCREMENT;

--
-- Restricciones para tablas volcadas
--

--
-- Filtros para la tabla `apuntes`
--
ALTER TABLE `apuntes`
  ADD CONSTRAINT `fk_apunte_curso` FOREIGN KEY (`id_curso`) REFERENCES `cursos` (`id_curso`) ON DELETE CASCADE,
  ADD CONSTRAINT `fk_apunte_usuario` FOREIGN KEY (`id_usuario_subida`) REFERENCES `usuarios` (`id_usuario`) ON DELETE CASCADE,
  ADD CONSTRAINT `fk_apunte_validador` FOREIGN KEY (`id_validador`) REFERENCES `usuarios` (`id_usuario`) ON DELETE SET NULL;

--
-- Filtros para la tabla `cursos`
--
ALTER TABLE `cursos`
  ADD CONSTRAINT `fk_curso_malla` FOREIGN KEY (`id_malla`) REFERENCES `mallas_curriculares` (`id_malla`) ON DELETE CASCADE;

--
-- Filtros para la tabla `grupos_tutoria`
--
ALTER TABLE `grupos_tutoria`
  ADD CONSTRAINT `fk_grupo_creador` FOREIGN KEY (`id_creador`) REFERENCES `usuarios` (`id_usuario`) ON DELETE CASCADE,
  ADD CONSTRAINT `fk_grupo_curso` FOREIGN KEY (`id_curso`) REFERENCES `cursos` (`id_curso`) ON DELETE SET NULL;

--
-- Filtros para la tabla `historial_actividad`
--
ALTER TABLE `historial_actividad`
  ADD CONSTRAINT `fk_actividad_usuario` FOREIGN KEY (`id_usuario`) REFERENCES `usuarios` (`id_usuario`) ON DELETE CASCADE;

--
-- Filtros para la tabla `libros`
--
ALTER TABLE `libros`
  ADD CONSTRAINT `fk_libro_categoria` FOREIGN KEY (`id_categoria`) REFERENCES `categorias_libros` (`id_categoria`) ON DELETE SET NULL,
  ADD CONSTRAINT `fk_libro_usuario` FOREIGN KEY (`id_usuario_subida`) REFERENCES `usuarios` (`id_usuario`) ON DELETE CASCADE,
  ADD CONSTRAINT `fk_libro_validador` FOREIGN KEY (`id_validador`) REFERENCES `usuarios` (`id_usuario`) ON DELETE SET NULL;

--
-- Filtros para la tabla `mensajes_chat`
--
ALTER TABLE `mensajes_chat`
  ADD CONSTRAINT `fk_mensaje_grupo` FOREIGN KEY (`id_grupo`) REFERENCES `grupos_tutoria` (`id_grupo`) ON DELETE CASCADE,
  ADD CONSTRAINT `fk_mensaje_usuario` FOREIGN KEY (`id_usuario`) REFERENCES `usuarios` (`id_usuario`) ON DELETE CASCADE;

--
-- Filtros para la tabla `miembros_grupo`
--
ALTER TABLE `miembros_grupo`
  ADD CONSTRAINT `fk_miembro_grupo` FOREIGN KEY (`id_grupo`) REFERENCES `grupos_tutoria` (`id_grupo`) ON DELETE CASCADE,
  ADD CONSTRAINT `fk_miembro_usuario` FOREIGN KEY (`id_usuario`) REFERENCES `usuarios` (`id_usuario`) ON DELETE CASCADE;

--
-- Filtros para la tabla `reportes`
--
ALTER TABLE `reportes`
  ADD CONSTRAINT `fk_reporte_moderador` FOREIGN KEY (`id_moderador`) REFERENCES `usuarios` (`id_usuario`) ON DELETE SET NULL,
  ADD CONSTRAINT `fk_reporte_usuario` FOREIGN KEY (`id_usuario_reporta`) REFERENCES `usuarios` (`id_usuario`) ON DELETE CASCADE;

--
-- Filtros para la tabla `resenas_comedores`
--
ALTER TABLE `resenas_comedores`
  ADD CONSTRAINT `fk_resena_comedor` FOREIGN KEY (`id_comedor`) REFERENCES `comedores` (`id`) ON DELETE CASCADE,
  ADD CONSTRAINT `fk_resena_usuario` FOREIGN KEY (`id_usuario`) REFERENCES `usuarios` (`id_usuario`) ON DELETE CASCADE;

--
-- Filtros para la tabla `sugerencias_docentes`
--
ALTER TABLE `sugerencias_docentes`
  ADD CONSTRAINT `fk_sugerencia_docente` FOREIGN KEY (`id_docente`) REFERENCES `usuarios` (`id_usuario`) ON DELETE CASCADE,
  ADD CONSTRAINT `fk_sugerencia_respuesta` FOREIGN KEY (`id_usuario_respuesta`) REFERENCES `usuarios` (`id_usuario`) ON DELETE SET NULL;

--
-- Filtros para la tabla `tutorias`
--
ALTER TABLE `tutorias`
  ADD CONSTRAINT `fk_tutoria_tutor` FOREIGN KEY (`tutor_id`) REFERENCES `usuarios` (`id_usuario`) ON DELETE CASCADE;

--
-- Filtros para la tabla `usuarios`
--
ALTER TABLE `usuarios`
  ADD CONSTRAINT `fk_usuario_rol` FOREIGN KEY (`id_rol`) REFERENCES `roles` (`id_rol`) ON UPDATE CASCADE;
COMMIT;

/*!40101 SET CHARACTER_SET_CLIENT=@OLD_CHARACTER_SET_CLIENT */;
/*!40101 SET CHARACTER_SET_RESULTS=@OLD_CHARACTER_SET_RESULTS */;
/*!40101 SET COLLATION_CONNECTION=@OLD_COLLATION_CONNECTION */;
