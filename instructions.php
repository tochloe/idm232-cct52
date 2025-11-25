<?php
require_once("db.php");

// Get recipe ID from URL
$recipe_id = isset($_GET['id']) ? intval($_GET['id']) : 0;

if ($recipe_id <= 0) {
    header("Location: index.php");
    exit();
}

// Connect to database
$conn = db_connect();

// Fetch recipe details
$sql = "SELECT * FROM recipes_1 WHERE id = ?";
$stmt = $conn->prepare($sql);
$stmt->bind_param("i", $recipe_id);
$stmt->execute();
$result = $stmt->get_result();

if ($result->num_rows === 0) {
    header("Location: error.html");
    exit();
}

$recipe = $result->fetch_assoc();
$conn->close();
?>

<!DOCTYPE html>
<html lang="en">
<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <link rel="stylesheet" href="./normalize.css">
    <link rel="stylesheet" href="./style.css">
    <title><?= htmlspecialchars($recipe['title']) ?> | WHO'S HUNGRY?</title>
</head>
<body>

<!-- NAVIGATION -->
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
        <a href="./index.php"><img class="logo" src="img/whos_hungry_logo.svg" alt="logo"></a>
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

<main>
    <header class="header">
        <label for="nav-toggle" class="menu-icon">
            <img src="./images/logo.png" alt="logo" class="menu-img">
        </label>
        <div class="recipe-detail">
            <h1><?= htmlspecialchars($recipe['title']) ?></h1>
            <p>with <?= htmlspecialchars($recipe['subheading']) ?></p>
        </div>
    </header>

    <div class="recipe-detail-content">
        <!-- Hero Image -->
        <?php if (!empty($recipe['hero_img'])): ?>
        <div class="recipe-hero-image">
            <img src="img/<?= htmlspecialchars($recipe['hero_img']) ?>" 
                 alt="<?= htmlspecialchars($recipe['title']) ?>">
        </div>
        <?php endif; ?>

        <!-- Bio/Description -->
        <?php if (!empty($recipe['bio'])): ?>
        <div class="recipe-description">
            <?= nl2br(htmlspecialchars($recipe['bio'])) ?>
        </div>
        <?php endif; ?>

        <!-- Ingredients Section with Image -->
        <?php if (!empty($recipe['ingredients'])): ?>
        <div class="recipe-ingredients">
            <h2 class="ingredients-title">Ingredients</h2>
            <div class="ingredients-list">
                <?= nl2br(htmlspecialchars($recipe['ingredients'])) ?>
            </div>
        </div>
        <?php endif; ?>

        <!-- Ingredients Image (if separate) -->
        <?php if (!empty($recipe['ingredients_image'])): ?>
        <div class="recipe-step-image">
            <img src="img/<?= htmlspecialchars($recipe['ingredients_image']) ?>" 
                 alt="Ingredients for <?= htmlspecialchars($recipe['title']) ?>">
        </div>
        <?php endif; ?>

        <!-- Recipe Instructions -->
        <?php if (!empty($recipe['recipe'])): ?>
        <div class="recipe-instructions">
            <?= nl2br(htmlspecialchars($recipe['recipe'])) ?>
        </div>
        <?php endif; ?>

        <!-- Step Images - Display based on how many exist -->
        <?php 
        $stepImages = [];
        if (!empty($recipe['step1_img'])) $stepImages[] = $recipe['step1_img'];
        if (!empty($recipe['step2_img'])) $stepImages[] = $recipe['step2_img'];
        if (!empty($recipe['step3_img'])) $stepImages[] = $recipe['step3_img'];
        if (!empty($recipe['step4_img'])) $stepImages[] = $recipe['step4_img'];
        
        $imageCount = count($stepImages);
        ?>

        <?php if ($imageCount == 1): ?>
            <div class="recipe-step-image">
                <img src="img/<?= htmlspecialchars($stepImages[0]) ?>" alt="Step 1">
            </div>
        <?php elseif ($imageCount == 2): ?>
            <div class="duo-img">
                <div class="recipe-grid-image">
                    <img src="img/<?= htmlspecialchars($stepImages[0]) ?>" alt="Step 1">
                </div>
                <div class="recipe-grid-image">
                    <img src="img/<?= htmlspecialchars($stepImages[1]) ?>" alt="Step 2">
                </div>
            </div>
        <?php elseif ($imageCount == 3): ?>
            <div class="recipe-step-image">
                <img src="img/<?= htmlspecialchars($stepImages[0]) ?>" alt="Step 1">
            </div>
            <div class="duo-img">
                <div class="recipe-grid-image">
                    <img src="img/<?= htmlspecialchars($stepImages[1]) ?>" alt="Step 2">
                </div>
                <div class="recipe-grid-image">
                    <img src="img/<?= htmlspecialchars($stepImages[2]) ?>" alt="Step 3">
                </div>
            </div>
        <?php elseif ($imageCount == 4): ?>
            <div class="duo-img">
                <div class="recipe-grid-image">
                    <img src="img/<?= htmlspecialchars($stepImages[0]) ?>" alt="Step 1">
                </div>
                <div class="recipe-grid-image">
                    <img src="img/<?= htmlspecialchars($stepImages[1]) ?>" alt="Step 2">
                </div>
            </div>
            <div class="duo-img">
                <div class="recipe-grid-image">
                    <img src="img/<?= htmlspecialchars($stepImages[2]) ?>" alt="Step 3">
                </div>
                <div class="recipe-grid-image">
                    <img src="img/<?= htmlspecialchars($stepImages[3]) ?>" alt="Step 4">
                </div>
            </div>
        <?php endif; ?>

        <!-- Tips Section -->
        <?php if (!empty($recipe['tips'])): ?>
        <div class="recipe-instructions">
            <strong>Tips & Tricks:</strong><br>
            <?= nl2br(htmlspecialchars($recipe['tips'])) ?>
        </div>
        <?php endif; ?>

        <!-- Tools Section -->
        <?php if (!empty($recipe['tools'])): ?>
        <div class="recipe-instructions">
            <strong>Tools You'll Need:</strong><br>
            <?= nl2br(htmlspecialchars($recipe['tools'])) ?>
        </div>
        <?php endif; ?>

    </div>
</main>

<footer class="footer">
    <p class="footer-text">2025</p>
    <p class="footer-text">IDM232_CCT52</p>
</footer>

</body>
</html>