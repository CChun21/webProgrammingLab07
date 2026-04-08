<!DOCTYPE html>
<html>
<body>

<h2>Registration Page</h2>
<form action="dem_db_4.php" method="post">
<label for="uname">User Name:</label>
<input type="text" id="fname" name="uname" value=""><br>
<label for="psw">Password:</label>
<input type="password" id="pass" name="psw" value=""><br>
<label for="psw2">Reenter Password:</label>
<input type="password" id="pass" name="psw2" value=""><br>
<label for="email">Email:</label>
<input type="email" id="email" name="email" value=""><br>
<br>
<input type="submit" name="login" value="Submit">
</form>
</body>
</html>

<?php
session_start();
//attach the secure data file
include('/home/cc1814/P/dbh.inc');
$conn = mysqli_connect($db_server,$user,$password,$db_names);//Method -1
//Get the Data
$username=trim(addslashes($_POST["uname"]));
$passcode=trim(addslashes($_POST["psw"]));
$email=trim(addslashes($_POST["email"]));

if(isset($_POST["login"])){
if(!empty($_POST["uname"])&& !empty($_POST["psw"])&& !empty($_POST["psw2"])){
if($_POST["psw"]==$_POST["psw2"]){
$sql_string="INSERT INTO LoginSystem(username,password,email) VALUES ('$username','$passcode','$email')";
mysqli_query($conn,$sql_string);
echo "User data is registered";
}
else
{
echo "Both Passwords dont match";
}
}
else{
echo "Missing username or password fix it <br>";
}
}
else{
echo "waiting for entry";
}
mysqli_close($conn);
session_destroy();
?>