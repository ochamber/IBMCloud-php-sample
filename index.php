<?php
if ( isset($_GET["word"]) ) {
  $message = "Sent Word : ".$_GET["word"]."<br>";
} else {
  $message = "No word sent.<br>";
}
$vcap_services = json_decode(getenv("VCAP_SERVICES"), true);
$cred = $vcap_services["user-provided"][0]["credentials"];
$connection_str = "host=".$cred["public_hostname"]." dbname=compose user=".$cred["username"]." password=".$cred["password"];
$link = pg_connect($connection_str);
if (!$link) {
  $message .= "DB connection error! ".$connection_str;
} else {
  $message .= "DB successfuly connected.<br>";
  if ( isset($_GET["reset"]) ) {
    $sql="DROP TABLE texts";
    pg_exec($link, $sql) or die(pg_errormessage());
  }
  $sql="CREATE TABLE IF NOT EXISTS texts ( id SERIAL, text varchar(20) )";
  pg_exec($link, $sql) or die(pg_errormessage());
  if ( isset($_GET["word"]) ) {
    $sql="INSERT INTO texts (text) VALUES('".$_GET["word"]."')";
    pg_exec($link, $sql) or die(pg_errormessage());
  }
  $result = pg_query($link, "SELECT id, text FROM texts");
  if (!$result) {
    echo "An error occurred.\n";
    exit;
  }
  $table = "<table>";
  $table .= "<tr><th>ID</th><th>Word</th></tr>";
  while ($row = pg_fetch_row($result)) {
    $table .= "<tr><td>".$row[0]."</td><td>".$row[1]."</td></tr>";
  }
  $table .= "</table>"; 
  pg_close($link);
}
?>
<!DOCTYPE html>
<html>
<head>
	<title>PHP Starter Application</title>
	<meta http-equiv="Content-Type" content="text/html; charset=utf-8">
	<link rel="stylesheet" href="style.css" />
</head>
<body>
	<table>
		<tr>
			<td style='width: 30%;'><img class = 'newappIcon' src='images/newapp-icon.png'>
			</td>
			<td>
				<h1 id = "message"><?php echo $message; ?>
</h1>
<p>
<?php echo $table;?>
</P>
				<p class='description'></p> Thanks for creating a <span class="blue">PHP Starter Application</span>. To get started see the Start Coding guide under your app in your dashboard.
			</td>
		</tr>
	</table>
</body>
</html>
