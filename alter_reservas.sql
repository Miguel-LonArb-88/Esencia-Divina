-- Agregar columna id_spas a la tabla reservas
ALTER TABLE `reservas`
ADD COLUMN `id_spas` int,
ADD FOREIGN KEY (`id_spas`) REFERENCES `spas`(`id_spa`);