<?php
//attach the secure data file
include('dbh.inc');
// 1. Set up the db connection
try{
$conn = mysqli_connect($hostname,$user,$password);//Method -2

// 2. Connect to the database
$db_names='demo';
mysqli_select_db($conn, $db_names);
}
catch(mysqli_sql_exception)
{
die('Connection failed: ' . $conn->error);
}
// Check connection
if ($conn) {
echo"Connection Successful";
}
mysqli_close(conn);
?>