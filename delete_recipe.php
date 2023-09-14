<?php
require_once 'great1.php';

// Check if the recipe ID is provided
if (!isset($_GET['id'])) {
    header('Location: sample4.html.php');
    exit();
}

$id = $_GET['id'];

// Delete the recipe from the 'recipes' table
$query = "DELETE FROM recipe_db WHERE id='$id'";

if (mysqli_query($conn, $query)) {
    header('Location: sample4.html.php');
    exit();
} else {
    // Handle error if the recipe couldn't be deleted
    echo "Error: " . mysqli_error($conn);
}
?>
