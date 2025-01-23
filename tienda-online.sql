-- phpMyAdmin SQL Dump
-- version 5.2.1
-- https://www.phpmyadmin.net/
--
-- Servidor: 127.0.0.1
-- Tiempo de generación: 23-01-2025 a las 12:15:16
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
-- Base de datos: `tienda-online`
--

-- --------------------------------------------------------

--
-- Estructura de tabla para la tabla `categorias`
--

CREATE TABLE `categorias` (
  `id` int(11) NOT NULL,
  `nombre` varchar(100) NOT NULL,
  `descripcion` text DEFAULT NULL,
  `padre_id` int(11) DEFAULT NULL,
  `activo` tinyint(1) NOT NULL DEFAULT 1
) ENGINE=InnoDB DEFAULT CHARSET=utf8mb4 COLLATE=utf8mb4_general_ci;

--
-- Volcado de datos para la tabla `categorias`
--

INSERT INTO `categorias` (`id`, `nombre`, `descripcion`, `padre_id`, `activo`) VALUES
(1, 'Electrónica', 'Gadgets y dispositivos electrónicos', NULL, 1),
(2, 'Ropa', 'Ropa para hombre y mujer', NULL, 1),
(3, 'Hogar', 'Artículos para el hogar', NULL, 1),
(4, 'Smartphones', 'Teléfonos móviles y accesorios', 1, 1),
(5, 'Laptops', 'Ordenadores portátiles', 1, 1),
(6, 'Tablets', 'Tablets y accesorios', 1, 1),
(7, 'Ropa Hombre', 'Ropa exclusiva para hombre', 2, 1),
(8, 'Ropa Mujer', 'Ropa exclusiva para mujer', 2, 1),
(9, 'Accesorios', 'Complementos de moda', 2, 1),
(10, 'Muebles', 'Muebles para el hogar', 3, 1),
(11, 'Decoración', 'Artículos decorativos', 3, 1),
(12, 'Electrodomésticos', 'Aparatos para el hogar', 3, 1);

-- --------------------------------------------------------

--
-- Estructura de tabla para la tabla `historial_pedidos`
--

CREATE TABLE `historial_pedidos` (
  `id` int(11) NOT NULL,
  `pedido_id` int(11) NOT NULL,
  `usuario_id` int(11) NOT NULL,
  `estado_anterior` enum('pendiente','procesando','enviado','entregado','cancelado') NOT NULL,
  `estado_nuevo` enum('pendiente','procesando','enviado','entregado','cancelado') NOT NULL,
  `fecha_cambio` timestamp NOT NULL DEFAULT current_timestamp()
) ENGINE=InnoDB DEFAULT CHARSET=utf8mb4 COLLATE=utf8mb4_general_ci;

-- --------------------------------------------------------

--
-- Estructura de tabla para la tabla `metodos_envio`
--

CREATE TABLE `metodos_envio` (
  `id` int(11) NOT NULL,
  `nombre` varchar(50) NOT NULL,
  `costo` decimal(10,2) NOT NULL,
  `activo` tinyint(1) DEFAULT 1
) ENGINE=InnoDB DEFAULT CHARSET=utf8mb4 COLLATE=utf8mb4_general_ci;

--
-- Volcado de datos para la tabla `metodos_envio`
--

INSERT INTO `metodos_envio` (`id`, `nombre`, `costo`, `activo`) VALUES
(1, 'Envío Estándar', 5.99, 1),
(2, 'Envío Express', 12.99, 1),
(3, 'Recogida en tienda', 0.00, 1);

-- --------------------------------------------------------

--
-- Estructura de tabla para la tabla `metodos_pago`
--

CREATE TABLE `metodos_pago` (
  `id` int(11) NOT NULL,
  `nombre` varchar(50) NOT NULL,
  `activo` tinyint(1) DEFAULT 1
) ENGINE=InnoDB DEFAULT CHARSET=utf8mb4 COLLATE=utf8mb4_general_ci;

