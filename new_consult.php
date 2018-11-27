<!DOCTYPE html>
<html>
    <body>
        <h3>Register a new consult</h3>
        <form action="insert_consult.php" method="post">
            <table>
                <tr>
                    <td align='right'>Date:</td>
                    <td><input type="date" name="consult_date"></td>
                </tr>
                <tr>
                    <td align='right'>Veterinary VAT:</td>
                    <td><input type="text" name="consult_vat_vet"></td>
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