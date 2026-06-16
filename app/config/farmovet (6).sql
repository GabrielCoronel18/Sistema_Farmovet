-- phpMyAdmin SQL Dump
-- version 5.2.1
-- https://www.phpmyadmin.net/
--
-- Servidor: 127.0.0.1
-- Tiempo de generación: 17-06-2026 a las 00:41:04
-- Versión del servidor: 10.4.32-MariaDB
-- Versión de PHP: 8.0.30

SET SQL_MODE = "NO_AUTO_VALUE_ON_ZERO";
START TRANSACTION;
SET time_zone = "+00:00";


/*!40101 SET @OLD_CHARACTER_SET_CLIENT=@@CHARACTER_SET_CLIENT */;
/*!40101 SET @OLD_CHARACTER_SET_RESULTS=@@CHARACTER_SET_RESULTS */;
/*!40101 SET @OLD_COLLATION_CONNECTION=@@COLLATION_CONNECTION */;
/*!40101 SET NAMES utf8mb4 */;

--
-- Base de datos: `farmovet`
--

-- --------------------------------------------------------

--
-- Estructura de tabla para la tabla `alergia`
--

CREATE TABLE `alergia` (
  `id_alergia` int(11) NOT NULL,
  `nombre_alergia` varchar(80) NOT NULL,
  `tipo` varchar(80) NOT NULL,
  `estado` tinyint(1) NOT NULL
) ENGINE=InnoDB DEFAULT CHARSET=utf8mb4 COLLATE=utf8mb4_spanish_ci;

-- --------------------------------------------------------

--
-- Estructura de tabla para la tabla `alergia_mascota`
--

CREATE TABLE `alergia_mascota` (
  `id_alergia_mascota` int(11) NOT NULL,
  `id_alergia` int(11) NOT NULL,
  `id_mascota` int(11) NOT NULL,
  `fecha_deteccion` date NOT NULL
) ENGINE=InnoDB DEFAULT CHARSET=utf8mb4 COLLATE=utf8mb4_spanish_ci;

-- --------------------------------------------------------

--
-- Estructura de tabla para la tabla `anamnesis`
--

CREATE TABLE `anamnesis` (
  `id_anamnesis` int(11) NOT NULL,
  `id_consulta` int(11) NOT NULL,
  `motivo_consulta` varchar(200) NOT NULL,
  `inicio_enfermedad` date NOT NULL,
  `examenes_efectuados` varchar(200) NOT NULL,
  `tratamientos_realizados` varchar(200) NOT NULL,
  `ult_desp_interna` date NOT NULL,
  `ult_desp_int_producto` varchar(80) NOT NULL,
  `ult_desp_externa` date NOT NULL,
  `ult_desp_ext_producto` varchar(80) NOT NULL,
  `ultima_vacunacion` date NOT NULL,
  `tipo_alimentacion` varchar(100) NOT NULL,
  `frec_alimentacion` varchar(100) NOT NULL,
  `apetito` varchar(100) NOT NULL,
  `ingesta_agua` varchar(100) NOT NULL,
  `contacto_animal` varchar(100) NOT NULL,
  `vomito` varchar(100) NOT NULL,
  `heces` varchar(100) NOT NULL,
  `miccion` varchar(100) NOT NULL,
  `fch_ultimo_celo` date NOT NULL,
  `prod_higiene` varchar(80) NOT NULL,
  `frec_higiene` varchar(100) NOT NULL,
  `int_ambiente` varchar(100) NOT NULL,
  `ext_ambiente` varchar(100) NOT NULL,
  `act_ectoparasitos` tinyint(1) NOT NULL,
  `ant_ectoparasitos` tinyint(1) NOT NULL
) ENGINE=InnoDB DEFAULT CHARSET=utf8mb4 COLLATE=utf8mb4_spanish_ci;

-- --------------------------------------------------------

--
-- Estructura de tabla para la tabla `cirugia_previa`
--

