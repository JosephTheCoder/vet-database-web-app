<?php
    session_start();
?>

<!DOCTYPE html>
<html>
    <head>
        <title>Consult Details</title>
    </head>

    <body>
        <?php        
            if (empty($_GET['date']) || empty($_SESSION['animal_name'] || empty($_SESSION['animal_vat']))) {
                // Invalid request
                echo("<p><b>Invalid Search parameters.</b></p><p>Please include date, animal and owner names.</p>");
            } else { 
                // Process and cleaning :)
                $date_timestamp = strip_tags($_GET['date'],"<b><i><a><p>");
                $date_timestamp = htmlspecialchars($date_timestamp);
                $_SESSION['date'] = $date_timestamp;

                $animal_name = $_SESSION['animal_name'];

                // Database access
                $connection = require_once('db.php');
                $sql = "SELECT  animal.gender, animal.age, consult.weight, animal.species_name, animal.colour, consult.VAT_owner, consult.s, consult.o, consult.a, consult.p FROM consult, animal WHERE consult.VAT_owner = animal.VAT AND consult.name = animal.name AND consult.date_timestamp = :date_timestamp AND consult.name = :animal_name AND consult.VAT_owner = :animal_vat";
                
                $stmt = $connection->prepare($sql);
                
                $stmt->bindParam(':date_timestamp', $date_timestamp);
                $stmt->bindParam(':animal_name', $_SESSION['animal_name']);
                $stmt->bindParam(':animal_vat', $_SESSION['animal_vat']);
                
                if ( !$stmt->execute() ) {
                    echo("<p>An error occurred!</p>");
                    $connection = NULL;
                    exit();
                }
                
                echo("<p>-------------------------------------------------------------------</p>");
                echo("<p><h2>$animal_name Consult Details</h2><p>");
                echo("<p><h4>Date: $date_timestamp</h4><p>");
                echo("<a href=\"javascript:history.go(-1)\"><button><- Back</button></a>");
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
                
                // PRESCRIPTION QUERIES
                $sql = "SELECT consult_diagnosis.code, prescription.name_med, prescription.lab, prescription.dosage FROM consult LEFT JOIN consult_diagnosis ON (consult.VAT_owner = consult_diagnosis.VAT_owner AND consult.date_timestamp = consult_diagnosis.date_timestamp AND consult_diagnosis.name = consult.name) LEFT JOIN prescription ON (prescription.date_timestamp = consult_diagnosis.date_timestamp AND prescription.name = consult_diagnosis.name AND prescription.VAT_owner = consult_diagnosis.VAT_owner AND prescription.code = consult_diagnosis.code) WHERE consult.date_timestamp = :date_timestamp AND consult.name = :animal_name";

                $stmt = $connection->prepare($sql);

                $stmt->bindParam(':date_timestamp', $date_timestamp);
                $stmt->bindParam(':animal_name', $_SESSION['animal_name']);

                if ( $stmt->execute() ) {
                    echo("<p>-------------------------------------------------------------------</p>");
                    echo("<h4>Prescriptions of the animal:</h4>");

                    if ($stmt->rowCount() > 0 ) {
                        foreach ($stmt as $result) {
                            echo("<p><b>Diagnositc code:</b> ".$result['code']."</p>");
                            echo("<p><b>Med:</b> ".$result['name_med']."</p>");
                            echo("<p><b>Lab:</b> ".$result['lab']."</p>");
                            echo("<p><b>Dosage:</b> ".$result['dosage']."</p>");
                            echo("</table>");
                            echo("<p>-------------------------------------------------------------------</p>");
                        }
                    }
                } 
                 
                else {
                    echo("<p>$animal_name doesn't have any prescriptions in the clinic.</p>");
                }

                $connection = NULL;
            }
        ?>
    </body>
</html>
