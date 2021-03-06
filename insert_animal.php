<!DOCTYPE html>
<html>
    <head>
        <title>Adding a new animal</title>
    </head>
    <body>
        <?php
            if ( empty($_REQUEST['animal_vat']) || empty($_REQUEST['animal_name']) || empty($_REQUEST['animal_species']) || empty($_REQUEST['animal_colour']) || empty($_REQUEST['animal_gender']) || empty($_REQUEST['animal_birth'])) {
                // Invalid request / user directly opened file.
                echo("<p>ERROR: New animal info is incomplete!</p>");
                echo("<a href=\"javascript:history.go(-1)\"><button><- Back</button></a>");
            } else {
                // Request with all required parameters was made
                $animal_vat = strip_tags($_REQUEST['animal_vat'],"<b><i><a><p>");
                $animal_vat = htmlspecialchars($animal_vat);

                $animal_name = strip_tags($_REQUEST['animal_name'],"<b><i><a><p>");
                $animal_name = htmlspecialchars($animal_name);

                $animal_species = strip_tags($_REQUEST['animal_species'],"<b><i><a><p>");
                $animal_species = htmlspecialchars($animal_species);

                $animal_colour = strip_tags($_REQUEST['animal_colour'],"<b><i><a><p>");
                $animal_colour = htmlspecialchars($animal_colour);

                $animal_gender = strip_tags($_REQUEST['animal_gender'],"<b><i><a><p>");
                $animal_gender = htmlspecialchars($animal_gender);

                $animal_birth = strip_tags($_REQUEST['animal_birth'],"<b><i><a><p>");
                $animal_birth = htmlspecialchars($animal_birth);

                // Database access
                $connection = require_once('db.php');
                $sql = "INSERT INTO animal (name, VAT, species_name, colour, gender, birth_year, age) VALUES (:name, :vat, :species, :colour, :gender, :birth, :age)";
                $stmt = $connection->prepare($sql);
                
                $stmt->bindParam(':name', $animal_name);
                $stmt->bindParam(':vat', $animal_vat);
                $stmt->bindParam(':species', $animal_species);
                $stmt->bindParam(':colour', $animal_colour);
                $stmt->bindParam(':gender', $animal_gender);
                $stmt->bindParam(':birth', $animal_birth);

                $age_calc = "TIMESTAMPDIFF(year, birth_year, curdate())";
                $stmt->bindParam(':age', $age_calc);

                
                if ( !$stmt->execute() ) {
                    echo("<p>An error occurred! The animal was not added!</p>");
                    print_r($stmt->errorInfo());
                    if ($stmt->errorInfo()[0] == "23000"){
                        echo("<p>The owner's VAT is non existant</p>");
                    }
                    
                    $connection = NULL;
                    exit();
                }
              
                echo("<p>SUCCESS: Animal added successfully!</p>");
                echo("<p>Animal info:</p>");
                echo("<table border=1>");
                echo("<tr><td align='right'>Name:</td><td>$animal_name</td></tr>");
                echo("<tr><td align='right'>Client VAT:</td><td>$animal_vat</td></tr>");
                echo("<tr><td align='right'>Species:</td><td>$animal_species</td></tr>");
                echo("<tr><td align='right'>Colour:</td><td>$animal_colour</td></tr>");
                echo("<tr><td align='right'>Gender:</td><td>$animal_gender</td></tr>");
                echo("<tr><td align='right'>Birth-date:</td><td>$animal_birth</td></tr>");
                echo("</table>");                    
        
                // Close connection
                $connection = NULL;
            }
        ?>
    </body>
</html>
