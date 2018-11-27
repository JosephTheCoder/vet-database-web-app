<!DOCTYPE html>
<html>
    <body>
        <h3>Add a new animal</h3>
        <form action="insert_animal.php" method="post">
            <table>
                <tr>
                    <td align='right'>Client VAT:</td>
                    <td><input type="text" name="animal_vat"></td>
                </tr>
                <tr>
                    <td align='right'>Name:</td>
                    <td><input type="text" name="animal_name"></td>
                </tr>                
                <tr>
                    <td align='right'>Species name:</td>
                    <td><input type="TEXT" name="animal_species"></td>
                </tr>
                <tr>
                    <td align='right'>Color:</td>
                    <td><input type="text" name="animal_color"></td>
                </tr>
                <tr>
                    <td align='right'>Gender:</td>
                    <td><input type="text" name="animal_gender"></td>
                </tr>
                <tr>
                    <td align='right'>Birth date:</td>
                    <td><input type="date" name="animal_birth"></td>
                </tr>
            </table>
            <p><input type="submit" value="Submit"></p>
        </form>
    </body>
</html>