-- phpMyAdmin SQL Dump
-- version 5.2.1
-- https://www.phpmyadmin.net/
--
-- Servidor: localhost
-- Tiempo de generación: 28-11-2023 a las 22:28:13
-- Versión del servidor: 10.4.28-MariaDB
-- Versión de PHP: 8.1.17

SET SQL_MODE = "NO_AUTO_VALUE_ON_ZERO";
START TRANSACTION;
SET time_zone = "+00:00";


/*!40101 SET @OLD_CHARACTER_SET_CLIENT=@@CHARACTER_SET_CLIENT */;
/*!40101 SET @OLD_CHARACTER_SET_RESULTS=@@CHARACTER_SET_RESULTS */;
/*!40101 SET @OLD_COLLATION_CONNECTION=@@COLLATION_CONNECTION */;
/*!40101 SET NAMES utf8mb4 */;

--
-- Base de datos: `TP_LA_COMANDA`
--

-- --------------------------------------------------------

--
-- Estructura de tabla para la tabla `Comanda`
--

CREATE TABLE `Comanda` (
  `ID` int(11) NOT NULL,
  `Fecha` varchar(10) NOT NULL,
  `Hora` varchar(10) NOT NULL,
  `ID_Mesa` int(11) NOT NULL,
  `ID_Empleado` int(11) NOT NULL,
  `ID_Pedido` int(11) NOT NULL,
  `Pedidos` longtext CHARACTER SET utf8mb4 COLLATE utf8mb4_bin NOT NULL CHECK (json_valid(`Pedidos`)),
  `NombreCliente` varchar(255) DEFAULT NULL,
  `FotoMesa` varchar(255) DEFAULT NULL,
  `Estado` varchar(50) DEFAULT NULL,
  `ClaveMesa` varchar(10) NOT NULL,
  `ClavePedido` varchar(10) NOT NULL,
  `TiempoEstimado` varchar(10) NOT NULL
) ENGINE=InnoDB DEFAULT CHARSET=utf8mb4 COLLATE=utf8mb4_general_ci;

--
-- Volcado de datos para la tabla `Comanda`
--

INSERT INTO `Comanda` (`ID`, `Fecha`, `Hora`, `ID_Mesa`, `ID_Empleado`, `ID_Pedido`, `Pedidos`, `NombreCliente`, `FotoMesa`, `Estado`, `ClaveMesa`, `ClavePedido`, `TiempoEstimado`) VALUES
(13, '2023-11-28', '13:24:30pm', 20, 18, 54, '[{\"Producto\":\"Refresco de Cola\",\"Cantidad\":\"3\",\"Sector\":\"Bartender\",\"Tiempo\":\"00:12\",\"Estado\":\"Listo para servir\"},{\"Producto\":\"Cerveza Corona\",\"Cantidad\":\"5\",\"Sector\":\"Cervecero\",\"Tiempo\":\"00:08\",\"Estado\":\"Listo para servir\"},{\"Producto\":\"Cerveza Artesanal\",\"Cantidad\":\"3\",\"Sector\":\"Cervecero\",\"Tiempo\":\"00:08\",\"Estado\":\"Listo para servir\"},{\"Producto\":\"Milanesa a caballo\",\"Cantidad\":\"2\",\"Sector\":\"Cocinero\",\"Tiempo\":\"00:58\",\"Estado\":\"Listo para servir\"},{\"Producto\":\"Hamburguesa de garbanzo\",\"Cantidad\":\"3\",\"Sector\":\"Cocinero\",\"Tiempo\":\"00:58\",\"Estado\":\"Listo para servir\"}]', 'Rocío', NULL, 'Listo para servir', 'FS1U7', '1EDGQ', '00:58');

-- --------------------------------------------------------

--
-- Estructura de tabla para la tabla `Empleado`
--

CREATE TABLE `Empleado` (
  `ID` int(11) NOT NULL,
  `Nombre` varchar(50) NOT NULL,
  `Apellido` varchar(50) NOT NULL,
  `Sector` varchar(50) DEFAULT NULL,
  `Clave` text NOT NULL,
  `Estado` varchar(50) NOT NULL DEFAULT 'Activo',
  `Operaciones` int(11) NOT NULL DEFAULT 0,
  `FechaAlta` varchar(10) NOT NULL,
  `FechaBaja` varchar(10) DEFAULT NULL
) ENGINE=InnoDB DEFAULT CHARSET=utf8mb4 COLLATE=utf8mb4_general_ci;

--
-- Volcado de datos para la tabla `Empleado`
--

