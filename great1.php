<?php
$connection=mysqli_connect('localhost','root','','adminlogin');
if(!$connection){
    die("Failed to connect". mysql_error($connection));
}
else{
    echo("Connected successfully!");
}

?>
