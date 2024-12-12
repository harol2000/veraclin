-- phpMyAdmin SQL Dump
-- version 5.2.1
-- https://www.phpmyadmin.net/
--
-- Host: 127.0.0.1
-- Generation Time: Dec 02, 2024 at 08:39 AM
-- Server version: 10.4.32-MariaDB
-- PHP Version: 8.2.12

SET SQL_MODE = "NO_AUTO_VALUE_ON_ZERO";
START TRANSACTION;
SET time_zone = "+00:00";


/*!40101 SET @OLD_CHARACTER_SET_CLIENT=@@CHARACTER_SET_CLIENT */;
/*!40101 SET @OLD_CHARACTER_SET_RESULTS=@@CHARACTER_SET_RESULTS */;
/*!40101 SET @OLD_COLLATION_CONNECTION=@@COLLATION_CONNECTION */;
/*!40101 SET NAMES utf8mb4 */;

--
-- Database: `tienda_ropa`
--

-- --------------------------------------------------------

--
-- Table structure for table `clientes`
--

CREATE TABLE `clientes` (
  `id` int(11) NOT NULL,
  `nombre` varchar(100) NOT NULL,
  `ruc` varchar(11) NOT NULL,
  `correo` varchar(100) DEFAULT NULL,
  `telefono` varchar(20) DEFAULT NULL,
  `direccion` varchar(255) DEFAULT NULL,
  `password` varchar(255) DEFAULT NULL
) ENGINE=InnoDB DEFAULT CHARSET=utf8mb4 COLLATE=utf8mb4_general_ci;

--
-- Dumping data for table `clientes`
--

INSERT INTO `clientes` (`id`, `nombre`, `ruc`, `correo`, `telefono`, `direccion`, `password`) VALUES
(4, 'cliente12345622', '12345678999', 'cliente123456@gmail.com', '932670446222', 'av. asdsadas22', '$2y$10$DoIomKgIysddG5Rtkf1n4efrPk/34o6ouWX3StoKd5vbaVQuz/6fa'),
(5, 'cliente asds', '12222222222', '2222@gmail.com', '211111111', 'av. los angelessad', NULL),
(6, 'zzzzzzzzz', '12345678900', 'zzz@gmail.com', '932670446', 'av. Gamarraasd', '$2y$10$Rry1B91pDXImIEZSCq76R.9M5WgWkpeeLkSnzM8UDQc4u2WLGSG0K'),
(7, 'clientenew', '12345678900', 'clientenew@gmail.com', '932670446', 'av. Gamarradsad', '$2y$10$Hd6CNJuaPt.BynOEoXqA1eIK6H8BnB3/tysNVrHu/jhT9NP42M0.u'),
(8, 'cliente new', '12345678901', 'clientenew2@gmail.com', '932670446222222', '15434asd', '$2y$10$PupUygJPe9QAzHFW8.YQbO2hojR1h.h6QlR6Vjk2d7frYvSbkov3y'),
(9, 'asdasd', '12312312312', 'harolpineda2000@gmail.com', '932670446222222', '15434', '$2y$10$ErLSCeo7PX5sv7.ZrRnQGOyVPPaNY6Tm6yi9dOeSe1HXwQEh7k68C');

-- --------------------------------------------------------

--
-- Table structure for table `datos_empresa`
--

CREATE TABLE `datos_empresa` (
  `id` int(11) NOT NULL,
  `ruc` varchar(11) NOT NULL,
  `nombre_empresa` varchar(100) NOT NULL,
  `razon_social` varchar(100) NOT NULL,
  `telefono` varchar(15) NOT NULL,
  `correo` varchar(100) NOT NULL,
  `direccion` varchar(255) NOT NULL,
  `igv` decimal(5,2) NOT NULL DEFAULT 18.00
) ENGINE=InnoDB DEFAULT CHARSET=utf8mb4 COLLATE=utf8mb4_general_ci;

--
-- Dumping data for table `datos_empresa`
--

INSERT INTO `datos_empresa` (`id`, `ruc`, `nombre_empresa`, `razon_social`, `telefono`, `correo`, `direccion`, `igv`) VALUES
(1, '10123456789', 'Brianna', 'BrianaShop', '932670446222222', 'brianna@gmail.com', 'av. Gamarra', 18.00);

