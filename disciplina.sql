-- phpMyAdmin SQL Dump
-- version 5.2.1
-- https://www.phpmyadmin.net/
--
-- Servidor: 127.0.0.1
-- Tiempo de generación: 04-04-2025 a las 10:12:59
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
-- Base de datos: `disciplina`
--
CREATE DATABASE IF NOT EXISTS `disciplina` DEFAULT CHARACTER SET utf8mb4 COLLATE utf8mb4_general_ci;
USE `disciplina`;

-- --------------------------------------------------------

--
-- Estructura de tabla para la tabla `talumnos`
--

DROP TABLE IF EXISTS `talumnos`;
CREATE TABLE IF NOT EXISTS `talumnos` (
  `IdAlumno` int(11) NOT NULL,
  `Alumno` varchar(100) DEFAULT NULL,
  `Apellido1` varchar(50) DEFAULT NULL,
  `Apellido2` varchar(50) DEFAULT NULL,
  `Nombre` varchar(50) DEFAULT NULL,
  `Direccion` varchar(150) DEFAULT NULL,
  `CP` varchar(5) DEFAULT NULL,
  `Localidad` varchar(100) DEFAULT NULL,
  `Provincia` varchar(50) DEFAULT NULL,
  `FechaNac` date DEFAULT NULL,
  `CorreoAlumno` varchar(100) DEFAULT NULL,
  `CorreoAlumnoCorp` varchar(100) DEFAULT NULL,
  `TelefonoAl` varchar(20) DEFAULT NULL,
  `Tutor1` varchar(100) DEFAULT NULL,
  `CorreoTutor1` varchar(100) DEFAULT NULL,
  `TelefonoTutor1` varchar(100) DEFAULT NULL,
  `Tutor2` varchar(100) DEFAULT NULL,
  `CorreoTutor2` varchar(100) DEFAULT NULL,
  `TelefonoTutor2` varchar(20) DEFAULT NULL,
  `Repetidor` varchar(9) DEFAULT NULL,
  `Custodia` varchar(100) DEFAULT NULL,
  PRIMARY KEY (`IdAlumno`)
) ENGINE=InnoDB DEFAULT CHARSET=utf8 COLLATE=utf8_general_ci;

-- --------------------------------------------------------

--
-- Estructura de tabla para la tabla `talumnounidad`
--

DROP TABLE IF EXISTS `talumnounidad`;
CREATE TABLE IF NOT EXISTS `talumnounidad` (
  `id` int(11) NOT NULL AUTO_INCREMENT,
  `AlumnoIdAlUn` int(11) NOT NULL,
  `UnidadIdAlUn` int(11) DEFAULT NULL,
  `NivelAlUn` varchar(200) DEFAULT NULL,
  `CursoAlUn` varchar(5) NOT NULL,
  PRIMARY KEY (`id`)
) ENGINE=InnoDB AUTO_INCREMENT=3878 DEFAULT CHARSET=utf8mb4 COLLATE=utf8mb4_general_ci;

-- --------------------------------------------------------

--
-- Estructura de tabla para la tabla `tanotacionesabs`
--

DROP TABLE IF EXISTS `tanotacionesabs`;
CREATE TABLE IF NOT EXISTS `tanotacionesabs` (
  `IdAnotacion` int(11) NOT NULL,
  `AlumnoIdAn` int(11) NOT NULL,
  `FechaAn` date NOT NULL,
  `EstadoAn` varchar(10) NOT NULL,
  `TipoAn` varchar(100) NOT NULL,
  `Anotacion` text DEFAULT NULL,
  `ObservacionesAn` text DEFAULT NULL,
  `CorreoTutorAn` varchar(10) NOT NULL,
  `SenecaAn` varchar(10) NOT NULL,
  `JefeEstudios` varchar(100) DEFAULT NULL,
  `HoraCita` varchar(10) DEFAULT NULL,
  `FechaCita` date DEFAULT NULL,
  `CentroSS` varchar(50) DEFAULT NULL,
  `NumeroHermanos` varchar(10) DEFAULT NULL,
  `ProblemasHermanos` text DEFAULT NULL,
  `Antecedentes` text DEFAULT NULL,
  `Actuaciones` text DEFAULT NULL,
  `OtraInformacion` text DEFAULT NULL,
  PRIMARY KEY (`IdAnotacion`)
) ENGINE=InnoDB DEFAULT CHARSET=utf8 COLLATE=utf8_general_ci;

-- --------------------------------------------------------

--
-- Estructura de tabla para la tabla `tconfiguracion`
--

DROP TABLE IF EXISTS `tconfiguracion`;
CREATE TABLE IF NOT EXISTS `tconfiguracion` (
  `orden` int(11) NOT NULL AUTO_INCREMENT,
  `tipo` varchar(20) NOT NULL,
  `id` varchar(30) DEFAULT NULL,
  `etiqueta` varchar(100) DEFAULT NULL,
  `valor1` varchar(400) DEFAULT NULL,
  `valor2` varchar(400) DEFAULT NULL,
  `valor3` varchar(400) DEFAULT NULL,
  `fecha1` date DEFAULT NULL,
  `fecha2` date DEFAULT NULL,
  PRIMARY KEY (`orden`)
) ENGINE=InnoDB AUTO_INCREMENT=97 DEFAULT CHARSET=utf8mb4 COLLATE=utf8mb4_general_ci;

--
-- Volcado de datos para la tabla `tconfiguracion`
--

