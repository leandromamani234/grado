-- phpMyAdmin SQL Dump
-- version 5.2.0
-- https://www.phpmyadmin.net/
--
-- Servidor: 127.0.0.1
-- Tiempo de generación: 10-05-2025 a las 03:47:48
-- Versión del servidor: 10.4.27-MariaDB
-- Versión de PHP: 8.2.0

SET SQL_MODE = "NO_AUTO_VALUE_ON_ZERO";
START TRANSACTION;
SET time_zone = "+00:00";


/*!40101 SET @OLD_CHARACTER_SET_CLIENT=@@CHARACTER_SET_CLIENT */;
/*!40101 SET @OLD_CHARACTER_SET_RESULTS=@@CHARACTER_SET_RESULTS */;
/*!40101 SET @OLD_COLLATION_CONNECTION=@@COLLATION_CONNECTION */;
/*!40101 SET NAMES utf8mb4 */;

--
-- Base de datos: `proyect1`
--

-- --------------------------------------------------------

--
-- Estructura de tabla para la tabla `deudas`
--

CREATE TABLE `deudas` (
  `id_deuda` int(11) NOT NULL,
  `id_socio` int(11) NOT NULL,
  `monto` decimal(10,2) NOT NULL,
  `fecha_deuda` date NOT NULL,
  `estado` varchar(50) NOT NULL,
  `tipo_deuda` varchar(100) NOT NULL,
  `observaciones` text DEFAULT NULL
) ENGINE=InnoDB DEFAULT CHARSET=utf8mb4 COLLATE=utf8mb4_general_ci;

--
-- Volcado de datos para la tabla `deudas`
--

INSERT INTO `deudas` (`id_deuda`, `id_socio`, `monto`, `fecha_deuda`, `estado`, `tipo_deuda`, `observaciones`) VALUES
(5, 4, '26.00', '2025-04-03', 'Pagado', 'Deuda Acumulada', ''),
(6, 7, '29.00', '2025-02-01', 'Pagado', 'Multa + Consumo', ''),
(7, 15, '30.00', '2025-04-03', 'Anulado', 'Deuda Acumulada', ''),
(8, 7, '30.00', '2025-04-28', 'En Mora', 'Reconexión + Consumo', '');

-- --------------------------------------------------------

--
-- Estructura de tabla para la tabla `lecturas`
--

CREATE TABLE `lecturas` (
  `id_lectura` int(11) NOT NULL,
  `numero_casa` int(11) NOT NULL,
  `lectura_inicial` decimal(10,2) NOT NULL,
  `fecha_lectura` datetime NOT NULL
) ENGINE=InnoDB DEFAULT CHARSET=utf8mb4 COLLATE=utf8mb4_general_ci;

-- --------------------------------------------------------

--
-- Estructura de tabla para la tabla `medidor`
--

CREATE TABLE `medidor` (
  `id_medidor` int(11) NOT NULL,
  `serie` varchar(50) NOT NULL,
  `marca` varchar(50) NOT NULL,
  `lectura_inicial` decimal(10,2) NOT NULL,
  `id_propiedad` int(11) DEFAULT NULL
) ENGINE=InnoDB DEFAULT CHARSET=utf8mb4 COLLATE=utf8mb4_general_ci;

--
-- Volcado de datos para la tabla `medidor`
--

INSERT INTO `medidor` (`id_medidor`, `serie`, `marca`, `lectura_inicial`, `id_propiedad`) VALUES
(22, '34567', 'emelec', '15.00', 10),
(23, '34564', 'emelec', '15.00', 11),
(27, '00066678', 'patito', '229350.00', 12);

-- --------------------------------------------------------

--
-- Estructura de tabla para la tabla `otb`
--

CREATE TABLE `otb` (
  `id_otb` int(11) NOT NULL,
  `nombre` varchar(45) NOT NULL,
  `distrito` varchar(45) DEFAULT NULL,
  `municipio` varchar(45) DEFAULT NULL,
  `consumo_minimo` varchar(255) DEFAULT NULL,
  `cantidad_maxima` decimal(10,2) DEFAULT NULL,
  `cantidad_minima` decimal(10,2) DEFAULT NULL,
  `m3_adicional` decimal(10,2) DEFAULT NULL
) ENGINE=InnoDB DEFAULT CHARSET=utf8mb4 COLLATE=utf8mb4_general_ci;

