<?php
require_once("db.php");

// connect to database
$conn = db_connect();

function loadRecipesFromDatabase($conn) {
    $sql = "SELECT id, title, subheading, hero_img FROM recipes_1";
    $result = $conn->query($sql);

    if (!$result) {
        die("SQL Error: " . $conn->error);
    }

    $recipes = [];

    while ($row = $result->fetch_assoc()) {
        $recipes[] = $row;
    }

    return $recipes;
}

$recipes = loadRecipesFromDatabase($conn);
$conn->close();
?>

<!DOCTYPE html>
<html lang="en">
<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <link rel="stylesheet" href="./normalize.css">
    <link rel="stylesheet" href="./style.css">
    <title>WHO'S HUNGRY?</title>
</head>
<body>

<!-- NAVIGATION BAR -->
<input type="checkbox" id="nav-toggle" class="nav-toggle">

<nav class="side">

    <div class="search-container">
        <form class="search-form" action="#" method="get">
            <input type="search" class="search-input" placeholder="Search recipes..." aria-label="Search recipes">
            <button type="submit" class="search-button" aria-label="Search">
                <svg class="search-icon" xmlns="http://www.w3.org/2000/svg" viewBox="0 0 24 24" fill="none" stroke="currentColor" stroke-width="2" stroke-linecap="round" stroke-linejoin="round">
                    <circle cx="11" cy="11" r="8"></circle>
                    <path d="m21 21-4.35-4.35"></path>
                </svg>
            </button>
        </form>
    </div>

    <div class="logo-container">
        <a href="./index.php"><img class="logo" src="images/logo.png" alt="logo"></a>
    </div>

    <div class="nav-section">
        <p class="nav-title">WHO'S HUNGRY?</p>
        <p class="nav-sub">Breakfast, Lunch, or Dinner</p>
        <p class="nav-desc">A collection of recipes for any occasion and any skill level from all over the world. Cook with us!</p>
    </div>

    <div class="nav-tabs">
        <a href="./index.php" class="nav-link active">Recipes</a>
        <a href="./about.html" class="nav-link">About</a>
        <a href="./help.html" class="nav-link">Help</a>
    </div>
</nav>
<!-- NAVIGATION BAR end -->

<main>
<header class="header">
    <label for="nav-toggle" class="menu-icon">
        <img src="./images/logo.png" alt="logo" class="menu-img">
    </label>
    <h1>Popular Recipes</h1>
</header> 

<div class="recipe-container">
    <div class="recipe-grid">

        <?php foreach ($recipes as $recipe): ?>
        <a class="recipe-card" href="./instructions.php?id=<?= $recipe['id'] ?>">
            <article class="card">
                <div class="card-border">
                    <img 
                        src="img/<?= htmlspecialchars($recipe['hero_img']) ?>" 
                        alt="<?= htmlspecialchars($recipe['title']) ?>" 
                        class="recipe-img"
                    >
                </div>
                <h2 class="recipe-title"><?= htmlspecialchars($recipe['title']) ?></h2>
                <p class="recipe-sub">with <?= htmlspecialchars($recipe['subheading']) ?></p>
            </article>
        </a>
        <?php endforeach; ?>
        
    </div>
</div>
</main>

<footer class="footer">
    <p class="footer-text">2025</p>
    <p class="footer-text">IDM232_CCT52</p>
</footer>

</body>
</html>