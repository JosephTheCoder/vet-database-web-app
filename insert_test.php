<?php
    session_start();
?>

<!DOCTYPE html>
<html>
    <head>
        <title>Inserting bloodtest</title>
    </head>
    <body>
        <?php
            if ( empty($_SESSION['animal_name']) || empty($_SESSION['animal_vat']) || empty($_SESSION['date']) || empty($_REQUEST['assistant_vat'])) {
                // Invalid request / user directly opened file.
                echo("<p>ERROR: New blood test info is incomplete!</p>");
                echo("<a href=\"javascript:history.go(-1)\"><button><- Back</button></a>");
            } else {
                // Request with all required parameters was made
                $assistant_vat = strip_tags($_REQUEST['assistant_vat'],"<b><i><a><p>");
                $assistant_vat = htmlspecialchars($assistant_vat);       

                // Database access
                $connection = require_once('db.php');

                // which num is the procedure
                $sql = "SELECT MAX(num) as nr FROM `procedure` WHERE name = :anmal_name AND VAT_owner = :ownvat ";
                $stmt = $connection->prepare($sql);                
                $stmt->bindParam(':ownvat', $_SESSION['animal_vat']);
                $stmt->bindParam(':anmal_name', $_SESSION['animal_name']);

                if ( !$stmt->execute() ) {
                    echo("<p>An error while accessing the existing procedures</p>");
                    echo("<a href=\"javascript:history.go(-1)\"><button><- Back</button></a>");
                    $connection = NULL;
                    exit();
                }

                $result = $stmt->fetch();
                $num = $result['nr'] + 1;
                
                $connection->beginTransaction();  // begin the database transaction
                
                // create a procedure!! (& test procedure)
                $sql = "INSERT INTO `procedure` (name, VAT_owner, date_timestamp, num, description) VALUES (:anmal_name, :ownvat, :datestamp, :num, 'blood test with some indicators')";
                $stmt = $connection->prepare($sql);
                $stmt->bindParam(':anmal_name', $_SESSION['animal_name']);
                $stmt->bindParam(':datestamp', $_SESSION['date']);
                $stmt->bindParam(':ownvat', $_SESSION['animal_vat']);
                $stmt->bindParam(':num', $num);

                if ( !$stmt->execute() ) {
                    echo("<p>An error occurred! The procedure was not added!</p>");
                    echo("<a href=\"javascript:history.go(-1)\"><button><- Back</button></a>");
                    $connection = NULL;
                    exit();
                }
                
                $sql = "INSERT INTO test_procedure (name, VAT_owner, date_timestamp, num, type) VALUES (:anmal_name, :ownvat, :datestamp, :num, 'blood')";
                $stmt = $connection->prepare($sql);
                $stmt->bindParam(':anmal_name', $_SESSION['animal_name']);
                $stmt->bindParam(':datestamp', $_SESSION['date']);
                $stmt->bindParam(':ownvat', $_SESSION['animal_vat']);
                $stmt->bindParam(':num', $num);

                if ( !$stmt->execute() ) {
                    echo("<p>An error occurred! The test procedure was not added!</p>");
                    echo("<a href=\"javascript:history.go(-1)\"><button><- Back</button></a>");
                    $connection->rollback();
                    $connection = NULL;
                    exit();
                }
                
                // preformed by                
                $sql = "INSERT INTO performed (name, VAT_owner, date_timestamp, num, VAT_assistant) VALUES (:anmal_name, :ownvat, :datestamp, :num, :assvat)";
                $stmt = $connection->prepare($sql);
                $stmt->bindParam(':anmal_name', $_SESSION['animal_name']);
                $stmt->bindParam(':datestamp', $_SESSION['date']);
                $stmt->bindParam(':ownvat', $_SESSION['animal_vat']);
                $stmt->bindParam(':num', $num);
                $stmt->bindParam(':assvat', $assistant_vat);

                if ( !$stmt->execute() ) {
                    echo("<p>An error occurred! The procedure was not added!</p>");

                    if ($stmt->errorInfo()[0] == "23000"){
                        echo("<p>The assistant's VAT is non existant.</p>");
                    }
                    
                    echo("<a href=\"javascript:history.go(-1)\"><button><- Back</button></a>");
                    $connection->rollback();
                    $connection = NULL;
                    exit();
                }

                // Insert each one of the procedures, starting with the magic_power
                if(!empty($_REQUEST['magic_power'])){

                    $magic_power = strip_tags($_REQUEST['magic_power'],"<b><i><a><p>");
                    $magic_power = htmlspecialchars($magic_power);
                    
                    $sql = "INSERT INTO produced_indicator (name, VAT_owner, date_timestamp, num, indicator_name, value) VALUES (:anmal_name, :ownvat, :datestamp, :num, 'magic power', :value_ind)";
                    $stmt = $connection->prepare($sql);

                    $stmt->bindParam(':anmal_name', $_SESSION['animal_name']);
                    $stmt->bindParam(':datestamp', $_SESSION['date']);
                    $stmt->bindParam(':value_ind', $magic_power);
                    $stmt->bindParam(':ownvat', $_SESSION['animal_vat']);
                    $stmt->bindParam(':num', $num);

                    if ( !$stmt->execute() ) {
                        echo("<p>An error occurred! The magic power indicator was not added, the procedure was deleted!</p>");
                        echo("<a href=\"javascript:history.go(-1)\"><button><- Back</button></a>");
                        $connection->rollback();
                        $connection = NULL;
                        exit();
                    }     
                }
                
                // Insert glicose results indicator
                if(!empty($_REQUEST['glicose'])){

                    $glicose = strip_tags($_REQUEST['glicose'],"<b><i><a><p>");
                    $glicose = htmlspecialchars($glicose);
                    
                    $sql = "INSERT INTO produced_indicator (name, VAT_owner, date_timestamp, num, indicator_name, value) VALUES (:anmal_name, :ownvat, :datestamp, :num, 'glicose', :value_ind)";
                    $stmt = $connection->prepare($sql);

                    $stmt->bindParam(':anmal_name', $_SESSION['animal_name']);
                    $stmt->bindParam(':datestamp', $_SESSION['date']);
                    $stmt->bindParam(':value_ind', $glicose);
                    $stmt->bindParam(':ownvat', $_SESSION['animal_vat']);
                    $stmt->bindParam(':num', $num);

                    if ( !$stmt->execute() ) {
                        echo("<p>An error occurred! The glicose indicator was not added, the procedure was deleted!</p>");
                        echo("<a href=\"javascript:history.go(-1)\"><button><- Back</button></a>");
                        $connection->rollback();
                        $connection = NULL;
                        exit();
                    }
                }
                

                // Insert creatinine results indicator
                if(!empty($_REQUEST['creatinine'])){

                    $creatinine = strip_tags($_REQUEST['creatinine'],"<b><i><a><p>");
                    $creatinine = htmlspecialchars($creatinine);
                    
                    $sql = "INSERT INTO produced_indicator (name, VAT_owner, date_timestamp, num, indicator_name, value) VALUES (:anmal_name, :ownvat, :datestamp, :num, 'creatinine level', :value_ind)";
                    $stmt = $connection->prepare($sql);

                    $stmt->bindParam(':anmal_name', $_SESSION['animal_name']);
                    $stmt->bindParam(':datestamp', $_SESSION['date']);
                    $stmt->bindParam(':value_ind', $creatinine);
                    $stmt->bindParam(':ownvat', $_SESSION['animal_vat']);
                    $stmt->bindParam(':num', $num);

                    if ( !$stmt->execute() ) {
                        echo("<p>An error occurred! The creatinine indicator was not added, the procedure was deleted!</p>");
                        echo("<a href=\"javascript:history.go(-1)\"><button><- Back</button></a>");
                        $connection->rollback();
                        $connection = NULL;
                        exit();
                    }     
                }
                
                
                $connection->commit();
                echo("<p>The blood test was added successfully!</p>");                    
                
                $connection = NULL;
            }
        ?>
    </body>
</html>
