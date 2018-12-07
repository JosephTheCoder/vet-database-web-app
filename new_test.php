<?php
    // We start a session in order to safe the search parameters.
    session_start();
?>

<!DOCTYPE html>
<html>
    <body>
        <?php 
            $date = strip_tags($_GET['date'],"<b><i><a><p>");
            $date = htmlspecialchars($date);
            $_SESSION['date'] = $date;
        ?>

        <h3>Add a new test</h3>
        <form action="insert_test.php" method="post">
            <table>
                <tr>
                    <td align='right'>Assistant VAT:</td>
                    <td><input type="text" name="assistant_vat"></td>
                </tr>
                <tr>
                    <td align='right'>Magic Power (mg/L):</td>
                    <td><input type="number" name="magic_power"></td>
                </tr>
                <tr>
                    <td align='right'>Glicose (mg/L):</td>
                    <td><input type="number" name="glicose"></td>
                </tr>                
                <tr>
                    <td align='right'>Creatinine (mg/L):</td>
                    <td><input type="number" name="creatinine"></td>
                </tr>
            </table>
            <p><input type="submit" value="Add blood test"></p>
        </form>
    </body>
</html>