CREATE TABLE `cirugia_previa` (
  `id_cirugia_previa` int(11) NOT NULL,
  `id_mascota` int(11) NOT NULL,
  `nombre_cirugia` varchar(100) NOT NULL,
  `fecha_cirugia` date NOT NULL
) ENGINE=InnoDB DEFAULT CHARSET=utf8mb4 COLLATE=utf8mb4_spanish_ci;

-- --------------------------------------------------------

--
-- Estructura de tabla para la tabla `cliente`
--

CREATE TABLE `cliente` (
  `cedula_cliente` varchar(12) NOT NULL,
  `nombre` varchar(80) NOT NULL,
  `apellido` varchar(80) NOT NULL,
  `correo` varchar(110) NOT NULL,
  `telefono` int(12) NOT NULL,
  `direccion` varchar(150) NOT NULL,
  `estado` tinyint(1) NOT NULL
) ENGINE=InnoDB DEFAULT CHARSET=utf8mb4 COLLATE=utf8mb4_spanish_ci;

--
-- Volcado de datos para la tabla `cliente`
--

INSERT INTO `cliente` (`cedula_cliente`, `nombre`, `apellido`, `correo`, `telefono`, `direccion`, `estado`) VALUES
('12313122', 'Jaime', 'Rodrigez', 'JamRod2@gmail.com', 12331231, 'Su casa', 1);

-- --------------------------------------------------------

--
-- Estructura de tabla para la tabla `consulta`
--

CREATE TABLE `consulta` (
  `id_consulta` int(11) NOT NULL,
  `cedula_usuario` varchar(12) NOT NULL,
  `id_mascota` int(11) NOT NULL,
  `fecha` date NOT NULL,
  `tipo_ingreso` tinyint(1) NOT NULL,
  `remitido` tinyint(1) NOT NULL,
  `pronostico` varchar(100) NOT NULL,
  `tratamiento_consulta` varchar(80) NOT NULL,
  `peso_trat-consulta` varchar(80) NOT NULL,
  `comentario_seguimientoo` varchar(200) NOT NULL,
  `estado` tinyint(1) NOT NULL
) ENGINE=InnoDB DEFAULT CHARSET=utf8mb4 COLLATE=utf8mb4_spanish_ci;

-- --------------------------------------------------------

--
-- Estructura de tabla para la tabla `diagnostico`
--

CREATE TABLE `diagnostico` (
  `id_diagnostico` int(11) NOT NULL,
  `id_consulta` int(11) NOT NULL,
  `id_patologia` int(11) NOT NULL,
  `tipo_diagnostico` varchar(80) NOT NULL
) ENGINE=InnoDB DEFAULT CHARSET=utf8mb4 COLLATE=utf8mb4_spanish_ci;

-- --------------------------------------------------------

--
-- Estructura de tabla para la tabla `enfermedades_sufridas`
--

CREATE TABLE `enfermedades_sufridas` (
  `id_enfermedad_sufrida` int(11) NOT NULL,
  `id_mascota` int(11) NOT NULL,
  `id_patologia` int(11) NOT NULL,
  `fecha_diagnostico` date NOT NULL,
  `estado_enfermedad` varchar(100) NOT NULL
) ENGINE=InnoDB DEFAULT CHARSET=utf8mb4 COLLATE=utf8mb4_spanish_ci;

-- --------------------------------------------------------

--
-- Estructura de tabla para la tabla `especie`
--

CREATE TABLE `especie` (
  `id_especie` int(11) NOT NULL,
  `nombre_especie` varchar(80) NOT NULL,
  `estado` tinyint(1) NOT NULL
) ENGINE=InnoDB DEFAULT CHARSET=utf8mb4 COLLATE=utf8mb4_spanish_ci;

--
-- Volcado de datos para la tabla `especie`
--

INSERT INTO `especie` (`id_especie`, `nombre_especie`, `estado`) VALUES
(1, 'Perro', 1);

-- --------------------------------------------------------

--
-- Estructura de tabla para la tabla `examen_clinico`
--