--
-- Volcado de datos para la tabla `otb`
--

INSERT INTO `otb` (`id_otb`, `nombre`, `distrito`, `municipio`, `consumo_minimo`, `cantidad_maxima`, `cantidad_minima`, `m3_adicional`) VALUES
(1, 'barrio fabril', 'B', 'colcapirhua', '12', '6.00', '2.00', '3.00'),
(7, 'man', 'g', 'colcapirhua', '12', '3.00', '4.00', '0.00'),
(8, 'sausalito', 'norte', 'vinto', '10', '100.00', '5.00', '50.00');

-- --------------------------------------------------------

--
-- Estructura de tabla para la tabla `persona`
--

CREATE TABLE `persona` (
  `id_persona` int(11) NOT NULL,
  `nombre` varchar(45) NOT NULL,
  `primer_apellido` varchar(45) NOT NULL,
  `segundo_apellido` varchar(45) DEFAULT NULL,
  `telefono` varchar(45) DEFAULT NULL,
  `celular` varchar(45) DEFAULT NULL,
  `direccion` varchar(100) DEFAULT NULL,
  `email` varchar(45) DEFAULT NULL,
  `CI` varchar(45) DEFAULT NULL
) ENGINE=InnoDB DEFAULT CHARSET=utf8 COLLATE=utf8_general_ci;

--
-- Volcado de datos para la tabla `persona`
--

INSERT INTO `persona` (`id_persona`, `nombre`, `primer_apellido`, `segundo_apellido`, `telefono`, `celular`, `direccion`, `email`, `CI`) VALUES
(1, 'Manuel', 'vegas', 'dead', '3463466', '46346346', 'Calle enominada', 'manuel@example.com', '658568'),
(2, 'Void', 'Call', 'Moster', '4675768', '467687988', 'Calle enominada', 'void@example.com', '456546'),
(3, 'mentor', 'Balls', 'poder', '3546457', '65678678', 'Calle enominada', 'mentor@example.com', '456546'),
(4, 'franz', 'calani', 'yergo', '4676467', '64758647', 'Calle enominada', 'franz@example.com', '4554678'),
(5, 'Mortal', 'Kombat', 'yeah', '3548675', '68678999', 'Calle ficticia', 'mortal@example.com', '7654321'),
(6, 'laslo', 'alalala', 'alololo', '2345678', '65432345', 'Calle misteriosa', 'laslo@example.com', '9876543'),
(7, 'pepe', 'montaño', 'lol', '3546457', '65678678', 'Calle enominada', 'pepemontao142@gmail.com', '456546'),
(8, 'leandro', '', 'mamani', '51615165', '786767887', 'blanco galindo', 'le1059583@gmail.com', '7867867'),
(9, 'alejandro', 'will', 'mercado', '578685', '79697969', 'calle primero de mayo ', 'alejandrowilss142@gmail.com', '89797'),
(10, 'miguel', 'juaniquina', 'limachi', '546474', '54756886', 'calle primero de mayo ', 'migueljuani142@gmail.com', '68799'),
(11, 'leandro elias', '', 'mamani', '51615165', '62602101', 'blanco galindo', 'le1059583@gmail.com', '9407179'),
(12, 'pancrasio', 'sf', 'fs', '54574', '45654', 'blanco galindo', 'le1059583@gmail.com', '9407179'),
(13, 'Martin', 'Albino', '', '4235476', '77655677', 'Av. Indepenndencia #17', 'maacrazy@gmail.com', '123456'),
(14, 'leandro', 'juquien', 'mamani', '45646', '5647567', 'blanco galindo', 'le1059@gmail.com', '357657'),
(15, 'Marianela', 'Choque', 'Gonsalez', '4652776', '75495546', 'Barrio Fabril', 'leonela.ststrum@gmail.com', '5065446');

-- --------------------------------------------------------

--
-- Estructura de tabla para la tabla `propiedades`
--

CREATE TABLE `propiedades` (
  `id_propiedades` int(11) NOT NULL,
  `manzano` varchar(45) NOT NULL,
  `numero` int(11) NOT NULL,
  `id_socio` int(11) NOT NULL
) ENGINE=InnoDB DEFAULT CHARSET=utf8mb4 COLLATE=utf8mb4_general_ci;