-- --------------------------------------------------------

--
-- Table structure for table `entradas`
--

CREATE TABLE `entradas` (
  `id` int(11) NOT NULL,
  `producto_id` int(11) NOT NULL,
  `proveedor_id` int(11) NOT NULL,
  `cantidad` int(11) NOT NULL,
  `precio_entrada` decimal(10,2) NOT NULL,
  `fecha` datetime DEFAULT current_timestamp()
) ENGINE=InnoDB DEFAULT CHARSET=utf8mb4 COLLATE=utf8mb4_general_ci;

--
-- Dumping data for table `entradas`
--

INSERT INTO `entradas` (`id`, `producto_id`, `proveedor_id`, `cantidad`, `precio_entrada`, `fecha`) VALUES
(18, 31, 4, 123, 0.00, '2024-12-01 18:51:42'),
(19, 32, 4, 123, 123.00, '2024-12-01 18:55:40'),
(20, 33, 4, 44, 22.00, '2024-12-01 21:54:41');

-- --------------------------------------------------------

--
-- Table structure for table `kardex`
--

CREATE TABLE `kardex` (
  `id` int(11) NOT NULL,
  `producto_id` int(11) NOT NULL,
  `fecha` datetime DEFAULT current_timestamp(),
  `tipo_documento` enum('entrada','salida','Boleta','Boleta de Compra') NOT NULL,
  `numero_documento` varchar(50) NOT NULL,
  `estado` enum('emitido') DEFAULT 'emitido',
  `ingresos` int(11) DEFAULT NULL,
  `salidas` int(11) DEFAULT NULL,
  `saldo` int(11) NOT NULL
) ENGINE=InnoDB DEFAULT CHARSET=utf8mb4 COLLATE=utf8mb4_general_ci;

--
-- Dumping data for table `kardex`
--

INSERT INTO `kardex` (`id`, `producto_id`, `fecha`, `tipo_documento`, `numero_documento`, `estado`, `ingresos`, `salidas`, `saldo`) VALUES
(40, 27, '2024-12-01 18:03:34', 'salida', 'BOLETA-27', 'emitido', 0, 3333, 0),
(41, 28, '2024-12-01 18:07:30', 'entrada', 'REGISTRO-28', 'emitido', 123, 0, 123),
(42, 29, '2024-12-01 18:08:03', 'entrada', 'REGISTRO-29', 'emitido', 123, 0, 123),
(43, 29, '2024-12-01 18:08:20', 'salida', 'BOLETA-28', 'emitido', 0, 12, 111),
(44, 30, '2024-12-01 18:42:15', 'entrada', 'REGISTRO-30', 'emitido', 12, 0, 12),
(45, 31, '2024-12-01 18:51:42', 'entrada', 'REGISTRO-31', 'emitido', 123, 0, 123),
(46, 32, '2024-12-01 18:55:40', 'entrada', 'REGISTRO-32', 'emitido', 123, 0, 123),
(47, 23, '2024-12-01 19:14:15', 'salida', 'BOLETA-29', 'emitido', 0, 2, 23),
(48, 31, '2024-12-01 19:14:15', 'salida', 'BOLETA-29', 'emitido', 0, 2, 121),
(49, 32, '2024-12-01 21:39:21', 'salida', 'BOLETA-30', 'emitido', 0, 2, 121),
(50, 33, '2024-12-01 21:54:41', 'entrada', 'REGISTRO-33', 'emitido', 44, 0, 44),
(51, 33, '2024-12-01 21:54:51', 'salida', 'BOLETA-31', 'emitido', 0, 22, 22),
(52, 33, '2024-12-01 22:01:43', 'salida', 'BOLETA-32', 'emitido', 0, 22, 0),
(53, 32, '2024-12-01 22:07:41', 'salida', 'BOLETA-33', 'emitido', 0, 112, 9),
(54, 33, '2024-12-01 22:10:01', 'entrada', 'EDIT-STOCK-33', 'emitido', 22, 0, 22),
(55, 33, '2024-12-02 00:46:40', 'salida', 'BOLETA-34', 'emitido', 0, 2, 20),
(56, 23, '2024-12-02 02:27:13', 'entrada', 'EDIT-STOCK-23', 'emitido', 2, 0, 25);

