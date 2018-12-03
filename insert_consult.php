<?php
    // We start a session in order to safe the search parameters.
    session_start();
?>

<!DOCTYPE html>
<html>
    <head>
        <title>Adding a new consult</title> 
    </head>
    <body>
        <?php //basically copied from insert_animal.php
            if ( empty($_REQUEST['consult_vat_cli']) || empty($_REQUEST['consult_vat_vet']) || empty($_SESSION['animal_name']) || empty($_SESSION['animal_vat'])) {
                // Invalid request / user directly opened file.
                echo("<p>ERROR: Consult info must be provided on the request!</p>");
            } else {
                // Request with all required parameters was made
                $vat_vet = strip_tags($_REQUEST['consult_vat_vet'],"<b><i><a><p>");
                $vat_vet = htmlspecialchars($vat_vet);   

                $vat_cli = strip_tags($_REQUEST['consult_vat_cli'],"<b><i><a><p>");
                $vat_cli = htmlspecialchars($vat_cli);                        
            
                $weigth = strip_tags($_REQUEST['weigth'],"<b><i><a><p>");
                $weigth = htmlspecialchars($weigth);                    
          
                $consult_subj_obs = strip_tags($_REQUEST['consult_subj_obs'],"<b><i><a><p>");
                $consult_subj_obs = htmlspecialchars($consult_subj_obs);                    
           
                $consult_obj_obs = strip_tags($_REQUEST['consult_obj_obs'],"<b><i><a><p>");
                $consult_obj_obs = htmlspecialchars($consult_obj_obs);                    
            
                $consult_assessment = strip_tags($_REQUEST['consult_assessment'],"<b><i><a><p>");
                $consult_assessment = htmlspecialchars($consult_assessment);                    
           
                $consult_plan = strip_tags($_REQUEST['consult_plan'],"<b><i><a><p>");
                $consult_plan = htmlspecialchars($consult_plan);                    
           
                $diagnostic_codes = strip_tags($_REQUEST['diagnostic_codes'],"<b><i><a><p>");
                $diagnostic_codes = htmlspecialchars($diagnostic_codes);  

                $date_timestamp = date("Y-m-d H:i:s"); 

                // Database access
                $connection = require_once('db.php');
                $query_str = "INSERT INTO consult VALUES (:name, :vat_owner, :consultdate, :s, :o, :a, :p, :vat_client, :vat_vet, :weigth)";
                $stmt = $connection->prepare($query_str);
                
                $stmt->bindParam(':name', $_SESSION['animal_name']);
                $stmt->bindParam(':vat_owner', $_SESSION['animal_vat']);
                $stmt->bindParam(':consultdate', $date_timestamp);
                $stmt->bindParam(':s', $consult_subj_obs);
                $stmt->bindParam(':o', $consult_obj_obs);
                $stmt->bindParam(':a', $consult_assessment);
                $stmt->bindParam(':p', $consult_plan);
                $stmt->bindParam(':vat_client', $vat_cli);
                $stmt->bindParam(':vat_vet', $vat_vet);
                $stmt->bindParam(':weigth', $weigth);

                
                if ( !$stmt->execute() ) {
                    echo("<p>An error occurred! The consult was not added!</p>");
                    exit();
                }
              
                echo("<p>SUCCESS: Consult added successfully!</p>");


                $query2 = "INSERT INTO consult_diagnosis VALUES (:code, :name, :vat_owner, :consultdate)";
                $stmt2 = $connection->prepare($query2);

                $stmt2->bindParam(':code', $diagnostic_codes);                
                $stmt2->bindParam(':name', $_SESSION['animal_name']);
                $stmt2->bindParam(':vat_owner', $_SESSION['animal_vat']);
                $stmt2->bindParam(':consultdate', $date_timestamp);

                if ( !$stmt2->execute() ) {
                    echo("<p>An error occurred! The diagnosis was not added!</p>");
                    exit();
                }
              
                echo("<p>SUCCESS: Diagnosis added successfully!</p>");

                // Close connection
                $connection = NULL;
            }
        ?>
    </body>
</html>
