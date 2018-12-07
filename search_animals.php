<!DOCTYPE html>
<html>
    <head>
        <title>Search Results</title>
    </head>

    <body>        
        <?php            
            if (empty($_REQUEST['client_vat']) || empty($_REQUEST['client_name']) || empty($_REQUEST['animal_name'])) {
                // Invalid request
                echo("<p>Invalid Search parameters. Please always include a animal name, a client vat and name</p>");
                echo("<a href=\"javascript:history.go(-1)\"><button>GO BACK</button></a>");
            } 
            
            else {    
                // Process and cleaning :)
                $client_vat = strip_tags($_REQUEST['client_vat'],"<b><i><a><p>");
                $client_vat = htmlspecialchars($client_vat);

                $client_name = strip_tags($_REQUEST['client_name'],"<b><i><a><p>");
                $client_name = htmlspecialchars($client_name);

                $animal_name = strip_tags($_REQUEST['animal_name'],"<b><i><a><p>");
                $animal_name = htmlspecialchars($animal_name);

    
                // Database access
                $connection = require_once('db.php');
                $sql = "SELECT DISTINCT person.name as person_name, animal.name as animal_name, animal.species_name, animal.age, animal.VAT as animal_vat FROM person, client, animal, consult WHERE animal.name = :animal_name AND person.name LIKE :client_name AND person.VAT = client.VAT AND client.VAT = animal.VAT AND consult.VAT_owner = client.VAT AND consult.name = animal.name AND consult.VAT_client = :client_vat";
                $stmt = $connection->prepare($sql);

                $clnt_name = '%'.$client_name.'%';

                $stmt->bindParam(':client_vat', $client_vat);
                $stmt->bindParam(':client_name', $clnt_name);
                $stmt->bindParam(':animal_name', $animal_name);

                if ( !$stmt->execute() ) {
                    echo("<p>An error occurred!</p>");
                    $connection = NULL;
                    exit();
                }

                echo("<p>-------------------------------------------------------------------</p>");
                echo("<h2>Search results</h2>");
                echo("<a href=\"javascript:history.go(-1)\"><button><- Back</button></a>");
                echo("<p>-------------------------------------------------------------------</p>");
                
                echo("<h4>Search Parameters:</h4><p><b>Client VAT:</b> $client_vat</p><p><b>Client name:</b> $client_name</p><p><b>Animal name:</b> $animal_name</p>");


                if ($stmt->rowCount() > 0 ) {
                    echo('<table border="1" cellpadding="3">');
                    echo("<thead><tr><th>Client</th><th>Animal</th><th>Species</th><th>Age</th></tr></thead>");
                    
                    foreach($stmt as $query) {
                        echo("<tr><td>".$query['person_name']."</td><td><a href='list_consults.php?client=".$query['animal_vat']."&animal=".$query['animal_name']."'>".$query['animal_name']."</a></td><td>".$query['species_name']."</td><td>".$query['age']."</td><td><a href='new_consult.php?client=".$query['animal_vat']."&animal=".$query['animal_name']."'> New consult </a></td></tr>");
                    }
                    
                    echo("</table>");
                } else {
                    echo("<p>0 results</p>");
                    echo("<p>-------------------------------------------------------------------</p>");
                    include('new_animal.php');
                }
                
                $connection = NULL;
            }
        ?>
    </body>
</html>