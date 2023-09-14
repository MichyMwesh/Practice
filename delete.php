<?php
include 'great1.php';
if(isset($_GET['deleteid'])){
    $id=$_GET['deleteid'];
    $sql="delete from `signup_tb` where id=$id";
    $result=mysqli_query($connection,$sql);
    if($result){
       // echo"deleted successfully";
       header('location:display.php');
    } 
    else{
die(mysqli_error($connection));
}
    }
?>