--
-- Volcado de datos para la tabla `propiedades`
--

INSERT INTO `propiedades` (`id_propiedades`, `manzano`, `numero`, `id_socio`) VALUES
(10, 'A', 10, 4),
(11, 'A', 20, 7),
(12, 'C', 31, 15);

-- --------------------------------------------------------

--
-- Estructura de tabla para la tabla `recibos`
--

CREATE TABLE `recibos` (
  `id_recibo` int(11) NOT NULL,
  `id_propiedad` int(11) NOT NULL,
  `fecha_lectura` date NOT NULL,
  `lectura_anterior` decimal(10,2) NOT NULL,
  `lectura_actual` decimal(10,2) NOT NULL,
  `consumo_m3` decimal(10,2) GENERATED ALWAYS AS (`lectura_actual` - `lectura_anterior`) STORED,
  `importe_bs` decimal(10,2) NOT NULL,
  `observaciones` text DEFAULT NULL,
  `numero_serie` varchar(4) NOT NULL
) ENGINE=InnoDB DEFAULT CHARSET=utf8mb4 COLLATE=utf8mb4_general_ci;

--
-- Volcado de datos para la tabla `recibos`
--

INSERT INTO `recibos` (`id_recibo`, `id_propiedad`, `fecha_lectura`, `lectura_anterior`, `lectura_actual`, `importe_bs`, `observaciones`, `numero_serie`) VALUES
(1, 10, '2026-01-03', '15.00', '56.00', '77.00', 'Ninguna', '0001'),
(3, 11, '2025-05-05', '15.00', '16.00', '15.00', 'Ninguna', '0002'),
(4, 10, '2025-05-07', '56.00', '89.00', '61.00', 'Ninguna', '0003'),
(6, 12, '2025-05-01', '0.00', '229365.00', '25.00', 'Ninguna', '0004');

-- --------------------------------------------------------

--
-- Estructura de tabla para la tabla `roles`
--

CREATE TABLE `roles` (
  `id_rol` int(11) NOT NULL,
  `nombre_rol` varchar(50) NOT NULL
) ENGINE=InnoDB DEFAULT CHARSET=utf8mb4 COLLATE=utf8mb4_general_ci;

--
-- Volcado de datos para la tabla `roles`
--

INSERT INTO `roles` (`id_rol`, `nombre_rol`) VALUES
(1, 'admin'),
(3, 'lecturador'),
(2, 'presidente'),
(4, 'tesorera');

-- --------------------------------------------------------

--
-- Estructura de tabla para la tabla `socios`
--

CREATE TABLE `socios` (
  `id_persona` int(11) NOT NULL,
  `matricula` varchar(100) DEFAULT NULL,
  `estado` varchar(45) NOT NULL,
  `id_otb` int(11) NOT NULL
) ENGINE=InnoDB DEFAULT CHARSET=utf8mb4 COLLATE=utf8mb4_general_ci;

--
-- Volcado de datos para la tabla `socios`
--

INSERT INTO `socios` (`id_persona`, `matricula`, `estado`, `id_otb`) VALUES
(4, NULL, 'Activo', 1),
(7, NULL, 'Activo', 1),
(15, NULL, 'Inactivo', 1);

-- --------------------------------------------------------

--
-- Estructura de tabla para la tabla `tokens_recuperacion`
--

CREATE TABLE `tokens_recuperacion` (
  `id` int(11) NOT NULL,
  `id_usuario` int(11) NOT NULL,
  `token` varchar(64) NOT NULL,
  `fecha_creacion` timestamp NOT NULL DEFAULT current_timestamp()
) ENGINE=InnoDB DEFAULT CHARSET=utf8mb4 COLLATE=utf8mb4_general_ci;

--
-- Volcado de datos para la tabla `tokens_recuperacion`
--

