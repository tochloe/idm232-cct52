<?php
// Handle search redirect
if (isset($_GET['search']) && !empty($_GET['search'])) {
    $term = urlencode($_GET['search']);
    header("Location: index.php?search=$term");
    exit;
}
?>
<!DOCTYPE html>
<html lang="en">
<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <link rel="stylesheet" href="./normalize.css">
    <link rel="stylesheet" href="./style.css">
    <title>WHO'S HUNGRY? | Help</title>
</head>
<body>

<input type="checkbox" id="nav-toggle" class="nav-toggle">

<nav class="side">

    <div class="search-container">
        <form class="search-form" action="" method="get">
            <input type="search" name="search" class="search-input" placeholder="Search recipes..." aria-label="Search recipes">
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
        <a href="./index.php" class="nav-link">Recipes</a>
        <a href="./about.php" class="nav-link">About</a>
        <a href="./help.php" class="nav-link active">Help</a>
    </div>
</nav>

<main>
<header class="header">
    <label for="nav-toggle" class="menu-icon">
        <img src="img/whos_hungry_logo.svg" alt="logo" class="menu-img">
    </label>
    <h1>Help</h1>
</header> 

<div class="center-content">
    <div class="help-section">
        <h3 class="help-heading">SEARCH!</h3>
        <p>Find recipes with a quick search in our <em>recipes</em> tab.</p>
    </div>

    <div class="help-section">
        <h3 class="help-heading">TRY!</h3>
        <p>Follow each step and refer to the provided photos to impress your friends.</p>
    </div>

    <div class="help-section">
        <h3 class="help-heading">SHARE!</h3>
        <p>Tag us @whoshungryrecipes so we can judge your culinary journey!</p>
    </div>

    <p class="help-disclaimer">All recipes are provided by Blue Apron...</p>
</div>

</main>

<footer class="footer">
    <p class="footer-text">2025</p>
    <p class="footer-text">IDM232_CCT52</p>
</footer>
</body>
</html>
