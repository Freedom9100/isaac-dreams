-- =============================================
-- Дамп базы данных: isaac_dreams
-- =============================================

CREATE DATABASE IF NOT EXISTS `isaac_dreams`
  DEFAULT CHARACTER SET utf8
  COLLATE utf8_general_ci;

USE `isaac_dreams`;

-- ---------------------------------------------
-- Таблица пользователей
-- ---------------------------------------------
CREATE TABLE `users` (
  `id`         INT NOT NULL AUTO_INCREMENT,
  `name`       VARCHAR(100) NOT NULL,
  `login`      VARCHAR(150) NOT NULL,
  `pass_hash`  VARCHAR(255) NOT NULL,
  `role`       ENUM('user','admin') DEFAULT 'user',
  `progress`   INT DEFAULT 0,   -- кол-во пройденных уровней (0..6)
  `created_at` DATETIME DEFAULT CURRENT_TIMESTAMP,
  PRIMARY KEY (`id`),
  UNIQUE KEY `login` (`login`)
) ENGINE=InnoDB DEFAULT CHARSET=utf8;

-- Если таблица users уже создана без колонки progress — выполни отдельно:
-- ALTER TABLE `users` ADD COLUMN `progress` INT DEFAULT 0;

-- ---------------------------------------------
-- Таблица стикеров (артефактов)
-- ---------------------------------------------
CREATE TABLE `stickers` (
  `id`          INT NOT NULL AUTO_INCREMENT,
  `title`       VARCHAR(100) NOT NULL,
  `description` TEXT,
  `file_path`   VARCHAR(255) DEFAULT '',
  `active`      TINYINT(1) DEFAULT 1,
  `created_at`  DATETIME DEFAULT CURRENT_TIMESTAMP,
  PRIMARY KEY (`id`)
) ENGINE=InnoDB DEFAULT CHARSET=utf8;

-- ---------------------------------------------
-- Таблица этапов (аномалий)
-- ---------------------------------------------
CREATE TABLE `stages` (
  `id`          INT NOT NULL AUTO_INCREMENT,
  `title`       VARCHAR(100) NOT NULL,
  `lore`        TEXT,
  `sticker_id`  INT DEFAULT NULL,
  `image_path`  VARCHAR(255) DEFAULT '',
  `active`      TINYINT(1) DEFAULT 1,
  `created_at`  DATETIME DEFAULT CURRENT_TIMESTAMP,
  PRIMARY KEY (`id`),
  FOREIGN KEY (`sticker_id`) REFERENCES `stickers`(`id`) ON DELETE SET NULL
) ENGINE=InnoDB DEFAULT CHARSET=utf8;

-- ---------------------------------------------
-- Пройденные этапы (аномалии) пользователей
-- (создаётся ПОСЛЕ users и stages, т.к. ссылается на них)
-- ---------------------------------------------
CREATE TABLE `user_stages` (
  `id`          INT NOT NULL AUTO_INCREMENT,
  `user_id`     INT NOT NULL,
  `stage_id`    INT NOT NULL,
  `unlocked_at` DATETIME DEFAULT CURRENT_TIMESTAMP,
  PRIMARY KEY (`id`),
  UNIQUE KEY `user_stage` (`user_id`, `stage_id`),
  FOREIGN KEY (`user_id`)  REFERENCES `users`(`id`)  ON DELETE CASCADE,
  FOREIGN KEY (`stage_id`) REFERENCES `stages`(`id`) ON DELETE CASCADE
) ENGINE=InnoDB DEFAULT CHARSET=utf8;

-- ---------------------------------------------
-- Администратор по умолчанию
-- Логин:  admin@isaac-dreams.ru
-- Пароль: Admin1234
-- ---------------------------------------------
INSERT INTO `users` (`name`, `login`, `pass_hash`, `role`) VALUES
('Администратор', 'admin@isaac-dreams.ru', '$2y$12$TGssF5uA2A6IOR7gIwcAF.NalURMLGnBQwIsj1rQ5BsgkbM4nbL6G', 'admin');