INSERT INTO `Empleado` (`ID`, `Nombre`, `Apellido`, `Sector`, `Clave`, `Estado`, `Operaciones`, `FechaAlta`, `FechaBaja`) VALUES
(1, 'Moe', 'Howard', 'Socio', '$2y$10$ujzmwmXf2l4H2gKuZeU7SOK.6W8gmaffPjFthCH2u2cNsG1qzVlm2', 'Activo', 0, '2023-11-27', NULL),
(2, 'Curly', 'Howard', 'Socio', '$2y$10$VbHCIZWZpGUN9klBz5Dd6Oh/9FPAAhoLBtS7HDe94EBCfChnuB6EO', 'Activo', 0, '2023-11-27', NULL),
(3, 'Larry', 'Fine', 'Socio', '$2y$10$2fUmNkZ/06tYR9YRKDeizu/wKgSBfnKAcrurSf.zGhIi2KVelW6Wq', 'Activo', 0, '2023-11-27', NULL),
(17, 'Chuck', 'Norris', 'Bartender', '$2y$10$irQW/VXdN6cWWjjse.c8iuHse2j4HM8DBHYGNw.6nNRl0/.YV4tQK', 'Activo', 7, '2023-11-27', NULL),
(18, 'Max', 'Payne', 'Mozo', '$2y$10$vGjSvKtUHualZWSX6Fu33OCUHY64scvkQbPHoO0lFjnsy1XHZzlVi', 'Activo', 7, '2023-11-27', NULL),
(19, 'Clint', 'Eastwood', 'Cocinero', '$2y$10$PoIT/.quGcP0//EqaOa/PedLpz4uOAgIJojjYsQKsrdtVGDDdzs8i', 'Activo', 45, '2023-11-27', NULL),
(24, 'Nikola', 'Tesla', 'Mozo', '$2y$10$XqHJbcSWcmQB5pBKGnMjle85YoosrxDipLky.Ay/W1upY8maINygG', 'Activo', 2, '2023-11-27', NULL),
(30, 'Tom', 'York', 'Cervecero', '$2y$10$T/Y3gGFhPBS0tpy.MNtNP.SHPAiS2M6212.4tAJ50zeH1qW.7YIi6', 'Activo', 7, '2023-11-27', NULL);

-- --------------------------------------------------------

--
-- Estructura de tabla para la tabla `Encuesta`
--

CREATE TABLE `Encuesta` (
  `ID` int(11) NOT NULL,
  `ID_Comanda` int(11) NOT NULL,
  `PuntuacionMesa` int(11) NOT NULL,
  `PuntuacionMozo` int(11) NOT NULL,
  `PuntuacionCocinero` int(11) NOT NULL,
  `PuntuacionRestaurante` int(11) NOT NULL,
  `Fecha` varchar(10) NOT NULL,
  `Comentarios` text NOT NULL
) ENGINE=InnoDB DEFAULT CHARSET=utf8mb4 COLLATE=utf8mb4_general_ci;

-- --------------------------------------------------------

--
-- Estructura de tabla para la tabla `Mesa`
--

CREATE TABLE `Mesa` (
  `ID` int(11) NOT NULL,
  `ID_Pedido` int(11) DEFAULT NULL,
  `Estado` varchar(50) NOT NULL,
  `ID_Empleado` int(11) DEFAULT NULL,
  `Cliente` varchar(50) DEFAULT NULL,
  `CodigoUnico` varchar(5) DEFAULT NULL
) ENGINE=InnoDB DEFAULT CHARSET=utf8mb4 COLLATE=utf8mb4_general_ci;

--
-- Volcado de datos para la tabla `Mesa`
--

INSERT INTO `Mesa` (`ID`, `ID_Pedido`, `Estado`, `ID_Empleado`, `Cliente`, `CodigoUnico`) VALUES
(20, 54, 'Con cliente esperando pedido', 18, 'Rocío', 'FS1U7'),
(21, NULL, 'Libre', NULL, NULL, NULL),
(22, NULL, 'Libre', NULL, NULL, NULL),
(23, 56, 'Con cliente esperando pedido', 24, 'Santiago', '7PUQA'),
(24, NULL, 'Libre', NULL, NULL, NULL),
(25, 55, 'Con cliente esperando pedido', 18, 'Gabriela', 'I7VSF'),
(26, 65, 'Con cliente esperando pedido', 18, 'Marcela', '78C0G'),
(27, 66, 'Con cliente esperando pedido', 18, 'Adriana', 'ZRPGL'),
(28, 67, 'Con cliente esperando pedido', 24, 'Rocío', 'WECJV'),
(29, NULL, 'Libre', NULL, NULL, NULL),
(30, NULL, 'Libre', NULL, NULL, NULL),
(31, NULL, 'Libre', NULL, NULL, NULL);

-- --------------------------------------------------------

--
-- Estructura de tabla para la tabla `Pedido`
--

