-- =============================================================
-- Invoice System — MySQL dump
-- Laravel 11 / National Book Store POS (Ventic Branch)
-- Generated: 2026-05-19
--
-- Import via phpMyAdmin or:
--   mysql -u root -p invoice_system < db.sql
-- =============================================================

SET FOREIGN_KEY_CHECKS = 0;
SET SQL_MODE = 'NO_AUTO_VALUE_ON_ZERO';
SET NAMES utf8mb4;

-- -------------------------------------------------------------
-- Database
-- -------------------------------------------------------------
CREATE DATABASE IF NOT EXISTS `invoice_system`
    CHARACTER SET utf8mb4
    COLLATE utf8mb4_unicode_ci;

USE `invoice_system`;

-- =============================================================
-- STRUCTURE
-- =============================================================

-- -------------------------------------------------------------
-- customers
-- (no FK dependencies — must come before users & invoices)
-- -------------------------------------------------------------
DROP TABLE IF EXISTS `customers`;
CREATE TABLE `customers` (
    `id`             BIGINT UNSIGNED NOT NULL AUTO_INCREMENT,
    `name`           VARCHAR(255)    NOT NULL,
    `address`        VARCHAR(255)    NOT NULL,
    `contact_number` VARCHAR(255)    NOT NULL,
    `created_at`     TIMESTAMP       NULL,
    `updated_at`     TIMESTAMP       NULL,
    PRIMARY KEY (`id`)
) ENGINE=InnoDB DEFAULT CHARSET=utf8mb4 COLLATE=utf8mb4_unicode_ci;

-- -------------------------------------------------------------
-- users
-- -------------------------------------------------------------
DROP TABLE IF EXISTS `users`;
CREATE TABLE `users` (
    `id`                BIGINT UNSIGNED NOT NULL AUTO_INCREMENT,
    `customer_id`       BIGINT UNSIGNED NULL,
    `is_admin`          TINYINT(1)      NOT NULL DEFAULT 0,
    `name`              VARCHAR(255)    NOT NULL,
    `email`             VARCHAR(255)    NOT NULL,
    `email_verified_at` TIMESTAMP       NULL,
    `password`          VARCHAR(255)    NOT NULL,
    `remember_token`    VARCHAR(100)    NULL,
    `created_at`        TIMESTAMP       NULL,
    `updated_at`        TIMESTAMP       NULL,
    PRIMARY KEY (`id`),
    UNIQUE KEY `users_email_unique` (`email`),
    KEY `users_customer_id_foreign` (`customer_id`),
    CONSTRAINT `users_customer_id_foreign`
        FOREIGN KEY (`customer_id`) REFERENCES `customers` (`id`)
        ON DELETE SET NULL
) ENGINE=InnoDB DEFAULT CHARSET=utf8mb4 COLLATE=utf8mb4_unicode_ci;

-- -------------------------------------------------------------
-- password_reset_tokens
-- -------------------------------------------------------------
DROP TABLE IF EXISTS `password_reset_tokens`;
CREATE TABLE `password_reset_tokens` (
    `email`      VARCHAR(255) NOT NULL,
    `token`      VARCHAR(255) NOT NULL,
    `created_at` TIMESTAMP    NULL,
    PRIMARY KEY (`email`)
) ENGINE=InnoDB DEFAULT CHARSET=utf8mb4 COLLATE=utf8mb4_unicode_ci;

-- -------------------------------------------------------------
-- sessions
-- -------------------------------------------------------------
DROP TABLE IF EXISTS `sessions`;
CREATE TABLE `sessions` (
    `id`            VARCHAR(255)    NOT NULL,
    `user_id`       BIGINT UNSIGNED NULL,
    `ip_address`    VARCHAR(45)     NULL,
    `user_agent`    TEXT            NULL,
    `payload`       LONGTEXT        NOT NULL,
    `last_activity` INT             NOT NULL,
    PRIMARY KEY (`id`),
    KEY `sessions_user_id_index` (`user_id`),
    KEY `sessions_last_activity_index` (`last_activity`)
) ENGINE=InnoDB DEFAULT CHARSET=utf8mb4 COLLATE=utf8mb4_unicode_ci;