--
-- Volcado de datos para la tabla `metodos_pago`
--

INSERT INTO `metodos_pago` (`id`, `nombre`, `activo`) VALUES
(1, 'Tarjeta de Crédito', 1),
(2, 'PayPal', 1),
(3, 'Transferencia Bancaria', 1);

-- --------------------------------------------------------

--
-- Estructura de tabla para la tabla `pedidos`
--

CREATE TABLE `pedidos` (
  `id` int(11) NOT NULL,
  `usuario_id` int(11) NOT NULL,
  `total` decimal(10,2) NOT NULL,
  `estado` enum('pendiente','procesando','enviado','entregado','cancelado') NOT NULL DEFAULT 'pendiente',
  `fecha` timestamp NOT NULL DEFAULT current_timestamp(),
  `updated_at` timestamp NOT NULL DEFAULT current_timestamp() ON UPDATE current_timestamp()
) ENGINE=InnoDB DEFAULT CHARSET=utf8mb4 COLLATE=utf8mb4_general_ci;

--
-- Volcado de datos para la tabla `pedidos`
--

INSERT INTO `pedidos` (`id`, `usuario_id`, `total`, `estado`, `fecha`, `updated_at`) VALUES
(1, 3, 8299.92, 'pendiente', '2025-01-22 08:57:24', '2025-01-22 08:57:24'),
(2, 3, 19.99, 'pendiente', '2025-01-22 09:03:30', '2025-01-22 09:03:30'),
(3, 3, 499.99, 'pendiente', '2025-01-22 09:05:03', '2025-01-22 09:05:03'),
(4, 3, 1799.98, 'pendiente', '2025-01-22 09:05:56', '2025-01-22 09:05:56'),
(5, 3, 499.99, 'pendiente', '2025-01-22 09:06:31', '2025-01-22 09:06:31'),
(6, 3, 499.99, 'pendiente', '2025-01-22 09:09:09', '2025-01-22 09:09:09');

-- --------------------------------------------------------

--
-- Estructura de tabla para la tabla `pedidos_productos`
--

CREATE TABLE `pedidos_productos` (
  `pedido_id` int(11) NOT NULL,
  `producto_id` int(11) NOT NULL,
  `cantidad` int(11) NOT NULL,
  `precio_unitario` decimal(10,2) NOT NULL
) ENGINE=InnoDB DEFAULT CHARSET=utf8mb4 COLLATE=utf8mb4_general_ci;

--
-- Volcado de datos para la tabla `pedidos_productos`
--

INSERT INTO `pedidos_productos` (`pedido_id`, `producto_id`, `cantidad`, `precio_unitario`) VALUES
(1, 1, 3, 599.99),
(1, 2, 5, 1299.99),
(2, 3, 1, 19.99),
(3, 4, 1, 499.99),
(4, 2, 1, 1299.99),
(4, 4, 1, 499.99),
(5, 4, 1, 499.99),
(6, 4, 1, 499.99);

-- --------------------------------------------------------

--
-- Estructura de tabla para la tabla `productos`
--

CREATE TABLE `productos` (
  `id` int(11) NOT NULL,
  `nombre` varchar(255) NOT NULL,
  `descripcion` text DEFAULT NULL,
  `precio` decimal(10,2) NOT NULL,
  `stock` int(11) NOT NULL,
  `activo` tinyint(1) NOT NULL DEFAULT 1,
  `categoria_id` int(11) DEFAULT NULL,
  `destacado` tinyint(1) NOT NULL DEFAULT 0,
  `created_at` timestamp NOT NULL DEFAULT current_timestamp(),
  `updated_at` timestamp NOT NULL DEFAULT current_timestamp() ON UPDATE current_timestamp(),
  `imagen` varchar(255) DEFAULT NULL
) ENGINE=InnoDB DEFAULT CHARSET=utf8mb4 COLLATE=utf8mb4_general_ci;

