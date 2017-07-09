 <?php

 include 'dbh.php';

 $username = $_POST['username'];
 $password = $_POST['password'];

$sql = "INSERT INTO user (first, last, uid, pwd) 
VALUES ('$first', '$last', '$uid', '$pwd')";
$result = $conn->query($sql);

header("Location: index.php");
