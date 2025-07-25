-- Estructura de tabla para la tabla `reservas`
CREATE TABLE `reservas` (
  `id_reserva` int NOT NULL AUTO_INCREMENT,
  `id_cliente` int NOT NULL,
  `fecha` date NOT NULL,
  `hora` time NOT NULL,
  `estado` varchar(20) NOT NULL,
  PRIMARY KEY (`id_reserva`),
  FOREIGN KEY (`id_cliente`) REFERENCES `usuarios`(`id`)
) ENGINE=InnoDB DEFAULT CHARSET=utf8mb4 COLLATE=utf8mb4_0900_ai_ci;

-- Estructura de tabla para la tabla `detalle_reserva`
CREATE TABLE `detalle_reserva` (
  `id_detalle` int NOT NULL AUTO_INCREMENT,
  `id_reserva` int NOT NULL,
  `id_servicio` int NOT NULL,
  PRIMARY KEY (`id_detalle`),
  FOREIGN KEY (`id_reserva`) REFERENCES `reservas`(`id_reserva`),
  FOREIGN KEY (`id_servicio`) REFERENCES `servicios`(`id`)
) ENGINE=InnoDB DEFAULT CHARSET=utf8mb4 COLLATE=utf8mb4_0900_ai_ci;

-- Estructura de tabla para la tabla `factura`
CREATE TABLE `factura` (
  `id_factura` int NOT NULL AUTO_INCREMENT,
  `id_reserva` int NOT NULL,
  `monto` float NOT NULL,
  `estado` varchar(20) NOT NULL,
  PRIMARY KEY (`id_factura`),
  FOREIGN KEY (`id_reserva`) REFERENCES `reservas`(`id_reserva`)
) ENGINE=InnoDB DEFAULT CHARSET=utf8mb4 COLLATE=utf8mb4_0900_ai_ci;