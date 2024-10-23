CREATE DATABASE pokemon
    DEFAULT CHARACTER SET utf8
    COLLATE utf8_unicode_ci;

USE pokemon;

CREATE TABLE pokemon (
  id BIGINT(20) NOT NULL AUTO_INCREMENT PRIMARY KEY,
  name VARCHAR(100) NOT NULL UNIQUE,
  type VARCHAR(100) NOT NULL,
  evolution INT(1) NOT NULL
) ENGINE=InnoDB DEFAULT CHARSET=utf8 COLLATE=utf8_unicode_ci;

CREATE USER 'pokeuser'@'localhost'
    IDENTIFIED BY 'productpassword';

GRANT ALL
    ON pokemon.*
    TO 'pokeuser'@'localhost';

FLUSH PRIVILEGES;