CREATE TABLE `Pedido` (
  `ID` int(11) NOT NULL,
  `Productos` text NOT NULL,
  `ID_Mesa` int(11) NOT NULL,
  `CodigoUnico` varchar(5) NOT NULL,
  `Estado` varchar(50) NOT NULL DEFAULT 'Pedido',
  `TiempoEstimado` varchar(5) NOT NULL,
  `ValorTotal` float(10,2) NOT NULL
) ENGINE=InnoDB DEFAULT CHARSET=utf8mb4 COLLATE=utf8mb4_general_ci;

--
-- Volcado de datos para la tabla `Pedido`
--

INSERT INTO `Pedido` (`ID`, `Productos`, `ID_Mesa`, `CodigoUnico`, `Estado`, `TiempoEstimado`, `ValorTotal`) VALUES
(54, '[{\"Producto\":\"Refresco de Cola\",\"Cantidad\":\"3\",\"Sector\":\"Bartender\",\"Tiempo\":\"00:15\",\"Estado\":\"En preparacion\"},{\"Producto\":\"Cerveza Corona\",\"Cantidad\":\"5\",\"Sector\":\"Cervecero\",\"Tiempo\":\"00:08\",\"Estado\":\"Entregado\"},{\"Producto\":\"Cerveza Artesanal\",\"Cantidad\":\"3\",\"Sector\":\"Cervecero\",\"Tiempo\":\"00:08\",\"Estado\":\"Entregado\"},{\"Producto\":\"Milanesa a caballo\",\"Cantidad\":\"2\",\"Sector\":\"Cocinero\",\"Tiempo\":\"00:54\",\"Estado\":\"En preparacion\"},{\"Producto\":\"Hamburguesa de garbanzo\",\"Cantidad\":\"3\",\"Sector\":\"Cocinero\",\"Tiempo\":\"00:54\",\"Estado\":\"En preparacion\"}]', 20, '1EDGQ', 'En preparacion', '00:54', 3250.00),
(55, '[{\"Producto\":\"Refresco de Cola\",\"Cantidad\":\"3\",\"Sector\":\"Bartender\",\"Tiempo\":\"00:00\",\"Estado\":\"Pedido\"},{\"Producto\":\"Cerveza Corona\",\"Cantidad\":\"5\",\"Sector\":\"Cervecero\",\"Tiempo\":\"00:00\",\"Estado\":\"Pedido\"},{\"Producto\":\"Cerveza Artesanal\",\"Cantidad\":\"3\",\"Sector\":\"Cervecero\",\"Tiempo\":\"00:00\",\"Estado\":\"Pedido\"},{\"Producto\":\"Milanesa a caballo\",\"Cantidad\":\"2\",\"Sector\":\"Cocinero\",\"Tiempo\":\"00:40\",\"Estado\":\"Pedido\"},{\"Producto\":\"Hamburguesa de garbanzo\",\"Cantidad\":\"3\",\"Sector\":\"Cocinero\",\"Tiempo\":\"00:40\",\"Estado\":\"Pedido\"}]', 23, '8UNPZ', 'Pedido', '00:40', 3250.00),
(56, '[{\"Producto\":\"Refresco de Cola\",\"Cantidad\":\"3\",\"Sector\":\"Bartender\",\"Tiempo\":\"00:00\",\"Estado\":\"Pedido\"},{\"Producto\":\"Cerveza Corona\",\"Cantidad\":\"5\",\"Sector\":\"Cervecero\",\"Tiempo\":\"00:00\",\"Estado\":\"Pedido\"},{\"Producto\":\"Cerveza Artesanal\",\"Cantidad\":\"3\",\"Sector\":\"Cervecero\",\"Tiempo\":\"00:00\",\"Estado\":\"Pedido\"},{\"Producto\":\"Milanesa a caballo\",\"Cantidad\":\"2\",\"Sector\":\"Cocinero\",\"Tiempo\":\"00:40\",\"Estado\":\"Pedido\"},{\"Producto\":\"Hamburguesa de garbanzo\",\"Cantidad\":\"3\",\"Sector\":\"Cocinero\",\"Tiempo\":\"00:40\",\"Estado\":\"Pedido\"}]', 25, 'PZPD7', 'Pedido', '00:40', 3250.00),
(57, '[{\"Producto\":\"Refresco de Cola\",\"Cantidad\":\"3\",\"Sector\":\"Bartender\",\"Tiempo\":\"00:00\",\"Estado\":\"Pedido\"},{\"Producto\":\"Cerveza Corona\",\"Cantidad\":\"5\",\"Sector\":\"Cervecero\",\"Tiempo\":\"00:00\",\"Estado\":\"Pedido\"},{\"Producto\":\"Cerveza Artesanal\",\"Cantidad\":\"3\",\"Sector\":\"Cervecero\",\"Tiempo\":\"00:00\",\"Estado\":\"Pedido\"},{\"Producto\":\"Milanesa a caballo\",\"Cantidad\":\"2\",\"Sector\":\"Cocinero\",\"Tiempo\":\"00:00\",\"Estado\":\"Pedido\"},{\"Producto\":\"Hamburguesa de garbanzo\",\"Cantidad\":\"3\",\"Sector\":\"Cocinero\",\"Tiempo\":\"00:00\",\"Estado\":\"Pedido\"}]', 25, 'P42P2', 'Pedido', '00:00', 3250.00),
(58, '[{\"Producto\":\"Refresco de Cola\",\"Cantidad\":\"3\",\"Sector\":\"Bartender\",\"Tiempo\":\"00:00\",\"Estado\":\"Pedido\"},{\"Producto\":\"Cerveza Corona\",\"Cantidad\":\"5\",\"Sector\":\"Cervecero\",\"Tiempo\":\"00:00\",\"Estado\":\"Pedido\"},{\"Producto\":\"Cerveza Artesanal\",\"Cantidad\":\"3\",\"Sector\":\"Cervecero\",\"Tiempo\":\"00:00\",\"Estado\":\"Pedido\"},{\"Producto\":\"Milanesa a caballo\",\"Cantidad\":\"2\",\"Sector\":\"Cocinero\",\"Tiempo\":\"00:00\",\"Estado\":\"Pedido\"},{\"Producto\":\"Hamburguesa de garbanzo\",\"Cantidad\":\"3\",\"Sector\":\"Cocinero\",\"Tiempo\":\"00:00\",\"Estado\":\"Pedido\"}]', 25, 'SJJR7', 'Pedido', '00:00', 3250.00),
(59, '[{\"Producto\":\"Refresco de Cola\",\"Cantidad\":\"3\",\"Sector\":\"Bartender\",\"Tiempo\":\"00:00\",\"Estado\":\"Pedido\"},{\"Producto\":\"Cerveza Corona\",\"Cantidad\":\"5\",\"Sector\":\"Cervecero\",\"Tiempo\":\"00:00\",\"Estado\":\"Pedido\"},{\"Producto\":\"Cerveza Artesanal\",\"Cantidad\":\"3\",\"Sector\":\"Cervecero\",\"Tiempo\":\"00:00\",\"Estado\":\"Pedido\"},{\"Producto\":\"Milanesa a caballo\",\"Cantidad\":\"2\",\"Sector\":\"Cocinero\",\"Tiempo\":\"00:00\",\"Estado\":\"Pedido\"},{\"Producto\":\"Hamburguesa de garbanzo\",\"Cantidad\":\"3\",\"Sector\":\"Cocinero\",\"Tiempo\":\"00:00\",\"Estado\":\"Pedido\"}]', 25, 'VMLF7', 'Pedido', '00:00', 3250.00),
(60, '[{\"Producto\":\"Refresco de Cola\",\"Cantidad\":\"3\",\"Sector\":\"Bartender\",\"Tiempo\":\"00:00\",\"Estado\":\"Pedido\"},{\"Producto\":\"Cerveza Corona\",\"Cantidad\":\"5\",\"Sector\":\"Cervecero\",\"Tiempo\":\"00:00\",\"Estado\":\"Pedido\"},{\"Producto\":\"Cerveza Artesanal\",\"Cantidad\":\"3\",\"Sector\":\"Cervecero\",\"Tiempo\":\"00:00\",\"Estado\":\"Pedido\"},{\"Producto\":\"Milanesa a caballo\",\"Cantidad\":\"2\",\"Sector\":\"Cocinero\",\"Tiempo\":\"00:00\",\"Estado\":\"Pedido\"},{\"Producto\":\"Hamburguesa de garbanzo\",\"Cantidad\":\"3\",\"Sector\":\"Cocinero\",\"Tiempo\":\"00:00\",\"Estado\":\"Pedido\"}]', 25, '87CMJ', 'Pedido', '00:00', 3250.00),
(61, '[{\"Producto\":\"Refresco de Cola\",\"Cantidad\":\"3\",\"Sector\":\"Bartender\",\"Tiempo\":\"00:00\",\"Estado\":\"Pedido\"},{\"Producto\":\"Cerveza Corona\",\"Cantidad\":\"5\",\"Sector\":\"Cervecero\",\"Tiempo\":\"00:00\",\"Estado\":\"Pedido\"},{\"Producto\":\"Cerveza Artesanal\",\"Cantidad\":\"3\",\"Sector\":\"Cervecero\",\"Tiempo\":\"00:00\",\"Estado\":\"Pedido\"},{\"Producto\":\"Milanesa a caballo\",\"Cantidad\":\"2\",\"Sector\":\"Cocinero\",\"Tiempo\":\"00:00\",\"Estado\":\"Pedido\"},{\"Producto\":\"Hamburguesa de garbanzo\",\"Cantidad\":\"3\",\"Sector\":\"Cocinero\",\"Tiempo\":\"00:00\",\"Estado\":\"Pedido\"}]', 25, 'SC86A', 'Pedido', '00:00', 3250.00),
(62, '[{\"Producto\":\"Refresco de Cola\",\"Cantidad\":\"3\",\"Sector\":\"Bartender\",\"Tiempo\":\"00:00\",\"Estado\":\"Pedido\"},{\"Producto\":\"Cerveza Corona\",\"Cantidad\":\"5\",\"Sector\":\"Cervecero\",\"Tiempo\":\"00:00\",\"Estado\":\"Pedido\"},{\"Producto\":\"Cerveza Artesanal\",\"Cantidad\":\"3\",\"Sector\":\"Cervecero\",\"Tiempo\":\"00:00\",\"Estado\":\"Pedido\"},{\"Producto\":\"Milanesa a caballo\",\"Cantidad\":\"2\",\"Sector\":\"Cocinero\",\"Tiempo\":\"00:00\",\"Estado\":\"Pedido\"},{\"Producto\":\"Hamburguesa de garbanzo\",\"Cantidad\":\"3\",\"Sector\":\"Cocinero\",\"Tiempo\":\"00:00\",\"Estado\":\"Pedido\"}]', 25, 'LLA05', 'Pedido', '00:00', 3250.00),
(63, '[{\"Producto\":\"Refresco de Cola\",\"Cantidad\":\"3\",\"Sector\":\"Bartender\",\"Tiempo\":\"00:00\",\"Estado\":\"Pedido\"},{\"Producto\":\"Cerveza Corona\",\"Cantidad\":\"5\",\"Sector\":\"Cervecero\",\"Tiempo\":\"00:00\",\"Estado\":\"Pedido\"},{\"Producto\":\"Cerveza Artesanal\",\"Cantidad\":\"3\",\"Sector\":\"Cervecero\",\"Tiempo\":\"00:00\",\"Estado\":\"Pedido\"},{\"Producto\":\"Milanesa a caballo\",\"Cantidad\":\"2\",\"Sector\":\"Cocinero\",\"Tiempo\":\"00:00\",\"Estado\":\"Pedido\"},{\"Producto\":\"Hamburguesa de garbanzo\",\"Cantidad\":\"3\",\"Sector\":\"Cocinero\",\"Tiempo\":\"00:00\",\"Estado\":\"Pedido\"}]', 25, '4IU2A', 'Pedido', '00:00', 3250.00),
(64, '[{\"Producto\":\"Refresco de Cola\",\"Cantidad\":\"3\",\"Sector\":\"Bartender\",\"Tiempo\":\"00:00\",\"Estado\":\"Pedido\"},{\"Producto\":\"Cerveza Corona\",\"Cantidad\":\"5\",\"Sector\":\"Cervecero\",\"Tiempo\":\"00:00\",\"Estado\":\"Pedido\"},{\"Producto\":\"Cerveza Artesanal\",\"Cantidad\":\"3\",\"Sector\":\"Cervecero\",\"Tiempo\":\"00:00\",\"Estado\":\"Pedido\"},{\"Producto\":\"Milanesa a caballo\",\"Cantidad\":\"2\",\"Sector\":\"Cocinero\",\"Tiempo\":\"00:00\",\"Estado\":\"Pedido\"},{\"Producto\":\"Hamburguesa de garbanzo\",\"Cantidad\":\"3\",\"Sector\":\"Cocinero\",\"Tiempo\":\"00:00\",\"Estado\":\"Pedido\"}]', 25, 'N2EVR', 'Pedido', '00:00', 3250.00),
(65, '[{\"Producto\":\"Refresco de Cola\",\"Cantidad\":\"3\",\"Sector\":\"Bartender\",\"Tiempo\":\"00:00\",\"Estado\":\"Pedido\"},{\"Producto\":\"Cerveza Corona\",\"Cantidad\":\"5\",\"Sector\":\"Cervecero\",\"Tiempo\":\"00:00\",\"Estado\":\"Pedido\"},{\"Producto\":\"Cerveza Artesanal\",\"Cantidad\":\"3\",\"Sector\":\"Cervecero\",\"Tiempo\":\"00:00\",\"Estado\":\"Pedido\"},{\"Producto\":\"Milanesa a caballo\",\"Cantidad\":\"2\",\"Sector\":\"Cocinero\",\"Tiempo\":\"00:00\",\"Estado\":\"Pedido\"},{\"Producto\":\"Hamburguesa de garbanzo\",\"Cantidad\":\"3\",\"Sector\":\"Cocinero\",\"Tiempo\":\"00:00\",\"Estado\":\"Pedido\"}]', 26, 'NGOZZ', 'Pedido', '00:00', 3250.00),
(66, '[{\"Producto\":\"Refresco de Cola\",\"Cantidad\":\"3\",\"Sector\":\"Bartender\",\"Tiempo\":\"00:00\",\"Estado\":\"Pedido\"},{\"Producto\":\"Cerveza Corona\",\"Cantidad\":\"5\",\"Sector\":\"Cervecero\",\"Tiempo\":\"00:00\",\"Estado\":\"Pedido\"},{\"Producto\":\"Cerveza Artesanal\",\"Cantidad\":\"3\",\"Sector\":\"Cervecero\",\"Tiempo\":\"00:00\",\"Estado\":\"Pedido\"},{\"Producto\":\"Milanesa a caballo\",\"Cantidad\":\"2\",\"Sector\":\"Cocinero\",\"Tiempo\":\"00:00\",\"Estado\":\"Pedido\"},{\"Producto\":\"Hamburguesa de garbanzo\",\"Cantidad\":\"3\",\"Sector\":\"Cocinero\",\"Tiempo\":\"00:00\",\"Estado\":\"Pedido\"}]', 26, '1HJ60', 'Pedido', '00:00', 3250.00),
(67, '[{\"Producto\":\"Refresco de Cola\",\"Cantidad\":\"3\",\"Sector\":\"Bartender\",\"Tiempo\":\"00:00\",\"Estado\":\"Pedido\"},{\"Producto\":\"Cerveza Corona\",\"Cantidad\":\"5\",\"Sector\":\"Cervecero\",\"Tiempo\":\"00:00\",\"Estado\":\"Pedido\"},{\"Producto\":\"Cerveza Artesanal\",\"Cantidad\":\"3\",\"Sector\":\"Cervecero\",\"Tiempo\":\"00:00\",\"Estado\":\"Pedido\"},{\"Producto\":\"Milanesa a caballo\",\"Cantidad\":\"2\",\"Sector\":\"Cocinero\",\"Tiempo\":\"00:00\",\"Estado\":\"Pedido\"},{\"Producto\":\"Hamburguesa de garbanzo\",\"Cantidad\":\"3\",\"Sector\":\"Cocinero\",\"Tiempo\":\"00:00\",\"Estado\":\"Pedido\"}]', 27, 'OEV23', 'Pedido', '00:00', 3250.00),
(68, '[{\"Producto\":\"Refresco de Cola\",\"Cantidad\":\"3\",\"Sector\":\"Bartender\",\"Tiempo\":\"00:00\",\"Estado\":\"Pedido\"},{\"Producto\":\"Cerveza Corona\",\"Cantidad\":\"5\",\"Sector\":\"Cervecero\",\"Tiempo\":\"00:00\",\"Estado\":\"Pedido\"},{\"Producto\":\"Cerveza Artesanal\",\"Cantidad\":\"3\",\"Sector\":\"Cervecero\",\"Tiempo\":\"00:00\",\"Estado\":\"Pedido\"},{\"Producto\":\"Milanesa a caballo\",\"Cantidad\":\"2\",\"Sector\":\"Cocinero\",\"Tiempo\":\"00:00\",\"Estado\":\"Pedido\"},{\"Producto\":\"Hamburguesa de garbanzo\",\"Cantidad\":\"3\",\"Sector\":\"Cocinero\",\"Tiempo\":\"00:00\",\"Estado\":\"Pedido\"}]', 28, 'F2IBJ', 'Pedido', '00:00', 3250.00),
(69, '[{\"Producto\":\"Refresco de Cola\",\"Cantidad\":\"3\",\"Sector\":\"Bartender\",\"Tiempo\":\"00:00\",\"Estado\":\"Pedido\"},{\"Producto\":\"Cerveza Corona\",\"Cantidad\":\"5\",\"Sector\":\"Cervecero\",\"Tiempo\":\"00:00\",\"Estado\":\"Pedido\"},{\"Producto\":\"Cerveza Artesanal\",\"Cantidad\":\"3\",\"Sector\":\"Cervecero\",\"Tiempo\":\"00:00\",\"Estado\":\"Pedido\"},{\"Producto\":\"Milanesa a caballo\",\"Cantidad\":\"2\",\"Sector\":\"Cocinero\",\"Tiempo\":\"00:00\",\"Estado\":\"Pedido\"},{\"Producto\":\"Hamburguesa de garbanzo\",\"Cantidad\":\"3\",\"Sector\":\"Cocinero\",\"Tiempo\":\"00:00\",\"Estado\":\"Pedido\"}]', 29, 'T3AQM', 'Pedido', '00:00', 3250.00),
(70, '[{\"Producto\":\"Refresco de Cola\",\"Cantidad\":\"3\",\"Sector\":\"Bartender\",\"Tiempo\":\"00:00\",\"Estado\":\"Pedido\"},{\"Producto\":\"Cerveza Corona\",\"Cantidad\":\"5\",\"Sector\":\"Cervecero\",\"Tiempo\":\"00:00\",\"Estado\":\"Pedido\"},{\"Producto\":\"Cerveza Artesanal\",\"Cantidad\":\"3\",\"Sector\":\"Cervecero\",\"Tiempo\":\"00:00\",\"Estado\":\"Pedido\"},{\"Producto\":\"Milanesa a caballo\",\"Cantidad\":\"2\",\"Sector\":\"Cocinero\",\"Tiempo\":\"00:00\",\"Estado\":\"Pedido\"},{\"Producto\":\"Hamburguesa de garbanzo\",\"Cantidad\":\"3\",\"Sector\":\"Cocinero\",\"Tiempo\":\"00:00\",\"Estado\":\"Pedido\"}]', 30, '9TA1M', 'Pedido', '00:00', 3250.00),
(71, '[{\"Producto\":\"Refresco de Cola\",\"Cantidad\":\"3\",\"Sector\":\"Bartender\",\"Tiempo\":\"00:00\",\"Estado\":\"Pedido\"},{\"Producto\":\"Cerveza Corona\",\"Cantidad\":\"5\",\"Sector\":\"Cervecero\",\"Tiempo\":\"00:00\",\"Estado\":\"Pedido\"},{\"Producto\":\"Cerveza Artesanal\",\"Cantidad\":\"3\",\"Sector\":\"Cervecero\",\"Tiempo\":\"00:00\",\"Estado\":\"Pedido\"},{\"Producto\":\"Milanesa a caballo\",\"Cantidad\":\"2\",\"Sector\":\"Cocinero\",\"Tiempo\":\"00:00\",\"Estado\":\"Pedido\"},{\"Producto\":\"Hamburguesa de garbanzo\",\"Cantidad\":\"3\",\"Sector\":\"Cocinero\",\"Tiempo\":\"00:00\",\"Estado\":\"Pedido\"}]', 31, 'P7DJ8', 'Pedido', '00:00', 3250.00);

