<?php

function loadRecipes() {
    $filepath = __DIR__ . "/data/recipes.csv";

    if (!file_exists($filepath)) {
        die("CSV file not found: " . $filepath);
    }

    $file = fopen($filepath, "r");
    $recipes = [];

    $headers = fgetcsv($file);

    while (($row = fgetcsv($file)) !== false) {

        // SKIP empty or malformed rows
        if (count($row) !== count($headers)) {
            continue;
        }

        $recipes[] = array_combine($headers, $row);
    }

    fclose($file);
    return $recipes;
}
// --- Load CSV ---
$csv = array_map('str_getcsv', file('data/recipes.csv'));
$header = array_shift($csv); // remove header row

$recipes = [];
foreach ($csv as $row) {
    $recipes[] = array_combine($header, $row);
}



// --- Validate recipe ID ---
if (!isset($_GET['id'])) {
    die("No recipe selected.");
}

$id = $_GET['id'];

$recipe = null;
foreach ($recipes as $r) {
    if ($r['id'] == $id) {
        $recipe = $r;
        break;
    }
}

if (!$recipe) {
    die("Recipe not found.");
}

// Clean folder name — must match your folder structure exactly
$folder = 'img/' . $recipe['title'] . '/';
?>
<!DOCTYPE html>
<html lang="en">
<head>
    <meta charset="UTF-8" />
    <meta name="viewport" content="width=device-width, initial-scale=1.0" />
    <link rel="stylesheet" href="./normalize.css" />
    <link rel="stylesheet" href="./style.css" />
    <title>WHO'S HUNGRY? | <?= htmlspecialchars($recipe['title']) ?></title>
</head>
<body>

<!-- NAV STAYS THE SAME — OMITTED FOR BREVITY -->

<main>

<header class="header">
    <label for="nav-toggle" class="menu-icon">
        <img src="./images/logo.png" alt="logo" class="menu-img" />
    </label>

    <div class="recipe-detail">
        <h1><?= htmlspecialchars($recipe['title']) ?></h1>
        <p><?= htmlspecialchars($recipe['subheading']) ?></p>
    </div>
</header>

<div class="recipe-detail-content">

    <!-- HERO IMAGE -->
    <div class="recipe-hero-image">
        <img src="<?= $folder . $recipe['hero_img'] ?>" alt="<?= htmlspecialchars($recipe['title']) ?>">
    </div>

    <!-- DESCRIPTION -->
    <p class="recipe-description">
        <?= nl2br(htmlspecialchars($recipe['bio'])) ?>
    </p>

    <!-- INGREDIENTS -->
    <div class="recipe-ingredients">
        <h2 class="ingredients-title">Ingredients:</h2>
        <div class="ingredients-list">
            <p><?= nl2br(htmlspecialchars($recipe['ingredients'])) ?></p>
        </div>
    </div>

    <!-- INGREDIENTS IMAGE -->
    <?php if ($recipe['ingredients_image']): ?>
    <div class="recipe-step-image">
        <img src="<?= $folder . $recipe['ingredients_image'] ?>" alt="Ingredients">
    </div>
    <?php endif; ?>

    <!-- TOOLS -->
    <div class="recipe-ingredients">
        <h2 class="ingredients-title">Tools:</h2>
        <div class="ingredients-list">
            <p><?= nl2br(htmlspecialchars($recipe['tools'])) ?></p>
        </div>
    </div>

    <!-- RECIPE STEPS (1–4) -->
    <div class="recipe-instructions">
        <?php
        $steps = explode("||", $recipe['recipe']); 
        // CSV must separate steps using "||"
        ?>
        <?php foreach ($steps as $index => $step): ?>
            <p class="instruction-step">
                <strong>Step <?= $index + 1 ?>:</strong><br>
                <?= nl2br(htmlspecialchars($step)) ?>
            </p>

            <?php
            // match step images
            $img_key = "step" . ($index + 1) . "_img";
            if (!empty($recipe[$img_key])):
            ?>
                <div class="recipe-step-image">
                    <img src="<?= $folder . $recipe[$img_key] ?>" alt="Step <?= $index + 1 ?>">
                </div>
            <?php endif; ?>

        <?php endforeach; ?>
    </div>

    <!-- TIPS -->
    <?php if (!empty($recipe['tips'])): ?>
    <div class="recipe-instructions">
        <h2>Tips</h2>
        <p><?= nl2br(htmlspecialchars($recipe['tips'])) ?></p>
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
