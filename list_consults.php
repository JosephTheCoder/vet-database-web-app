<?php
    // We start a session in order to safe the search parameters.
    session_start();
?>

<!DOCTYPE html>
<html>
    <head>
        <title>Consult List</title>
    </head>

    <body>        
        <?php        
            if (empty($_GET['client']) || empty($_GET['animal'])) {
                // Invalid request
                echo("<p><b>Invalid Search parameters.</b></p><p>Please include client and animal names.</p>");
            } else { 
                // Process and cleaning :)
                $client_name = strip_tags($_GET['client'],"<b><i><a><p>");
                $client_name = htmlspecialchars($client_name);
                $_SESSION['client_name'] = $client_name;

                $animal_name = strip_tags($_GET['animal'],"<b><i><a><p>");
                $animal_name = htmlspecialchars($animal_name);
                $_SESSION['animal_name'] = $animal_name;

                // Database access
                $connection = require_once('db.php'); //TODO query
                $query_str = "SELECT consult.date_timestamp, consult.VAT_client, consult.VAT_vet FROM consult WHERE consult.name = :animal_name";
                $stmt = $connection->prepare($query_str);

                $stmt->bindParam(':animal_name', $animal_name);

                if ( !$stmt->execute() ) {
                    echo("<p>An error occurred!</p>");
                    exit();
                }
                
                echo("<p>-------------------------------------------------------------------</p>");
                echo("<p><h2>$animal_name Consult Dashboard</h2><p>");
                echo("<p>-------------------------------------------------------------------</p>");

                echo("<h3>List of consults</h3>");

                if ($stmt->rowCount() > 0 ) {
                    echo("<table border=1 cellpadding='3'>");
                    echo("<thead><tr><th>Date</th><th>Client</th><th>Vet</th></tr></thead>");

                    foreach($stmt as $query) {
                        echo("<tr><td><a href='consult_details.php?date=".$query['date_timestamp']."'>".$query['date_timestamp']."</a>
                        </td><td>".$query['VAT_client']."</td><td>".$query['VAT_vet']."</td><td><form action='new_test.php' method='get'>
                        <input type='submit' value='New test'/></form></td></tr>");
                    }

                    echo("</table>");
                } else {
                    echo("<p>$animal_name hasn't had any consults in the clinic.</p>");
                }

                include('new_consult.php');

                $connection = NULL;
            }
        ?>
    </body>
</html>