CREATE TABLE `examen_clinico` (
  `id_examen` int(11) NOT NULL,
  `id_consulta` int(11) NOT NULL,
  `peso` int(11) NOT NULL,
  `cc` int(1) NOT NULL,
  `temp_celsius` int(11) NOT NULL,
  `pulso_yugular` varchar(80) NOT NULL,
  `fr_rpm` int(11) NOT NULL,
  `fc_lpm` int(11) NOT NULL,
  `pulso_ppm` int(11) NOT NULL,
  `tlc_seg` int(11) NOT NULL,
  `tpc_seg` int(11) NOT NULL,
  `pas` varchar(40) NOT NULL,
  `pad` varchar(40) NOT NULL,
  `prcnt_deshidratacion` int(11) NOT NULL,
  `gangliios_palpables` varchar(100) NOT NULL,
  `mucosas_visibles` varchar(100) NOT NULL,
  `ectoparasitos` varchar(100) NOT NULL,
  `actitud` varchar(80) NOT NULL,
  `hallazgos` varchar(400) NOT NULL
) ENGINE=InnoDB DEFAULT CHARSET=utf8mb4 COLLATE=utf8mb4_spanish_ci;

-- --------------------------------------------------------

--
-- Estructura de tabla para la tabla `mascota`
--

CREATE TABLE `mascota` (
  `id_mascota` int(11) NOT NULL,
  `nombre` varchar(80) NOT NULL,
  `edad` int(11) NOT NULL,
  `sexo` varchar(50) NOT NULL,
  `chip` varchar(50) NOT NULL,
  `procedencia` varchar(100) NOT NULL,
  `fch_nacimiento` date NOT NULL,
  `id_raza` int(11) NOT NULL,
  `pelaje` varchar(80) NOT NULL,
  `cedula_cliente` varchar(12) NOT NULL,
  `estado` tinyint(1) NOT NULL
) ENGINE=InnoDB DEFAULT CHARSET=utf8mb4 COLLATE=utf8mb4_spanish_ci;

--
-- Volcado de datos para la tabla `mascota`
--

INSERT INTO `mascota` (`id_mascota`, `nombre`, `edad`, `sexo`, `chip`, `procedencia`, `fch_nacimiento`, `id_raza`, `pelaje`, `cedula_cliente`, `estado`) VALUES
(4, 'Logan', 5, 'Masculino', 'Si', 'Viene de...', '2021-06-16', 1, 'De color...', '12313122', 1),
(20, 'Lisa', 9, 'Femenino', 'Si', 'asdasda', '2026-06-17', 1, 'adas', '12313122', 1),
(23, 'Jason', 6, 'Masculino', 'No', 'dadada', '2020-06-16', 1, 'Negro', '12313122', 1);

-- --------------------------------------------------------

--
-- Estructura de tabla para la tabla `medicamento`
--

CREATE TABLE `medicamento` (
  `id_medicamento` int(11) NOT NULL,
  `nombre_medicamento` varchar(80) NOT NULL,
  `id_tipo_medicamento` int(11) NOT NULL,
  `id_presentacion` int(11) NOT NULL,
  `estado` tinyint(1) NOT NULL
) ENGINE=InnoDB DEFAULT CHARSET=utf8mb4 COLLATE=utf8mb4_spanish_ci;

-- --------------------------------------------------------

--
-- Estructura de tabla para la tabla `patologia`
--

CREATE TABLE `patologia` (
  `id_patologia` int(11) NOT NULL,
  `nombre` varchar(80) NOT NULL,
  `tipo` varchar(80) NOT NULL,
  `sintomas` varchar(150) NOT NULL,
  `estado` tinyint(1) NOT NULL
) ENGINE=InnoDB DEFAULT CHARSET=utf8mb4 COLLATE=utf8mb4_spanish_ci;

-- --------------------------------------------------------

--
-- Estructura de tabla para la tabla `plan_sanitario`
--

