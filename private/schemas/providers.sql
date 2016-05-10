--
-- Table schema for `provders` table
--
CREATE TABLE `providers` (
    `id`           INTEGER PRIMARY KEY AUTOINCREMENT NOT NULL,
    `name`         VARCHAR(250) NOT NULL,
    `location`     VARCHAR(250) NOT NULL,
    `phone_number` VARCHAR(15),
    `provides`     VARCHAR(250)
);
