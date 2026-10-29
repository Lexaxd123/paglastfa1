-- ------------------------------------------------------------------
-- Morris POS - Database export (IT0049 TFA4)
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
    avatar VARCHAR(255) NULL,          -- TFA3: filename of the prepared avatar
    password VARCHAR(255) NULL,        -- TFA4: password_hash() value, never plain text
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

-- avatar is NULL for the samples, so the listing shows the placeholder.
-- TFA4: every account has a bcrypt hash of the shared demo password
-- (see README.md); the plain password is never stored.
INSERT INTO users (username, full_name, password, created_at) VALUES
    ('admin', 'Admin User', '$2y$10$BTK02SjIRxWLqCGJhEs9Uu1HnGXy4mKGGoPawHcZnLI5KiHU8quSm', '2026-09-28 08:00:00'),
    ('juan.delacruz', 'Juan Dela Cruz', '$2y$10$G14QRGw3hvV3ktpB4twTXubQS3vyMTERKkfvhevG7ytkeRzAiOrL2', '2026-09-29 08:30:00'),
    ('maria.santos', 'Maria Santos', '$2y$10$si3pojdb6fuoNBfQJ3E8NumKGa85Csll8nSQaNyLvc81u8ZQq/TlS', '2026-09-29 09:00:00'),
    ('jose.rizal', 'Jose Rizal', '$2y$10$rW/pKqTDnGoP1rkYlh9PBejmLG/Feg9XFpjnQF/wQ6D1O6u8ETXte', '2026-09-30 10:00:00'),
    ('andres.bonifacio', 'Andres Bonifacio', '$2y$10$BLy.EYUed0t0NsTsDedryeIQCnbv.XuSD9FEqpDLXP8jJMbJjwd42', '2026-09-30 10:30:00');