--
-- Volcado de datos para la tabla `productos`
--

INSERT INTO `productos` (`id`, `nombre`, `descripcion`, `precio`, `stock`, `activo`, `categoria_id`, `destacado`, `created_at`, `updated_at`, `imagen`) VALUES
(1, 'Smartphone X', 'Teléfono inteligente de última generación', 599.99, 50, 1, 1, 1, '2025-01-20 08:20:48', '2025-01-20 08:20:48', 'img/smartphone.jpg'),
(2, 'Laptop Pro', 'Laptop potente para trabajo y gaming', 1299.99, 30, 1, 1, 1, '2025-01-20 08:20:48', '2025-01-20 08:20:48', 'img/laptop.jpg'),
(3, 'Camiseta Algodón', 'Camiseta de algodón 100%', 19.99, 100, 1, 2, 0, '2025-01-20 08:20:48', '2025-01-20 08:20:48', 'img/camiseta.jpg'),
(4, 'Sofá 3 Plazas', 'Sofá cómodo y elegante', 499.99, 10, 1, 3, 1, '2025-01-20 08:20:48', '2025-01-20 08:20:48', 'img/sofa.jpg'),
(5, 'iPhone 13', 'Último modelo de iPhone con cámara avanzada', 999.99, 50, 1, 4, 1, '2025-01-23 11:13:54', '2025-01-23 11:13:54', 'img/iphone13.jpg'),
(6, 'Samsung Galaxy S21', 'Potente smartphone Android con pantalla AMOLED', 899.99, 40, 1, 4, 0, '2025-01-23 11:13:54', '2025-01-23 11:13:54', 'img/galaxys21.jpg'),
(7, 'Xiaomi Redmi Note 10', 'Smartphone de gama media con gran relación calidad-precio', 249.99, 100, 1, 4, 0, '2025-01-23 11:13:54', '2025-01-23 11:13:54', 'img/redminote10.jpg'),
(8, 'MacBook Air M1', 'Portátil ligero y potente con chip M1 de Apple', 999.99, 30, 1, 5, 1, '2025-01-23 11:13:54', '2025-01-23 11:13:54', 'img/macbookair.jpg'),
(9, 'Dell XPS 13', 'Ultrabook con pantalla InfinityEdge y procesador Intel', 1299.99, 25, 1, 5, 0, '2025-01-23 11:13:54', '2025-01-23 11:13:54', 'img/dellxps13.jpg'),
(10, 'Lenovo ThinkPad X1 Carbon', 'Laptop empresarial de alta gama', 1499.99, 20, 1, 5, 0, '2025-01-23 11:13:54', '2025-01-23 11:13:54', 'img/thinkpadx1.jpg'),
(11, 'iPad Air', 'Tablet versátil con chip A14 Bionic', 599.99, 45, 1, 6, 1, '2025-01-23 11:13:55', '2025-01-23 11:13:55', 'img/ipadair.jpg'),
(12, 'Samsung Galaxy Tab S7', 'Tablet Android de alta gama con S Pen incluido', 649.99, 35, 1, 6, 0, '2025-01-23 11:13:55', '2025-01-23 11:13:55', 'img/galaxytabs7.jpg'),
(13, 'Amazon Fire HD 10', 'Tablet asequible ideal para entretenimiento', 149.99, 80, 1, 6, 0, '2025-01-23 11:13:55', '2025-01-23 11:13:55', 'img/firehd10.jpg'),
(14, 'Camisa Oxford', 'Camisa clásica de algodón para hombre', 49.99, 100, 1, 7, 1, '2025-01-23 11:13:55', '2025-01-23 11:13:55', 'img/camisaoxford.jpg'),
(15, 'Jeans Slim Fit', 'Jeans ajustados de mezclilla para hombre', 59.99, 80, 1, 7, 0, '2025-01-23 11:13:55', '2025-01-23 11:13:55', 'img/jeansslimfit.jpg'),
(16, 'Chaqueta de Cuero', 'Chaqueta de cuero genuino para hombre', 199.99, 30, 1, 7, 0, '2025-01-23 11:13:55', '2025-01-23 11:13:55', 'img/chaquetacuero.jpg'),
(17, 'Vestido de Noche', 'Elegante vestido de noche para mujer', 89.99, 50, 1, 8, 1, '2025-01-23 11:13:55', '2025-01-23 11:13:55', 'img/vestidonoche.jpg'),
(18, 'Blusa de Seda', 'Blusa ligera de seda para mujer', 69.99, 60, 1, 8, 0, '2025-01-23 11:13:55', '2025-01-23 11:13:55', 'img/blusaseda.jpg'),
(19, 'Pantalón de Yoga', 'Pantalón cómodo para yoga y ejercicio', 39.99, 120, 1, 8, 0, '2025-01-23 11:13:55', '2025-01-23 11:13:55', 'img/pantalonyoga.jpg'),
(20, 'Bolso de Cuero', 'Bolso de cuero genuino para mujer', 129.99, 40, 1, 9, 1, '2025-01-23 11:13:55', '2025-01-23 11:13:55', 'img/bolsocuero.jpg'),
(21, 'Cinturón de Cuero', 'Cinturón de cuero para hombre', 29.99, 70, 1, 9, 0, '2025-01-23 11:13:55', '2025-01-23 11:13:55', 'img/cinturoncuero.jpg'),
(22, 'Bufanda de Lana', 'Bufanda suave de lana para invierno', 24.99, 90, 1, 9, 0, '2025-01-23 11:13:55', '2025-01-23 11:13:55', 'img/bufandalana.jpg'),
(23, 'Sofá de 3 Plazas', 'Cómodo sofá de 3 plazas para sala de estar', 599.99, 15, 1, 10, 1, '2025-01-23 11:13:55', '2025-01-23 11:13:55', 'img/sofa3plazas.jpg'),
(24, 'Mesa de Comedor', 'Mesa de comedor extensible para 6-8 personas', 399.99, 20, 1, 10, 0, '2025-01-23 11:13:55', '2025-01-23 11:13:55', 'img/mesacomedor.jpg'),
(25, 'Silla de Oficina Ergonómica', 'Silla de oficina con soporte lumbar', 199.99, 30, 1, 10, 0, '2025-01-23 11:13:55', '2025-01-23 11:13:55', 'img/sillaoficina.jpg'),
(26, 'Lámpara de Pie', 'Lámpara de pie moderna para sala de estar', 79.99, 40, 1, 11, 1, '2025-01-23 11:13:55', '2025-01-23 11:13:55', 'img/lamparapie.jpg'),
(27, 'Juego de Cortinas', 'Juego de cortinas opacas para dormitorio', 49.99, 50, 1, 11, 0, '2025-01-23 11:13:55', '2025-01-23 11:13:55', 'img/cortinas.jpg'),
(28, 'Espejo de Pared', 'Espejo decorativo grande para pared', 89.99, 25, 1, 11, 0, '2025-01-23 11:13:55', '2025-01-23 11:13:55', 'img/espejopared.jpg'),
(29, 'Refrigerador Side by Side', 'Refrigerador de gran capacidad con dispensador de agua', 1299.99, 10, 1, 12, 1, '2025-01-23 11:13:55', '2025-01-23 11:13:55', 'img/refrigerador.jpg'),
(30, 'Lavadora de Carga Frontal', 'Lavadora eficiente de 10kg de capacidad', 599.99, 15, 1, 12, 0, '2025-01-23 11:13:55', '2025-01-23 11:13:55', 'img/lavadora.jpg'),
(31, 'Horno Eléctrico', 'Horno eléctrico de convección para cocina', 299.99, 20, 1, 12, 0, '2025-01-23 11:13:55', '2025-01-23 11:13:55', 'img/hornoelectrico.jpg');