-- --------------------------------------------------------

--
-- Table structure for table `productos`
--

CREATE TABLE `productos` (
  `id` int(11) NOT NULL,
  `nombre` varchar(100) NOT NULL,
  `descripcion` text DEFAULT NULL,
  `precio_compra` decimal(10,2) NOT NULL,
  `precio_venta` decimal(10,2) NOT NULL,
  `stock` int(11) NOT NULL DEFAULT 0,
  `imagen` varchar(255) DEFAULT NULL
) ENGINE=InnoDB DEFAULT CHARSET=utf8mb4 COLLATE=utf8mb4_general_ci;

--
-- Dumping data for table `productos`
--

INSERT INTO `productos` (`id`, `nombre`, `descripcion`, `precio_compra`, `precio_venta`, `stock`, `imagen`) VALUES
(23, 'pink blusa XDddd', 'blusa rosada', 22.00, 33.00, 25, 'prod_674ce47ca086e.jpg'),
(24, 'producto1', 'produncto', 22.00, 33.00, 22, 'prod_674ce817e4ec0.gif'),
(25, 'producto2', 'producto2', 22.00, 33.00, 222, 'prod_674ce949ee85a.jpg'),
(26, 'producto3', 'producto3', 22.00, 33.00, 333, 'prod_674ce998cb90a.jpg'),
(27, 'producto 4', 'producto 4', 22.00, 33.00, 0, 'prod_674ce9d9986fb.jpg'),
(28, 'producto final', 'producto final', 123.00, 123.00, 123, 'prod_674cec327c97f.png'),
(29, 'producto10', 'producto10', 123.00, 123.00, 111, 'prod_674cec5384ae4.png'),
(30, 'producto1111', 'producto1111', 123.00, 123.00, 12, 'prod_674cf45763ff6.png'),
(31, 'productofinalfinal', 'productofinalfinalproductofinalfinalproductofinalfinal', 123.00, 123.00, 121, 'prod_674cf68ed5c50.png'),
(32, 'producto22222', 'producto22222', 123.00, 123.00, 9, 'prod_674cf77cb9012.png'),
(33, 'productorojo', 'productorojo', 22.00, 33.00, 20, 'prod_674d2171ccfc5.png');

-- --------------------------------------------------------

--
-- Table structure for table `proveedores`
--

CREATE TABLE `proveedores` (
  `id` int(11) NOT NULL,
  `nombre` varchar(100) NOT NULL,
  `telefono` varchar(20) DEFAULT NULL,
  `direccion` varchar(255) DEFAULT NULL,
  `ruc` varchar(11) NOT NULL,
  `departamento` varchar(50) NOT NULL,
  `provincia` varchar(50) NOT NULL,
  `distrito` varchar(50) NOT NULL
) ENGINE=InnoDB DEFAULT CHARSET=utf8mb4 COLLATE=utf8mb4_general_ci;

--
-- Dumping data for table `proveedores`
--

INSERT INTO `proveedores` (`id`, `nombre`, `telefono`, `direccion`, `ruc`, `departamento`, `provincia`, `distrito`) VALUES
(4, 'talara uno', '1234567890', 'av. los ageluz', '12345678901', 'Cusco', 'Urubamba', 'Ollantaytambo');

-- --------------------------------------------------------

--
-- Table structure for table `salidas`
--

CREATE TABLE `salidas` (
  `id` int(11) NOT NULL,
  `venta_id` int(11) NOT NULL,
  `producto_id` int(11) NOT NULL,
  `cantidad` int(11) NOT NULL,
  `subtotal` decimal(10,2) NOT NULL
) ENGINE=InnoDB DEFAULT CHARSET=utf8mb4 COLLATE=utf8mb4_general_ci;

--
-- Dumping data for table `salidas`
--

