
<!-- saved from url=(0070)https://fenix.tecnico.ulisboa.pt/downloadFile/563568428768752/test.php -->
<html><head><meta http-equiv="Content-Type" content="text/html; charset=windows-1252"></head><body style="">
<?php

	$host="db.ist.utl.pt";	// MySQL is hosted in this machine
	$user="ist181570";	// <== replace istxxx by your IST identity
	$password="eeua4108";	// <== paste here the password assigned by mysql_reset
	$dbname = $user;	// Do nothing here, your database has the same name as your username.

 
	$connection = new PDO("mysql:host=" . $host. ";dbname=" . $dbname, $user, $password, array(PDO::ATTR_ERRMODE = PDO::ERRMODE_WARNING));

	echo("<p>Connected to MySQL database $dbname on $host as user $user</p>\n");

	$sql = "SELECT * FROM animal;";

	echo("<p>Query: " . $sql . "</p>\n");

	$result = $connection-&gt;query($sql);
	
	$num = $result-&gt;rowCount();

	echo("<p>$num records retrieved:</p>\n");

	echo("\n");
	echo("\n");
	foreach($result as $row)
	{
		echo("\n");
	}
	echo("<table border="\&quot;1\&quot;"><tbody><tr><td>account_number</td><td>branch_name</td><td>balance</td></tr><tr><td>");
		echo($row["account_number"]);
		echo("</td><td>");
		echo($row["branch_name"]);
		echo("</td><td>");
		echo($row["balance"]);
		echo("</td></tr></tbody></table>\n");
		
        $connection = null;
	
	echo("<p>Connection closed.</p>\n");

	echo("<p>Test completed successfully. Now you know how to connect to your MySQL database.</p>\n");

?&gt;


</body></html>