INSERT INTO `tconfiguracion` (`orden`, `tipo`, `id`, `etiqueta`, `valor1`, `valor2`, `valor3`, `fecha1`, `fecha2`) VALUES
(1, 'variable', 'nombreies', 'Nombre del centro', 'IES Ejemplo', NULL, NULL, NULL, NULL),
(2, 'variable', 'localidadies', 'Localidad del centro', 'Granada', NULL, NULL, NULL, NULL),
(3, 'variable', 'correojefaturaestudios', 'Correo-e de Jefatura de Estudios', 'jefatura@ejemplo.xx', NULL, NULL, NULL, NULL),
(4, 'variable', 'correoclaustro', 'Correo-e del grupo del claustro.', 'claustro@iesejemplo.xxx', NULL, NULL, NULL, NULL),
(5, 'variable', 'correoenvios', 'Correo-e para envío de correos', 'envios@iesejemplo.xxx', NULL, NULL, NULL, NULL),
(6, 'variable', 'hostcorreoenvios', 'Host del correo-e para envíos', 'smtp.mailersend.net', NULL, NULL, NULL, NULL),
(7, 'variable', 'passcorreoenvios', 'Contraseña del correo de envíos', 'xxxx', NULL, NULL, NULL, NULL),
(8, 'variable', 'puertohostenvio', 'Puerto del servidor de correo-e para envíos', '587', NULL, NULL, NULL, NULL),
(9, 'variable', 'profesordirector', 'Nombre del director del centro', 'Pepito Martín Martín', NULL, NULL, NULL, NULL),
(10, 'variable', 'provinciaies', 'Provincia del centro', 'Granada', NULL, NULL, NULL, NULL),
(11, 'variable', 'codigocentro', 'Código del centro', '18000000', NULL, NULL, NULL, NULL),
(12, 'variable', 'direccioncentro', 'Dirección postal del centro', 'C/ Ejemplo, 22', NULL, NULL, NULL, NULL),
(14, 'variable', 'profesorabsentismo', 'Nombre del profesor encargado del absentismo', 'Juanito Pérez Pérez', NULL, NULL, NULL, NULL),
(16, 'fechastrimestres', '1Trim', '1º Trimestre', NULL, NULL, NULL, '2024-09-16', '2024-12-20'),
(17, 'fechastrimestres', '2Trim', '2º Trimestre', NULL, NULL, NULL, '2025-01-07', '2025-04-11'),
(18, 'fechastrimestres', '3Trim', '3º Trimestre', NULL, NULL, NULL, '2025-04-21', '2025-06-24'),
(19, 'festivo', NULL, 'Vacaciones de Verano 2024', NULL, NULL, NULL, '2024-09-01', '2024-09-15'),
(20, 'festivo', NULL, 'Día de la Hispanidad sabado', NULL, NULL, NULL, '2024-10-12', '2024-10-12'),
(21, 'festivo', NULL, 'Día de Todos los Santos', NULL, NULL, NULL, '2024-10-31', '2024-11-01'),
(22, 'festivo', NULL, 'Día de la Constitución', NULL, NULL, NULL, '2024-12-06', '2024-12-09'),
(23, 'festivo', NULL, 'Vacaciones de Navidad', NULL, NULL, NULL, '2024-12-23', '2025-01-06'),
(24, 'festivo', NULL, 'Día de Andalucia y de la Comunidad Educativa', NULL, NULL, NULL, '2025-02-27', '2025-03-03'),
(25, 'festivo', NULL, 'Vacaciones de Semana Santa', NULL, NULL, NULL, '2025-04-14', '2025-04-18'),
(26, 'festivo', NULL, 'Día del Trabajo', NULL, NULL, NULL, '2025-05-01', '2025-05-02'),
(27, 'festivo', NULL, 'Día de la Cruz sabado', NULL, NULL, NULL, '2025-05-03', '2025-05-03'),
(28, 'festivo', NULL, 'Día del Corpus', NULL, NULL, NULL, '2025-06-19', '2025-06-20'),
(29, 'festivo', NULL, 'Vacaciones de Verano 2025', NULL, NULL, NULL, '2025-06-25', '2025-09-14'),
(34, 'tipoparte', 'C-Perturbación desarrollo clas', NULL, '(Contraria) Perturbación del normal desarrollo de las actividades de la clase', '', NULL, NULL, NULL),
(35, 'tipoparte', 'C-Falta de Colaboración', NULL, '(Contraria) Falta de colaboración sistemática en la realización de las actividades', 'si', NULL, NULL, NULL),
(36, 'tipoparte', 'C-Dificultar estudio', NULL, '(Contraria) Impedir o dificultar el estudio de sus compañeros', '', NULL, NULL, NULL),
(37, 'tipoparte', 'C-Faltas puntualidad', NULL, '(Contraria) Faltas injustificadas de puntualidad', '', NULL, NULL, NULL),
(38, 'tipoparte', 'C-Faltas asistencia', NULL, '(Contraria) Faltas injustificadas de asistencia a clase', '', NULL, NULL, NULL),
(39, 'tipoparte', 'C-Actuaciones incorrectas', NULL, '(Contraria) Actuaciones incorrectas hacia algún miembro de la comunidad educativa', '', NULL, NULL, NULL),
(40, 'tipoparte', 'C-Daños instalaciones, doc', NULL, '(Contraria) Daños en instalaciones, documentos del centro o en las pertenencias de un miembro de la comunidad educativa', '', NULL, NULL, NULL),
(41, 'tipoparte', 'G-Agresión física', NULL, '(Grave) Agresión física a un miembro de la comunidad educativa', '', NULL, NULL, NULL),
(42, 'tipoparte', 'G-Injurias y ofensas', NULL, '(Grave) Injurias y ofensas contra un miembro de la comunidad educativa', '', NULL, NULL, NULL),
(43, 'tipoparte', 'G-Perjudicar salud y integrida', NULL, '(Grave) Actuaciones perjudiciales para la salud y la integridad, o incitación a ellas', '', NULL, NULL, NULL),
(44, 'tipoparte', 'G-Vejaciones y humillaciones', NULL, '(Grave) Vejaciones o humillaciones contra un miembro de la comunidad educativa', '', NULL, NULL, NULL),
(45, 'tipoparte', 'G-Amenazas', NULL, '(Grave) Amenazas o coaciones a un miembro de la comunidad educativa', '', NULL, NULL, NULL),
(46, 'tipoparte', 'G-Suplantacion, falsificación.', NULL, '(Grave) Suplantación de la personalidad, y falsificación o subtracción de documentos', '', NULL, NULL, NULL),
(47, 'tipoparte', 'G-Deterioro grave ', NULL, '(Grave) Deterioro grave de instalaciones o documentos del centro, o pertenencias de un miembro de la comunidad educativa', '', NULL, NULL, NULL),
(48, 'tipoparte', 'G-Reiteración cond. contrarias', NULL, '(Grave) Reiteración en un mismo curso de conductas contrarias a las normas de convivencia', '', NULL, NULL, NULL),
(49, 'tipoparte', 'G-Impedir desarrollo centro', NULL, '(Grave) Impedir el normal desarrollo de las actividades del centro', '', NULL, NULL, NULL),
(50, 'tipoparte', 'G-Incumplimiento correcciones', NULL, '(Grave) Incumplimiento de las correcciones impuestas', '', NULL, NULL, NULL),
(51, 'tipoparte', 'C - Otros', NULL, '(Contraria) Otros', '', NULL, NULL, NULL),
(65, 'tiposancion', 'AC-Aula Convivencia', NULL, 'Aula de Convivencia', NULL, NULL, NULL, NULL),
(67, 'tiposancion', 'AR-Recreo Convivencia', NULL, 'Aula de Convivencia en el recreo', NULL, NULL, NULL, NULL),
(68, 'tiposancion', 'EX-Expulsión', NULL, 'Suspensión del derecho de asistencia al centro', NULL, NULL, NULL, NULL),
(69, 'tiposancion', 'EX-Expulsión en Aldeas', NULL, 'Suspensión del derecho de asistencia al centro con asistencia a Aldeas Infantiles', NULL, NULL, NULL, NULL),
(70, 'tiposancion', 'EC-Expulsión algunas clases', NULL, 'Suspensión del derecho de asistencia a determinadas clases', NULL, NULL, NULL, NULL),
(71, 'tiposancion', 'ES-Asist. cursos superiores', NULL, 'Asistencia a clases de cursos superiores', NULL, NULL, NULL, NULL),
(72, 'tiposancion', 'OO-Otros', NULL, 'Otros', NULL, NULL, NULL, NULL),
(80, 'tramohorario', 'M1', NULL, '8:15-9:15', NULL, NULL, NULL, NULL),
(81, 'tramohorario', 'M2', NULL, '9:15-10:15', NULL, NULL, NULL, NULL),
(82, 'tramohorario', 'M3', NULL, '10:15-11:15', NULL, NULL, NULL, NULL),
(83, 'tramohorario', 'M-Recreo', NULL, 'Recreo mañana', NULL, NULL, NULL, NULL),
(84, 'tramohorario', 'M4', NULL, '11:45-12:45', NULL, NULL, NULL, NULL),
(85, 'tramohorario', 'M5', NULL, '12:45-13:45', NULL, NULL, NULL, NULL),
(86, 'tramohorario', 'M6', NULL, '13:45-14:45', NULL, NULL, NULL, NULL),
(87, 'tramohorario', 'T1', NULL, '16:00-17:00', NULL, NULL, NULL, NULL),
(88, 'tramohorario', 'T2', NULL, '17:00-18:00', NULL, NULL, NULL, NULL),
(89, 'tramohorario', 'T3', NULL, '18:00-19:00', NULL, NULL, NULL, NULL),
(90, 'tramohorario', 'T4', NULL, 'Recreo tarde', NULL, NULL, NULL, NULL),
(91, 'tramohorario', 'T5', NULL, '19:00-20:00', NULL, NULL, NULL, NULL),
(92, 'tramohorario', 'T6', NULL, '20:00-21:00', NULL, NULL, NULL, NULL),
(93, 'tramohorario', 'Otra', NULL, '21:00-22:00', NULL, NULL, NULL, NULL),
(95, 'centrosSS', NULL, 'Servicios Sociales Barrio Blanco', 'Centro cívico Barrio Blanco C/ Laurel, 25', '18000 - Granada', 'Este', NULL, NULL),
(96, 'centrosSS', NULL, 'Sevicios Sociales Barrio Bajo', 'Ayuntamiento Barrio Bajo C/ Corcega, 2', '18000 - Granada', 'Barrio Bajo', NULL, NULL);