-- --------------------------------------------------------

--
-- Estructura de tabla para la tabla `usuarios`
--

CREATE TABLE `usuarios` (
  `id` int(11) NOT NULL,
  `email` varchar(255) NOT NULL,
  `password` varchar(255) NOT NULL,
  `nombre` varchar(100) NOT NULL,
  `direccion` text DEFAULT NULL,
  `telefono` varchar(20) DEFAULT NULL,
  `rol` enum('cliente','empleado','admin') NOT NULL DEFAULT 'cliente',
  `activo` tinyint(1) NOT NULL DEFAULT 1,
  `created_at` timestamp NOT NULL DEFAULT current_timestamp(),
  `updated_at` timestamp NOT NULL DEFAULT current_timestamp() ON UPDATE current_timestamp(),
  `calle` varchar(255) DEFAULT NULL,
  `ciudad` varchar(100) DEFAULT NULL,
  `codigo_postal` varchar(20) DEFAULT NULL,
  `pais` varchar(100) DEFAULT NULL
) ENGINE=InnoDB DEFAULT CHARSET=utf8mb4 COLLATE=utf8mb4_general_ci;

--
-- Volcado de datos para la tabla `usuarios`
--

INSERT INTO `usuarios` (`id`, `email`, `password`, `nombre`, `direccion`, `telefono`, `rol`, `activo`, `created_at`, `updated_at`, `calle`, `ciudad`, `codigo_postal`, `pais`) VALUES
(1, 'admin@tienda.com', '240be518fabd2724ddb6f04eeb1da5967448d7e831c08c8fa822809f74c720a9', 'Admin', NULL, '600123456', 'admin', 1, '2025-01-20 08:20:48', '2025-01-20 08:20:48', 'Calle Principal 123', 'Madrid', '28001', 'España'),
(2, 'cliente1@tienda.com', '09a31a7001e261ab1e056182a71d3cf57f582ca9a29cff5eb83be0f0549730a9', 'Juan Pérez', NULL, '610987654', 'cliente', 1, '2025-01-20 08:20:48', '2025-01-20 08:20:48', 'Av. Siempre Viva 742', 'Barcelona', '08001', 'España'),
(3, 'usuario0@gmail.com', '$2y$10$9WA2tsVjkK.eMwBSI1IW6.uEVe6HcxxBCcefREsAKiWcVNDpRSxMe', 'usuario0', NULL, NULL, 'cliente', 1, '2025-01-21 08:16:45', '2025-01-21 08:16:45', NULL, NULL, NULL, NULL);

