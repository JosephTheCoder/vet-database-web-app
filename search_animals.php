<!DOCTYPE html>
<html>
    <head>
        <title>Search Results</title>
    </head>

    <body>
        <h2>Search results</h2>
        
        <?php        
        $client_vat = (empty($_REQUEST['client_vat']) ? '' : $_REQUEST['client_vat']);
        $client_name = (empty($_REQUEST['client_name']) ? '' : $_REQUEST['client_name']);
        $animal_name = (empty($_REQUEST['animal_name']) ? '' : $_REQUEST['animal_name']);

        $connection = require_once('db.php');
        $query_str = "SELECT DISTINCT person.name as person_name, animal.name as animal_name, animal.species_name, animal.age FROM person, client, animal, consult WHERE (animal.name = :animal_name AND person.name LIKE :client_name AND person.VAT = client.VAT AND client.VAT = animal.VAT AND consult.VAT_owner = client.VAT AND consult.name = animal.name) OR (animal.name = :animal_name AND client.VAT = :client_vat AND client.VAT = consult.VAT_client AND consult.name = animal.name AND person.VAT = consult.VAT_owner)";
        $stmt = $connection->prepare($query_str);

        $clnt_name = '%'.$client_name.'%';

        $stmt->bindParam(':client_vat', $client_vat);
        $stmt->bindParam(':client_name', $clnt_name);
        $stmt->bindParam(':animal_name', $animal_name);

        echo("<h4>Results for: $client_name </h4>");

        if ( !$stmt->execute() ) {
            echo("<p>An error occurred!</p>");
            exit();
        }

        if ($stmt->rowCount() > 0 ) {
            echo('<table border="1" cellpadding="5">');
            echo("<thead><tr><th>Client</th><th>Animal</th><th>Species</th><th>Age</th></tr></thead>");
            
            foreach($stmt as $animal) {
                echo("<tr><td>".$animal['person_name']."</td><td>"."<a href='consult_details.php?animal_name=".$animal['animal_name']."'>".$animal['animal_name'].'</td><td>'.$animal['species_name'].'</td><td>'.$animal['age']."</td></tr>");
            }
            
            echo("</table>");
        } else {
            echo("<p>No animal was found!</p>");
            include('new_animal.php');
        }

        $stmt->close();
        $connection = NULL;
        ?>
    </body>
</html>