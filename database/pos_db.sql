-- ------------------------------------------------------------------
-- Morris POS - Database export (IT0049 TFA2)
-- Engine: MySQL 8 / MariaDB 10.x
--
-- Local (XAMPP / Laragon): run the whole file as-is.
-- Shared hosting (InfinityFree, etc.): the host creates the database
-- for you, so comment out the CREATE DATABASE and USE lines, select
-- your database in phpMyAdmin, then import this file.
-- ------------------------------------------------------------------

CREATE DATABASE IF NOT EXISTS pos_db
    CHARACTER SET utf8mb4
    COLLATE utf8mb4_general_ci;

USE pos_db;

DROP TABLE IF EXISTS customers;
DROP TABLE IF EXISTS users;

-- ------------------------------------------------------------------
-- Table: customers
-- ------------------------------------------------------------------
CREATE TABLE customers (
    id INT AUTO_INCREMENT PRIMARY KEY,
    full_name VARCHAR(100) NOT NULL,
    email VARCHAR(100) NOT NULL,
    phone VARCHAR(20),
    created_at DATETIME NOT NULL
);

-- ------------------------------------------------------------------
-- Table: users
-- ------------------------------------------------------------------
CREATE TABLE users (
    id INT AUTO_INCREMENT PRIMARY KEY,
    username VARCHAR(50) NOT NULL UNIQUE,
    full_name VARCHAR(100) NOT NULL,
    created_at DATETIME NOT NULL
);

-- ------------------------------------------------------------------
-- Sample data (same people as the TFA1 static arrays)
-- ------------------------------------------------------------------
INSERT INTO customers (full_name, email, phone, created_at) VALUES
    ('LexaFps',      'lexafps@example.com',      '09123456789', '2026-10-01 09:00:00'),
    ('Hitmanreborn', 'hitmanreborn@example.com', '09187654321', '2026-10-01 09:15:00'),
    ('Pedro Omsim',  'pedro@example.com',        '09112223344', '2026-10-02 10:30:00'),
    ('Ada Wong',     'ada@example.com',          '09998887766', '2026-10-03 13:45:00'),
    ('Zyrah',        'zyrah@example.com',        '09001112233', '2026-10-04 16:20:00');

INSERT INTO users (username, full_name, created_at) VALUES
    ('admin',          'Admin User',      '2026-09-28 08:00:00'),
    ('juan.delacruz',  'Juan Dela Cruz',  '2026-09-29 08:30:00'),
    ('maria.santos',   'Maria Santos',    '2026-09-29 09:00:00'),
    ('jose.rizal',     'Jose Rizal',      '2026-09-30 10:00:00'),
    ('andres.bonifacio','Andres Bonifacio','2026-09-30 10:30:00');
