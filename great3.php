<?php
include 'great1.php';
if(isset($_POST['SUBMIT'])){ 
    $username=$_POST['username'];
    $email=$_POST['email'];
    $password =$_POST['password'];
    $query="INSERT INTO signup_tb(username,email,password)
    VALUES(?,?,?)";
$stmt = mysqli_stmt_init($connection);
mysqli_stmt_prepare($stmt, $query);
mysqli_stmt_bind_param($stmt, "sss", $username,$email,$password);
mysqli_stmt_execute($stmt);
header("location: sample4.html");
//header("location: update.php");
}
?>

<!DOCTYPE html>
<html lang="en">
<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <title>Document</title>
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
 body{
    background-color:pink;
 }


    </style>
</head>
<body>
 <form action="great3.php" method="post">
    <div class="honest">
    <h1><b>SIGN UP</b></h1>
    <label for="Username"></label>  
    Username :<input type="text" id="Username"name="username" required><br><br><br><br>
    
    <label for="Email"></label>
    Email:<input type="email" name="email" required><br><br><br><br>
   
    <label for="Password"></label> 
   Password:<input type="text" name="password" required><br><br><br><br>
    </div>

    <button class="btn" name='SUBMIT'>SUBMIT</button>
<a href="sample4.html">BACK</a>

</form>



</body>
</html>