-- --------------------------------------------------------

--
-- Estructura de tabla para la tabla `Producto`
--

CREATE TABLE `Producto` (
  `ID` int(11) NOT NULL,
  `Nombre` varchar(255) NOT NULL,
  `Cantidad` int(11) NOT NULL,
  `Precio` float(10,2) NOT NULL,
  `Tiempo` varchar(10) NOT NULL,
  `Sector` varchar(50) NOT NULL
) ENGINE=InnoDB DEFAULT CHARSET=utf8mb4 COLLATE=utf8mb4_general_ci;

--
-- Volcado de datos para la tabla `Producto`
--

INSERT INTO `Producto` (`ID`, `Nombre`, `Cantidad`, `Precio`, `Tiempo`, `Sector`) VALUES
(10, 'Hamburguesa', 284, 950.00, '00:15', 'Cocinero'),
(11, 'Pizza Margarita', 2054, 1200.00, '00:25', 'Cocinero'),
(12, 'Ensalada César', 802, 800.00, '00:10', 'Cocinero'),
(13, 'Pasta Alfredo', 180, 1000.00, '00:20', 'Cocinero'),
(14, 'Sushi Variado', 120, 1500.00, '00:30', 'Cocinero'),
(15, 'Refresco de Cola', -3, 150.00, '00:00', 'Bartender'),
(16, 'Cerveza Artesanal', -3, 300.00, '00:00', 'Cervecero'),
(17, 'Agua Mineral', 600, 50.00, '00:00', 'Bartender'),
(18, 'Polenta con salsa', 624, 350.00, '00:30', 'Cocinero'),
(19, 'Milanesa a caballo', -2, 1300.00, '00:40', 'Cocinero'),
(20, 'Hamburguesa de garbanzo', -3, 800.00, '00:10', 'Cocinero'),
(21, 'Daikiri', 303, 600.00, '00:05', 'Bartender'),
(22, 'Cerveza Corona', -5, 700.00, '00:00', 'Cervecero'),
(60, 'Tacos de Carnitas', 456, 120.00, '00:10', 'Cocinero\n'),
(61, 'Sopa de Tomate', 789, 150.00, '00:15', 'Cocinero\n'),
(62, 'Filete de Salmón', 101, 850.00, '00:20', 'Cocinero\n'),
(63, 'Risotto de Champiñones', 204, 700.00, '00:18', 'Cocinero\n'),
(64, 'Pato a la Naranja', 308, 1200.00, '00:25', 'Cocinero\n'),
(65, 'Mojito', 567, 180.00, '00:05', 'Bartender\n'),
(66, 'Vino Tinto Reserva', 902, 250.00, '00:00', 'Bartender\n'),
(67, 'Agua con Gas', 345, 30.00, '00:00', 'Bartender\n'),
(68, 'Tarta de Chocolate', 678, 200.00, '00:12', 'Cocinero\n'),
(69, 'Tiramisú', 987, 180.00, '00:10', 'Cocinero\n'),
(70, 'Pizza BBQ', 111, 1100.00, '00:22', 'Cocinero\n'),
(71, 'Wrap de Pollo', 222, 450.00, '00:15', 'Cocinero\n'),
(72, 'Helado de Vainilla', 333, 60.00, '00:05', 'Cocinero\n'),
(73, 'Cóctel de Frutas', 444, 120.00, '00:08', 'Bartender\n'),
(74, 'Té Verde', 555, 40.00, '00:02', 'Bartender\n'),
(75, 'Nachos con Queso', 666, 180.00, '00:12', 'Cocinero\n'),
(76, 'Burrito de Vegetales', 777, 550.00, '00:18', 'Cocinero\n'),
(77, 'Tacos de Carnitas', 456, 120.00, '00:10', 'Cocinero\n'),
(78, 'Sopa de Tomate', 789, 150.00, '00:15', 'Cocinero\n'),
(79, 'Filete de Salmón', 101, 850.00, '00:20', 'Cocinero\n'),
(80, 'Risotto de Champiñones', 204, 700.00, '00:18', 'Cocinero\n'),
(81, 'Pato a la Naranja', 308, 1200.00, '00:25', 'Cocinero\n'),
(82, 'Mojito', 567, 180.00, '00:05', 'Bartender\n'),
(83, 'Vino Tinto Reserva', 902, 250.00, '00:00', 'Bartender\n'),
(84, 'Agua con Gas', 345, 30.00, '00:00', 'Bartender\n'),
(85, 'Tarta de Chocolate', 678, 200.00, '00:12', 'Cocinero\n'),
(86, 'Tiramisú', 987, 180.00, '00:10', 'Cocinero\n'),
(87, 'Pizza BBQ', 111, 1100.00, '00:22', 'Cocinero\n'),
(88, 'Wrap de Pollo', 222, 450.00, '00:15', 'Cocinero\n'),
(89, 'Helado de Vainilla', 333, 60.00, '00:05', 'Cocinero\n'),
(90, 'Cóctel de Frutas', 444, 120.00, '00:08', 'Bartender\n'),
(91, 'Té Verde', 555, 40.00, '00:02', 'Bartender\n'),
(92, 'Nachos con Queso', 666, 180.00, '00:12', 'Cocinero\n'),
(93, 'Burrito de Vegetales', 777, 550.00, '00:18', 'Cocinero\n');

