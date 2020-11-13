<?php
    // Set a setup for database camagru
    $host = DB_HOST;
    $username = DB_USER;
    $password = DB_PASS;

    try {
        $conn = new PDO("mysql:host=$host", $username, $password);
        // set the PDO error mode to exception
        $conn->setAttribute(PDO::ATTR_ERRMODE, PDO::ERRMODE_EXCEPTION);
        $sql = "CREATE DATABASE if not exists Camagru;
        use Camagru;
        CREATE TABLE IF NOT EXISTS Users(
            `id` int(11) NOT NULL PRIMARY KEY AUTO_INCREMENT,
            `email` VARCHAR(255) NOT NULL,
            `name` VARCHAR(255) NOT NULL,
            `password` VARCHAR(255) NOT NULL,
            `vkey` VARCHAR(255) NOT NULL,
            `verify` int(11) NOT NULL DEFAULT 0,
            `created_at` datetime NOT NULL DEFAULT CURRENT_TIMESTAMP,
            `notify` int(11) NOT NULL DEFAULT 0
        );
        CREATE TABLE IF NOT EXISTS Posts(
            `id` int(11) NOT NULL AUTO_INCREMENT,
            `user_id` int(11)  NOT NULL,
            `img_dir` LONGBLOB NOT NULL,
            `type` VARCHAR(55) NOT NULL,
            `filter` VARCHAR(255) NOT NULL DEFAULT 'none',
            `created_at` datetime NOT NULL DEFAULT CURRENT_TIMESTAMP,
            PRIMARY KEY (id),
            FOREIGN KEY (user_id) REFERENCES Users(id) ON DELETE CASCADE
        );
        CREATE TABLE IF NOT EXISTS Comment(
            `id` int(11) NOT NULL AUTO_INCREMENT,
            `post_id` int(11) NOT NULL,
            `user_id` int(11) NOT NULL,
            `comment` VARCHAR(55) NOT NULL,
            `created_at` DATETIME NOT NULL DEFAULT CURRENT_TIMESTAMP,
            PRIMARY KEY (id),
            FOREIGN KEY (post_id) REFERENCES Posts(id) ON DELETE CASCADE
        );
        CREATE TABLE IF NOT EXISTS Likes(
            `id` int(11) NOT NULL AUTO_INCREMENT,
            `post_id` int(11) NOT NULL,
            `user_id` int(11) NOT NULL,
            `like` int(11) NOT NULL,
            `created_at` DATETIME NOT NULL DEFAULT CURRENT_TIMESTAMP,
            PRIMARY KEY (id),
            FOREIGN KEY (post_id) REFERENCES Posts(id) ON DELETE CASCADE
        );
        ";
        // use exec() because no results are returned
        $conn->exec($sql);
        
  } catch(PDOException $e) {
    echo $sql . "<br>" . $e->getMessage();
  }
    ?>