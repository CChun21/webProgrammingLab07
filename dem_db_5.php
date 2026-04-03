<?php
//attach the secure data file
include('dbh.inc');
$conn = mysqli_connect($db_server,$user,$password,$db_names);//Method -1

// Check connection
if ($conn) {
echo"Connection Successfull"."<br>";
}
?>

<!DOCTYPE html>
<html>
<body>
<?php
echo "<br>PHP script to retrivew data from DB<br>";
//$sql="SELECT * from tempo"; //uncomment when trying to print all values in table

$sql="SELECT * from tempo WHERE Username='Sun'";

$res=mysqli_query($conn,$sql);


// code for printing specific data from table
if(mysqli_num_rows($res)>0){
$row=mysqli_fetch_assoc($res);
echo $row["Id"]."<br>";
echo $row["Username"]."<br>";
echo $row["reg_date"]."<br>";

/*
// code for printing all the data from table
while($row=mysqli_fetch_assoc($res)){
echo $row["Id"]."<br>";
echo $row["Username"]."<br>";
echo $row["reg_date"]."<br>";
}*/

}

mysqli_close($conn);

?>

</body>
</html>