-- --------------------------------------------------------

--
-- Estructura de tabla para la tabla `tdatos`
--

DROP TABLE IF EXISTS `tdatos`;
CREATE TABLE IF NOT EXISTS `tdatos` (
  `Id` int(11) NOT NULL,
  `C_IdComunicacion` varchar(50) DEFAULT NULL,
  `C_TituloComunicacion` varchar(100) DEFAULT NULL,
  `C_CuerpoComunicacion` text DEFAULT NULL,
  `C_Cuerpo2Comunicacion` text DEFAULT NULL,
  `C_Cuerpo3Comunicacion` text DEFAULT NULL,
  `C_CorreosDestino` text DEFAULT NULL,
  `C_ConsultasBD` text DEFAULT NULL,
  `C_FicheroAdjunto` text DEFAULT NULL,
  `F_IdTipoAnotacion` varchar(50) DEFAULT NULL,
  `F_TipoAnotacion` varchar(200) DEFAULT NULL,
  `J_MotivosAbs` varchar(50) DEFAULT NULL,
  `L_IdRelacionCampos` varchar(20) DEFAULT NULL,
  `L_NuestroCampo` text DEFAULT NULL,
  `L_SenecaCampo` text DEFAULT NULL,
  PRIMARY KEY (`Id`)
) ENGINE=InnoDB DEFAULT CHARSET=utf8 COLLATE=utf8_general_ci;