--
-- Índices para tablas volcadas
--

--
-- Indices de la tabla `categorias`
--
ALTER TABLE `categorias`
  ADD PRIMARY KEY (`id`),
  ADD KEY `padre_id` (`padre_id`);

--
-- Indices de la tabla `historial_pedidos`
--
ALTER TABLE `historial_pedidos`
  ADD PRIMARY KEY (`id`),
  ADD KEY `pedido_id` (`pedido_id`),
  ADD KEY `usuario_id` (`usuario_id`);

--
-- Indices de la tabla `metodos_envio`
--
ALTER TABLE `metodos_envio`
  ADD PRIMARY KEY (`id`);

--
-- Indices de la tabla `metodos_pago`
--
ALTER TABLE `metodos_pago`
  ADD PRIMARY KEY (`id`);

--
-- Indices de la tabla `pedidos`
--
ALTER TABLE `pedidos`
  ADD PRIMARY KEY (`id`),
  ADD KEY `usuario_id` (`usuario_id`);

--
-- Indices de la tabla `pedidos_productos`
--
ALTER TABLE `pedidos_productos`
  ADD PRIMARY KEY (`pedido_id`,`producto_id`),
  ADD KEY `producto_id` (`producto_id`);

--
-- Indices de la tabla `productos`
--
ALTER TABLE `productos`
  ADD PRIMARY KEY (`id`),
  ADD KEY `categoria_id` (`categoria_id`);

