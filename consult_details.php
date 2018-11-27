<?php
    // We start a session in order to safe the search parameters.
    session_start();
?>

<!DOCTYPE html>
<html>
    <head>
        <title>Consult Details</title>
    </head>

    <body>        
        <?php        
            if (empty($_GET['date']) || empty($_SESSION['animal_name'])) {
                // Invalid request
                echo("<p><b>Invalid Search parameters.</b></p><p>Please include date and animal names.</p>");
            } else { 
                // Process and cleaning :)
                $date_timestamp = strip_tags($_GET['date'],"<b><i><a><p>");
                $date_timestamp = htmlspecialchars($date_timestamp);

                // Database access
                $connection = require_once('db.php'); //TODO query
                $query_str = "SELECT animal.gender, animal.age, consult.weight, consult.s, consult.o, consult.a, consult.p FROM consult, animal WHERE animal.name = :animal_name AND consult.date_timestamp = :date_timestamp";
                $stmt = $connection->prepare($query_str);

                $stmt->bindParam(':date_timestamp', $date_timestamp);
                $stmt->bindParam(':animal_name', $_SESSION['animal_name']);

                if ( !$stmt->execute() ) {
                    echo("<p>An error occurred!</p>");
                    exit();
                }
                
                echo("<p>-------------------------------------------------------------------</p>");
                echo("<p><h2>$animal_name Consult Details</h2><p>");
                echo("<p><h4>Date: $date_timestamp</h4><p>");
                echo("<p>-------------------------------------------------------------------</p>");

                echo("<h4>Characteristics of the animal:</h4>");

                if ($stmt->rowCount() > 0 ) {

                    $result = $stmt->fetch();
                    echo("<p><b>Gender:</b> ".$result['gender']."</p>");
                    echo("<p><b>Age:</b> ".$result['age']."</p>");
                    echo("<p><b>Weight:</b> ".$result['weight']."</p>");
                    
                    echo("<p><b>Subjective observation:</b> ".$result['s']."</p>");
                    echo("<p><b>Objective observation:</b> ".$result['o']."</p>");
                    echo("<p><b>Assessment:</b> ".$result['a']."</p>");
                    echo("<p><b>Plan:</b> ".$result['p']."</p>");

                    echo("</table>");
                } else {
                    echo("<p>$animal_name hasn't had any consults in the clinic.</p>");
                }

                $connection = NULL;
            }
        ?>
    </body>
</html>