-- phpMyAdmin SQL Dump
-- version 5.2.1
-- https://www.phpmyadmin.net/
--
-- Servidor: 127.0.0.1
-- Tiempo de generación: 20-11-2023 a las 00:42:50
-- Versión del servidor: 10.4.28-MariaDB
-- Versión de PHP: 8.2.4

SET SQL_MODE = "NO_AUTO_VALUE_ON_ZERO";
START TRANSACTION;
SET time_zone = "+00:00";


/*!40101 SET @OLD_CHARACTER_SET_CLIENT=@@CHARACTER_SET_CLIENT */;
/*!40101 SET @OLD_CHARACTER_SET_RESULTS=@@CHARACTER_SET_RESULTS */;
/*!40101 SET @OLD_COLLATION_CONNECTION=@@COLLATION_CONNECTION */;
/*!40101 SET NAMES utf8mb4 */;

--
-- Base de datos: `prog_tp_lacomanda`
--

-- --------------------------------------------------------

--
-- Estructura de tabla para la tabla `empleados`
--

CREATE TABLE `empleados` (
  `id` int(11) NOT NULL,
  `nombre` varchar(50) NOT NULL,
  `usuario` varchar(50) NOT NULL,
  `clave` varchar(50) NOT NULL,
  `rol` varchar(50) NOT NULL,
  `cant_operaciones` int(11) NOT NULL,
  `estado` varchar(50) NOT NULL,
  `fecha_alta` date NOT NULL,
  `fecha_baja` date DEFAULT NULL
) ENGINE=InnoDB DEFAULT CHARSET=utf8mb4 COLLATE=utf8mb4_general_ci;

--
-- Volcado de datos para la tabla `empleados`
--

INSERT INTO `empleados` (`id`, `nombre`, `usuario`, `clave`, `rol`, `cant_operaciones`, `estado`, `fecha_alta`, `fecha_baja`) VALUES
(1, 'juanfernandez', 'juanFer', 'juan123', 'socio', 0, 'Disponible', '2023-11-18', NULL),
(2, '', '', '', '', 0, '', '2023-11-18', NULL),
(3, 'pepe', 'pepeGonzalez', 'pepe123', 'cocinero', 2, 'De baja', '2023-11-18', '2023-11-19'),
(4, 'Matias', 'matias_15', 'matias123', 'mozo', 10, 'Disponible', '2023-11-19', NULL);

-- --------------------------------------------------------

--
-- Estructura de tabla para la tabla `mesas`
--

CREATE TABLE `mesas` (
  `id` int(11) NOT NULL,
  `codigo_mesa` varchar(50) NOT NULL,
  `estado` varchar(50) NOT NULL,
  `cant_utilizada` int(11) NOT NULL,
  `cant_facturacion` int(11) NOT NULL,
  `mayor_importe` int(11) NOT NULL,
  `menor_importe` int(11) NOT NULL
) ENGINE=InnoDB DEFAULT CHARSET=utf8mb4 COLLATE=utf8mb4_general_ci;

--
-- Volcado de datos para la tabla `mesas`
--

INSERT INTO `mesas` (`id`, `codigo_mesa`, `estado`, `cant_utilizada`, `cant_facturacion`, `mayor_importe`, `menor_importe`) VALUES
(6, 'vcCDkT', 'Con cliente a la espera de pedido', 10, 200000, 50000, 150000);

-- --------------------------------------------------------

--
-- Estructura de tabla para la tabla `pedidos`
--

CREATE TABLE `pedidos` (
  `id` int(11) NOT NULL,
  `codigo_pedido` varchar(50) NOT NULL,
  `estado` varchar(50) NOT NULL,
  `id_empleado` int(11) NOT NULL,
  `id_mesa` int(11) NOT NULL,
  `tiempo_estimado` int(11) NOT NULL,
  `horario_inicio` date DEFAULT NULL,
  `horario_fin` date DEFAULT NULL
) ENGINE=InnoDB DEFAULT CHARSET=utf8mb4 COLLATE=utf8mb4_general_ci;

--
-- Volcado de datos para la tabla `pedidos`
--

INSERT INTO `pedidos` (`id`, `codigo_pedido`, `estado`, `id_empleado`, `id_mesa`, `tiempo_estimado`, `horario_inicio`, `horario_fin`) VALUES
(1, 'JzXJoL', 'En preparacion', 1, 2, 25, NULL, NULL),
(2, 'uyGCZ5', 'En preparacion', 1, 6, 40, NULL, NULL);

