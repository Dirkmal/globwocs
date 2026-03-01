-- ─────────────────────────────────────────────────────────────
-- GLOBWOCS CO. LTD — DATABASE SCHEMA
-- Run this in phpMyAdmin or via: mysql -u user -p dbname < install.sql
-- ─────────────────────────────────────────────────────────────

SET NAMES utf8mb4;
SET time_zone = '+01:00';

-- Projects
CREATE TABLE IF NOT EXISTS `projects` (
  `id`          INT UNSIGNED NOT NULL AUTO_INCREMENT,
  `name`        VARCHAR(255) NOT NULL,
  `description` TEXT,
  `location`    VARCHAR(255),
  `year`        VARCHAR(10),
  `category`    VARCHAR(100),
  `area_sqm`    VARCHAR(50),
  `is_featured` TINYINT(1) NOT NULL DEFAULT 0,
  `sort_order`  INT NOT NULL DEFAULT 0,
  `created_at`  DATETIME NOT NULL DEFAULT CURRENT_TIMESTAMP,
  PRIMARY KEY (`id`),
  KEY `idx_featured` (`is_featured`),
  KEY `idx_sort`     (`sort_order`)
) ENGINE=InnoDB DEFAULT CHARSET=utf8mb4 COLLATE=utf8mb4_unicode_ci;

-- Project images (multiple per project)
CREATE TABLE IF NOT EXISTS `project_images` (
  `id`         INT UNSIGNED NOT NULL AUTO_INCREMENT,
  `project_id` INT UNSIGNED NOT NULL,
  `filename`   VARCHAR(255) NOT NULL,
  `caption`    VARCHAR(500),
  `is_cover`   TINYINT(1) NOT NULL DEFAULT 0,
  `sort_order` INT NOT NULL DEFAULT 0,
  PRIMARY KEY (`id`),
  KEY `idx_project`  (`project_id`),
  KEY `idx_cover`    (`is_cover`),
  CONSTRAINT `fk_images_project`
    FOREIGN KEY (`project_id`) REFERENCES `projects` (`id`)
    ON DELETE CASCADE ON UPDATE CASCADE
) ENGINE=InnoDB DEFAULT CHARSET=utf8mb4 COLLATE=utf8mb4_unicode_ci;

-- Admin users
CREATE TABLE IF NOT EXISTS `admin_users` (
  `id`            INT UNSIGNED NOT NULL AUTO_INCREMENT,
  `username`      VARCHAR(100) NOT NULL UNIQUE,
  `password_hash` VARCHAR(255) NOT NULL,
  `last_login`    DATETIME,
  `created_at`    DATETIME NOT NULL DEFAULT CURRENT_TIMESTAMP,
  PRIMARY KEY (`id`)
) ENGINE=InnoDB DEFAULT CHARSET=utf8mb4 COLLATE=utf8mb4_unicode_ci;

-- Default admin: username=admin, password=password
-- CHANGE THIS PASSWORD IMMEDIATELY AFTER DEPLOYMENT via /admin/password.php
INSERT IGNORE INTO `admin_users` (`username`, `password_hash`)
VALUES ('admin', '$2y$12$QKbCG3nQfSSJ2kGlbwl.1eLk8Bfg0Maz.MFMdH78b7YjVNNd0Xbzm');
-- Password above is: password (bcrypt hash)