CREATE TABLE `plan_sanitario` (
  `id_plan` int(11) NOT NULL,
  `id_mascota` int(11) NOT NULL,
  `id_medicamento` int(11) NOT NULL,
  `fecha_aplicacion` date NOT NULL,
  `proximo_refuerzo` date NOT NULL,
  `estado` tinyint(1) NOT NULL
) ENGINE=InnoDB DEFAULT CHARSET=utf8mb4 COLLATE=utf8mb4_spanish_ci;

-- --------------------------------------------------------

--
-- Estructura de tabla para la tabla `presentacion`
--

CREATE TABLE `presentacion` (
  `id_presentacion` int(11) NOT NULL,
  `nombre_presentacion` varchar(120) NOT NULL,
  `estado` tinyint(1) NOT NULL
) ENGINE=InnoDB DEFAULT CHARSET=utf8mb4 COLLATE=utf8mb4_spanish_ci;

-- --------------------------------------------------------

--
-- Estructura de tabla para la tabla `raza`
--

CREATE TABLE `raza` (
  `id_raza` int(11) NOT NULL,
  `nombre_raza` varchar(80) NOT NULL,
  `id_especie` int(11) NOT NULL,
  `estado` tinyint(1) NOT NULL
) ENGINE=InnoDB DEFAULT CHARSET=utf8mb4 COLLATE=utf8mb4_spanish_ci;

--
-- Volcado de datos para la tabla `raza`
--

INSERT INTO `raza` (`id_raza`, `nombre_raza`, `id_especie`, `estado`) VALUES
(1, 'Pastor Aleman', 1, 1);

-- --------------------------------------------------------

--
-- Estructura de tabla para la tabla `recipe`
--

CREATE TABLE `recipe` (
  `id_recipe` int(11) NOT NULL,
  `id_consulta` int(11) NOT NULL,
  `id_medicamento` int(11) NOT NULL,
  `dosis` varchar(150) NOT NULL,
  `frecuencia` varchar(150) NOT NULL,
  `duracion` varchar(150) NOT NULL
) ENGINE=InnoDB DEFAULT CHARSET=utf8mb4 COLLATE=utf8mb4_spanish_ci;

-- --------------------------------------------------------

--
-- Estructura de tabla para la tabla `resultado_laboratorio`
--

CREATE TABLE `resultado_laboratorio` (
  `id_resultado` int(11) NOT NULL,
  `id_consulta` int(11) NOT NULL,
  `hematologia_completa` varchar(300) NOT NULL,
  `coprologia` varchar(300) NOT NULL,
  `quimica_sanguinea` varchar(300) NOT NULL,
  `uro_sangre` varchar(50) NOT NULL,
  `uro_urob` varchar(50) NOT NULL,
  `uro_bli` varchar(50) NOT NULL,
  `uro_prot` varchar(50) NOT NULL,
  `uro_nitritos` varchar(50) NOT NULL,
  `uro_cetona` varchar(50) NOT NULL,
  `uro_glucosa` varchar(50) NOT NULL,
  `uro_ph` varchar(50) NOT NULL,
  `uro_leu` varchar(50) NOT NULL,
  `uro_densidad` varchar(50) NOT NULL,
  `uro_microorganismos` varchar(50) NOT NULL,
  `uro_celulas` varchar(50) NOT NULL,
  `uro_cilindros` varchar(50) NOT NULL,
  `uro_cristales` varchar(50) NOT NULL,
  `descarte` varchar(50) NOT NULL,
  `piel_otros` varchar(50) NOT NULL,
  `snap` varchar(50) NOT NULL,
  `observaciones` varchar(150) NOT NULL
) ENGINE=InnoDB DEFAULT CHARSET=utf8mb4 COLLATE=utf8mb4_spanish_ci;

-- --------------------------------------------------------

--
-- Estructura de tabla para la tabla `rol`
--