INSERT INTO `tokens_recuperacion` (`id`, `id_usuario`, `token`, `fecha_creacion`) VALUES
(1, 35, '9e645b5843a49c34f4b6d1e6d1ca1e6ca5d843de33d2fc6cd879e3f3befa0058', '2025-05-09 23:31:35'),
(2, 35, 'd2d37a4c7b1e7f4dea970556c91a39504a8368879101c10e4ba18215456cd762', '2025-05-09 23:33:08'),
(3, 35, '5caf9d9803fc97cc1a9adccf8616d7767e04776ef6eedc4601cf7b2dff20dd4a', '2025-05-09 23:39:33'),
(4, 35, 'cee4150ffb4ecc6f89305c864e6556d72029404d034a60d69660a08731eaf40b', '2025-05-09 23:41:10'),
(5, 35, '7304682347efb2a1dddf8ceb7fc21cf031c02a255e2c13869781c459db8d1a04', '2025-05-09 23:44:13'),
(8, 35, '521a3a539ee90faa33d15cda5f293b65dc31226301231519fdbb716e1220b1c9', '2025-05-10 00:02:47'),
(9, 35, '58c617a54d1791c0839a9e11fd1593685b0477737ac0d3ae5d45b11d130b712f', '2025-05-10 00:08:55'),
(10, 35, '60dd80fe56b741e885cbb7620e42e0c7e331bca9a7119033224a2715937494d5', '2025-05-10 00:09:58'),
(11, 35, '00dfa661e41469d1e639c4ffde1854348da7bb1f6649a4a459438d839ebdade5', '2025-05-10 00:26:06'),
(14, 35, 'bd72d8e6387da5f373f5181fd2679caa43e894a665b4c624515c67cc59fa99f6', '2025-05-10 01:00:35'),
(15, 35, '2f54827a17079e9936999a5da0145c1f708903e809d5ce6cffbf1288055155d5', '2025-05-10 01:04:20'),
(16, 35, '334e0b80562838bef6250ced9b62890df5997d32c095e8f57976813efeb180cc', '2025-05-10 01:07:30'),
(17, 29, '7447b0fe4db0987df3dc23cad4d57fd873bd99ef409ba31c87ec71774946a76f', '2025-05-10 01:07:53'),
(18, 29, 'c15a2988fe0cee61da1b30f01d1be05d20c3261696ff8b43a25a1e96e223de29', '2025-05-10 01:13:10'),
(19, 35, 'd7ca011afb4dc1d40e4c134d1b58212c13e03baa2e388bdc7ee7338aeda6ff57', '2025-05-10 01:13:59'),
(20, 29, '237a8c7576d9885db5484504776a856c83eab2a9df9ab4137065bef55a4747ab', '2025-05-10 01:15:51'),
(21, 29, '9f574d02ad2f162e2255341283932239302f78f6368c7f18dfeb05ea3fe815cd', '2025-05-10 01:24:36'),
(22, 35, 'e2bb97c178de330559cfc71903e610a7432e340fbf4ae85b1bb46f7a4edf10a4', '2025-05-10 01:28:29'),
(23, 29, '06d23df7e37602be60101f89087632122f807c09f426ad6664d7e5aabb2954db', '2025-05-10 01:34:36');

-- --------------------------------------------------------

--
-- Estructura de tabla para la tabla `usuarios`
--

CREATE TABLE `usuarios` (
  `id_usuario` int(11) NOT NULL,
  `nick` varchar(255) NOT NULL,
  `email` varchar(255) DEFAULT NULL,
  `pass` varchar(255) NOT NULL,
  `persona_id_persona` int(11) NOT NULL
) ENGINE=InnoDB DEFAULT CHARSET=utf8mb4 COLLATE=utf8mb4_general_ci;

--
-- Volcado de datos para la tabla `usuarios`
--

INSERT INTO `usuarios` (`id_usuario`, `nick`, `email`, `pass`, `persona_id_persona`) VALUES
(1, 'admin', NULL, 'admin', 1),
(2, 'moral', NULL, 'moral', 1),
(14, 'admin2', NULL, '$2y$10$71s91Im3dCSbv9THB4uZ.ujKEa.k31jfrOMBmFvqSKzdrMM8x2kSi', 1),
(15, 'admin3', NULL, 'admin3', 2),
(29, 'leandro', 'le1059583@gmail.com', '$2y$10$qLq7NE9b3EF9YBEv.qUI8uO23XOIV7X3uNVpk9478RZw76bK5qokm', 11),
(30, 'tesorera', 'le1059583@gmail.com', '$2y$10$xvDUNwg0WCig5XkoA9OAAOAR4ZJYbGr9m28CMnb4ODIDIrLWRK8kG', 12),
(31, 'presidente', 'le1059583@gmail.com', '$2y$10$6t32miIKwrUkhCi5i3WKRuTRJB3R6Y8BlI2ZXHSx/6VNOQa6i4XpW', 10),
(34, 'lecturador', 'le1059583@gmail.com', '$2y$10$YrVk4YgVwhIeKwpupPomJeYyPKtPGpl7gcr11ICEBUylFoZoeGH7W', 1),
(35, 'escalera', 'escalerabrayan12@gmail.com', '$2y$10$beD0.bSAdLGxb39HbZG6w.faS9RSBwwlyVB7mmXMYh0lwIJWHkD7e', 13);

