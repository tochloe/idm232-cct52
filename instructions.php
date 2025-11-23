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

<main>
    <header class="header">
        <label for="nav-toggle" class="menu-icon">
            <img src="./images/logo.png" alt="logo" class="menu-img">
        </label>
        <h1><?= htmlspecialchars($recipe['title']) ?></h1>
    </header>

    <div class="recipe-container">
        <!-- Hero Image -->
        <div class="hero-section">
            <img src="img/<?= htmlspecialchars($recipe['hero_img']) ?>" 
                 alt="<?= htmlspecialchars($recipe['title']) ?>" 
                 class="hero-image">
        </div>

        <!-- Recipe Header -->
        <div class="recipe-header">
            <h2 class="recipe-subtitle"><?= htmlspecialchars($recipe['subheading']) ?></h2>
            <?php if (!empty($recipe['culture'])): ?>
            <p class="recipe-culture"><strong>Cuisine:</strong> <?= htmlspecialchars($recipe['culture']) ?></p>
            <?php endif; ?>
        </div>

        <!-- Bio/Description -->
        <?php if (!empty($recipe['bio'])): ?>
        <section class="recipe-section">
            <p class="recipe-bio"><?= nl2br(htmlspecialchars($recipe['bio'])) ?></p>
        </section>
        <?php endif; ?>

        <!-- Ingredients Section -->
        <?php if (!empty($recipe['ingredients'])): ?>
        <section class="recipe-section ingredients-section">
            <h3 class="section-title">Ingredients</h3>
            <?php if (!empty($recipe['ingredients_image'])): ?>
            <div class="section-image">
                <img src="img/<?= htmlspecialchars($recipe['ingredients_image']) ?>" 
                     alt="Ingredients for <?= htmlspecialchars($recipe['title']) ?>"
                     class="step-img">
            </div>
            <?php endif; ?>
            <div class="ingredients-content">
                <?= nl2br(htmlspecialchars($recipe['ingredients'])) ?>
            </div>
        </section>
        <?php endif; ?>

        <!-- Tools Section -->
        <?php if (!empty($recipe['tools'])): ?>
        <section class="recipe-section tools-section">
            <h3 class="section-title">Tools You'll Need</h3>
            <div class="tools-content">
                <?= nl2br(htmlspecialchars($recipe['tools'])) ?>
            </div>
        </section>
        <?php endif; ?>

        <!-- Recipe Instructions with Step Images -->
        <?php if (!empty($recipe['recipe'])): ?>
        <section class="recipe-section instructions-section">
            <h3 class="section-title">Instructions</h3>
            
            <div class="instructions-content">
                <?= nl2br(htmlspecialchars($recipe['recipe'])) ?>
            </div>

            <!-- Step Images -->
            <div class="steps-gallery">
                <?php if (!empty($recipe['step1_img'])): ?>
                <div class="step-image">
                    <img src="img/<?= htmlspecialchars($recipe['step1_img']) ?>" 
                         alt="Step 1" class="step-img">
                </div>
                <?php endif; ?>

                <?php if (!empty($recipe['step2_img'])): ?>
                <div class="step-image">
                    <img src="img/<?= htmlspecialchars($recipe['step2_img']) ?>" 
                         alt="Step 2" class="step-img">
                </div>
                <?php endif; ?>

                <?php if (!empty($recipe['step3_img'])): ?>
                <div class="step-image">
                    <img src="img/<?= htmlspecialchars($recipe['step3_img']) ?>" 
                         alt="Step 3" class="step-img">
                </div>
                <?php endif; ?>

                <?php if (!empty($recipe['step4_img'])): ?>
                <div class="step-image">
                    <img src="img/<?= htmlspecialchars($recipe['step4_img']) ?>" 
                         alt="Step 4" class="step-img">
                </div>
                <?php endif; ?>
            </div>
        </section>
        <?php endif; ?>

        <!-- Tips Section -->
        <?php if (!empty($recipe['tips'])): ?>
        <section class="recipe-section tips-section">
            <h3 class="section-title">Tips & Tricks</h3>
            <div class="tips-content">
                <?= nl2br(htmlspecialchars($recipe['tips'])) ?>
            </div>
        </section>
        <?php endif; ?>

        <!-- Back Button -->
        <div class="recipe-actions">
            <a href="index.php" class="btn-back">← Back to All Recipes</a>
        </div>
    </div>
</main>

<footer class="footer">
    <p class="footer-text">2025</p>
    <p class="footer-text">IDM232_CCT52</p>
</footer>

</body>
</html>