CREATE TABLE `rol` (
  `id_rol` int(11) NOT NULL,
  `nombre_rol` varchar(80) NOT NULL,
  `estado` tinyint(1) NOT NULL
) ENGINE=InnoDB DEFAULT CHARSET=utf8mb4 COLLATE=utf8mb4_spanish_ci;

-- --------------------------------------------------------

--
-- Estructura de tabla para la tabla `tipo_medicamento`
--

CREATE TABLE `tipo_medicamento` (
  `id_tipo_medicamento` int(11) NOT NULL,
  `nom_tipo_medicamento` varchar(80) NOT NULL,
  `estado` tinyint(1) NOT NULL
) ENGINE=InnoDB DEFAULT CHARSET=utf8mb4 COLLATE=utf8mb4_spanish_ci;

-- --------------------------------------------------------

--
-- Estructura de tabla para la tabla `usuario`
--

CREATE TABLE `usuario` (
  `cedula_usuario` varchar(12) NOT NULL,
  `nombre` varchar(80) NOT NULL,
  `apellido` varchar(80) NOT NULL,
  `correo` varchar(100) NOT NULL,
  `telefono` int(12) NOT NULL,
  `contraseña` varchar(80) NOT NULL,
  `id_rol` int(11) NOT NULL,
  `estado` tinyint(1) NOT NULL
) ENGINE=InnoDB DEFAULT CHARSET=utf8mb4 COLLATE=utf8mb4_spanish_ci;

--
-- Índices para tablas volcadas
--

--
-- Indices de la tabla `alergia`
--
ALTER TABLE `alergia`
  ADD PRIMARY KEY (`id_alergia`);

--
-- Indices de la tabla `alergia_mascota`
--
ALTER TABLE `alergia_mascota`
  ADD PRIMARY KEY (`id_alergia_mascota`),
  ADD KEY `id_alergia` (`id_alergia`),
  ADD KEY `id_mascota` (`id_mascota`);

--
-- Indices de la tabla `anamnesis`
--
ALTER TABLE `anamnesis`
  ADD PRIMARY KEY (`id_anamnesis`),
  ADD UNIQUE KEY `id_consulta` (`id_consulta`);

--
-- Indices de la tabla `cirugia_previa`
--
ALTER TABLE `cirugia_previa`
  ADD PRIMARY KEY (`id_cirugia_previa`),
  ADD KEY `id_mascota` (`id_mascota`);

--
-- Indices de la tabla `cliente`
--
ALTER TABLE `cliente`
  ADD PRIMARY KEY (`cedula_cliente`);

--
-- Indices de la tabla `consulta`
--
ALTER TABLE `consulta`
  ADD PRIMARY KEY (`id_consulta`),
  ADD KEY `cedula_usuario` (`cedula_usuario`),
  ADD KEY `id_mascota` (`id_mascota`);

--
-- Indices de la tabla `diagnostico`
--
ALTER TABLE `diagnostico`
  ADD PRIMARY KEY (`id_diagnostico`),
  ADD KEY `id_patologia` (`id_patologia`),
  ADD KEY `id_consulta` (`id_consulta`);

--
-- Indices de la tabla `enfermedades_sufridas`
--
ALTER TABLE `enfermedades_sufridas`
  ADD PRIMARY KEY (`id_enfermedad_sufrida`),
  ADD KEY `id_mascota` (`id_mascota`),
  ADD KEY `id_patologia` (`id_patologia`);

--
-- Indices de la tabla `especie`
--
ALTER TABLE `especie`
  ADD PRIMARY KEY (`id_especie`);

--
-- Indices de la tabla `examen_clinico`
--
ALTER TABLE `examen_clinico`
  ADD PRIMARY KEY (`id_examen`),
  ADD UNIQUE KEY `id_consulta_2` (`id_consulta`),
  ADD KEY `id_consulta` (`id_consulta`);

--
-- Indices de la tabla `mascota`
--
ALTER TABLE `mascota`
  ADD PRIMARY KEY (`id_mascota`),
  ADD KEY `id_raza` (`id_raza`),
  ADD KEY `cedula_cliente` (`cedula_cliente`);

