CREATE DATABASE Camagru;

use Camagru;

CREATE TABLE Users(
    `id` int(11) NOT NULL PRIMARY KEY AUTO_INCREMENT,
    `email` VARCHAR(255) NOT NULL,
    `name` VARCHAR(255) NOT NULL,
    `password` VARCHAR(255) NOT NULL,
    `vkey` VARCHAR(255) NOT NULL,
    `verify` int(11) NOT NULL DEFAULT 0,
    `created_at` datetime NOT NULL DEFAULT CURRENT_TIMESTAMP,
    `notify` int(11) NOT NULL DEFAULT 0
);

CREATE TABLE Posts(
    `id` int(11) NOT NULL AUTO_INCREMENT,
    `user_id` int(11)  NOT NULL,
    `img_dir` LONGBLOB NOT NULL,
    `type` VARCHAR(55) NOT NULL,
    `filter` VARCHAR(255) NOT NULL DEFAULT "none",
    `created_at` datetime NOT NULL DEFAULT CURRENT_TIMESTAMP,
    PRIMARY KEY (id),
    FOREIGN KEY (user_id) REFERENCES Users(id) ON DELETE CASCADE
);

CREATE TABLE Comment(
    `id` int(11) NOT NULL AUTO_INCREMENT,
    `post_id` int(11) NOT NULL,
    `user_id` int(11) NOT NULL,
    `comment` VARCHAR(55) NOT NULL,
    `created_at` DATETIME NOT NULL DEFAULT CURRENT_TIMESTAMP,
    PRIMARY KEY (id),
    FOREIGN KEY (post_id) REFERENCES Posts(id) ON DELETE CASCADE
);

CREATE TABLE Likes(
    `id` int(11) NOT NULL AUTO_INCREMENT,
    `post_id` int(11) NOT NULL,
    `user_id` int(11) NOT NULL,
    `like` int(11) NOT NULL,
    `created_at` DATETIME NOT NULL DEFAULT CURRENT_TIMESTAMP,
    PRIMARY KEY (id),
    FOREIGN KEY (post_id) REFERENCES Posts(id) ON DELETE CASCADE
)

ALTER TABLE Users 
ADD notify int(11) NOT NULL DEFAULT 0;