-- -------------------------------------------------------------
-- cache
-- -------------------------------------------------------------
DROP TABLE IF EXISTS `cache`;
CREATE TABLE `cache` (
    `key`        VARCHAR(255) NOT NULL,
    `value`      MEDIUMTEXT   NOT NULL,
    `expiration` INT          NOT NULL,
    PRIMARY KEY (`key`),
    KEY `cache_expiration_index` (`expiration`)
) ENGINE=InnoDB DEFAULT CHARSET=utf8mb4 COLLATE=utf8mb4_unicode_ci;

-- -------------------------------------------------------------
-- cache_locks
-- -------------------------------------------------------------
DROP TABLE IF EXISTS `cache_locks`;
CREATE TABLE `cache_locks` (
    `key`        VARCHAR(255) NOT NULL,
    `owner`      VARCHAR(255) NOT NULL,
    `expiration` INT          NOT NULL,
    PRIMARY KEY (`key`),
    KEY `cache_locks_expiration_index` (`expiration`)
) ENGINE=InnoDB DEFAULT CHARSET=utf8mb4 COLLATE=utf8mb4_unicode_ci;

-- -------------------------------------------------------------
-- jobs
-- -------------------------------------------------------------
DROP TABLE IF EXISTS `jobs`;
CREATE TABLE `jobs` (
    `id`           BIGINT UNSIGNED     NOT NULL AUTO_INCREMENT,
    `queue`        VARCHAR(255)        NOT NULL,
    `payload`      LONGTEXT            NOT NULL,
    `attempts`     TINYINT UNSIGNED    NOT NULL,
    `reserved_at`  INT UNSIGNED        NULL,
    `available_at` INT UNSIGNED        NOT NULL,
    `created_at`   INT UNSIGNED        NOT NULL,
    PRIMARY KEY (`id`),
    KEY `jobs_queue_index` (`queue`)
) ENGINE=InnoDB DEFAULT CHARSET=utf8mb4 COLLATE=utf8mb4_unicode_ci;

-- -------------------------------------------------------------
-- job_batches
-- -------------------------------------------------------------
DROP TABLE IF EXISTS `job_batches`;
CREATE TABLE `job_batches` (
    `id`             VARCHAR(255) NOT NULL,
    `name`           VARCHAR(255) NOT NULL,
    `total_jobs`     INT          NOT NULL,
    `pending_jobs`   INT          NOT NULL,
    `failed_jobs`    INT          NOT NULL,
    `failed_job_ids` LONGTEXT     NOT NULL,
    `options`        MEDIUMTEXT   NULL,
    `cancelled_at`   INT          NULL,
    `created_at`     INT          NOT NULL,
    `finished_at`    INT          NULL,
    PRIMARY KEY (`id`)
) ENGINE=InnoDB DEFAULT CHARSET=utf8mb4 COLLATE=utf8mb4_unicode_ci;

-- -------------------------------------------------------------
-- failed_jobs
-- -------------------------------------------------------------
DROP TABLE IF EXISTS `failed_jobs`;
CREATE TABLE `failed_jobs` (
    `id`         BIGINT UNSIGNED NOT NULL AUTO_INCREMENT,
    `uuid`       VARCHAR(255)    NOT NULL,
    `connection` TEXT            NOT NULL,
    `queue`      TEXT            NOT NULL,
    `payload`    LONGTEXT        NOT NULL,
    `exception`  LONGTEXT        NOT NULL,
    `failed_at`  TIMESTAMP       NOT NULL DEFAULT CURRENT_TIMESTAMP,
    PRIMARY KEY (`id`),
    UNIQUE KEY `failed_jobs_uuid_unique` (`uuid`)
) ENGINE=InnoDB DEFAULT CHARSET=utf8mb4 COLLATE=utf8mb4_unicode_ci;

