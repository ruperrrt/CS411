 <?php

 include 'dbh.php';

 $username = $_POST['username'];
 $password = $_POST['password'];
 $email = $_POST['email'];

$sql = "INSERT INTO user (username, pwd, email) 
VALUES ('$username', '$password', '$email')";
$result = $conn->query($sql);

header("Location:http://sometimesnaive.web.engr.illinois.edu/");

?>
