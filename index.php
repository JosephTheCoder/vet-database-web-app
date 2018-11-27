<!-- Search using VAT, name of owner, name of animal-->
<!DOCTYPE html>
<html>
    <head>
        <meta charset="utf-8" />
        <meta http-equiv="X-UA-Compatible" content="IE=edge">
        <title>Clinic Database</title>
        <!--<meta name="viewport" content="width=device-width, initial-scale=1">
        <link rel="stylesheet" type="text/css" media="screen" href="main.css" />
        <script src="main.js"></script>-->
    </head>

    <body>
        -------------------------------------------------------------------
        <h1>Clinic Database</h1>
        -------------------------------------------------------------------
        
        <form action="search_animals.php" method="post">
            <p>Client VAT: <input type="text" name="client_vat"></p>
            <p>Client name: <input type="text" name="client_name"></p>
            <p>Animal name: <input type="text" name="animal_name"></p>
            <p><input type="submit" value="Search"></p>
        </form>
    </body>
</html>