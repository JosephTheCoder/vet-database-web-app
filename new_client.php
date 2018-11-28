<!-- this isn't needed -->
<!DOCTYPE html>
<html>
    <head>
        <title>New client</title>
    </head>

    <body>
        <h2>Add a new client</h2>
         <form action="insert_consult.php" method="post">
            <table>
                <tr>
                    <td align='right'>Client VAT:</td>
                    <td><input type="text" name="client_vat"></td>
                </tr>
                <tr>
                    <td align='right'>Name:</td>
                    <td><input type="text" name="person_name"></td>
                </tr>
                <tr>
                    <td align='right'>Street:</td>
                    <td><input type="text" name="address_street"></td>
                </tr>
                <tr>
                    <td align='right'>City:</td>
                    <td><input type="text" name="address_city"></td>
                </tr>
                <tr>
                    <td align='right'>Zip:</td>
                    <td><input type="text" name="address_zip"></td>
                </tr>
            </table>
            <p><input type="submit" value="Add client"></p>
        </form>
	</body>
</html>