--
-- Volcado de datos para la tabla `tdatos`
--

INSERT INTO `tdatos` (`Id`, `C_IdComunicacion`, `C_TituloComunicacion`, `C_CuerpoComunicacion`, `C_Cuerpo2Comunicacion`, `C_Cuerpo3Comunicacion`, `C_CorreosDestino`, `C_ConsultasBD`, `C_FicheroAdjunto`, `F_IdTipoAnotacion`, `F_TipoAnotacion`, `J_MotivosAbs`, `L_IdRelacionCampos`, `L_NuestroCampo`, `L_SenecaCampo`) VALUES
(1, 'CorreoParteATutor', 'Nuevo parte disciplinario de <<Alumno>> (<<Unidad>>)', 'Estimado tutor/a: <br> <br>\nSe ha registrado un nuevo parte disciplinario:<br> <br>\nAlumno/a: <<Alumno>> <br>\nUnidad: <<Unidad>> <br>\nFecha parte: <<darfechaesp(FechaPa)>> <br>\nHora: <<HoraPa>> <br>\nProfesor/a: <<Profesor>> <br>\nAsignatura: <<Asignatura>> <br>\nHechos: <<HechosPa>> <br> Aviso Familia: <<ComFamiliaPa>> <br> <br>\n\n\nSaludos,<br> <br>\n\nJefatura de Estudios <br>\n<<nombreies>>', NULL, NULL, 'CorreoProfesor', 'select * from tpartes where IdParte=\'<<Id>>\'--select * from talumnos where IdAlumno=\'<<AlumnoIdPa>>\'--select * from tunidades inner join talumnounidad on UnidadIdAlUn=IdUnidad where AlumnoIdAlUn=\'<<AlumnoIdPa>>\' and CursoAlUn=\'<<curso>>\'--select CorreoProfesor from tprofesores where IdProfesor=\'<<ProfesorIdUn>>\'--select Profesor from tprofesores where IdProfesor=\'<<ProfesorIdPa>>\'--select Concat(Nombre,\' \', substring(Apellido1,1,1),\'.\', substring(Apellido2,1,1),\'.\') as Iniciales from talumnos where IdAlumno=\'<<AlumnoIdPa>>\'--SELECT valor1 as nombreies FROM tconfiguracion WHERE id=\'nombreies\'', 'no', '01-Envio 1ª Carta', 'Enviada la 1ª Carta de citación a la familia para una reunión con el tutor/a del grupo donde tratar el problema de asistencia del alumno/a.', '(1)Probl. familiar', 'ImpProfes', 'IdProfesor--Profesor--Puesto', 'Usuario IdEA--Empleado/a--Puesto'),
(2, 'CorreoParteAFamilia', 'Comunicación de parte disciplinario.', 'Le comunicamos el siguiente parte disciplinario correspondiente a su hijo/a:<br><br>\n\nAlumno/a: <<Iniciales>> - (<<Unidad>>) <br>\nFecha del parte: <<darfechaesp(FechaPa)>><br>\nHora: <<HoraPa>><br>\nAsignatura: <<Asignatura>> <br>\nHechos: <<HechosPa>><br><br>\n\nAtentamente,<br><br>\n\nJefatura de Estudios<br>\n<<nombreies>>', NULL, NULL, 'CorreoTutor1,CorreoTutor2', 'select * from tpartes where IdParte=\'<<Id>>\'--select * from talumnos where IdAlumno=\'<<AlumnoIdPa>>\'--select Concat(Nombre,\' \', substring(Apellido1,1,1),\'.\', substring(Apellido2,1,1),\'.\') as Iniciales from talumnos where IdAlumno=\'<<AlumnoIdPa>>\'--select Unidad from tunidades inner join talumnounidad on UnidadIdAlUn=IdUnidad where AlumnoIdAlUn=\'<<AlumnoIdPa>>\' and CursoAlUn=\'<<curso>>\'--SELECT valor1 as nombreies FROM tconfiguracion WHERE id=\'nombreies\'', 'no', '02-Firma 1º Contrato', 'Realizada la 1ª reunión entre la familia y el tutor/a del grupo donde se ha tratado el problema de asistencia del alumno/a.', '(2)Desinterés', 'ImpUnidades', 'Unidad--ProfesorIdUn--Nivel--TurnoTarde', 'Unidad--Tutor/a--Curso--Turno de tarde/noche'),
(3, 'PDFSancionesSemanal', 'MEDIDAS DE CONVIVENCIA', 'IMPORTANTE: El profesorado correspondiente debe suministrar las tareas, en cantidad suficiente, para realizar por el alumno durante su sanción.', NULL, NULL, NULL, NULL, NULL, '03-Envio 2ª Carta', 'Enviada la 2ª Carta a la familia comunicando la situación de absentismo del alumno/a y citándoles a una reunión con Jefatura de Estudios.', '(3)Probl. laboral', 'ImpAlumnos', 'IdAlumno--Alumno--Apellido1--Apellido2--Nombre--Direccion--CP--Localidad--Provincia--FechaNac--CorreoAlumno--TelefonoAl--Nivel--UnidadUn--Apellido1Tu1--Apellido2Tu1--NombreTu1--CorreoTutor1--TelefonoTutor1--Apellido1Tu2--Apellido2Tu2--NombreTu2--CorreoTutor2--TelefonoTutor2--Repetidor--Custodia--EstadoMatricula', 'Nº Id. Escolar--Alumno/a--Primer apellido--Segundo apellido--Nombre--Dirección--Código postal--Localidad de residencia--Provincia de residencia--Fecha de nacimiento--Correo electrónico personal alumno/a--Teléfono personal alumno/a--Curso--Unidad--Primer apellido Primer tutor--Segundo apellido Primer tutor--Nombre Primer tutor--Correo Electrónico Primer tutor--Teléfono Primer tutor--Primer apellido Segundo tutor--Segundo apellido Segundo tutor--Nombre Segundo tutor--Correo Electrónico Segundo tutor--Teléfono Segundo tutor--Nº de matrículas en este curso--Custodia--Estado Matrícula'),
(4, 'PDFComunicaSancion', 'COMUNICACIÓN DE SANCIÓN A LOS TUTORES LEGALES', 'En virtud del Decreto 327/2010 (para Institutos de Educación Secundaria) que aprueban los Reglamentos Orgánicos de dichos centros, le comunicamos  las medidas correctoras aplicadas a los incidente en los que ha participado su hijo/a:', 'Ante este acuerdo de corrección/medida disciplinaria, el alumno o alumna, así como sus padres, madres o representantes legales, podrán presentar en el plazo de dos días lectivos, contados a partir de la fecha de su comunicación, una reclamación contra la misma, según lo establecido en el Artículo 41 del Decreto 327/2010 por el que se aprueba el Reglamento Orgánico de los Institutos de Educación Secundaria.', 'Deben devolver esta notificación firmada por el padre, la madre o el tutor legal.', NULL, NULL, NULL, '04-Firma 2º Contrato', 'Realizada la 2ª reunión entre la familia y Jefatura de Estudios donde se ha tratado la situación de absentismo del alumno/a.', '(4)Continuos despl.', 'ImpProfesCorreo', 'Profesor--CorreoProfesor--TelefonoProfesor', 'First Name--E-mail 1 - Value--Phone 1 - Value'),
(5, 'CorreoSancionesSemanalAClaustro', 'MEDIDAS DE CONVIVENCIA (Semana del <<Id>>)', 'A la atención del Claustro. <br><br>\nSe adjunta fichero PDF con las medidas de convivencia adoptadas para la semana del <<Id>>.<br>  <br> Atentamente,<br> <br>\n\nJefatura de Estudios.', NULL, NULL, 'Claustro', NULL, 'si', '05-Anexo II AASS', 'Se ha derivado, mediante Anexo II, la situación de absentismo del alumno/a a los Servicios Sociales Comunitarios.', '(5)No se sabe', 'ImpAlumnoCorreo', 'IdAlumno--CorreoAlumnoCorp', 'nie--correo'),
(6, 'CorreoSancionesSemanalAAC', 'MEDIDAS DE CONVIVENCIA (Semana del <<Id>>)', 'Estimado compañero responsable del Aula de Convivencia: <br><br>\nSe adjunta fichero PDF con las medidas de convivencia adoptadas para la semana del <<Id>>, actualizadas a fecha de hoy.<br><br> Atentamente,<br> <br>\n\nJefatura de Estudios.', NULL, NULL, 'Aula Convivencia', NULL, 'si', '06-Anexo III', 'Se ha remitido, mediante Anexo III, a la Delegación Territorial de Educación el expediente informativo sobre la situación de absentismo del alumno/a.', '(6)Otros', NULL, NULL, NULL),
(7, 'CorreoSancionAC', 'Nueva Sanción de Aula de Convivencia para <<Alumno>> (<<Unidad>>).', 'Estimado compañero responsable del Aula de Convivencia: <br><br>\n\nTe informo de una nueva sanción:<br><br>\nAlumno: <<Alumno>> (<<Unidad>>)<br>\nTipo de sanción: <<TipoSa>><br>\nFecha de inicio de la sanción: <<darfechaesp(FechaInicio)>><br>\nFecha de finalización de la sanción: <<darfechaesp(FechaFin)>><br>\nNúmero de días de sanción: <<DiasSancion>><br><br>\nAl final de semana te enviaremos un cuadrante resumen de todas las sanciones para la semana que viene.<br><br>\nAtentamente,<br> <br>\n\nJefatura de Estudios.', NULL, NULL, 'Aula Convivencia', 'select * from tsanciones where IdSancion=\'<<Id>>\'--select * from talumnos where IdAlumno=\'<<AlumnoIdSa>>\'--select Unidad from tunidades inner join talumnounidad on UnidadIdAlUn=IdUnidad where AlumnoIdAlUn=\'<<AlumnoIdSa>>\' and CursoAlUn=\'<<curso>>\'', 'no', '07-Actuación Familia', 'Actuación con la familia.', NULL, NULL, NULL, NULL),
(8, 'CorreoAnotacionATutor', 'Información de Absentismo sobre el alumno <<Alumno>> (<<Unidad>>)', 'Estimado tutor/a:<br><br>\n\nTe comunico la siguiente información relativa a absentismo:<br><br>\n\nAlumno/a: <<Alumno>> (<<Unidad>>)<br>\nFecha: <<darfechaesp(FechaAn)>><br>\n<<F_TipoAnotacion>><br>\n<<Anotacion>>\n<br><br>\n\nPara cualquier aclaración puedes contactar con el responsable de Absentismo de Jefatura de Estudios.<br><br>\n\nAtentamente,<br><br>\n\nJefatura de Estudios.', NULL, NULL, 'CorreoProfesor', 'select * from TAnotacionesAbs where IdAnotacion=\'<<Id>>\'--select * from TAlumnos where IdAlumno=\'<<AlumnoIdAn>>\'--select Unidad, ProfesorIdUn from TUnidades inner join TAlumnoUnidad on UnidadIdAlUn=IdUnidad where AlumnoIdAlUn=\'<<AlumnoIdAn>>\' and CursoAlUn=\'<<curso>>\'--select CorreoProfesor from TProfesores where IdProfesor=\'<<ProfesorIdUn>>\'--select F_TipoAnotacion from TDatos where F_IdTipoAnotacion=\'<<TipoAn>>\'', 'no', '08-Actuación Tutor', 'Actuación con el tutor del grupo del alumno.', NULL, NULL, NULL, NULL),
(9, '01-Envio 1ª Carta', 'CITACIÓN PARA REUNIÓN SOBRE ABSENTISMO DEL ALUMNO/A\n', 'Sr/Sra. padre, madre o tutor legal del alumno/a:\n\n<<Alumno>> - (<<Unidad>>)\n\nPor medio de la presente, pongo en su conocimiento que la Delegación de Educación  considera absentistas  a los alumnos que faltan al centro escolar sin justificar.\n\n\nEn este sentido le informamos que su hijo/a ha faltado a clase las siguientes horas:\n\n<<TablaFaltas>>\n\nPuede obtener información más detallada de las faltas de asistencia en la aplicación iPasen.\n\nCon esta notificación damos cumplimiento a su derecho como padre/madre a estar informado de las faltas de asistencia de su hijo/a y le recordamos que deberá usted pasarse por el centro para tratar del tema en una reunión con el tutor/a D/Dª <<Profesor>> del grupo de su hijo, en el siguiente horario:\n\nFecha: <<FechaCita>>  					Hora: <<HoraCita>>\n \nSin otro particular, reciba un cordial saludo.\n\n\n\n<<GranadaFechaHoy>>\n\n\n\n<<TablaFirmas>>', NULL, NULL, NULL, NULL, NULL, '09-Actuación Orientación', 'Actuación con el Departamento de Orientación del centro.', NULL, NULL, NULL, NULL),
(10, 'CorreoTutorJustificaFaltas', 'Petición de justificación de faltas del alumno/a <<Alumno>> - (<<Unidad>>)', 'Estimado tutor/a:<br><br>\n\nEl alumno/a de tu tutoría:<br><br>\n\n<<Alumno>> - (<<Unidad>>)<br><br>\n\nha tenido durante el mes de <<nombremes(OtroDato)>>:<br>\n<<HJT>> faltas justificadas,<br>\n<<HIT>> faltas injustificadas y<br>\n<<HRT>> retrasos.<br><br>\n\nPor favor, contacta con la familia para informarte sobre el motivo de las ausencias y pídeles que te justifiquen las faltas injustificadas de los días:<br>\n<<DiasInjustificadas>><br><br>\n\nUna vez que te hayan justificado dichas faltas adecuadamente grábalas en la plataforma Séneca lo antes posible, e informa al responsable de absentismo de Jefatura de Estudios sobre el motivo de las ausencias. <br><br>\n\nAtentamente,<br><br>\n\nJefatura de Estudios.', 'Se le ha pedido al tutor que contacte con la familia para saber el motivo de las ausencias y justificar las faltas del mes de <<nombremes(OtroDato)>>.\r\nDurante este mes ha faltado: Injustificadas <<HIT>> horas, justificadas <<HJT>> horas y <<HRT>> retrasos.\r\nLos días con faltas injustificadas han sido: <<DiasInjustificadas>>.', NULL, 'CorreoProfesor', 'select * from talumnos where IdAlumno=\'<<Id>>\'--select Unidad, ProfesorIdUn from tunidades inner join talumnoUnidad on UnidadIdAlUn=IdUnidad where AlumnoIdAlUn=\'<<Id>>\' and CursoUn=\'<<curso>>\'--select CorreoProfesor from tprofesores where IdProfesor=\'<<ProfesorIdUn>>\'--select sum(HorasI) as HIT from tfaltas where AlumnoIdFa=\'<<Id>>\' AND month(FechaFa)=<<OtroDato>>--select sum(HorasJ) as HJT from TFaltas where AlumnoIdFa=\'<<Id>>\' AND month(FechaFa)=<<OtroDato>>--select sum(HorasR) as HRT from tfaltas where AlumnoIdFa=\'<<Id>>\' AND month(FechaFa)=<<OtroDato>>--select group_concat(date_format(FechaFa, \"%d-%m-%Y\") separator \' / \') as DiasInjustificadas from tfaltas where AlumnoIdFa=\'<<Id>>\' and HorasI>0 and month(FechaFa)=<<OtroDato>>', 'no', '10-Actuación CoorETAE', 'Actuación con la Coordinadora de ETAE', NULL, NULL, NULL, NULL),
(11, NULL, NULL, NULL, NULL, NULL, NULL, NULL, NULL, '11-Petición Tutor Justificación faltas', 'Petición al tutor de información y justificación sobre las faltas de asistencia de un alumno/a.', NULL, NULL, NULL, NULL),
(12, NULL, NULL, NULL, NULL, NULL, NULL, NULL, NULL, '12-Otros', 'Otros', NULL, NULL, NULL, NULL);

