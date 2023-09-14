<?php
session_start();
require_once 'db_connection.php';

// Check if the admin is logged in
if (!isset($_SESSION['admin_id'])) {
    header('Location: login.php');
    exit();
}

// Fetch all authors from the database
$query_authors = "SELECT * FROM authors";
$result_authors = mysqli_query($conn, $query_authors);

// Fetch all recipes from the database
$query_recipes = "SELECT * FROM recipes";
$result_recipes = mysqli_query($conn, $query_recipes);

// Fetch gallery images from the database
$query_gallery_images = "SELECT * FROM gallery_images";
$result_gallery_images = mysqli_query($conn, $query_gallery_images);

// Handle adding new author
if ($_SERVER['REQUEST_METHOD'] === 'POST') {
    if (isset($_POST['add_author'])) {
        $author_username = $_POST['author_username'];
        $author_password = password_hash($_POST['author_password'], PASSWORD_DEFAULT);

        // Insert new author into the database
        $query_insert_author = "INSERT INTO authors (username, password) VALUES ('$author_username', '$author_password')";
        if (mysqli_query($conn, $query_insert_author)) {
            header('Location: dashboard.php'); // Redirect to dashboard after successful insertion
            exit();
        } else {
            echo "Error: " . mysqli_error($conn);
        }
    } elseif (isset($_POST['add_recipe'])) {
        $recipe_title = $_POST['recipe_title'];
        $recipe_ingredients = $_POST['recipe_ingredients'];
        $author_id = $_POST['author_id'];
        $recipe_image_id = $_POST['recipe_image_id'];

        // Insert new recipe into the database
        $query_insert_recipe = "INSERT INTO recipes (title, ingredients, author_id, recipe_image_id)
                               VALUES ('$recipe_title', '$recipe_ingredients', '$author_id', '$recipe_image_id')";
        if (mysqli_query($conn, $query_insert_recipe)) {
            header('Location: dashboard.php'); // Redirect to dashboard after successful insertion
            exit();
        } else {
            echo "Error: " . mysqli_error($conn);
        }
    } elseif (isset($_POST['add_gallery_image'])) {
        $recipe_id = isset($_POST['recipe_id']) ? $_POST['recipe_id'] : 0;
        $author_id = isset($_POST['author_id']) ? $_POST['author_id'] : 0;

        // Gallery image upload handling
        $image_filename = '';
        if ($_FILES['gallery_image']['name']) {
            $target_dir = 'gallery/';
            $image_filename = basename($_FILES['gallery_image']['name']);
            $target_path = $target_dir . $image_filename;
            move_uploaded_file($_FILES['gallery_image']['tmp_name'], $target_path);
        }

        // Insert gallery image data into the database
        $query_insert_gallery_image = "INSERT INTO gallery_images (filename, recipe_id, author_id) 
                                      VALUES ('$image_filename', '$recipe_id', '$author_id')";
        if (mysqli_query($conn, $query_insert_gallery_image)) {
            header('Location: dashboard.php'); // Redirect to dashboard after successful insertion
            exit();
        } else {
            echo "Error: " . mysqli_error($conn);
        }
    }
}
?>

<!DOCTYPE html>
<html>
<head>
    <title>Admin Dashboard</title>
    <link rel="stylesheet" type="text/css" href="style.css">
