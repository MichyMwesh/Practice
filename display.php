<?php
include 'great1.php';
?>
<!DOCTYPE html>
<html lang="en">
<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <title>Document</title>
    <link href="https://cdn.jsdelivr.net/npm/bootstrap@5.0.2/dist/css/bootstrap.min.css" rel="stylesheet" integrity="sha384-EVSTQN3/azprG1Anm3QDgpJLIm9Nao0Yz1ztcQTwFspd3yD65VohhpuuCOmLASjC" crossorigin="anonymous">
</head>
<body>
    <div class="container">
    <button class="btn btn-primary margin-5"><a href="great3.php" class="text-light">ADD USER</a>
</button>
</div>
    <table class="table">
  <thead>
    <tr>
      <th scope="col">Sl No</th>
      <th scope="col">Username</th>
      <th scope="col">Email</th>
       <th scope="col">Password</th>
       <th scope="col">Operations</th>
    </tr>
    </tr>
  </thead>
  <tbody>

    <?php
$sql="Select * from `signup_tb`";
$result=mysqli_query($connection,$sql);
if($result){
while($row=mysqli_fetch_assoc($result)){
    $ID=$row['ID'];
    $username=$row['username'];
    $email=$row['email'];
    $password=$row['password'];
    echo ' <tr>
    <th scope="row">'.$ID.'</th>
     <td>'.$username.'</td>
     <td>'.$email.'</td>
     <td>'.$password.'</td>
     <td>
    <button class="btn btn-primary"><a href="update.php? updateid='.$ID.'" class="text-light">Update</a></button>
    <button class="btn btn-danger" ><a href="delete.php? deleteid='.$ID.'">Delete</a></button>
   </tr>';
}
}
?>
     </tbody>
</table>
</body>
</html>















