<?php
//attach the secure data file
include('dbh.inc');
// 1. Set up the db connection
$conn = mysqli_connect($db_server,$user,$password,$db_names);//Method -1

// Check connection
if ($conn) {
echo"Connection Successful";
}
else {
echo"Connection failed";
}
mysqli_close($conn);
?>