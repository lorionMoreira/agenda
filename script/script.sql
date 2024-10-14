CREATE TABLE `agenda`.`location` (
  `id` INT NOT NULL AUTO_INCREMENT,
  `lat` VARCHAR(50) NULL,
  `long` VARCHAR(50) NULL,
  PRIMARY KEY (`id`));

CREATE TABLE `agenda`.`acoes_relacionada` (
  `id` INT NOT NULL AUTO_INCREMENT,
  PRIMARY KEY (`id`));

CREATE TABLE `agenda`.`documentos` (
  `id` INT NOT NULL AUTO_INCREMENT,
  PRIMARY KEY (`id`));

CREATE TABLE `agenda`.`area_atuacao` (
  `id` INT NOT NULL AUTO_INCREMENT,
  PRIMARY KEY (`id`));

CREATE TABLE `agenda`.`duvida` (
  `id` INT NOT NULL AUTO_INCREMENT,
  PRIMARY KEY (`id`));

ALTER TABLE `agenda`.`documentos` 
ADD CONSTRAINT `fk_area_atuacao`
  FOREIGN KEY (`area_atuacao_id`)
  REFERENCES `agenda`.`area_atuacao` (`id`)
  ON DELETE NO ACTION
  ON UPDATE NO ACTION;

CREATE TABLE `agenda`.`area_atuacao_location` (
  `id` INT NOT NULL AUTO_INCREMENT,
  PRIMARY KEY (`id`));

ALTER TABLE `agenda`.`area_atuacao_location` 
ADD COLUMN `area_atuacao_id` INT(11) NOT NULL AFTER `id`;

ALTER TABLE `agenda`.`area_atuacao_location` 
ADD COLUMN `localization_id` INT(11) NOT NULL AFTER `area_atuacao_id`;

ALTER TABLE `agenda`.`area_atuacao_location` 
ADD INDEX `fk_area_atuacao_idx_1` (`area_atuacao_id` ASC);
ALTER TABLE `agenda`.`area_atuacao_location` 
ADD CONSTRAINT `fk_area_atuacao_1`
  FOREIGN KEY (`area_atuacao_id`)
  REFERENCES `agenda`.`area_atuacao` (`id`)
  ON DELETE NO ACTION
  ON UPDATE NO ACTION;

ALTER TABLE `agenda`.`area_atuacao_location` 
ADD INDEX `fk_location_1_idx` (`localization_id` ASC);
ALTER TABLE `agenda`.`area_atuacao_location` 
ADD CONSTRAINT `fk_location_1`
  FOREIGN KEY (`localization_id`)
  REFERENCES `agenda`.`location` (`id`)
  ON DELETE NO ACTION
  ON UPDATE NO ACTION;

CREATE TABLE `agenda`.`area_atuacao_documentos` (
  `id` INT NOT NULL AUTO_INCREMENT,
  `documentos_id` INT(11) NOT NULL,
  `area_atuacao_id` INT(11) NOT NULL,
  PRIMARY KEY (`id`),
  INDEX `fk_documentos_idx_2` (`documentos_id` ASC),
  INDEX `fk_area_atuacao_idx_2` (`area_atuacao_id` ASC),
  CONSTRAINT `fk_documentos_2`
    FOREIGN KEY (`documentos_id`)
    REFERENCES `agenda`.`documentos` (`id`)
    ON DELETE NO ACTION
    ON UPDATE NO ACTION,
  CONSTRAINT `fk_area_atuacao_2`
    FOREIGN KEY (`area_atuacao_id`)
    REFERENCES `agenda`.`area_atuacao` (`id`)
    ON DELETE NO ACTION
    ON UPDATE NO ACTION);

ALTER TABLE `agenda`.`acoes_relacionada` 
ADD COLUMN `area_atuacao_id` INT NOT NULL AFTER `id`,
ADD INDEX `fk_area_atuacao_idx_acoes_relacionadas` (`area_atuacao_id` ASC);
ALTER TABLE `agenda`.`acoes_relacionada` 
ADD CONSTRAINT `fk_area_atuacao_acoes_relacionadas`
  FOREIGN KEY (`area_atuacao_id`)
  REFERENCES `agenda`.`area_atuacao` (`id`)
  ON DELETE NO ACTION
  ON UPDATE NO ACTION;

ALTER TABLE `agenda`.`duvida` 
ADD COLUMN `acoes_relacionada_id` INT NOT NULL AFTER `id`,
ADD INDEX `fk_acoes_relacionada_duvida_idx` (`acoes_relacionada_id` ASC);
ALTER TABLE `agenda`.`duvida` 
ADD CONSTRAINT `fk_acoes_relacionada_duvida`
  FOREIGN KEY (`acoes_relacionada_id`)
  REFERENCES `agenda`.`acoes_relacionada` (`id`)
  ON DELETE NO ACTION
  ON UPDATE NO ACTION;

ALTER TABLE `agenda`.`area_atuacao` 
ADD COLUMN `nome_arquivo` VARCHAR(100) NULL AFTER `id`,
ADD COLUMN `dir` VARCHAR(255) NULL AFTER `nome_arquivo`;

ALTER TABLE `agenda`.`area_atuacao` 
ADD COLUMN `ordem` INT NULL AFTER `dir`;

ALTER TABLE `agenda`.`area_atuacao` 
ADD COLUMN `titulo` VARCHAR(100) NULL AFTER `ordem`;

ALTER TABLE `agenda`.`area_atuacao` 
ADD COLUMN `descricao` TEXT NULL AFTER `titulo`;

ALTER TABLE `agenda`.`acoes_relacionada` 
ADD COLUMN `nome` TEXT NULL AFTER `area_atuacao_id`;

ALTER TABLE `agenda`.`area_atuacao` 
ADD COLUMN `cor` VARCHAR(45) NULL AFTER `descricao`;

ALTER TABLE `agenda`.`area_atuacao` 
ADD COLUMN `created_at` TIMESTAMP NULL AFTER `cor`,
ADD COLUMN `updated_at` TIMESTAMP NULL AFTER `created_at`;
