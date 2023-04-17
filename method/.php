<?php
    $db_host = "123.240.100.75";
    $db_username = "docker_user";
    $db_password = "qwer61134";
    $db_name = "insidetechnews";

        try {
            $conn = new pdo("mysql:host=$db_host;dbname=$db_name;charset=utf8", $db_username, $db_password);
            $conn -> setAttribute(PDO::ATTR_ERRMODE, PDO::ERRMODE_EXCEPTION);
        } catch (PDOException $e) {
            echo "Connet failed:".$e -> getMessage();
        }
?>