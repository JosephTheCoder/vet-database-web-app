<?php
    // We start a session in order to safe the search parameters.
    session_start();
?>

<!DOCTYPE html>
<html>
    <body>
        <h3>Add a new test</h3>
        <form action="insert_test.php" method="post">
            <table>
                <tr>
                    <td align='right'>Assistant VAT:</td>
                    <td><input type="number" name="ass_vat"></td>
                </tr>
                <tr>
                    <td align='right'>Glicose (mg):</td>
                    <td><input type="number" name="glic_result"></td>
                </tr>
                <tr>
                    <td align='right'>Magic power (Mg):</td>
                    <td><input type="number" name="mp_result"></td>
                </tr>                
                <tr>
                    <td align='right'>Creatine level (kg):</td>
                    <td><input type="number" name="cl_result"></td>
                </tr>
            </table>
            <p><input type="submit" value="Add blood test"></p>
        </form>
    </body>
</html>