-- --------------------------------------------------------

--
-- Estructura de tabla para la tabla `tfaltas`
--

DROP TABLE IF EXISTS `tfaltas`;
CREATE TABLE IF NOT EXISTS `tfaltas` (
  `IdFalta` int(11) NOT NULL,
  `AlumnoIdFa` int(11) NOT NULL,
  `FechaFa` date NOT NULL,
  `HorasI` int(11) NOT NULL DEFAULT 0,
  `HorasJ` int(11) NOT NULL DEFAULT 0,
  `HorasR` int(11) NOT NULL DEFAULT 0,
  `FechaActualizacion` date NOT NULL,
  PRIMARY KEY (`IdFalta`)
) ENGINE=InnoDB DEFAULT CHARSET=utf8 COLLATE=utf8_general_ci;

-- --------------------------------------------------------

--
-- Estructura de tabla para la tabla `tnotasalumno`
--

DROP TABLE IF EXISTS `tnotasalumno`;
CREATE TABLE IF NOT EXISTS `tnotasalumno` (
  `AlumnoIdNo` int(11) NOT NULL,
  `ABS_Seguimiento` varchar(3) DEFAULT NULL,
  `ABS_Motivo` varchar(50) DEFAULT NULL,
  `ObservacionesAl` text DEFAULT NULL,
  `SituacionFamiliar` text DEFAULT NULL,
  PRIMARY KEY (`AlumnoIdNo`)
) ENGINE=InnoDB DEFAULT CHARSET=utf8mb4 COLLATE=utf8mb4_general_ci;

