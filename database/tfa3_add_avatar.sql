-- ------------------------------------------------------------------
-- Morris POS - TFA3 upgrade script
-- Run this ONCE on a database that already has the TFA2 tables
-- (e.g. the hosted InfinityFree database). It keeps all existing rows.
-- Fresh installs should import pos_db.sql instead.
-- ------------------------------------------------------------------
ALTER TABLE users
    ADD COLUMN avatar VARCHAR(255) NULL AFTER full_name;
