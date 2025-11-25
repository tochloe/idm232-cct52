<?php
require_once("db.php");

// connect to database
$conn = db_connect();

// get search term if user submitted a search
$searchTerm = $_GET['search'] ?? '';

// UPDATED: allow searching
function loadRecipesFromDatabase($conn, $searchTerm = '') {

    if ($searchTerm !== '') {
        $safe = $conn->real_escape_string($searchTerm);

        $sql = "
            SELECT id, title, subheading, culture, hero_img 
            FROM recipes3
            WHERE title LIKE '%$safe%'
               OR subheading LIKE '%$safe%'
               OR culture LIKE '%$safe%'
        ";
    } else {
        $sql = "SELECT id, title, subheading, culture, hero_img FROM recipes3";
    }

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

$recipes = loadRecipesFromDatabase($conn, $searchTerm);

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
        <form class="search-form" action="index.php" method="get">
            <input 
                type="search" 
                class="search-input" 
                name="search"
                value="<?= htmlspecialchars($searchTerm) ?>"
                placeholder="Search recipes..." 
                aria-label="Search recipes"
            >
            <button type="submit" class="search-button" aria-label="Search">
                <svg class="search-icon" xmlns="http://www.w3.org/2000/svg" viewBox="0 0 24 24" fill="none" stroke="currentColor" stroke-width="2" stroke-linecap="round" stroke-linejoin="round">
                    <circle cx="11" cy="11" r="8"></circle>
                    <path d="m21 21-4.35-4.35"></path>
                </svg>
            </button>
        </form>
    </div>

    <div class="logo-container">
        <a href="./index.php"><img class="logo" src="img/whos_hungry_logo.svg" alt="logo"></a>
    </div>

    <div class="nav-section">
        <p class="nav-title">WHO'S HUNGRY?</p>
        <p class="nav-sub">Breakfast, Lunch, or Dinner</p>
        <p class="nav-desc">A collection of recipes for any occasion and any skill level from all over the world. Cook with us!</p>
    </div>

    <div class="nav-tabs">
        <a href="./index.php" class="nav-link active">Recipes</a>
        <a href="./about.php" class="nav-link">About</a>
        <a href="./help.php" class="nav-link">Help</a>
    </div>
</nav>
<!-- NAV END -->

<main>

<header class="header">
    <label for="nav-toggle" class="menu-icon">
        <img src="img/whos_hungry_logo.svg" alt="logo" class="menu-img">
    </label>

    <h1 class="header-title">
        <?php if (count($recipes) === 0): ?>
            Error
        <?php elseif ($searchTerm): ?>
            Results for “<?= htmlspecialchars($searchTerm) ?>”
        <?php else: ?>
            Popular Recipes
        <?php endif; ?>
    </h1>
</header> 

<!-- IF NO RESULTS, SHOW FULL ERROR PAGE -->
<?php if (count($recipes) === 0): ?>

<div class="error-content-container">
    <div class="error-content">
        <p class="error-title">UH OH!</p>
        <p class="error-message">
            No recipes found for “<?= htmlspecialchars($searchTerm) ?>”.<br>
            Try again — page does not exist or cannot be found.
        </p>
    </div>
</div>

<?php else: ?>

<!-- OTHERWISE SHOW THE GRID -->
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
                <div class="category-badge"><?= htmlspecialchars($recipe['culture']) ?></div>
                <h2 class="recipe-title"><?= htmlspecialchars($recipe['title']) ?></h2>
                <p class="recipe-sub">with <?= htmlspecialchars($recipe['subheading']) ?></p>
            </article>
        </a>
        <?php endforeach; ?>

    </div>
</div>

<?php endif; ?>

</main>

<footer class="footer">
    <p class="footer-text">2025</p>
    <p class="footer-text">IDM232_CCT52</p>
</footer>

</body>
</html>
