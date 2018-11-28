<?php
    // We start a session in order to safe the search parameters.
    session_start();
?>

<!-- TOTALLY INCOMPLETE!! YET TO FINISH: HOW DO I TAKE OWNER VAT AND NUM-->

<!DOCTYPE html>
<html>
    <head>
        <title>Inserting bloodtest</title>
    </head>
    <body>
        <?php
            if ( empty($_SESSION['animal_name']) || empty($_SESSION['client_name']) || empty($_SESSION['date'])) {
                // Invalid request / user directly opened file.
                echo("<p>ERROR: not enough info!</p>");
            } else {
                // Request with all required parameters was made
                $ass_vat = strip_tags($_REQUEST['ass_vat'],"<b><i><a><p>");
                $ass_vat = htmlspecialchars($ass_vat);

                $glic_result = strip_tags($_REQUEST['glic_result'],"<b><i><a><p>");
                $glic_result = htmlspecialchars($glic_result);

                $mp_result = strip_tags($_REQUEST['mp_result'],"<b><i><a><p>");
                $mp_result = htmlspecialchars($mp_result);

                $cl_result = strip_tags($_REQUEST['cl_result'],"<b><i><a><p>");
                $cl_result = htmlspecialchars($cl_result);

                // Database access
                $connection = require_once('db.php');

                // find which num and owner vat
                $search_vat = "SELECT client.VAT FROM person, client WHERE person.name = client.name AND person.name = :clnt_name";
                $finder = $connection->prepare($search_vat);
                $finder->bindParam(':clnt_name', $_SESSION['client_name']);

                $search_num = "SELECT MAX(num) FROM `procedure` WHERE name = :anmal_name AND VAT_owner = :ownvat ";


                // insert into produced indicator query
                $query_str = "INSERT INTO produced_indicator(name, VAT_owner, date_timestamp, num, indicator_name, value) VALUES (:anmal_name, :ownvat, :datestamp, :num, 'glicose', :value_gli), (:anmal_name, :ownvat, :datestamp, :num, 'magic power', :value_mp), (:anmal_name, :ownvat, :datestamp, :num, 'creatinine level', :value_cl)";
                $stmt = $connection->prepare($query_str);
                
                $stmt->bindParam(':anmal_name', $_SESSION['animal_name']);
                $stmt->bindParam(':datestamp', $_SESSION['date']);
                $stmt->bindParam(':value_gli', $glic_result);
                $stmt->bindParam(':value_mp', $mp_result);
                $stmt->bindParam(':value_cl', $cl_result);
                $stmt->bindParam(':ownvat', $owner_vat);
                $stmt->bindParam(':num', $num);
                
                if ( !$stmt->execute() ) {
                    echo("<p>An error occurred! The test was not added!</p>");
                    exit();
                }
              
                echo("<p>SUCCESS: Test added successfully!</p>");                    
        
                // Close connection
                $stmt->close();
                $connection = NULL;
            }
        ?>
    </body>
</html>