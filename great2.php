<?php
include 'great1.php';
if(isset($_POST['LOGIN'])){
$username=$_POST["username"];
$password=$_POST["password"];
$query="SELECT *FROM signup_tb WHERE username=? AND password=?";
$stmt=mysqli_stmt_init($connection);
mysqli_stmt_prepare($stmt, $query);
mysqli_stmt_bind_param($stmt, "ss",$username, $password);
mysqli_stmt_execute($stmt);


$result=mysqli_stmt_get_result($stmt);
if(!mysqli_fetch_assoc($result)){
    echo"incorrect";
}
else{
    header("location: sample4.html?success");
    exit();
}
}
?>



<!DOCTYPE html>
<html lang="en">
<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <title>Document</title>
    <link rel="stylesheet" href="sample2.css">
    <style>
        input{
    width:40%;
    height:5%;
    border:1px;
    border-radius: 05px;
    padding:8px 15px 8px 15px;
    margin:10px 0px 15px 0px;
    box-shadow:1px 1px 2px 1px grey;
}
    </style>
</head>
<body>
 
    <form method="post" action="great2.php">
        <h1>LOG IN</h1>
        username<input type="text" name="username" required><br><br>
        password<input type="text" name="password" required><br><br>
        <button type="submit" class="btn" name="LOGIN">LOGIN</button><br>
    <p>Do you have an account?</p>
    <a href="great3.php">Register</a>
    </form>
  

    <img src="./Recipe Image/ice cream.png" alt="">
    

</body>
</html>
