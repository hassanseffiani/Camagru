//extract sql from phpmyadmin
//set up your file in good way.
//$DB_DSN modify name
<?php
require "database.php";
$db = "CREATE DATABASE IF NOT EXISTS Camagru; USE Camagru;";

$user = "CREATE TABLE IF NOT EXISTS `CAMAGRU`.`Users`(
    `id` int(11) NOT NULL PRIMARY KEY AUTO_INCREMENT,
  `email` VARCHAR(255) NOT NULL,
  `name` VARCHAR(255) NOT NULL,
  `password` VARCHAR(255) NOT NULL,
  `vkey` VARCHAR(255) NOT NULL,
  `verify` int(11) NOT NULL DEFAULT 0,
  `created_at` datetime NOT NULL DEFAULT CURRENT_TIMESTAMP) ENGINE = InnoDB;";

$post = "CREATE TABLE IF NOT EXISTS `CAMAGRU`.`Posts`(
    `id` int(11) NOT NULL AUTO_INCREMENT,
  `user_id` int(11)  NOT NULL,
  `img_dir` LONGBLOB NOT NULL,
  `type` VARCHAR(55) NOT NULL,
  `filter` VARCHAR(255) NOT NULL DEFAULT 'none',
  `created_at` datetime NOT NULL DEFAULT CURRENT_TIMESTAMP,
  PRIMARY KEY (id),
  FOREIGN KEY (user_id) REFERENCES Users(id) ON DELETE CASCADE) ENGINE = InnoDB;";

$comment = "CREATE TABLE IF NOT EXISTS `CAMAGRU`.`Comment`(
              `id` int(11) NOT NULL AUTO_INCREMENT,
            `post_id` int(11) NOT NULL,
            `user_id` int(11) NOT NULL,
            `comment` VARCHAR(55) NOT NULL,
            `created_at` DATETIME NOT NULL DEFAULT CURRENT_TIMESTAMP,
            PRIMARY KEY (id),
            FOREIGN KEY (post_id) REFERENCES Posts(id) ON DELETE CASCADE) ENGINE = InnoDB;";

$like = "CREATE TABLE IF NOT EXISTS `CAMAGRU`.`Likes`(
              `id` int(11) NOT NULL AUTO_INCREMENT,
            `post_id` int(11) NOT NULL,
            `user_id` int(11) NOT NULL,
            `like` int(11) NOT NULL,
            `created_at` DATETIME NOT NULL DEFAULT CURRENT_TIMESTAMP,
            PRIMARY KEY (id),
            FOREIGN KEY (post_id) REFERENCES Posts(id) ON DELETE CASCADE) ENGINE = InnoDB;";



try {
  $pdo = new PDO(DB_DSN, DB_USER, DB_PASS);
//   $pdo->exec("CREATE DATABASE IF NOT EXISTS Camagru; USE Camagru");
$pdo->exec($db . $user . $post . $like . $comment);
}
catch(PDOException $ex){
    $msg = "Failed to connect to the database";
}
?>