-- --------------------------------------------------------

--
-- Estructura de tabla para la tabla `tpartes`
--

DROP TABLE IF EXISTS `tpartes`;
CREATE TABLE IF NOT EXISTS `tpartes` (
  `IdParte` int(11) NOT NULL,
  `EstadoPa` varchar(5) NOT NULL,
  `AlumnoIdPa` int(11) NOT NULL,
  `FechaPa` date NOT NULL,
  `HoraPa` varchar(20) DEFAULT NULL,
  `ProfesorIdPa` varchar(12) NOT NULL,
  `Asignatura` varchar(50) NOT NULL,
  `HechosPa` text NOT NULL,
  `TipoPa` varchar(100) NOT NULL,
  `ComFamiliaPa` text DEFAULT NULL,
  `ObservacionesPa` text DEFAULT NULL,
  `CorreoFamPa` varchar(5) DEFAULT NULL,
  `CorreoTutorPa` varchar(5) DEFAULT NULL,
  `origen` varchar(15) DEFAULT NULL,
  PRIMARY KEY (`IdParte`)
) ENGINE=InnoDB DEFAULT CHARSET=utf8 COLLATE=utf8_general_ci;

-- --------------------------------------------------------

--
-- Estructura de tabla para la tabla `tprofesores`
--

DROP TABLE IF EXISTS `tprofesores`;
CREATE TABLE IF NOT EXISTS `tprofesores` (
  `IdProfesor` varchar(12) NOT NULL,
  `AbreProfesor` varchar(10) DEFAULT NULL,
  `Profesor` varchar(100) NOT NULL,
  `CorreoProfesor` varchar(100) DEFAULT NULL,
  `TelefonoProfesor` varchar(100) DEFAULT NULL,
  `Puesto` text DEFAULT NULL,
  `Departamento` varchar(100) DEFAULT NULL,
  PRIMARY KEY (`IdProfesor`)
) ENGINE=InnoDB DEFAULT CHARSET=utf8 COLLATE=utf8_general_ci;

