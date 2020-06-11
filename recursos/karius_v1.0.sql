-- MySQL Workbench Forward Engineering

SET @OLD_UNIQUE_CHECKS=@@UNIQUE_CHECKS, UNIQUE_CHECKS=0;
SET @OLD_FOREIGN_KEY_CHECKS=@@FOREIGN_KEY_CHECKS, FOREIGN_KEY_CHECKS=0;
SET @OLD_SQL_MODE=@@SQL_MODE, SQL_MODE='TRADITIONAL,ALLOW_INVALID_DATES';

-- -----------------------------------------------------
-- Schema karius_db
-- -----------------------------------------------------

-- -----------------------------------------------------
-- Table `ks_usuario`
-- -----------------------------------------------------
DROP TABLE IF EXISTS `ks_usuario` ;

CREATE TABLE IF NOT EXISTS `ks_usuario` (
  `cod_usuario` INT(11) NOT NULL AUTO_INCREMENT,
  `rol` ENUM('admin', 'usuario') NOT NULL,
  `usuario` VARCHAR(45) NOT NULL,
  `clave` VARCHAR(100) NOT NULL,
  `nombres` VARCHAR(45) NULL,
  `apellidos` VARCHAR(45) NULL,
  `correo` VARCHAR(45) NULL,
  `telefono` VARCHAR(45) NULL,
  `imagen_perfil` VARCHAR(500) NULL,
  `fecha_registro` DATE NULL,
  `token_registro` VARCHAR(100) NULL,
  `correo_confirmado` ENUM('1', '0') NULL,
  PRIMARY KEY (`cod_usuario`),
  UNIQUE INDEX `usuario_UNIQUE` (`usuario` ASC))
ENGINE = InnoDB;


-- -----------------------------------------------------
-- Table `ks_cliente`
-- -----------------------------------------------------
DROP TABLE IF EXISTS `ks_cliente` ;

CREATE TABLE IF NOT EXISTS `ks_cliente` (
  `cod_cliente` INT(11) NOT NULL AUTO_INCREMENT,
  `razon_social` VARCHAR(100) NULL,
  `direccion_legal` VARCHAR(500) NULL,
  PRIMARY KEY (`cod_cliente`))
ENGINE = InnoDB;


-- -----------------------------------------------------
-- Table `ks_vehiculo`
-- -----------------------------------------------------
DROP TABLE IF EXISTS `ks_vehiculo` ;

CREATE TABLE IF NOT EXISTS `ks_vehiculo` (
  `cod_vehiculo` INT(11) NOT NULL AUTO_INCREMENT,
  `marca` VARCHAR(100) NULL,
  `placa` VARCHAR(45) NULL,
  `modelo` VARCHAR(45) NULL,
  `color` VARCHAR(45) NULL,
  `soat` VARCHAR(45) NULL,
  PRIMARY KEY (`cod_vehiculo`),
  UNIQUE INDEX `placa_UNIQUE` (`placa` ASC))
ENGINE = InnoDB;


-- -----------------------------------------------------
-- Table `ks_conductor`
-- -----------------------------------------------------
DROP TABLE IF EXISTS `ks_conductor` ;

CREATE TABLE IF NOT EXISTS `ks_conductor` (
  `cod_conductor` INT(11) NOT NULL AUTO_INCREMENT,
  `nombres` VARCHAR(100) NULL,
  `apellidos` VARCHAR(100) NULL,
  `numero_licencia` VARCHAR(45) NULL,
  `edad` VARCHAR(2) NULL,
  `sexo` VARCHAR(45) NULL,
  `foto` VARCHAR(100) NULL,
  PRIMARY KEY (`cod_conductor`))
ENGINE = InnoDB;


-- -----------------------------------------------------
-- Table `ks_traslado`
-- -----------------------------------------------------
DROP TABLE IF EXISTS `ks_traslado` ;

