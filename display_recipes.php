<?php
require_once 'great1.php';

// Function to fetch and display random recipes on the homepage
function displayRandomRecipes($connection) {
    // Fetch random recipes and their authors from the database
    $query = "SELECT recipe.title AS recipe_title, recipes.ingredients, authors.username AS author_username 
              FROM recipe_db 
              ORDER BY RAND()"; // Order by random to get different recipes on each refresh

    $result = mysqli_query($connection, $query);

    while ($row = mysqli_fetch_assoc($result)) {
        echo '<div class="recipe-card">';
        echo '<h3>' . $row['recipe_title'] . '</h3>';
        echo '<p><strong>Ingredients:</strong> ' . $row['ingredients'] . '</p>';
        echo '<p><strong>Author:</strong> ' . $row['author_username'] . '</p>';
        echo '</div>';
    }
}

// Call the function to display random recipes
displayRandomRecipes($connection);
?>