-- -------------------------------------------------------------
-- products
-- (no FK dependencies — must come before invoice_items)
-- -------------------------------------------------------------
DROP TABLE IF EXISTS `products`;
CREATE TABLE `products` (
    `id`             BIGINT UNSIGNED  NOT NULL AUTO_INCREMENT,
    `name`           VARCHAR(255)     NOT NULL,
    `price`          DECIMAL(10, 2)   NOT NULL,
    `stock_quantity` INT UNSIGNED     NOT NULL DEFAULT 0,
    `category`       VARCHAR(255)     NULL,
    `description`    VARCHAR(255)     NULL,
    `created_at`     TIMESTAMP        NULL,
    `updated_at`     TIMESTAMP        NULL,
    PRIMARY KEY (`id`)
) ENGINE=InnoDB DEFAULT CHARSET=utf8mb4 COLLATE=utf8mb4_unicode_ci;

-- -------------------------------------------------------------
-- invoices
-- -------------------------------------------------------------
DROP TABLE IF EXISTS `invoices`;
CREATE TABLE `invoices` (
    `id`           BIGINT UNSIGNED NOT NULL AUTO_INCREMENT,
    `invoice_date` DATETIME        NOT NULL,
    `trx_no`       VARCHAR(255)    NOT NULL,
    `serial_no`    VARCHAR(255)    NOT NULL,
    `clerk`        VARCHAR(255)    NOT NULL,
    `term_no`      VARCHAR(255)    NOT NULL DEFAULT '0002',
    `amount_due`   DECIMAL(10, 2)  NOT NULL,
    `cash`         DECIMAL(10, 2)  NOT NULL,
    `change`       DECIMAL(10, 2)  NOT NULL,
    `vat_sales`    DECIMAL(10, 2)  NOT NULL,
    `vat`          DECIMAL(10, 2)  NOT NULL,
    `vat_exempt`   DECIMAL(10, 2)  NOT NULL,
    `vat_zero`     DECIMAL(10, 2)  NOT NULL,
    `total_sales`  DECIMAL(10, 2)  NOT NULL,
    `customer_id`  BIGINT UNSIGNED NULL,
    `created_at`   TIMESTAMP       NULL,
    `updated_at`   TIMESTAMP       NULL,
    PRIMARY KEY (`id`),
    UNIQUE KEY `invoices_trx_no_unique` (`trx_no`),
    UNIQUE KEY `invoices_serial_no_unique` (`serial_no`),
    KEY `invoices_customer_id_index` (`customer_id`)
) ENGINE=InnoDB DEFAULT CHARSET=utf8mb4 COLLATE=utf8mb4_unicode_ci;

-- -------------------------------------------------------------
-- invoice_items
-- -------------------------------------------------------------
DROP TABLE IF EXISTS `invoice_items`;
CREATE TABLE `invoice_items` (
    `id`         BIGINT UNSIGNED NOT NULL AUTO_INCREMENT,
    `invoice_id` BIGINT UNSIGNED NOT NULL,
    `product_id` BIGINT UNSIGNED NULL,
    `item_name`  VARCHAR(255)    NOT NULL,
    `quantity`   INT             NOT NULL,
    `price`      DECIMAL(10, 2)  NOT NULL,
    `amount`     DECIMAL(10, 2)  NOT NULL,
    `created_at` TIMESTAMP       NULL,
    `updated_at` TIMESTAMP       NULL,
    PRIMARY KEY (`id`),
    KEY `invoice_items_product_id_index` (`product_id`),
    CONSTRAINT `invoice_items_invoice_id_foreign`
        FOREIGN KEY (`invoice_id`) REFERENCES `invoices` (`id`)
        ON DELETE CASCADE
) ENGINE=InnoDB DEFAULT CHARSET=utf8mb4 COLLATE=utf8mb4_unicode_ci;

