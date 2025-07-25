-- Renombrar la columna id a id_servicio
ALTER TABLE `servicios` CHANGE `id` `id_servicio` int NOT NULL AUTO_INCREMENT;

-- Eliminar la columna tipo
ALTER TABLE `servicios` DROP COLUMN `tipo`;

-- Actualizar la longitud de la descripción para permitir textos más largos
ALTER TABLE `servicios` MODIFY `descripcion` varchar(255) NOT NULL;

-- Agregar la columna id_spa y su clave foránea
ALTER TABLE `servicios` ADD COLUMN `id_spa` int NOT NULL;
ALTER TABLE `servicios` ADD CONSTRAINT `fk_servicios_spa` FOREIGN KEY (`id_spa`) REFERENCES `spas`(`id_spa`);