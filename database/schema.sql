CREATE DATABASE vanilla_chirp DEFAULT CHARACTER SET utf8mb4 COLLATE utf8mb4_unicode_ci;
USE vanilla_chirp;

CREATE TABLE users
(
    id         BIGINT UNSIGNED AUTO_INCREMENT PRIMARY KEY,
    name       VARCHAR(255) NOT NULL,
    email      VARCHAR(255) NOT NULL,
    password   VARCHAR(255) NOT NULL,
    created_at TIMESTAMP DEFAULT CURRENT_TIMESTAMP,
    updated_at TIMESTAMP DEFAULT CURRENT_TIMESTAMP ON UPDATE CURRENT_TIMESTAMP,

    CONSTRAINT email_users_unique UNIQUE (email)
);

CREATE TABLE chirps
(
    id         BIGINT UNSIGNED AUTO_INCREMENT PRIMARY KEY,
    user_id    BIGINT UNSIGNED NOT NULL,
    chirp_id   BIGINT UNSIGNED DEFAULT NULL,
    content    TINYTEXT        NOT NULL,
    created_at TIMESTAMP       DEFAULT CURRENT_TIMESTAMP,
    updated_at TIMESTAMP       DEFAULT CURRENT_TIMESTAMP ON UPDATE CURRENT_TIMESTAMP,

    FOREIGN KEY (user_id) REFERENCES users (id),
    FOREIGN KEY (chirp_id) REFERENCES chirps (id)
);

CREATE TABLE likes
(
    id         BIGINT UNSIGNED AUTO_INCREMENT PRIMARY KEY,
    user_id    BIGINT UNSIGNED NOT NULL,
    chirp_id   BIGINT UNSIGNED NOT NULL,
    created_at TIMESTAMP DEFAULT CURRENT_TIMESTAMP,
    updated_at TIMESTAMP DEFAULT CURRENT_TIMESTAMP ON UPDATE CURRENT_TIMESTAMP,

    -- A user cannot like the same chirp more than once.
    CONSTRAINT user_id_chirp_id_likes_unique UNIQUE (user_id, chirp_id),

    FOREIGN KEY (user_id) REFERENCES users (id),
    FOREIGN KEY (chirp_id) REFERENCES chirps (id)
);