-- -------------------------------------------------------------
-- migrations (Laravel internal tracking)
-- -------------------------------------------------------------
DROP TABLE IF EXISTS `migrations`;
CREATE TABLE `migrations` (
    `id`        INT UNSIGNED NOT NULL AUTO_INCREMENT,
    `migration` VARCHAR(255) NOT NULL,
    `batch`     INT          NOT NULL,
    PRIMARY KEY (`id`)
) ENGINE=InnoDB DEFAULT CHARSET=utf8mb4 COLLATE=utf8mb4_unicode_ci;

INSERT INTO `migrations` (`migration`, `batch`) VALUES
('0001_01_01_000000_create_users_table', 1),
('0001_01_01_000001_create_cache_table', 1),
('0001_01_01_000002_create_jobs_table', 1),
('2026_05_08_050511_create_invoices_table', 1),
('2026_05_08_050520_create_invoice_items_table', 1),
('2026_05_08_050527_create_products_table', 1),
('2026_05_08_050534_create_customers_table', 1),
('2026_05_08_081500_add_customer_link_and_role_to_users_table', 1),
('2026_05_08_081510_add_stock_quantity_to_products_table', 1);

-- =============================================================
-- SEED DATA
-- Password hash below = bcrypt('password') cost 12
-- Change before deploying to production!
-- =============================================================

-- -------------------------------------------------------------
-- customers
-- -------------------------------------------------------------
INSERT INTO `customers` (`id`, `name`, `address`, `contact_number`, `created_at`, `updated_at`) VALUES
(1, 'Jade Ventic',      'Liloan, Cebu',       '0917-000-1122', NOW(), NOW()),
(2, 'Marco Dela Cruz',  'Mandaue City, Cebu', '0918-555-0199', NOW(), NOW());

-- -------------------------------------------------------------
-- users
-- password = 'password'  (bcrypt, cost 12)
-- -------------------------------------------------------------
INSERT INTO `users` (`id`, `customer_id`, `is_admin`, `name`, `email`, `email_verified_at`, `password`, `remember_token`, `created_at`, `updated_at`) VALUES
(1, NULL, 1, 'Ventic Branch Cashier', 'cashier@venticbranch.test', NOW(),
 '$2y$12$T7GjU5HxqxGJP5NpPNJGpe0JB3HWVOhEPfGnK8MYWRqLSQJSjq3Jq', NULL, NOW(), NOW()),
(2, 1,    0, 'Jade Ventic',           'jade@venticbranch.test',    NOW(),
 '$2y$12$T7GjU5HxqxGJP5NpPNJGpe0JB3HWVOhEPfGnK8MYWRqLSQJSjq3Jq', NULL, NOW(), NOW());

-- NOTE: The hash above may not match your app's bcrypt rounds.
-- The safest way to seed users is always: php artisan migrate:fresh --seed

-- -------------------------------------------------------------
-- products
-- -------------------------------------------------------------
INSERT INTO `products` (`id`, `name`, `price`, `stock_quantity`, `category`, `description`, `created_at`, `updated_at`) VALUES
(1, 'Introduction to Programming',  549.00, 12, 'Books',           'Starter programming textbook for first-year students.',        NOW(), NOW()),
(2, 'Discrete Mathematics Workbook',315.00, 10, 'Books',           'Practice workbook with drills and examples.',                  NOW(), NOW()),
(3, 'Campus Ledger Notebook',        48.00, 48, 'School Supplies', 'Receipt-inspired sample item matching the project brief.',     NOW(), NOW()),
(4, 'A4 Bond Paper Pack',           189.00, 20, 'School Supplies', 'Everyday printing paper pack for student use.',               NOW(), NOW());

SET FOREIGN_KEY_CHECKS = 1;
