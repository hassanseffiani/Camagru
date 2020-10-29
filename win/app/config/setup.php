<?php
    $user = DB_USER;
    $password = DB_PASS;
    $host = DB_HOST;
    $dbname = "myDBPDO";

    $conn = new PDO("mysql:host=$host;dbname=$dbname", $user, $password);
    // set the PDO error mode to exception
    $conn->setAttribute(PDO::ATTR_ERRMODE, PDO::ERRMODE_EXCEPTION);
    $sql = "CREATE DATABASE myDBPDO";
    // use exec() because no results are returned
    $conn->exec($sql);
    $sql = "CREATE TABLE Users(
        `id` int(11) NOT NULL PRIMARY KEY AUTO_INCREMENT,
        `email` VARCHAR(255) NOT NULL,
        `name` VARCHAR(255) NOT NULL,
        `password` VARCHAR(255) NOT NULL,
        `vkey` VARCHAR(255) NOT NULL,
        `verify` int(11) NOT NULL DEFAULT 0,
        `created_at` datetime NOT NULL DEFAULT CURRENT_TIMESTAMP
    )";
    $conn->exec($sql);
    echo "Database created successfully<br>";

    $conn = null;