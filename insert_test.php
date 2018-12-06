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
            if ( empty($_SESSION['animal_name']) || empty($_SESSION['animal_vat']) || empty($_SESSION['date']) || empty($_REQUEST['ass_vat'])) {
                // Invalid request / user directly opened file.
                echo("<p>ERROR: not enough info!</p>");
            } else {
                // Request with all required parameters was made
                $ass_vat = strip_tags($_REQUEST['ass_vat'],"<b><i><a><p>");
                $ass_vat = htmlspecialchars($ass_vat);       

                // Database access
                $connection = require_once('db.php');

                // which num is the procedure
                $search_num = "SELECT MAX(num) as nr FROM `procedure` WHERE name = :anmal_name AND VAT_owner = :ownvat ";
                $finder = $connection->prepare($search_num);                
                $finder->bindParam(':ownvat', $_SESSION['animal_vat']);
                $finder->bindParam(':anmal_name', $_SESSION['animal_name']);
                if ( !$finder->execute() ) {
                    echo("<p>An error occurred! finder</p>");
                    exit();
                }
                $result = $finder->fetch();
                $num = $result['nr'] + 1;

                echo("<p>Procedure num ".$num."</p>");
                //$finder->close();
                
                // create a procedure!! (& test procedure)
                $insert_pro = "INSERT INTO `procedure` (name, VAT_owner, date_timestamp, num, description) VALUES (:anmal_name, :ownvat, :datestamp, :num, 'blood test with some indicators')";
                $inspro = $connection->prepare($insert_pro);
                $inspro->bindParam(':anmal_name', $_SESSION['animal_name']);
                $inspro->bindParam(':datestamp', $_SESSION['date']);
                $inspro->bindParam(':ownvat', $_SESSION['animal_vat']);
                $inspro->bindParam(':num', $num);
                if ( !$inspro->execute() ) {
                    echo("<p>An error occurred! The procedure was not added!</p>");
                    exit();
                }
                echo("<p> inserted procedure </p>");
                //$inspro->close();
                
                $insert_tp = "INSERT INTO test_procedure (name, VAT_owner, date_timestamp, num, type) VALUES (:anmal_name, :ownvat, :datestamp, :num, 'blood')";
                $instp = $connection->prepare($insert_tp);
                $instp->bindParam(':anmal_name', $_SESSION['animal_name']);
                $instp->bindParam(':datestamp', $_SESSION['date']);
                $instp->bindParam(':ownvat', $_SESSION['animal_vat']);
                $instp->bindParam(':num', $num);
                if ( !$instp->execute() ) {
                    echo("<p>An error occurred! The test procedure was not added!</p>");
                    exit();
                }
                echo("<p> inserted test procedure </p>");
                //$instp->close();
                
                // preformed by                
                $insert_pre = "INSERT INTO performed (name, VAT_owner, date_timestamp, num, VAT_assistant) VALUES (:anmal_name, :ownvat, :datestamp, :num, :assvat)";
                $inspre = $connection->prepare($insert_pre);
                $inspre->bindParam(':anmal_name', $_SESSION['animal_name']);
                $inspre->bindParam(':datestamp', $_SESSION['date']);
                $inspre->bindParam(':ownvat', $_SESSION['animal_vat']);
                $inspre->bindParam(':num', $num);
                $inspre->bindParam(':assvat', $ass_vat);
                if ( !$inspre->execute() ) {
                    echo("<p>An error occurred! The performed was not added!</p>");
                    exit();
                }
                echo("<p> inserted preformed </p>");
                //$inspre->close();
                
                // insert into produced indicator query
                if(!empty($_REQUEST['glic_result'])){

                    $glic_result = strip_tags($_REQUEST['glic_result'],"<b><i><a><p>");
                    $glic_result = htmlspecialchars($glic_result);
                    
                    $query_str = "INSERT INTO produced_indicator (name, VAT_owner, date_timestamp, num, indicator_name, value) VALUES (:anmal_name, :ownvat, :datestamp, :num, 'glicose', :value_gli)";
                    $stmt = $connection->prepare($query_str);

                    $stmt->bindParam(':anmal_name', $_SESSION['animal_name']);
                    $stmt->bindParam(':datestamp', $_SESSION['date']);
                    $stmt->bindParam(':value_gli', $glic_result);
                    $stmt->bindParam(':ownvat', $_SESSION['animal_vat']);
                    $stmt->bindParam(':num', $num);

                    if ( !$stmt->execute() ) {
                        echo("<p>An error occurred! The glicose indicator was not added!</p>");
                        exit();
                    }
                    //$stmt->close();          
                }
                
                if(!empty($_REQUEST['mp_result'])){

                    $mp_result = strip_tags($_REQUEST['mp_result'],"<b><i><a><p>");
                    $mp_result = htmlspecialchars($mp_result);
                    
                    $query_str = "INSERT INTO produced_indicator (name, VAT_owner, date_timestamp, num, indicator_name, value) VALUES (:anmal_name, :ownvat, :datestamp, :num, 'magic power', :value_mp)";
                    $stmt2 = $connection->prepare($query_str);

                    $stmt2->bindParam(':anmal_name', $_SESSION['animal_name']);
                    $stmt2->bindParam(':datestamp', $_SESSION['date']);
                    $stmt2->bindParam(':value_mp', $mp_result);
                    $stmt2->bindParam(':ownvat', $_SESSION['animal_vat']);
                    $stmt2->bindParam(':num', $num);

                    if ( !$stmt2->execute() ) {
                        echo("<p>An error occurred! The magic power indicator was not added!</p>");
                        exit();
                    }
                    //$stmt->close();          
                }
                
                if(!empty($_REQUEST['mp_result'])){

                    $cl_result = strip_tags($_REQUEST['cl_result'],"<b><i><a><p>");
                    $cl_result = htmlspecialchars($cl_result);
                    
                    $query_str = "INSERT INTO produced_indicator (name, VAT_owner, date_timestamp, num, indicator_name, value) VALUES (:anmal_name, :ownvat, :datestamp, :num, 'creatinine level', :value_cl)";
                    $stmt3 = $connection->prepare($query_str);

                    $stmt3->bindParam(':anmal_name', $_SESSION['animal_name']);
                    $stmt3->bindParam(':datestamp', $_SESSION['date']);
                    $stmt3->bindParam(':value_cl', $cl_result);
                    $stmt3->bindParam(':ownvat', $_SESSION['animal_vat']);
                    $stmt3->bindParam(':num', $num);

                    if ( !$stmt3->execute() ) {
                        echo("<p>An error occurred! The magic power indicator was not added!</p>");
                        exit();
                    }
                    //$stmt->close();          
                }
              
                echo("<p>SUCCESS: Test added successfully!</p>");                    
                
                $connection = NULL;
            }
        ?>
    </body>
</html>