CREATE TABLE IF NOT EXISTS `ks_traslado` (
  `cod_traslado` INT(11) NOT NULL AUTO_INCREMENT,
  `fecha_traslado` DATE NOT NULL,
  `punto_partida` VARCHAR(100) NULL,
  `punto_llegada` VARCHAR(100) NULL,
  `hora_llegada` TIME NULL,
  `temperatura_llegada` DECIMAL(6,2) NULL,
  `humedad_relativa_llegada` DECIMAL(6,2) NULL,
  `hora_salida` TIME NULL,
  `temperatura_salida` DECIMAL(6,2) NULL,
  `humedad_relativa_salida` DECIMAL(6,2) NULL,
  `total` DECIMAL(6,2) NULL,
  `cod_cliente` INT(11) NOT NULL,
  `cod_conductor` INT(11) NOT NULL,
  `cod_vehiculo` INT(11) NOT NULL,
  `cod_usuario` INT(11) NOT NULL,
  PRIMARY KEY (`cod_traslado`),
  INDEX `fk_traslado_usuario_idx` (`cod_usuario` ASC),
  INDEX `fk_traslado_cliente1_idx` (`cod_cliente` ASC),
  INDEX `fk_traslado_vehiculo1_idx` (`cod_vehiculo` ASC),
  INDEX `fk_traslado_conductor1_idx` (`cod_conductor` ASC),
  CONSTRAINT `fk_traslado_usuario`
    FOREIGN KEY (`cod_usuario`)
    REFERENCES `ks_usuario` (`cod_usuario`)
    ON DELETE NO ACTION
    ON UPDATE NO ACTION,
  CONSTRAINT `fk_traslado_cliente1`
    FOREIGN KEY (`cod_cliente`)
    REFERENCES `ks_cliente` (`cod_cliente`)
    ON DELETE NO ACTION
    ON UPDATE NO ACTION,
  CONSTRAINT `fk_traslado_vehiculo1`
    FOREIGN KEY (`cod_vehiculo`)
    REFERENCES `ks_vehiculo` (`cod_vehiculo`)
    ON DELETE NO ACTION
    ON UPDATE NO ACTION,
  CONSTRAINT `fk_traslado_conductor1`
    FOREIGN KEY (`cod_conductor`)
    REFERENCES `ks_conductor` (`cod_conductor`)
    ON DELETE NO ACTION
    ON UPDATE NO ACTION)
ENGINE = InnoDB;


-- -----------------------------------------------------
-- Table `ks_compra`
-- -----------------------------------------------------
DROP TABLE IF EXISTS `ks_compra` ;

CREATE TABLE IF NOT EXISTS `ks_compra` (
  `cod_compras` INT(11) NOT NULL AUTO_INCREMENT,
  `fecha` DATETIME NULL,
  `numero_factura` VARCHAR(100) NULL,
  `razon_social` VARCHAR(100) NULL,
  `descripcion` VARCHAR(5000) NULL,
  `observaciones` VARCHAR(5000) NULL,
  `monto` DECIMAL(6,2) NULL,
  `imagen_factura` VARCHAR(100) NULL,
  `categoria` VARCHAR(45) NULL,
  PRIMARY KEY (`cod_compras`))
ENGINE = InnoDB;


-- -----------------------------------------------------
-- Table `ks_destinatario`
-- -----------------------------------------------------
DROP TABLE IF EXISTS `ks_destinatario` ;

CREATE TABLE IF NOT EXISTS `ks_destinatario` (
  `cod_destinatario` INT(11) NOT NULL AUTO_INCREMENT,
  `nombre` VARCHAR(100) NULL,
  `distrito` VARCHAR(500) NULL,
  `numero_guia` VARCHAR(45) NULL,
  `hora_llegada` TIME NULL,
  `temperatura_llegada` DECIMAL(6,2) NULL,
  `humedad_relativa_llegada` DECIMAL(6,2) NULL,
  `hora_entrega` TIME NULL,
  `temperatura_entrega` DECIMAL(6,2) NULL,
  `humedad_relativa_entrega` DECIMAL(6,2) NULL,
  `hora_salida` TIME NULL,
  `cod_traslado` INT(11) NOT NULL,
  PRIMARY KEY (`cod_destinatario`),
  INDEX `fk_destinatario_traslado1_idx` (`cod_traslado` ASC),
  CONSTRAINT `fk_destinatario_traslado1`
    FOREIGN KEY (`cod_traslado`)
    REFERENCES `ks_traslado` (`cod_traslado`)
    ON DELETE NO ACTION
    ON UPDATE NO ACTION)