--
-- Indices de la tabla `usuarios`
--
ALTER TABLE `usuarios`
  ADD PRIMARY KEY (`id`),
  ADD UNIQUE KEY `email` (`email`);

--
-- AUTO_INCREMENT de las tablas volcadas
--

--
-- AUTO_INCREMENT de la tabla `categorias`
--
ALTER TABLE `categorias`
  MODIFY `id` int(11) NOT NULL AUTO_INCREMENT, AUTO_INCREMENT=13;

--
-- AUTO_INCREMENT de la tabla `historial_pedidos`
--
ALTER TABLE `historial_pedidos`
  MODIFY `id` int(11) NOT NULL AUTO_INCREMENT;

--
-- AUTO_INCREMENT de la tabla `metodos_envio`
--
ALTER TABLE `metodos_envio`
  MODIFY `id` int(11) NOT NULL AUTO_INCREMENT, AUTO_INCREMENT=4;

--
-- AUTO_INCREMENT de la tabla `metodos_pago`
--
ALTER TABLE `metodos_pago`
  MODIFY `id` int(11) NOT NULL AUTO_INCREMENT, AUTO_INCREMENT=4;

--
-- AUTO_INCREMENT de la tabla `pedidos`
--
ALTER TABLE `pedidos`
  MODIFY `id` int(11) NOT NULL AUTO_INCREMENT, AUTO_INCREMENT=7;

--
-- AUTO_INCREMENT de la tabla `productos`
--
ALTER TABLE `productos`
  MODIFY `id` int(11) NOT NULL AUTO_INCREMENT, AUTO_INCREMENT=32;

--
-- AUTO_INCREMENT de la tabla `usuarios`
--
ALTER TABLE `usuarios`
  MODIFY `id` int(11) NOT NULL AUTO_INCREMENT, AUTO_INCREMENT=4;

--
-- Restricciones para tablas volcadas
--

--
-- Filtros para la tabla `categorias`
--
ALTER TABLE `categorias`
  ADD CONSTRAINT `categorias_ibfk_1` FOREIGN KEY (`padre_id`) REFERENCES `categorias` (`id`);

--
-- Filtros para la tabla `historial_pedidos`
--
ALTER TABLE `historial_pedidos`
  ADD CONSTRAINT `historial_pedidos_ibfk_1` FOREIGN KEY (`pedido_id`) REFERENCES `pedidos` (`id`),
  ADD CONSTRAINT `historial_pedidos_ibfk_2` FOREIGN KEY (`usuario_id`) REFERENCES `usuarios` (`id`);

--
-- Filtros para la tabla `pedidos`
--
ALTER TABLE `pedidos`
  ADD CONSTRAINT `pedidos_ibfk_1` FOREIGN KEY (`usuario_id`) REFERENCES `usuarios` (`id`);

--
-- Filtros para la tabla `pedidos_productos`
--
ALTER TABLE `pedidos_productos`
  ADD CONSTRAINT `pedidos_productos_ibfk_1` FOREIGN KEY (`pedido_id`) REFERENCES `pedidos` (`id`),
  ADD CONSTRAINT `pedidos_productos_ibfk_2` FOREIGN KEY (`producto_id`) REFERENCES `productos` (`id`);

--
-- Filtros para la tabla `productos`
--
ALTER TABLE `productos`
  ADD CONSTRAINT `productos_ibfk_1` FOREIGN KEY (`categoria_id`) REFERENCES `categorias` (`id`);
COMMIT;

/*!40101 SET CHARACTER_SET_CLIENT=@OLD_CHARACTER_SET_CLIENT */;
/*!40101 SET CHARACTER_SET_RESULTS=@OLD_CHARACTER_SET_RESULTS */;
/*!40101 SET COLLATION_CONNECTION=@OLD_COLLATION_CONNECTION */;