</head>
<body>
    <div class="page-wrapper">
        <h1>Welcome to the Admin Dashboard</h1>
        <a href="logout.php">Logout</a> <!-- Logout link added -->

        <!-- Author List -->
        <h2>Authors:</h2>
        <ul>
            <?php while ($author = mysqli_fetch_assoc($result_authors)) : ?>
                <li><?php echo $author['username']; ?></li>
            <?php endwhile; ?>
        </ul>

        <!-- Add New Author Form -->
        <h2>Add New Author:</h2>
        <form method="POST" action="dashboard.php">
            <label for="author_username">Username:</label>
            <input type="text" name="author_username" id="author_username" required>

            <label for="author_password">Password:</label>
            <input type="password" name="author_password" id="author_password" required>

            <button type="submit" name="add_author">Add Author</button>
        </form>

        <!-- Recipe List -->
        <h2>Recipes:</h2>
        <ul>
            <?php while ($recipe = mysqli_fetch_assoc($result_recipes)) : ?>
                <li>
                    <strong><?php echo $recipe['title']; ?></strong>
                    <p>Ingredients: <?php echo $recipe['ingredients']; ?></p>
                    <?php
                    $recipe_image_id = $recipe['recipe_image_id'];
                    $query_selected_image = "SELECT * FROM gallery_images WHERE id = '$recipe_image_id'";
                    $result_selected_image = mysqli_query($conn, $query_selected_image);
                    $selected_image = mysqli_fetch_assoc($result_selected_image);
                    ?>
                    <?php if ($selected_image) : ?>
                        <img src="gallery/<?php echo $selected_image['filename']; ?>" alt="Recipe Image" height="200">
                    <?php endif; ?>
                    <a href="edit_recipe.php?id=<?php echo $recipe['id']; ?>">Edit</a>
                    <a href="delete_recipe.php?id=<?php echo $recipe['id']; ?>">Delete</a>
                </li>
            <?php endwhile; ?>
        </ul>

        <!-- Add New Recipe Form -->
        <h2>Add New Recipe:</h2>
        <form method="POST" action="dashboard.php">
            <label for="recipe_title">Recipe Title:</label>
            <input type="text" name="recipe_title" id="recipe_title" required>

            <label for="recipe_ingredients">Ingredients:</label>
            <textarea name="recipe_ingredients" id="recipe_ingredients" required></textarea>

            <!-- Dropdown list of existing authors -->
            <label for="author_id">Author:</label>
            <select name="author_id" id="author_id" required>
                <option value="" disabled selected>Select an Author</option>
                <?php mysqli_data_seek($result_authors, 0); ?>
                <?php while ($author = mysqli_fetch_assoc($result_authors)) : ?>
                    <option value="<?php echo $author['id']; ?>"><?php echo $author['username']; ?></option>
                <?php endwhile; ?>
            </select>

            <!-- Dropdown list of gallery images -->
            <label for="recipe_image_id">Recipe Image:</label>
            <select name="recipe_image_id" id="recipe_image_id" required>
                <option value="" disabled selected>Select an Image</option>
                <?php mysqli_data_seek($result_gallery_images, 0); ?>
                <?php while ($image = mysqli_fetch_assoc($result_gallery_images)) : ?>
                    <option value="<?php echo $image['id']; ?>"><?php echo $image['filename']; ?></option>
                <?php endwhile; ?>
            </select>

            <button type="submit" name="add_recipe">Add Recipe</button>
        </form>

        <!-- Gallery Image List -->
        <h2>Gallery Images:</h2>
        <ul>
            <?php mysqli_data_seek($result_gallery_images, 0); ?>
            <?php while ($gallery_image = mysqli_fetch_assoc($result_gallery_images)) : ?>
                <li>
                    <img src="gallery/<?php echo $gallery_image['filename']; ?>" alt="Gallery Image" height="200">
                </li>
            <?php endwhile; ?>
        </ul>

        <!-- Add New Gallery Image Form -->
        <h2>Add New Gallery Image:</h2>
        <form method="POST" action="dashboard.php" enctype="multipart/form-data">
            <label for="gallery_image">Image:</label>
            <input type="file" name="gallery_image" id="gallery_image" required>

            <!-- Dropdown list of existing authors -->
            <label for="author_id">Author:</label>
            <select name="author_id" id="author_id" required>
                <option value="" disabled selected>Select an Author</option>
                <?php mysqli_data_seek($result_authors, 0); ?>
                <?php while ($author = mysqli_fetch_assoc($result_authors)) : ?>
                    <option value="<?php echo $author['id']; ?>"><?php echo $author['username']; ?></option>
                <?php endwhile; ?>
            </select>

            <button type="submit" name="add_gallery_image">Add Image</button>
        </form>

        <footer>
            <p>@justus 2023</p>
        </footer>
    </div>
</body>
</html>