-- --------------------------------------------------------

--
-- Estructura de tabla para la tabla `usuario_rol`
--

CREATE TABLE `usuario_rol` (
  `id_usuario` int(11) NOT NULL,
  `id_rol` int(11) NOT NULL
) ENGINE=InnoDB DEFAULT CHARSET=utf8mb4 COLLATE=utf8mb4_general_ci;

--
-- Volcado de datos para la tabla `usuario_rol`
--

INSERT INTO `usuario_rol` (`id_usuario`, `id_rol`) VALUES
(29, 1),
(30, 4),
(31, 2),
(34, 3),
(35, 1);

--
-- Índices para tablas volcadas
--

--
-- Indices de la tabla `deudas`
--
ALTER TABLE `deudas`
  ADD PRIMARY KEY (`id_deuda`),
  ADD KEY `fk_deudas_socio` (`id_socio`);

--
-- Indices de la tabla `lecturas`
--
ALTER TABLE `lecturas`
  ADD PRIMARY KEY (`id_lectura`);

--
-- Indices de la tabla `medidor`
--
ALTER TABLE `medidor`
  ADD PRIMARY KEY (`id_medidor`),
  ADD KEY `id_propiedad` (`id_propiedad`);

--
-- Indices de la tabla `otb`
--
ALTER TABLE `otb`
  ADD PRIMARY KEY (`id_otb`);

--
-- Indices de la tabla `persona`
--
ALTER TABLE `persona`
  ADD PRIMARY KEY (`id_persona`);

--
-- Indices de la tabla `propiedades`
--
ALTER TABLE `propiedades`
  ADD PRIMARY KEY (`id_propiedades`),
  ADD KEY `fk_propiedades_socios` (`id_socio`);

--
-- Indices de la tabla `recibos`
--
ALTER TABLE `recibos`
  ADD PRIMARY KEY (`id_recibo`),
  ADD UNIQUE KEY `numero_serie` (`numero_serie`),
  ADD KEY `fk_recibos_propiedad_idx` (`id_propiedad`);

--
-- Indices de la tabla `roles`
--
ALTER TABLE `roles`
  ADD PRIMARY KEY (`id_rol`),
  ADD UNIQUE KEY `nombre_rol` (`nombre_rol`);

--
-- Indices de la tabla `socios`
--
ALTER TABLE `socios`
  ADD PRIMARY KEY (`id_persona`);

--
-- Indices de la tabla `tokens_recuperacion`
--
ALTER TABLE `tokens_recuperacion`
  ADD PRIMARY KEY (`id`),
  ADD KEY `id_usuario` (`id_usuario`);

--
-- Indices de la tabla `usuarios`
--
ALTER TABLE `usuarios`
  ADD PRIMARY KEY (`id_usuario`),
  ADD KEY `persona_id_persona` (`persona_id_persona`);

--
-- Indices de la tabla `usuario_rol`
--
ALTER TABLE `usuario_rol`
  ADD PRIMARY KEY (`id_usuario`,`id_rol`),
  ADD KEY `id_rol` (`id_rol`);

--
-- AUTO_INCREMENT de las tablas volcadas
--

--
-- AUTO_INCREMENT de la tabla `deudas`
--
ALTER TABLE `deudas`
  MODIFY `id_deuda` int(11) NOT NULL AUTO_INCREMENT, AUTO_INCREMENT=9;

--
-- AUTO_INCREMENT de la tabla `lecturas`
--
ALTER TABLE `lecturas`
  MODIFY `id_lectura` int(11) NOT NULL AUTO_INCREMENT;

--
-- AUTO_INCREMENT de la tabla `medidor`
--
ALTER TABLE `medidor`
  MODIFY `id_medidor` int(11) NOT NULL AUTO_INCREMENT, AUTO_INCREMENT=28;