--
-- Indices de la tabla `medicamento`
--
ALTER TABLE `medicamento`
  ADD PRIMARY KEY (`id_medicamento`),
  ADD KEY `id_tipo_medicamento` (`id_tipo_medicamento`),
  ADD KEY `id_presentacion` (`id_presentacion`);

--
-- Indices de la tabla `patologia`
--
ALTER TABLE `patologia`
  ADD PRIMARY KEY (`id_patologia`);

--
-- Indices de la tabla `plan_sanitario`
--
ALTER TABLE `plan_sanitario`
  ADD PRIMARY KEY (`id_plan`),
  ADD KEY `id_mascota` (`id_mascota`),
  ADD KEY `id_medicamento` (`id_medicamento`);

--
-- Indices de la tabla `presentacion`
--
ALTER TABLE `presentacion`
  ADD PRIMARY KEY (`id_presentacion`);

--
-- Indices de la tabla `raza`
--
ALTER TABLE `raza`
  ADD PRIMARY KEY (`id_raza`),
  ADD KEY `id_especie` (`id_especie`);

--
-- Indices de la tabla `recipe`
--
ALTER TABLE `recipe`
  ADD PRIMARY KEY (`id_recipe`),
  ADD KEY `id_consulta` (`id_consulta`),
  ADD KEY `id_medicamento` (`id_medicamento`);

--
-- Indices de la tabla `resultado_laboratorio`
--
ALTER TABLE `resultado_laboratorio`
  ADD PRIMARY KEY (`id_resultado`),
  ADD KEY `id_consulta` (`id_consulta`),
  ADD KEY `id_consulta_2` (`id_consulta`);

--
-- Indices de la tabla `rol`
--
ALTER TABLE `rol`
  ADD PRIMARY KEY (`id_rol`);

--
-- Indices de la tabla `tipo_medicamento`
--
ALTER TABLE `tipo_medicamento`
  ADD PRIMARY KEY (`id_tipo_medicamento`);

--
-- Indices de la tabla `usuario`
--
ALTER TABLE `usuario`
  ADD PRIMARY KEY (`cedula_usuario`),
  ADD KEY `id_rol` (`id_rol`);

--
-- AUTO_INCREMENT de las tablas volcadas
--

--
-- AUTO_INCREMENT de la tabla `alergia`
--
ALTER TABLE `alergia`
  MODIFY `id_alergia` int(11) NOT NULL AUTO_INCREMENT;

--
-- AUTO_INCREMENT de la tabla `alergia_mascota`
--
ALTER TABLE `alergia_mascota`
  MODIFY `id_alergia_mascota` int(11) NOT NULL AUTO_INCREMENT;

--
-- AUTO_INCREMENT de la tabla `anamnesis`
--
ALTER TABLE `anamnesis`
  MODIFY `id_anamnesis` int(11) NOT NULL AUTO_INCREMENT;

--
-- AUTO_INCREMENT de la tabla `cirugia_previa`
--
ALTER TABLE `cirugia_previa`
  MODIFY `id_cirugia_previa` int(11) NOT NULL AUTO_INCREMENT;

--
-- AUTO_INCREMENT de la tabla `consulta`
--
ALTER TABLE `consulta`
  MODIFY `id_consulta` int(11) NOT NULL AUTO_INCREMENT;

--
-- AUTO_INCREMENT de la tabla `diagnostico`
--
ALTER TABLE `diagnostico`
  MODIFY `id_diagnostico` int(11) NOT NULL AUTO_INCREMENT;

--
-- AUTO_INCREMENT de la tabla `enfermedades_sufridas`
--
ALTER TABLE `enfermedades_sufridas`
  MODIFY `id_enfermedad_sufrida` int(11) NOT NULL AUTO_INCREMENT;

--
-- AUTO_INCREMENT de la tabla `especie`
--
ALTER TABLE `especie`
  MODIFY `id_especie` int(11) NOT NULL AUTO_INCREMENT, AUTO_INCREMENT=2;