ENGINE = InnoDB;


-- -----------------------------------------------------
-- Table `ks_almacen`
-- -----------------------------------------------------
DROP TABLE IF EXISTS `ks_almacen` ;

CREATE TABLE IF NOT EXISTS `ks_almacen` (
  `cod_almacen` INT(11) NOT NULL AUTO_INCREMENT,
  `nombre_almacen` VARCHAR(45) NULL,
  `direccion_almacen` VARCHAR(500) NULL,
  `cod_cliente` INT(11) NOT NULL,
  PRIMARY KEY (`cod_almacen`),
  INDEX `fk_almacen_cliente1_idx` (`cod_cliente` ASC),
  CONSTRAINT `fk_almacen_cliente1`
    FOREIGN KEY (`cod_cliente`)
    REFERENCES `ks_cliente` (`cod_cliente`)
    ON DELETE NO ACTION
    ON UPDATE NO ACTION)
ENGINE = InnoDB;


-- -----------------------------------------------------
-- Table `ks_session`
-- -----------------------------------------------------
DROP TABLE IF EXISTS `ks_session` ;

CREATE TABLE IF NOT EXISTS `ks_session` (
  `id` CHAR(32) NOT NULL,
  `name` CHAR(32) NOT NULL,
  `modified` INT(11) NULL,
  `lifetime` INT(11) NULL,
  `sessioncol` VARCHAR(45) NULL,
  `data` TEXT NULL,
  PRIMARY KEY (`id`, `name`))
ENGINE = InnoDB;


-- -----------------------------------------------------
-- Placeholder table for view `ks_vw_traslado`
-- -----------------------------------------------------
CREATE TABLE IF NOT EXISTS `ks_vw_traslado` (`cod_traslado` INT, `fecha_traslado` INT, `punto_partida` INT, `punto_llegada` INT, `hora_llegada` INT, `temperatura_llegada` INT, `humedad_relativa_llegada` INT, `hora_salida` INT, `temperatura_salida` INT, `humedad_relativa_salida` INT, `total` INT, `cod_cliente` INT, `razon_social` INT, `direccion_legal` INT, `cod_vehiculo` INT, `marca` INT, `placa` INT, `modelo` INT, `color` INT, `soat` INT, `cod_conductor` INT, `nombres` INT, `apellidos` INT, `numero_licencia` INT, `cod_usuario` INT, `u_nombres` INT, `u_apellidos` INT, `correo` INT, `telefono` INT);

-- -----------------------------------------------------
-- View `ks_vw_traslado`
-- -----------------------------------------------------
DROP TABLE IF EXISTS `ks_vw_traslado`;
DROP VIEW IF EXISTS `ks_vw_traslado` ;
CREATE  OR REPLACE VIEW ks_vw_traslado
AS
  SELECT t.cod_traslado, t.fecha_traslado, t.punto_partida, t.punto_llegada,
		t.hora_llegada, t.temperatura_llegada, t.humedad_relativa_llegada,
        t.hora_salida, t.temperatura_salida, t.humedad_relativa_salida, t.total,
		c.cod_cliente, c.razon_social, c.direccion_legal,
        v.cod_vehiculo, v.marca, v.placa, v.modelo, v.color, v.soat,
        cr.cod_conductor, cr.nombres, cr.apellidos, cr.numero_licencia,        
        u.cod_usuario, u.nombres as u_nombres, u.apellidos as u_apellidos, u.correo, u.telefono
  FROM ks_traslado t
  INNER JOIN ks_cliente c ON c.cod_cliente = t.cod_cliente
  INNER JOIN ks_vehiculo v ON v.cod_vehiculo = t.cod_vehiculo  
  INNER JOIN ks_conductor cr ON cr.cod_conductor = t.cod_conductor
  INNER JOIN ks_usuario u ON u.cod_usuario = t.cod_usuario;

