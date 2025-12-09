<?php
require_once("db.php");

$conn = db_connect();

$searchTerm = $_GET['search'] ?? '';
$cultureFilter = $_GET['culture'] ?? '';

function loadRecipesFromDatabase($conn, $searchTerm = '', $cultureFilter = '') {
    $recipes = [];

    // Search through title, subheading, culture, AND ingredients column
    $sql = "SELECT id, title, subheading, culture, hero_img FROM recipes3";
    
    $conditions = [];
    $params = [];

    if ($searchTerm !== '') {
        // Search in title, subheading, culture, AND ingredients
        $conditions[] = "(title LIKE ? OR subheading LIKE ? OR culture LIKE ? OR ingredients LIKE ?)";
        $like = "%" . $searchTerm . "%";
        $params[] = $like;
        $params[] = $like;
        $params[] = $like;
        $params[] = $like;
    }

    if ($cultureFilter !== '' && $cultureFilter !== 'all') {
        $conditions[] = "culture = ?";
        $params[] = $cultureFilter;
    }

    if (!empty($conditions)) {
        $sql .= " WHERE " . implode(" AND ", $conditions);
    }

    // Order by title for consistent results
    $sql .= " ORDER BY title";

    $stmt = $conn->prepare($sql);
    if (!$stmt) {
        die("Prepare failed: " . $conn->error);
    }

    if (!empty($params)) {
        // Build the types string dynamically based on number of parameters
        $types = str_repeat('s', count($params));
        $stmt->bind_param($types, ...$params);
    }

    $stmt->execute();
    $result = $stmt->get_result();

    while ($row = $result->fetch_assoc()) {
        $recipes[] = $row;
    }

    $stmt->close();
    return $recipes;
}

// Get all unique cultures for filter buttons
function getAllCultures($conn) {
    $sql = "SELECT DISTINCT culture FROM recipes3 ORDER BY culture";
    $stmt = $conn->prepare($sql);
    
    if (!$stmt) {
        die("Prepare failed: " . $conn->error);
    }
    
    $stmt->execute();
    $result = $stmt->get_result();
    
    $cultures = [];
    while ($row = $result->fetch_assoc()) {
        $cultures[] = $row['culture'];
    }
    
    $stmt->close();
    return $cultures;
}

$recipes = loadRecipesFromDatabase($conn, $searchTerm, $cultureFilter);
$allCultures = getAllCultures($conn);

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
<label for="nav-toggle" class="nav-overlay"></label>

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
            <?php if ($cultureFilter): ?>
                <input type="hidden" name="culture" value="<?= htmlspecialchars($cultureFilter) ?>">
            <?php endif; ?>
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

<header>
    <label for="nav-toggle" class="menu-icon">
        <svg class="hamburger-menu" viewBox="0 0 24 24" fill="none" stroke="currentColor" stroke-width="2" stroke-linecap="round" stroke-linejoin="round">
            <line x1="3" y1="6" x2="21" y2="6"></line>
            <line x1="3" y1="12" x2="21" y2="12"></line>
            <line x1="3" y1="18" x2="21" y2="18"></line>
        </svg>
    </label>

    <h1 class="header-title">
        <?php if (count($recipes) === 0): ?>
            Error
        <?php elseif ($searchTerm): ?>
            Results for "<?= htmlspecialchars($searchTerm) ?>"
        <?php elseif ($cultureFilter && $cultureFilter !== 'all'): ?>
            <?= htmlspecialchars($cultureFilter) ?> Recipes
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
            <?php if ($searchTerm): ?>
                No recipes found for "<?= htmlspecialchars($searchTerm) ?>".<br>
            <?php elseif ($cultureFilter): ?>
                No recipes found for <?= htmlspecialchars($cultureFilter) ?> cuisine.<br>
            <?php else: ?>
                No recipes found.<br>
            <?php endif; ?>
            Try again — page does not exist or cannot be found.
        </p>
    </div>
</div>

<?php else: ?>

<!-- FILTER SECTION -->
<div class="filter-section">
    <div class="filter-container">
        <a href="index.php" class="filter-button <?= $cultureFilter === '' || $cultureFilter === 'all' ? 'active' : '' ?>">
            All
        </a>
        <?php foreach ($allCultures as $culture): ?>
            <a href="index.php?culture=<?= urlencode($culture) ?>" 
               class="filter-button <?= $cultureFilter === $culture ? 'active' : '' ?>">
                <?= htmlspecialchars($culture) ?>
            </a>
        <?php endforeach; ?>
    </div>
</div> 



<!-- RECIPE GRID -->
<div class="recipe-container">
    <div class="recipe-grid">

        <?php foreach ($recipes as $recipe): ?>
       <div class="recipe-card">
    <a href="./instructions.php?id=<?= $recipe['id'] ?>" class="card-link">
        <article class="card">
            <div class="card-border">
                <img 
                    src="img/<?= htmlspecialchars($recipe['hero_img']) ?>" 
                    alt="<?= htmlspecialchars($recipe['title']) ?>" 
                    class="recipe-img"
                >
            </div>
            <a href="index.php?culture=<?= urlencode($recipe['culture']) ?>" class="category-badge">
        <?= htmlspecialchars($recipe['culture']) ?>
    </a>
            <h2 class="recipe-title"><?= htmlspecialchars($recipe['title']) ?></h2>
            <p class="recipe-sub">with <?= htmlspecialchars($recipe['subheading']) ?></p>
        </article>
    </a>

    
</div>
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