--
-- AUTO_INCREMENT de la tabla `otb`
--
ALTER TABLE `otb`
  MODIFY `id_otb` int(11) NOT NULL AUTO_INCREMENT, AUTO_INCREMENT=9;

--
-- AUTO_INCREMENT de la tabla `persona`
--
ALTER TABLE `persona`
  MODIFY `id_persona` int(11) NOT NULL AUTO_INCREMENT, AUTO_INCREMENT=17;

--
-- AUTO_INCREMENT de la tabla `propiedades`
--
ALTER TABLE `propiedades`
  MODIFY `id_propiedades` int(11) NOT NULL AUTO_INCREMENT, AUTO_INCREMENT=13;

--
-- AUTO_INCREMENT de la tabla `recibos`
--
ALTER TABLE `recibos`
  MODIFY `id_recibo` int(11) NOT NULL AUTO_INCREMENT, AUTO_INCREMENT=7;

--
-- AUTO_INCREMENT de la tabla `roles`
--
ALTER TABLE `roles`
  MODIFY `id_rol` int(11) NOT NULL AUTO_INCREMENT, AUTO_INCREMENT=6;

--
-- AUTO_INCREMENT de la tabla `tokens_recuperacion`
--
ALTER TABLE `tokens_recuperacion`
  MODIFY `id` int(11) NOT NULL AUTO_INCREMENT, AUTO_INCREMENT=25;

--
-- AUTO_INCREMENT de la tabla `usuarios`
--
ALTER TABLE `usuarios`
  MODIFY `id_usuario` int(11) NOT NULL AUTO_INCREMENT, AUTO_INCREMENT=36;

--
-- Restricciones para tablas volcadas
--

--
-- Filtros para la tabla `deudas`
--
ALTER TABLE `deudas`
  ADD CONSTRAINT `fk_deudas_socio` FOREIGN KEY (`id_socio`) REFERENCES `socios` (`id_persona`) ON DELETE CASCADE;

--
-- Filtros para la tabla `medidor`
--
ALTER TABLE `medidor`
  ADD CONSTRAINT `medidor_ibfk_1` FOREIGN KEY (`id_propiedad`) REFERENCES `propiedades` (`id_propiedades`) ON DELETE CASCADE;

--
-- Filtros para la tabla `propiedades`
--
ALTER TABLE `propiedades`
  ADD CONSTRAINT `fk_propiedades_socios` FOREIGN KEY (`id_socio`) REFERENCES `socios` (`id_persona`) ON DELETE CASCADE ON UPDATE CASCADE;

--
-- Filtros para la tabla `recibos`
--
ALTER TABLE `recibos`
  ADD CONSTRAINT `fk_recibos_propiedad` FOREIGN KEY (`id_propiedad`) REFERENCES `propiedades` (`id_propiedades`) ON DELETE CASCADE ON UPDATE CASCADE;

--
-- Filtros para la tabla `tokens_recuperacion`
--
ALTER TABLE `tokens_recuperacion`
  ADD CONSTRAINT `tokens_recuperacion_ibfk_1` FOREIGN KEY (`id_usuario`) REFERENCES `usuarios` (`id_usuario`);

--
-- Filtros para la tabla `usuarios`
--
ALTER TABLE `usuarios`
  ADD CONSTRAINT `usuarios_ibfk_1` FOREIGN KEY (`persona_id_persona`) REFERENCES `persona` (`id_persona`) ON DELETE CASCADE;

--
-- Filtros para la tabla `usuario_rol`
--
ALTER TABLE `usuario_rol`
  ADD CONSTRAINT `usuario_rol_ibfk_1` FOREIGN KEY (`id_usuario`) REFERENCES `usuarios` (`id_usuario`) ON DELETE CASCADE,
  ADD CONSTRAINT `usuario_rol_ibfk_2` FOREIGN KEY (`id_rol`) REFERENCES `roles` (`id_rol`) ON DELETE CASCADE;
COMMIT;

/*!40101 SET CHARACTER_SET_CLIENT=@OLD_CHARACTER_SET_CLIENT */;
/*!40101 SET CHARACTER_SET_RESULTS=@OLD_CHARACTER_SET_RESULTS */;
/*!40101 SET COLLATION_CONNECTION=@OLD_COLLATION_CONNECTION */;
