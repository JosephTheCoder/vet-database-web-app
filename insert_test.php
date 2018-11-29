<?php
    // We start a session in order to safe the search parameters.
    session_start();
?>

<!-- TOTALLY INCOMPLETE!! YET TO TEST -->

<!DOCTYPE html>
<html>
    <head>
        <title>Inserting bloodtest</title>
    </head>
    <body>
        <?php
            if ( empty($_SESSION['animal_name']) || empty($_SESSION['client_name']) || empty($_SESSION['date']) || empty($_REQUEST['ass_vat'])) {
                // Invalid request / user directly opened file.
                echo("<p>ERROR: not enough info!</p>");
            } else {
                // Request with all required parameters was made
                $ass_vat = strip_tags($_REQUEST['ass_vat'],"<b><i><a><p>");
                $ass_vat = htmlspecialchars($ass_vat);

                // Database access
                $connection = require_once('db.php');

                // find the owner vat
                $search_vat = "SELECT client.VAT FROM person, client WHERE person.name = client.name AND person.name = :clnt_name";
                $finder = $connection->prepare($search_vat);
                $finder->bindParam(':clnt_name', $_SESSION['client_name']);
                if ( !$finder->execute() ) {
                    echo("<p>An error occurred! finder</p>");
                    exit();
                }
                $result = $finder->fetch();
                $owner_vat = $result['VAT'];
                $finder->close();
                
                // which num is the procedure
                $search_num = "SELECT MAX(num) as nr FROM `procedure` WHERE name = :anmal_name AND VAT_owner = :ownvat ";
                $finder2 = $connection->prepare($search_num);                
                $finder2->bindParam(':ownvat', $owner_vat);
                $finder2->bindParam(':anmal_name', $_SESSION['animal_name']);
                if ( !$finder2->execute() ) {
                    echo("<p>An error occurred! finder2</p>");
                    exit();
                }
                $result = $finder->fetch();
                $num = $result['nr'] + 1; // can i do this?
                $finder2->close();
                
                // create a procedure!! (& test procedure)
                $insert_pro = "INSERT INTO `procedure` VALUES (:anmal_name, :ownvat, :datestamp, :num, 'blood test')";
                $inspro = $connection->prepare($insert_pro);
                $inspro->bindParam(':anmal_name', $_SESSION['animal_name']);
                $inspro->bindParam(':datestamp', $_SESSION['date']);
                $inspro->bindParam(':ownvat', $owner_vat);
                $inspro->bindParam(':num', $num);
                if ( !$inspro->execute() ) {
                    echo("<p>An error occurred! The procedure was not added!</p>");
                    exit();
                }
                $inspro->close();
                
                $insert_tp = "INSERT INTO test_procedure VALUES (:anmal_name, :ownvat, :datestamp, :num, 'blood')";
                $instp = $connection->prepare($insert_tp);
                $instp->bindParam(':anmal_name', $_SESSION['animal_name']);
                $instp->bindParam(':datestamp', $_SESSION['date']);
                $instp->bindParam(':ownvat', $owner_vat);
                $instp->bindParam(':num', $num);
                if ( !$instp->execute() ) {
                    echo("<p>An error occurred! The test procedure was not added!</p>");
                    exit();
                }
                $instp->close();
                
                // preformed by                
                $insert_pre = "INSERT INTO `procedure` VALUES (:anmal_name, :ownvat, :datestamp, :num, :assvat)";
                $inspre = $connection->prepare($insert_pre);
                $inspre->bindParam(':anmal_name', $_SESSION['animal_name']);
                $inspre->bindParam(':datestamp', $_SESSION['date']);
                $inspre->bindParam(':ownvat', $owner_vat);
                $inspre->bindParam(':num', $num);
                $inspre->bindParam(':assvat', $ass_vat);
                if ( !$inspre->execute() ) {
                    echo("<p>An error occurred! The preformed was not added!</p>");
                    exit();
                }
                $inspre->close();
                
                // insert into produced indicator query
                if(!empty($_REQUEST['glic_result'])){

                    $glic_result = strip_tags($_REQUEST['glic_result'],"<b><i><a><p>");
                    $glic_result = htmlspecialchars($glic_result);
                    
                    $query_str = "INSERT INTO produced_indicator VALUES (:anmal_name, :ownvat, :datestamp, :num, 'glicose', :value_gli), (:anmal_name, :ownvat, :datestamp, :num, 'magic power', :value_mp), (:anmal_name, :ownvat, :datestamp, :num, 'creatinine level', :value_cl)";
                    $stmt = $connection->prepare($query_str);

                    $stmt->bindParam(':anmal_name', $_SESSION['animal_name']);
                    $stmt->bindParam(':datestamp', $_SESSION['date']);
                    $stmt->bindParam(':value_gli', $glic_result);
                    $stmt->bindParam(':ownvat', $owner_vat);
                    $stmt->bindParam(':num', $num);

                    if ( !$stmt->execute() ) {
                        echo("<p>An error occurred! The test was not added!</p>");
                        exit();
                    }
                    $stmt->close();                
                }
                
                // MISSING a query for each other produced indicator
              
                echo("<p>SUCCESS: Test added successfully!</p>");                    
                
                $connection = NULL;
            }
        ?>
    </body>
</html>