-- --------------------------------------------------------

--
-- Estructura de tabla para la tabla `tsanciones`
--

DROP TABLE IF EXISTS `tsanciones`;
CREATE TABLE IF NOT EXISTS `tsanciones` (
  `IdSancion` int(11) NOT NULL,
  `AlumnoIdSa` int(11) NOT NULL,
  `EstadoSa` varchar(5) NOT NULL,
  `FechaSa` date NOT NULL,
  `HechosSa` text DEFAULT NULL,
  `TipoSa` varchar(50) NOT NULL,
  `FechaInicio` date DEFAULT NULL,
  `FechaFin` date DEFAULT NULL,
  `DiasSancion` int(11) DEFAULT NULL,
  `ComFamiliaSa` text DEFAULT NULL,
  `ObservacionesSa` text DEFAULT NULL,
  `observacionescomsancion` text DEFAULT NULL,
  `PedirTar` varchar(5) DEFAULT NULL,
  `DadasTar` varchar(5) DEFAULT NULL,
  `ReSeneca` varchar(5) DEFAULT NULL,
  `DadasCom` varchar(5) DEFAULT NULL,
  `ComPasen` varchar(5) DEFAULT NULL,
  `ArchiCom` varchar(5) DEFAULT NULL,
  PRIMARY KEY (`IdSancion`)
) ENGINE=InnoDB DEFAULT CHARSET=utf8 COLLATE=utf8_general_ci;