SET SQL_MODE=@OLD_SQL_MODE;
SET FOREIGN_KEY_CHECKS=@OLD_FOREIGN_KEY_CHECKS;
SET UNIQUE_CHECKS=@OLD_UNIQUE_CHECKS;

-- -----------------------------------------------------
-- Data for table `ks_usuario`
-- -----------------------------------------------------
START TRANSACTION;
INSERT INTO `ks_usuario` (`cod_usuario`, `rol`, `usuario`, `clave`, `nombres`, `apellidos`, `correo`, `telefono`, `imagen_perfil`, `fecha_registro`, `token_registro`, `correo_confirmado`) VALUES (DEFAULT, 'admin', 'admin', 'admin', 'Rafael', 'Contreras', 'zirtrex@gmail.com', '966102508', NULL, NULL, NULL, NULL);
INSERT INTO `ks_usuario` (`cod_usuario`, `rol`, `usuario`, `clave`, `nombres`, `apellidos`, `correo`, `telefono`, `imagen_perfil`, `fecha_registro`, `token_registro`, `correo_confirmado`) VALUES (DEFAULT, 'usuario', 'usuario', 'usuario', 'Juan', 'Paredes', 'jparedes@correo.com', '123456789', NULL, NULL, NULL, NULL);

COMMIT;


-- -----------------------------------------------------
-- Data for table `ks_cliente`
-- -----------------------------------------------------
START TRANSACTION;
INSERT INTO `ks_cliente` (`cod_cliente`, `razon_social`, `direccion_legal`) VALUES (DEFAULT, 'Medical Store', 'Jr. Progreso');
INSERT INTO `ks_cliente` (`cod_cliente`, `razon_social`, `direccion_legal`) VALUES (DEFAULT, 'Uvas Store', 'Av. Las Lomas');

COMMIT;


-- -----------------------------------------------------
-- Data for table `ks_vehiculo`
-- -----------------------------------------------------
START TRANSACTION;
INSERT INTO `ks_vehiculo` (`cod_vehiculo`, `marca`, `placa`, `modelo`, `color`, `soat`) VALUES (DEFAULT, 'Mazda', '123456', 'CX-3', 'Rojo', '123456');
INSERT INTO `ks_vehiculo` (`cod_vehiculo`, `marca`, `placa`, `modelo`, `color`, `soat`) VALUES (DEFAULT, 'Toyota', '654321', 'Yaris', 'Gris', '123456');

COMMIT;


-- -----------------------------------------------------
-- Data for table `ks_conductor`
-- -----------------------------------------------------
START TRANSACTION;
INSERT INTO `ks_conductor` (`cod_conductor`, `nombres`, `apellidos`, `numero_licencia`, `edad`, `sexo`, `foto`) VALUES (DEFAULT, 'Alberto', 'Rivas', '12345', '24', 'Masculino', NULL);
INSERT INTO `ks_conductor` (`cod_conductor`, `nombres`, `apellidos`, `numero_licencia`, `edad`, `sexo`, `foto`) VALUES (DEFAULT, 'Manuel', 'Merino', '56378', '45', 'Masculino', NULL);

COMMIT;


-- -----------------------------------------------------
-- Data for table `ks_almacen`
-- -----------------------------------------------------
START TRANSACTION;
INSERT INTO `ks_almacen` (`cod_almacen`, `nombre_almacen`, `direccion_almacen`, `cod_cliente`) VALUES (DEFAULT, 'AL PQ', 'Av. Las Malvinas', 1);
INSERT INTO `ks_almacen` (`cod_almacen`, `nombre_almacen`, `direccion_almacen`, `cod_cliente`) VALUES (DEFAULT, 'AL PR', 'Av. Union', 1);

COMMIT;

