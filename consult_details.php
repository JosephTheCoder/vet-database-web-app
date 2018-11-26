<!DOCTYPE html>
<html>
    <head>
        <title>Consults of </title>
    </head>

    <body>
        <h2>Search results</h2>
        
        <?php        
        $client_vat = (empty($_REQUEST['client_vat']) ? '' : $_REQUEST['client_vat']);
        $client_name = (empty($_REQUEST['client_name']) ? '' : $_REQUEST['client_name']);
        $animal_name = (empty($_REQUEST['animal_name']) ? '' : $_REQUEST['animal_name']);

        $connection = require_once('db.php');

        $query_str = "SELECT animal.name, animal.species_name, animal.age FROM animal, client WHERE client.VAT in (SELECT VAT FROM person WHERE person.name LIKE %:clnt_name%) and animal.VAT = client.VAT and animal.name = :anml.name";

        $stmt = $connection->prepare();
        echo("<h4>Results for: $client_name </h4>");
        $client_name = '%'.$client_name.'%';
        $stmt->bindParam(':clnt_name', $client_name);
        $stmt->bindParam(':anml_name', $animal_name);

        if ( !$stmt->execute() ) {
            echo("<p>An error occurred!</p>");
            exit();
        }

        if ($stmt->rowCount() > 0 ) {
            echo("<table border=1 cellpadding='5'>");
            echo("<thead><tr><th>Name</th><th>Species Name</th><th>Age</th></tr></thead>");
            foreach($stmt as $animal) {
                echo("<tr><td><a href='consult_details.php?pat_id=".$animal['name']."'>".$animal['species_name'].$animal['age']"</a></td></tr>");
            }
            echo("</table>");
        } else {
            echo("<p>No animal was found!</p>");
            include('new_animal.php');
        }
        $connection = NULL;
        ?>
    </body>
</html>