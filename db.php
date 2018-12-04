<?php
    // muda-se para a db que quiserem.
    $hostname = "db.tecnico.ulisboa.pt";
    $username= "ist181570";
    $password = "eeua4108";

    $dsn = "mysql:host=$hostname;dbname=$username";

    try {
        return new PDO($dsn, $username, $password);
    } catch (PDOException $e) {
        echo("<p>ERROR: " . $e->getMessage() . "</p>");
        exit();
    }
?>
