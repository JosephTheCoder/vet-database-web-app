<?php
    session_start();
?>

<!DOCTYPE html>
<html>
    <body>
        <?php

        // get our parameters to the session        
            if (empty($_GET['client']) || empty($_GET['animal'])) {
                // Invalid request
                echo("<p>error in retrieving animal ID</p>");
            } else { 
                // Process and cleaning :)
                $animal_vat = strip_tags($_GET['client'],"<b><i><a><p>");
                $animal_vat = htmlspecialchars($animal_vat);
                $_SESSION['animal_vat'] = $animal_vat;

                $animal_name = strip_tags($_GET['animal'],"<b><i><a><p>");
                $animal_name = htmlspecialchars($animal_name);
                $_SESSION['animal_name'] = $animal_name;
            }
        ?>
        <h3>Register a new consult</h3>
        <form action="insert_consult.php" method="post">
            <p> Note: consult date will be set for this moment</p>
            <table>
                <tr>
                    <td align='right'>Veterinary VAT:</td>
                    <td><input type="text" name="consult_vat_vet"></td>
                </tr>
                <tr>
                    <td align='right'>Client VAT:</td>
                    <td><input type="text" name="consult_vat_cli"></td>
                </tr>
                <tr>
                    <td align='right'>Animal weigth:</td>
                    <td><input type="number" name="weigth"></td>
                </tr>
                <tr>
                    <td align='right'>Subjective observation:</td>
                    <td><input type="text" name="consult_subj_obs"></td>
                </tr>                
                <tr>
                    <td align='right'>Objective observation:</td>
                    <td><input type="TEXT" name="consult_obj_obs"></td>
                </tr>
                <tr>
                    <td align='right'>Assessment:</td>
                    <td><input type="text" name="consult_assessment"></td>
                </tr>
                <tr>
                    <td align='right'>Plan:</td>
                    <td><input type="text" name="consult_plan"></td>
                </tr>
                <tr>
                    <td align='right'>Diagnostic codes:</td>
                    <td><input type="text" name="diagnostic_codes"></td>
                </tr>
            </table>
            <p><input type="submit" value="Create consult"></p>
        </form>
    </body>
</html>