--
-- AUTO_INCREMENT de la tabla `examen_clinico`
--
ALTER TABLE `examen_clinico`
  MODIFY `id_examen` int(11) NOT NULL AUTO_INCREMENT;

--
-- AUTO_INCREMENT de la tabla `mascota`
--
ALTER TABLE `mascota`
  MODIFY `id_mascota` int(11) NOT NULL AUTO_INCREMENT, AUTO_INCREMENT=24;

--
-- AUTO_INCREMENT de la tabla `medicamento`
--
ALTER TABLE `medicamento`
  MODIFY `id_medicamento` int(11) NOT NULL AUTO_INCREMENT;

--
-- AUTO_INCREMENT de la tabla `patologia`
--
ALTER TABLE `patologia`
  MODIFY `id_patologia` int(11) NOT NULL AUTO_INCREMENT;

--
-- AUTO_INCREMENT de la tabla `plan_sanitario`
--
ALTER TABLE `plan_sanitario`
  MODIFY `id_plan` int(11) NOT NULL AUTO_INCREMENT;

--
-- AUTO_INCREMENT de la tabla `presentacion`
--
ALTER TABLE `presentacion`
  MODIFY `id_presentacion` int(11) NOT NULL AUTO_INCREMENT;

--
-- AUTO_INCREMENT de la tabla `raza`
--
ALTER TABLE `raza`
  MODIFY `id_raza` int(11) NOT NULL AUTO_INCREMENT, AUTO_INCREMENT=2;

--
-- AUTO_INCREMENT de la tabla `recipe`
--
ALTER TABLE `recipe`
  MODIFY `id_recipe` int(11) NOT NULL AUTO_INCREMENT;

--
-- AUTO_INCREMENT de la tabla `resultado_laboratorio`
--
ALTER TABLE `resultado_laboratorio`
  MODIFY `id_resultado` int(11) NOT NULL AUTO_INCREMENT;

--
-- AUTO_INCREMENT de la tabla `rol`
--
ALTER TABLE `rol`
  MODIFY `id_rol` int(11) NOT NULL AUTO_INCREMENT;

--
-- AUTO_INCREMENT de la tabla `tipo_medicamento`
--
ALTER TABLE `tipo_medicamento`
  MODIFY `id_tipo_medicamento` int(11) NOT NULL AUTO_INCREMENT;

--
-- Restricciones para tablas volcadas
--

--
-- Filtros para la tabla `alergia_mascota`
--
ALTER TABLE `alergia_mascota`
  ADD CONSTRAINT `alergia_mascota_ibfk_1` FOREIGN KEY (`id_alergia`) REFERENCES `alergia` (`id_alergia`),
  ADD CONSTRAINT `alergia_mascota_ibfk_2` FOREIGN KEY (`id_mascota`) REFERENCES `mascota` (`id_mascota`);

--
-- Filtros para la tabla `anamnesis`
--
ALTER TABLE `anamnesis`
  ADD CONSTRAINT `anamnesis_ibfk_1` FOREIGN KEY (`id_consulta`) REFERENCES `consulta` (`id_consulta`);

--
-- Filtros para la tabla `cirugia_previa`
--
ALTER TABLE `cirugia_previa`
  ADD CONSTRAINT `cirugia_previa_ibfk_1` FOREIGN KEY (`id_mascota`) REFERENCES `mascota` (`id_mascota`);

--
-- Filtros para la tabla `consulta`
--
ALTER TABLE `consulta`
  ADD CONSTRAINT `consulta_ibfk_1` FOREIGN KEY (`cedula_usuario`) REFERENCES `usuario` (`cedula_usuario`),
  ADD CONSTRAINT `consulta_ibfk_2` FOREIGN KEY (`id_mascota`) REFERENCES `mascota` (`id_mascota`);

