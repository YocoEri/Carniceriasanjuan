-- Ejecutar UNA vez en phpMyAdmin (base carniceria_san_juan): agrega la columna "categoria" a productos
ALTER TABLE `productos` ADD COLUMN `categoria` varchar(50) NOT NULL DEFAULT 'General' AFTER `precio`;

UPDATE `productos` SET `categoria` = 'Res'       WHERE `id` IN (1, 2);
UPDATE `productos` SET `categoria` = 'Cerdo'     WHERE `id` = 3;
UPDATE `productos` SET `categoria` = 'Pollo'     WHERE `id` = 4;
UPDATE `productos` SET `categoria` = 'Embutidos' WHERE `id` = 5;
UPDATE `productos` SET `categoria` = 'Cerdo'     WHERE `id` IN (6, 7);
