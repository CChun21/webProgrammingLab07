<?php
session_start();
//attach the secure data file
include('/home/cc1814/P/dbh.inc');
$conn = mysqli_connect($db_server,$user,$password,$db_names);//Method -1

/*
// Check connection
if ($conn) {
echo"Connection Successfull"."<br>";
}
*/
?>

<!DOCTYPE html>
<html>
<body>

<h2>Login Page</h2>
<form action="dem_db_4.php" method="post">
<label for="uname">User Name:</label>
<input type="text" id="fname" name="uname" value=""><br>
<label for="psw">Password:</label>
<input type="password" id="pass" name="psw" value=""><br>
<br>
<input type="submit" name="login" value="Submit">
</form>
</body>
</html>

<?php
echo "<br>PHP script to retrivew data from DB<br>";

//check LoginSystem user/pass in accordance to the given username and password

/*
$username=trim(addslashes($_POST["uname"]));
$passcode=trim(addslashes($_POST["psw"]));
$sql="SELECT (username, password) from LoginSystem WHERE Username=$username AND Password=$passcode";
*/

//$res=mysqli_query($conn,$sql);

mysqli_close($conn);

?>

</body>
</html>