<?php
//attach the secure data file
include('dbh.inc');
$conn = mysqli_connect($db_server,$user,$password,$db_names);//Method -1
$sql_string="INSERT INTO tempo(Username,pass) VALUES ('Superman','Batman')";
try{
mysqli_query($conn,$sql_string);
echo"User data is registered";
}
catch(mysqli_sql_exception)
{
echo"User data is not registered";
}

mysqli_close($conn);

?>