<?php
session_start();
require_once 'great1.php';

// Check if the recipe ID is provided
if (!isset($_GET['id'])) {
    header('Location: dashboard.php');
    exit();
}

$recipe_id = $_GET['id'];

// Check if the user is logged in (either admin or author)
if (!isset($_SESSION['admin_id']) && !isset($_SESSION['author_id'])) {
    header('Location: login.php');
    exit();
}

// Fetch the recipe details from the database
$query_recipe = "SELECT * FROM recipes WHERE id='$recipe_id' LIMIT 1";
$result_recipe = mysqli_query($conn, $query_recipe);
$recipe = mysqli_fetch_assoc($result_recipe);

// Check if the user has permission to edit the recipe
if (isset($_SESSION['author_id']) && $recipe['author_id'] != $_SESSION['author_id']) {
    header('Location: author_dashboard.php');
    exit();
}

// Fetch all authors for the dropdown (only for admins)
if (isset($_SESSION['admin_id'])) {
    $query_authors = "SELECT * FROM authors";
    $result_authors = mysqli_query($conn, $query_authors);
}

// Fetch gallery images from the database
$query_gallery_images = "SELECT * FROM gallery_images";
$result_gallery_images = mysqli_query($conn, $query_gallery_images);

// Update the recipe if the form is submitted
if ($_SERVER['REQUEST_METHOD'] === 'POST') {
    $title = $_POST['title'];
    $ingredients = $_POST['ingredients'];
    $instructions = $_POST['instructions'];
    $author_id = $_POST['author_id']; // Only for admins
    $recipe_image_id = $_POST['recipe_image_id'];

    // Update the recipe in the database
    $query_update_recipe = "UPDATE recipes SET title='$title', ingredients='$ingredients', instructions='$instructions', author_id='$author_id', recipe_image_id='$recipe_image_id' WHERE id='$recipe_id'";
    if (mysqli_query($conn, $query_update_recipe)) {
        $success_message = "Recipe updated successfully!";
    } else {
        $error_message = "Error updating recipe: " . mysqli_error($conn);
    }
}
?>

<!DOCTYPE html>
<html>
<head>
    <title>Edit Recipe</title>
    <link rel="stylesheet" type="text/css" href="style.css">
</head>
<body>
    <div class="page-wrapper">
        <h1>Edit Recipe</h1>
        <?php if (isset($_SESSION['admin_id'])) : ?>
            <a href="dashboard.php">Back to Admin Dashboard</a>
        <?php elseif (isset($_SESSION['author_id'])) : ?>
            <a href="author_dashboard.php">Back to Author Dashboard</a>
        <?php endif; ?>

        <?php if (isset($success_message)) : ?>
            <p><?php echo $success_message; ?></p>
            <?php if (isset($_SESSION['admin_id'])) : ?>
                <a href="dashboard.php">Go back to Admin Dashboard</a>
            <?php elseif (isset($_SESSION['author_id'])) : ?>
                <a href="author_dashboard.php">Go back to Author Dashboard</a>
            <?php endif; ?>
        <?php elseif (isset($error_message)) : ?>
            <p><?php echo $error_message; ?></p>
        <?php endif; ?>

        <!-- Create a form to edit an existing recipe -->
        <form method="POST" action="edit_recipe.php?id=<?php echo $recipe['id']; ?>">
            <input type="hidden" name="id" value="<?php echo $recipe['id']; ?>">

            <label for="title">Title:</label>
            <input type="text" name="title" id="title" value="<?php echo $recipe['title']; ?>" required>

            <label for="ingredients">Ingredients:</label>
            <textarea name="ingredients" id="ingredients" required><?php echo $recipe['ingredients']; ?></textarea>

            <label for="instructions">Instructions:</label>
            <textarea name="instructions" id="instructions" required><?php echo $recipe['instructions']; ?></textarea>

            <!-- Add a dropdown to select the author for the recipe (only for admins) -->
            <?php if (isset($_SESSION['admin_id'])) : ?>
                <label for="author_id">Author:</label>
                <select name="author_id" id="author_id" required>
                    <?php
                    while ($author = mysqli_fetch_assoc($result_authors)) {
                        $selected = ($author['id'] === $recipe['author_id']) ? 'selected' : '';
                        echo '<option value="' . $author['id'] . '" ' . $selected . '>' . $author['username'] . '</option>';
                    }
                    ?>
                </select>
            <?php endif; ?>

            <!-- Dropdown list of gallery images -->
            <label for="recipe_image_id">Recipe Image:</label>
            <select name="recipe_image_id" id="recipe_image_id" required>
                <option value="" disabled>Select an Image</option>
                <?php while ($image = mysqli_fetch_assoc($result_gallery_images)) : ?>
                    <?php $selected = ($image['id'] === $recipe['recipe_image_id']) ? 'selected' : ''; ?>
                    <option value="<?php echo $image['id']; ?>" <?php echo $selected; ?>><?php echo $image['filename']; ?></option>
                <?php endwhile; ?>
            </select>

            <button type="submit">Update Recipe</button>
        </form>
    </div>
</body>
</html>