INSERT INTO `salidas` (`id`, `venta_id`, `producto_id`, `cantidad`, `subtotal`) VALUES
(22, 27, 27, 3333, 109989.00),
(23, 28, 29, 12, 1476.00),
(24, 29, 23, 2, 66.00),
(25, 29, 31, 2, 246.00),
(26, 30, 32, 2, 246.00),
(27, 31, 33, 22, 726.00),
(28, 32, 33, 22, 726.00),
(29, 33, 32, 112, 13776.00),
(30, 34, 33, 2, 66.00);

-- --------------------------------------------------------

--
-- Table structure for table `usuarios`
--

CREATE TABLE `usuarios` (
  `id` int(11) NOT NULL,
  `nombre` varchar(100) NOT NULL,
  `email` varchar(100) NOT NULL,
  `password` varchar(255) NOT NULL,
  `rol` enum('administrador','vendedor') NOT NULL
) ENGINE=InnoDB DEFAULT CHARSET=utf8mb4 COLLATE=utf8mb4_general_ci;

--
-- Dumping data for table `usuarios`
--

INSERT INTO `usuarios` (`id`, `nombre`, `email`, `password`, `rol`) VALUES
(1, 'Administrador', 'admin@tienda.com', '$2y$10$J8ptoV/s.4Og4K5bqgnbgujVn.DGMSJj7qYRANn9Tsvi.V3HFBXtu', 'administrador'),
(2, 'vendedor1', 'vendedor1@gmail.com', '$2y$10$rsasCFZl4B8UEgYg0G.00eWk052ks5SUE/nG25wSIXe.VQuCZYOXy', 'vendedor'),
(3, 'melisa f', 'melisa@gmail.com', '$2y$10$9eaWOUUWjE2PtTq3g/MEFur0d3FA43exlgFBuHZ0rY/SjvAy4vQzu', 'vendedor');

-- --------------------------------------------------------

--
-- Table structure for table `ventas`
--

CREATE TABLE `ventas` (
  `id` int(11) NOT NULL,
  `cliente_id` int(11) NOT NULL,
  `user_id` int(11) NOT NULL,
  `total` decimal(10,2) NOT NULL,
  `fecha` datetime DEFAULT current_timestamp()
) ENGINE=InnoDB DEFAULT CHARSET=utf8mb4 COLLATE=utf8mb4_general_ci;

--
-- Dumping data for table `ventas`
--

INSERT INTO `ventas` (`id`, `cliente_id`, `user_id`, `total`, `fecha`) VALUES
(27, 4, 1, 109989.00, '2024-12-01 18:03:34'),
(28, 4, 1, 1476.00, '2024-12-01 18:08:20'),
(29, 4, 1, 312.00, '2024-12-01 19:14:15'),
(30, 4, 1, 246.00, '2024-12-01 21:39:21'),
(31, 4, 1, 726.00, '2024-12-01 21:54:51'),
(32, 4, 2, 726.00, '2024-12-01 22:01:43'),
(33, 4, 1, 13776.00, '2024-12-01 22:07:41'),
(34, 5, 1, 66.00, '2024-12-02 00:46:40');

--
-- Indexes for dumped tables
--

--
-- Indexes for table `clientes`
--
ALTER TABLE `clientes`
  ADD PRIMARY KEY (`id`),
  ADD UNIQUE KEY `correo` (`correo`);

--
-- Indexes for table `datos_empresa`
--
ALTER TABLE `datos_empresa`
  ADD PRIMARY KEY (`id`);

--
-- Indexes for table `entradas`
--
ALTER TABLE `entradas`
  ADD PRIMARY KEY (`id`),
  ADD KEY `producto_id` (`producto_id`),
  ADD KEY `proveedor_id` (`proveedor_id`);

--
-- Indexes for table `kardex`
--
ALTER TABLE `kardex`
  ADD PRIMARY KEY (`id`),
  ADD KEY `producto_id` (`producto_id`);

--
-- Indexes for table `productos`
--
ALTER TABLE `productos`
  ADD PRIMARY KEY (`id`);

--
-- Indexes for table `proveedores`
--
ALTER TABLE `proveedores`
  ADD PRIMARY KEY (`id`);

--
-- Indexes for table `salidas`
--
ALTER TABLE `salidas`
  ADD PRIMARY KEY (`id`),
  ADD KEY `venta_id` (`venta_id`),
  ADD KEY `producto_id` (`producto_id`);

