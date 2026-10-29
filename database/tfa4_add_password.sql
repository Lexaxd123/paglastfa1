-- ------------------------------------------------------------------
-- Morris POS - TFA4 upgrade script
-- Run this ONCE on a database that already has the TFA3 tables
-- (e.g. the hosted InfinityFree database). It keeps all existing rows.
-- Fresh installs should import pos_db.sql instead.
--
-- 1) adds users.password (holds a password_hash() value, never plain text)
-- 2) gives each EXISTING user a bcrypt hash of the shared demo password
--    (same result as: php spark db:seed UserPasswordSeeder)
-- Demo password for graders: see README.md. Change it after grading.
-- Users added later through the app get their own password hash.
-- ------------------------------------------------------------------
ALTER TABLE users
    ADD COLUMN password VARCHAR(255) NULL AFTER avatar;

UPDATE users SET password = '$2y$10$BTK02SjIRxWLqCGJhEs9Uu1HnGXy4mKGGoPawHcZnLI5KiHU8quSm' WHERE username = 'admin' AND password IS NULL;
UPDATE users SET password = '$2y$10$G14QRGw3hvV3ktpB4twTXubQS3vyMTERKkfvhevG7ytkeRzAiOrL2' WHERE username = 'juan.delacruz' AND password IS NULL;
UPDATE users SET password = '$2y$10$si3pojdb6fuoNBfQJ3E8NumKGa85Csll8nSQaNyLvc81u8ZQq/TlS' WHERE username = 'maria.santos' AND password IS NULL;
UPDATE users SET password = '$2y$10$rW/pKqTDnGoP1rkYlh9PBejmLG/Feg9XFpjnQF/wQ6D1O6u8ETXte' WHERE username = 'jose.rizal' AND password IS NULL;
UPDATE users SET password = '$2y$10$BLy.EYUed0t0NsTsDedryeIQCnbv.XuSD9FEqpDLXP8jJMbJjwd42' WHERE username = 'andres.bonifacio' AND password IS NULL;
