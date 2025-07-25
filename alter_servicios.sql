ALTER TABLE `servicios`
ADD COLUMN `duracion` int DEFAULT NULL COMMENT 'Duración en minutos',
ADD COLUMN `categoria` varchar(50) DEFAULT NULL,
ADD COLUMN `destacado` tinyint(1) DEFAULT 0,
ADD COLUMN `beneficios` text DEFAULT NULL,
ADD COLUMN `precio_anterior` float DEFAULT NULL;

-- Actualizar los servicios existentes con valores por defecto
UPDATE `servicios` SET
  `duracion` = 60,
  `categoria` = 'General',
  `destacado` = 0,
  `beneficios` = 'Servicio profesional,Atención personalizada',
  `precio_anterior` = precio * 1.2
WHERE 1;