<!DOCTYPE html>
<html lang="en">

<head>
    <link rel="stylesheet" href="assets/gemma.css?v=<?php echo time(); ?>" />
    <meta charset="UTF-8" />
    <meta name="viewport" content="width=device-width, initial-scale=1.0" />
    <title>Boarding House Rental Management System</title>
</head>

<body>
    <!-- Header -->
    <div class="header">
        <h1>Boarding House Rental Management System</h1>
    </div>
    <div class="container">
        <?php include ('includes/sidebar.php') ?>


        <div class="category-form">
            <h2>Category Form</h2>
            <div class="category-name">
                <label for="category-name">Name:</label>
                <input type="text" id="category-name" name="category-name">
            </div>
            <div class="rectangular-box"></div>
            <div class="action-buttons">
                <button>Save</button>
                <button>Cancel</button>
            </div>
        </div>
        <div class="category-list-container">
            <h2>Category List</h2>
            <div class="entries">Show</div>
        </div>
    </div>
</body>

</html>