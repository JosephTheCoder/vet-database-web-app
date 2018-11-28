<?php
    $hostname = "db.tecnico.ulisboa.pt";
    $username= "ist425422";
    $password = "ehhn4994";

    $dsn = "mysql:host=$hostname;dbname=$username";

    try {
        return new PDO($dsn, $username, $password);
    } catch (PDOException $e) {
        echo("<p>ERROR: " . $e->getMessage() . "</p>");
        exit();
    }
?>