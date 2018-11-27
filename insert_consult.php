<!DOCTYPE html>
<html>
    <head>
        <title>Adding a new consult</title>
    </head>
    <body>
        <h2>Adding a new animal</h2>
        <?php
            if ( empty($_REQUEST['animal_vat']) || empty($_REQUEST['animal_name']) || empty($_REQUEST['animal_species']) || empty($_REQUEST['animal_color']) || empty($_REQUEST['animal_gender']) || empty($_REQUEST['animal_birth'])) {
                // Invalid request / user directly opened file.
                echo("<p>ERROR: Animal info must be provided on the request!</p>");
            } else {
                // Request with all required parameters was made
                $animal_vat = strip_tags($_REQUEST['animal_vat'],"<b><i><a><p>");
                $animal_vat = htmlspecialchars($animal_vat);

                $animal_name = strip_tags($_REQUEST['animal_name'],"<b><i><a><p>");
                $animal_name = htmlspecialchars($animal_name);

                $animal_species = strip_tags($_REQUEST['animal_species'],"<b><i><a><p>");
                $animal_species = htmlspecialchars($animal_species);

                $animal_color = strip_tags($_REQUEST['animal_color'],"<b><i><a><p>");
                $animal_color = htmlspecialchars($animal_color);

                $animal_gender = strip_tags($_REQUEST['animal_gender'],"<b><i><a><p>");
                $animal_gender = htmlspecialchars($animal_gender);

                $animal_birth = strip_tags($_REQUEST['animal_birth'],"<b><i><a><p>");
                $animal_birth = htmlspecialchars($animal_birth);

                $connection = require_once('db.php');
                $stmt = $connection->prepare("INSERT INTO animal VALUES(:id, :name, :birthday, :address)");
                $stmt->bindParam(':id', $new_patient_number);
                $stmt->bindParam(':name', $new_patient_name);
                $stmt->bindParam(':birthday', $new_patient_birthday);
                $stmt->bindParam(':address', $new_patient_address);
                if ( $stmt->execute() ) {
                    // Patient added                 
                    echo("<p>SUCCESS: Patient added successfully!</p>");
                    echo("<p>Patient info:</p>");
                    echo("<table border=1>");
                    echo("<tr><td align='right'>Number (ID):</td><td>$new_patient_number</td></tr>");
                    echo("<tr><td align='right'>Name:</td><td>$new_patient_name</td></tr>");
                    echo("<tr><td align='right'>Birthday:</td><td>$new_patient_birthday</td></tr>");
                    echo("<tr><td align='right'>Address:</td><td>$new_patient_address</td></tr>");
                    echo("</table>");                    
                } else {
                    // Failed to insert a patient
                    echo("<p>An error occurred! The patient was not added!</p>");
                }
                // Close connection
                $connection = NULL;
            }
        ?>
    </body>
</html>