-- --------------------------------------------------------

--
-- Estructura de tabla para la tabla `tsancionparte`
--

DROP TABLE IF EXISTS `tsancionparte`;
CREATE TABLE IF NOT EXISTS `tsancionparte` (
  `id` int(11) NOT NULL AUTO_INCREMENT,
  `SancionIdSaPa` int(11) NOT NULL,
  `ParteIdSaPa` int(11) NOT NULL,
  `AlumnoIdSaPa` int(11) NOT NULL,
  `IdSancionxAlumno` int(11) NOT NULL,
  `LetraSancionxAlumno` varchar(5) NOT NULL,
  PRIMARY KEY (`id`)
) ENGINE=InnoDB AUTO_INCREMENT=125 DEFAULT CHARSET=utf8 COLLATE=utf8_general_ci;

-- --------------------------------------------------------

--
-- Estructura de tabla para la tabla `ttemporal`
--

DROP TABLE IF EXISTS `ttemporal`;
CREATE TABLE IF NOT EXISTS `ttemporal` (
  `Id` int(11) NOT NULL,
  `C1` text DEFAULT NULL,
  `C2` text DEFAULT NULL,
  `C3` text DEFAULT NULL,
  `C4` text DEFAULT NULL,
  `C5` text DEFAULT NULL,
  `C6` text DEFAULT NULL,
  `C7` text DEFAULT NULL,
  `C8` text DEFAULT NULL,
  `C9` text DEFAULT NULL,
  `C10` text DEFAULT NULL,
  `C11` text DEFAULT NULL,
  `C12` text DEFAULT NULL,
  `C13` text DEFAULT NULL,
  `C14` text DEFAULT NULL,
  `C15` text DEFAULT NULL,
  `C16` text DEFAULT NULL,
  `C17` text DEFAULT NULL,
  `C18` text DEFAULT NULL,
  `C19` text DEFAULT NULL,
  `C20` text DEFAULT NULL,
  `C21` text DEFAULT NULL,
  `C22` text DEFAULT NULL,
  `C23` text DEFAULT NULL,
  `C24` text DEFAULT NULL,
  `C25` text DEFAULT NULL,
  `C26` text DEFAULT NULL,
  `C27` text DEFAULT NULL,
  `C28` text DEFAULT NULL,
  `C29` text DEFAULT NULL,
  `C30` text DEFAULT NULL,
  `C31` text DEFAULT NULL,
  `C32` text DEFAULT NULL,
  `C33` text DEFAULT NULL,
  `C34` text DEFAULT NULL,
  `C35` text DEFAULT NULL,
  `C36` text DEFAULT NULL,
  `C37` text DEFAULT NULL,
  `C38` text DEFAULT NULL,
  `C39` text DEFAULT NULL,
  `C40` text DEFAULT NULL,
  `C41` text DEFAULT NULL,
  `C42` text DEFAULT NULL,
  `C43` text DEFAULT NULL,
  `C44` text DEFAULT NULL,
  `C45` text DEFAULT NULL,
  `C46` text DEFAULT NULL,
  `C47` text DEFAULT NULL,
  `C48` text DEFAULT NULL,
  `C49` text DEFAULT NULL,
  `C50` text DEFAULT NULL,
  `C51` text DEFAULT NULL,
  PRIMARY KEY (`Id`)
) ENGINE=InnoDB DEFAULT CHARSET=utf8mb4 COLLATE=utf8mb4_general_ci;

-- --------------------------------------------------------

--
-- Estructura de tabla para la tabla `tunidades`
--

DROP TABLE IF EXISTS `tunidades`;
CREATE TABLE IF NOT EXISTS `tunidades` (
  `IdUnidad` int(11) NOT NULL,
  `Unidad` varchar(20) NOT NULL,
  `Orden` int(11) DEFAULT NULL,
  `ProfesorIdUn` varchar(12) DEFAULT NULL,
  `Nivel` text DEFAULT NULL,
  `Bloque` varchar(50) DEFAULT NULL,
  `TurnoTarde` varchar(10) DEFAULT NULL,
  `CursoUn` varchar(5) DEFAULT NULL,
  PRIMARY KEY (`IdUnidad`)
) ENGINE=InnoDB DEFAULT CHARSET=utf8 COLLATE=utf8_general_ci;
COMMIT;

/*!40101 SET CHARACTER_SET_CLIENT=@OLD_CHARACTER_SET_CLIENT */;
/*!40101 SET CHARACTER_SET_RESULTS=@OLD_CHARACTER_SET_RESULTS */;
/*!40101 SET COLLATION_CONNECTION=@OLD_COLLATION_CONNECTION */;
