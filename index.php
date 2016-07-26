<?php
if ( isset($_GET["word"]) ) {
  echo "Sent Word : ".$_GET["word"]."<br>";
} else {
  echo "No word sent.<br>";
}
$vcap_services = json_decode(getenv("VCAP_SERVICES"), true);
$cred = $vcap_services["user-provided"][0]["credentials"];
$connection_str = "host=".$cred["public_hostname"]." dbname=compose user=".$cred["username"]." password=".$cred["password"];
$link = pg_connect($connection_str);
if (!$link) {
  echo "DB connection error! ".$connection_str;
} else {
  echo "DB successfuly connected.<br>";
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
  echo "<table>";
  echo "<tr><th>ID</th><th>Word</th></tr>";
  while ($row = pg_fetch_row($result)) {
    echo "<tr><td>".$row[0]."</td><td>".$row[1]."</td></tr>";
  }
  echo "</table>"; 
  pg_close($link);
}