--
-- Indexes for table `usuarios`
--
ALTER TABLE `usuarios`
  ADD PRIMARY KEY (`id`),
  ADD UNIQUE KEY `email` (`email`);

--
-- Indexes for table `ventas`
--
ALTER TABLE `ventas`
  ADD PRIMARY KEY (`id`),
  ADD KEY `cliente_id` (`cliente_id`);

--
-- AUTO_INCREMENT for dumped tables
--

--
-- AUTO_INCREMENT for table `clientes`
--
ALTER TABLE `clientes`
  MODIFY `id` int(11) NOT NULL AUTO_INCREMENT, AUTO_INCREMENT=10;

--
-- AUTO_INCREMENT for table `datos_empresa`
--
ALTER TABLE `datos_empresa`
  MODIFY `id` int(11) NOT NULL AUTO_INCREMENT, AUTO_INCREMENT=4;

--
-- AUTO_INCREMENT for table `entradas`
--
ALTER TABLE `entradas`
  MODIFY `id` int(11) NOT NULL AUTO_INCREMENT, AUTO_INCREMENT=21;

--
-- AUTO_INCREMENT for table `kardex`
--
ALTER TABLE `kardex`
  MODIFY `id` int(11) NOT NULL AUTO_INCREMENT, AUTO_INCREMENT=57;

--
-- AUTO_INCREMENT for table `productos`
--
ALTER TABLE `productos`
  MODIFY `id` int(11) NOT NULL AUTO_INCREMENT, AUTO_INCREMENT=34;

--
-- AUTO_INCREMENT for table `proveedores`
--
ALTER TABLE `proveedores`
  MODIFY `id` int(11) NOT NULL AUTO_INCREMENT, AUTO_INCREMENT=6;

--
-- AUTO_INCREMENT for table `salidas`
--
ALTER TABLE `salidas`
  MODIFY `id` int(11) NOT NULL AUTO_INCREMENT, AUTO_INCREMENT=31;

--
-- AUTO_INCREMENT for table `usuarios`
--
ALTER TABLE `usuarios`
  MODIFY `id` int(11) NOT NULL AUTO_INCREMENT, AUTO_INCREMENT=4;

--
-- AUTO_INCREMENT for table `ventas`
--
ALTER TABLE `ventas`
  MODIFY `id` int(11) NOT NULL AUTO_INCREMENT, AUTO_INCREMENT=35;

--
-- Constraints for dumped tables
--

--
-- Constraints for table `entradas`
--
ALTER TABLE `entradas`
  ADD CONSTRAINT `entradas_ibfk_1` FOREIGN KEY (`producto_id`) REFERENCES `productos` (`id`) ON DELETE CASCADE,
  ADD CONSTRAINT `entradas_ibfk_2` FOREIGN KEY (`proveedor_id`) REFERENCES `proveedores` (`id`) ON DELETE CASCADE;

--
-- Constraints for table `kardex`
--
ALTER TABLE `kardex`
  ADD CONSTRAINT `kardex_ibfk_1` FOREIGN KEY (`producto_id`) REFERENCES `productos` (`id`) ON DELETE CASCADE;

--
-- Constraints for table `salidas`
--
ALTER TABLE `salidas`
  ADD CONSTRAINT `salidas_ibfk_1` FOREIGN KEY (`venta_id`) REFERENCES `ventas` (`id`) ON DELETE CASCADE,
  ADD CONSTRAINT `salidas_ibfk_2` FOREIGN KEY (`producto_id`) REFERENCES `productos` (`id`) ON DELETE CASCADE;

--
-- Constraints for table `ventas`
--
ALTER TABLE `ventas`
  ADD CONSTRAINT `ventas_ibfk_1` FOREIGN KEY (`cliente_id`) REFERENCES `clientes` (`id`) ON DELETE CASCADE;
COMMIT;

/*!40101 SET CHARACTER_SET_CLIENT=@OLD_CHARACTER_SET_CLIENT */;
/*!40101 SET CHARACTER_SET_RESULTS=@OLD_CHARACTER_SET_RESULTS */;
/*!40101 SET COLLATION_CONNECTION=@OLD_COLLATION_CONNECTION */;