-- --------------------------------------------------------

--
-- Estructura de tabla para la tabla `pedido_producto`
--

CREATE TABLE `pedido_producto` (
  `id` int(11) NOT NULL,
  `codigo_mesa` varchar(50) NOT NULL,
  `nombre_cliente` varchar(50) NOT NULL,
  `id_producto` int(11) NOT NULL,
  `estado` varchar(50) NOT NULL,
  `codigo_pedido` varchar(50) NOT NULL
) ENGINE=InnoDB DEFAULT CHARSET=utf8mb4 COLLATE=utf8mb4_general_ci;

--
-- Volcado de datos para la tabla `pedido_producto`
--

INSERT INTO `pedido_producto` (`id`, `codigo_mesa`, `nombre_cliente`, `id_producto`, `estado`, `codigo_pedido`) VALUES
(1, '1', 'jose', 1, 'En preparación', 'JzXJoL'),
(2, '1', 'matias', 2, 'En preparación', 'JzXJoL'),
(3, 'vcCDkT', 'luciana', 2, 'En preparación', 'uyGCZ5'),
(4, 'vcCDkT', 'alberto', 2, 'En preparación', 'uyGCZ5');

-- --------------------------------------------------------

--
-- Estructura de tabla para la tabla `productos`
--

CREATE TABLE `productos` (
  `id` int(11) NOT NULL,
  `nombre` varchar(50) NOT NULL,
  `sector` varchar(50) NOT NULL,
  `precio` int(11) NOT NULL,
  `cant_vendida` int(11) NOT NULL
) ENGINE=InnoDB DEFAULT CHARSET=utf8mb4 COLLATE=utf8mb4_general_ci;

--
-- Volcado de datos para la tabla `productos`
--

INSERT INTO `productos` (`id`, `nombre`, `sector`, `precio`, `cant_vendida`) VALUES
(1, 'cerveza', 'cerveceria', 30, 13),
(2, 'chocolate', 'candybar', 5, 49),
(4, 'cerveza artesanal nueva', 'Barra de choperas', 50, 15);

--
-- Índices para tablas volcadas
--

--
-- Indices de la tabla `empleados`
--
ALTER TABLE `empleados`
  ADD PRIMARY KEY (`id`);

--
-- Indices de la tabla `mesas`
--
ALTER TABLE `mesas`
  ADD PRIMARY KEY (`id`);

--
-- Indices de la tabla `pedidos`
--
ALTER TABLE `pedidos`
  ADD PRIMARY KEY (`id`);

--
-- Indices de la tabla `pedido_producto`
--
ALTER TABLE `pedido_producto`
  ADD PRIMARY KEY (`id`);

--
-- Indices de la tabla `productos`
--
ALTER TABLE `productos`
  ADD PRIMARY KEY (`id`);

--
-- AUTO_INCREMENT de las tablas volcadas
--

--
-- AUTO_INCREMENT de la tabla `empleados`
--
ALTER TABLE `empleados`
  MODIFY `id` int(11) NOT NULL AUTO_INCREMENT, AUTO_INCREMENT=5;

--
-- AUTO_INCREMENT de la tabla `mesas`
--
ALTER TABLE `mesas`
  MODIFY `id` int(11) NOT NULL AUTO_INCREMENT, AUTO_INCREMENT=7;

--
-- AUTO_INCREMENT de la tabla `pedidos`
--
ALTER TABLE `pedidos`
  MODIFY `id` int(11) NOT NULL AUTO_INCREMENT, AUTO_INCREMENT=4;

--
-- AUTO_INCREMENT de la tabla `pedido_producto`
--
ALTER TABLE `pedido_producto`
  MODIFY `id` int(11) NOT NULL AUTO_INCREMENT, AUTO_INCREMENT=7;

--
-- AUTO_INCREMENT de la tabla `productos`
--
ALTER TABLE `productos`
  MODIFY `id` int(11) NOT NULL AUTO_INCREMENT, AUTO_INCREMENT=5;
COMMIT;

/*!40101 SET CHARACTER_SET_CLIENT=@OLD_CHARACTER_SET_CLIENT */;
/*!40101 SET CHARACTER_SET_RESULTS=@OLD_CHARACTER_SET_RESULTS */;
/*!40101 SET COLLATION_CONNECTION=@OLD_COLLATION_CONNECTION */;