--
-- Índices para tablas volcadas
--

--
-- Indices de la tabla `Comanda`
--
ALTER TABLE `Comanda`
  ADD PRIMARY KEY (`ID`);

--
-- Indices de la tabla `Empleado`
--
ALTER TABLE `Empleado`
  ADD PRIMARY KEY (`ID`);

--
-- Indices de la tabla `Encuesta`
--
ALTER TABLE `Encuesta`
  ADD PRIMARY KEY (`ID`);

--
-- Indices de la tabla `Mesa`
--
ALTER TABLE `Mesa`
  ADD PRIMARY KEY (`ID`);

--
-- Indices de la tabla `Pedido`
--
ALTER TABLE `Pedido`
  ADD PRIMARY KEY (`ID`);

--
-- Indices de la tabla `Producto`
--
ALTER TABLE `Producto`
  ADD PRIMARY KEY (`ID`);

--
-- AUTO_INCREMENT de las tablas volcadas
--

--
-- AUTO_INCREMENT de la tabla `Comanda`
--
ALTER TABLE `Comanda`
  MODIFY `ID` int(11) NOT NULL AUTO_INCREMENT, AUTO_INCREMENT=14;

--
-- AUTO_INCREMENT de la tabla `Empleado`
--
ALTER TABLE `Empleado`
  MODIFY `ID` int(11) NOT NULL AUTO_INCREMENT, AUTO_INCREMENT=34;

--
-- AUTO_INCREMENT de la tabla `Encuesta`
--
ALTER TABLE `Encuesta`
  MODIFY `ID` int(11) NOT NULL AUTO_INCREMENT, AUTO_INCREMENT=7;

--
-- AUTO_INCREMENT de la tabla `Mesa`
--
ALTER TABLE `Mesa`
  MODIFY `ID` int(11) NOT NULL AUTO_INCREMENT, AUTO_INCREMENT=32;

--
-- AUTO_INCREMENT de la tabla `Pedido`
--
ALTER TABLE `Pedido`
  MODIFY `ID` int(11) NOT NULL AUTO_INCREMENT, AUTO_INCREMENT=72;

--
-- AUTO_INCREMENT de la tabla `Producto`
--
ALTER TABLE `Producto`
  MODIFY `ID` int(11) NOT NULL AUTO_INCREMENT, AUTO_INCREMENT=94;
COMMIT;

/*!40101 SET CHARACTER_SET_CLIENT=@OLD_CHARACTER_SET_CLIENT */;
/*!40101 SET CHARACTER_SET_RESULTS=@OLD_CHARACTER_SET_RESULTS */;
/*!40101 SET COLLATION_CONNECTION=@OLD_COLLATION_CONNECTION */;
