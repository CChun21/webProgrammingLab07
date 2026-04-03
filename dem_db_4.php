<!DOCTYPE html>
<html>
<body>

<h1> Example for inserting data to DB</h1>
<br>
<h2>Registration Page</h2>
<form action="dem_db_4.php" method="post">
<label for="uname">User Name:</label>
<input type="text" id="fname" name="uname" value=""><br>
<label for="psw">Password:</label>
<input type="password" id="pass" name="psw" value=""><br>
<label for="psw2">Reenter Password:</label>
<input type="password" id="pass" name="psw2" value=""><br>
<br>
<input type="submit" name="login" value="Submit">
</form>
</body>
</html>

<?php
//attach the secure data file
include('dbh.inc');
$conn = mysqli_connect($db_server,$user,$password,$db_names);//Method -1
//Get the Data
$username=trim(addslashes($_POST["uname"]));
$passcode=trim(addslashes($_POST["psw"]));

if(isset($_POST["login"])){
if(!empty($_POST["uname"])&& !empty($_POST["psw"])&& !empty($_POST["psw2"])){
if($_POST["psw"]==$_POST["psw2"]){
$sql_string="INSERT INTO tempo(Username,pass) VALUES ('$username','$passcode')";
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
?>