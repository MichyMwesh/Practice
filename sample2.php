<?php
include 'connect.php';
if(isset($_POST['LOG IN'])){
$username=$_POST["username"];
$password=$_POST["password"];
$query="SELECT *FROM logina WHERE username=? AND password=?)";
$stmt=mysqli_stmt_init($conn);
mysqli_stmt_prepare($stmt, $query);
}

mysqli_stmt_bind_param($stmt, "s",$username, $password);
mysqli_stmt_execute($stmt);


$result=mysqli_stmt_get_result($stmt);
if(!mysqli_fetch_assoc($result)){
    echo"incorrect";
}
else{
    header("location:home.html");
}
?>

<!DOCTYPE html>
<html lang="en">
<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <title>Document</title>
    <link rel="stylesheet" href="sample2.css">
</head>
<body>
 
    <form>
        <h1>LOG IN</h1>
        Username<input type="text" name="username" required><br><br>
        Password<input type="text" name="password" required><br><br>
    </form>
    <button class="btn">LOG IN</button><br>
    <button class="fot">RESET</button>
    <p>Do you have an account?</p>
    <a href="sample1.html">Register</a>
</body>
</html>