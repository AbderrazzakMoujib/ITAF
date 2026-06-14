-- ============================================================
-- ITAF Badging — Script de création de la base de données
-- À exécuter dans phpMyAdmin (cPanel/NindoHost) avant deployment
-- ============================================================

CREATE DATABASE IF NOT EXISTS `itaf_badging`
    CHARACTER SET utf8mb4
    COLLATE utf8mb4_unicode_ci;

USE `itaf_badging`;

-- ─── Table des événements ──────────────────────────────────────────────────
CREATE TABLE IF NOT EXISTS `events` (
    `id`          BIGINT UNSIGNED NOT NULL AUTO_INCREMENT,
    `nom`         VARCHAR(255)    NOT NULL,
    `slug`        VARCHAR(255)    NOT NULL UNIQUE,
    `date_debut`  DATE            NOT NULL,
    `date_fin`    DATE            NULL,
    `lieu`        VARCHAR(255)    NULL,
    `description` TEXT            NULL,
    `logo_path`   VARCHAR(255)    NULL,
    `actif`       TINYINT(1)      NOT NULL DEFAULT 1,
    `created_at`  TIMESTAMP       NULL,
    `updated_at`  TIMESTAMP       NULL,
    PRIMARY KEY (`id`)
) ENGINE=InnoDB DEFAULT CHARSET=utf8mb4 COLLATE=utf8mb4_unicode_ci;

-- ─── Table des visiteurs inscrits ─────────────────────────────────────────
CREATE TABLE IF NOT EXISTS `visitors` (
    `id`               BIGINT UNSIGNED NOT NULL AUTO_INCREMENT,
    `event_id`         BIGINT UNSIGNED NOT NULL,
    `nom`              VARCHAR(100)    NOT NULL,
    `prenom`           VARCHAR(100)    NOT NULL,
    `entreprise`       VARCHAR(150)    NULL,
    `fonction`         VARCHAR(100)    NULL,
    `email`            VARCHAR(255)    NOT NULL UNIQUE,
    `telephone`        VARCHAR(20)     NULL,
    `pays`             VARCHAR(80)     NULL,
    `token`            VARCHAR(255)    NOT NULL UNIQUE,
    `qr_code_path`     VARCHAR(255)    NULL,
    `scan_count`       INT             NOT NULL DEFAULT 0,
    `first_scanned_at` TIMESTAMP       NULL,
    `registered_at`    TIMESTAMP       NOT NULL DEFAULT CURRENT_TIMESTAMP,
    `created_at`       TIMESTAMP       NULL,
    `updated_at`       TIMESTAMP       NULL,
    PRIMARY KEY (`id`),
    CONSTRAINT `fk_visitors_event`
        FOREIGN KEY (`event_id`) REFERENCES `events`(`id`) ON DELETE CASCADE
) ENGINE=InnoDB DEFAULT CHARSET=utf8mb4 COLLATE=utf8mb4_unicode_ci;

-- ─── Table des scans (journal de présence) ────────────────────────────────
CREATE TABLE IF NOT EXISTS `scans` (
    `id`          BIGINT UNSIGNED NOT NULL AUTO_INCREMENT,
    `visitor_id`  BIGINT UNSIGNED NOT NULL,
    `scanned_at`  TIMESTAMP       NOT NULL DEFAULT CURRENT_TIMESTAMP,
    `kiosque_id`  VARCHAR(100)    NULL,
    `ip_address`  VARCHAR(45)     NULL,
    `created_at`  TIMESTAMP       NULL,
    `updated_at`  TIMESTAMP       NULL,
    PRIMARY KEY (`id`),
    CONSTRAINT `fk_scans_visitor`
        FOREIGN KEY (`visitor_id`) REFERENCES `visitors`(`id`) ON DELETE CASCADE
) ENGINE=InnoDB DEFAULT CHARSET=utf8mb4 COLLATE=utf8mb4_unicode_ci;

-- ─── Table des migrations Laravel ─────────────────────────────────────────
CREATE TABLE IF NOT EXISTS `migrations` (
    `id`        INT UNSIGNED    NOT NULL AUTO_INCREMENT,
    `migration` VARCHAR(255)    NOT NULL,
    `batch`     INT             NOT NULL,
    PRIMARY KEY (`id`)
) ENGINE=InnoDB DEFAULT CHARSET=utf8mb4 COLLATE=utf8mb4_unicode_ci;

-- ─── Données initiales : événement ITAF 2026 ──────────────────────────────
INSERT INTO `events` (`nom`, `slug`, `date_debut`, `date_fin`, `lieu`, `description`, `actif`, `created_at`, `updated_at`)
VALUES (
    'ITAF 2026',
    'itaf-2026',
    '2026-06-15',
    '2026-06-16',
    'Centre de Conférences Mohammed VI, Casablanca',
    'Forum International des Affaires et Technologies — 10ème édition',
    1,
    NOW(),
    NOW()
) ON DUPLICATE KEY UPDATE `actif` = 1;