--
-- Filtros para la tabla `diagnostico`
--
ALTER TABLE `diagnostico`
  ADD CONSTRAINT `diagnostico_ibfk_1` FOREIGN KEY (`id_patologia`) REFERENCES `patologia` (`id_patologia`),
  ADD CONSTRAINT `diagnostico_ibfk_2` FOREIGN KEY (`id_consulta`) REFERENCES `consulta` (`id_consulta`);

--
-- Filtros para la tabla `enfermedades_sufridas`
--
ALTER TABLE `enfermedades_sufridas`
  ADD CONSTRAINT `enfermedades_sufridas_ibfk_1` FOREIGN KEY (`id_patologia`) REFERENCES `patologia` (`id_patologia`),
  ADD CONSTRAINT `enfermedades_sufridas_ibfk_2` FOREIGN KEY (`id_mascota`) REFERENCES `mascota` (`id_mascota`);

--
-- Filtros para la tabla `examen_clinico`
--
ALTER TABLE `examen_clinico`
  ADD CONSTRAINT `examen_clinico_ibfk_1` FOREIGN KEY (`id_consulta`) REFERENCES `consulta` (`id_consulta`);

--
-- Filtros para la tabla `mascota`
--
ALTER TABLE `mascota`
  ADD CONSTRAINT `mascota_ibfk_1` FOREIGN KEY (`cedula_cliente`) REFERENCES `cliente` (`cedula_cliente`),
  ADD CONSTRAINT `mascota_ibfk_2` FOREIGN KEY (`id_raza`) REFERENCES `raza` (`id_raza`);

--
-- Filtros para la tabla `medicamento`
--
ALTER TABLE `medicamento`
  ADD CONSTRAINT `medicamento_ibfk_1` FOREIGN KEY (`id_tipo_medicamento`) REFERENCES `tipo_medicamento` (`id_tipo_medicamento`),
  ADD CONSTRAINT `medicamento_ibfk_2` FOREIGN KEY (`id_presentacion`) REFERENCES `presentacion` (`id_presentacion`);

--
-- Filtros para la tabla `plan_sanitario`
--
ALTER TABLE `plan_sanitario`
  ADD CONSTRAINT `plan_sanitario_ibfk_1` FOREIGN KEY (`id_medicamento`) REFERENCES `medicamento` (`id_medicamento`),
  ADD CONSTRAINT `plan_sanitario_ibfk_2` FOREIGN KEY (`id_mascota`) REFERENCES `mascota` (`id_mascota`);

--
-- Filtros para la tabla `raza`
--
ALTER TABLE `raza`
  ADD CONSTRAINT `raza_ibfk_1` FOREIGN KEY (`id_especie`) REFERENCES `especie` (`id_especie`);

--
-- Filtros para la tabla `recipe`
--
ALTER TABLE `recipe`
  ADD CONSTRAINT `recipe_ibfk_1` FOREIGN KEY (`id_recipe`) REFERENCES `consulta` (`id_consulta`),
  ADD CONSTRAINT `recipe_ibfk_2` FOREIGN KEY (`id_medicamento`) REFERENCES `medicamento` (`id_medicamento`);

--
-- Filtros para la tabla `resultado_laboratorio`
--
ALTER TABLE `resultado_laboratorio`
  ADD CONSTRAINT `resultado_laboratorio_ibfk_1` FOREIGN KEY (`id_consulta`) REFERENCES `consulta` (`id_consulta`);

--
-- Filtros para la tabla `usuario`
--
ALTER TABLE `usuario`
  ADD CONSTRAINT `usuario_ibfk_1` FOREIGN KEY (`id_rol`) REFERENCES `rol` (`id_rol`);
COMMIT;

/*!40101 SET CHARACTER_SET_CLIENT=@OLD_CHARACTER_SET_CLIENT */;
/*!40101 SET CHARACTER_SET_RESULTS=@OLD_CHARACTER_SET_RESULTS */;
/*!40101 SET COLLATION_CONNECTION=@OLD_COLLATION_CONNECTION */;
