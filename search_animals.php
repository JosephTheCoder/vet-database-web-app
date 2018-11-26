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

        $query_str = "SELECT animal.name, animal.species_name, animal.age FROM animal, client WHERE client.VAT in (SELECT VAT FROM person WHERE person.name LIKE :client_name) and animal.VAT = client.VAT and animal.name = :animal_name";
        $stmt = $connection->prepare($query_str);

        $clnt_name = '%'.$client_name.'%';

        $stmt->bindParam(':client_name', $clnt_name);
        $stmt->bindParam(':animal_name', $animal_name);
        
        echo("<h4>Results for: $client_name </h4>");

        if ( !$stmt->execute() ) {
            echo("<p>An error occurred!</p>");
            exit();
        }

        if ($stmt->rowCount() > 0 ) {
            echo('<table border="1" cellpadding="5">');
            echo("<thead><tr><th>Name</th><th>Species Name</th><th>Age</th></tr></thead>");
            
            foreach($stmt as $animal) {
                echo("<tr><td><a href='consult_details.php?animal_name=".$animal['name']."'>".$animal['name'].'</td><td>'.$animal['species_name'].'</td><td>'.$animal['age']."</td></tr>");
            }
            
            echo("</table>");
        } else {
            echo("<p>No animal was found!</p>");
            // include('new_animal.php');
        }

        $stmt->close();
        $connection = NULL;
        ?>
    </body>
</html>