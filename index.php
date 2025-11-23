<?php

function db_connect() {
    $host = getenv("DB_SERVER");
    $user = getenv("DB_USERNAME");
    $pass = getenv("DB_PASSWORD");
    $dbname = getenv("DB_NAME");

    $conn = new mysqli($host, $user, $pass, $dbname);

    if ($conn->connect_error) {
        die("Database connection failed: " . $conn->connect_error);
    }

    return $conn;
}

function loadRecipes() {
    $conn = db_connect();

    // Make sure your table matches these fields:
    // id, title, subheading, hero_img
    $sql = "SELECT id, title, subheading, hero_img FROM recipes";
    $result = $conn->query($sql);

    $recipes = [];

    if ($result && $result->num_rows > 0) {
        while ($row = $result->fetch_assoc()) {
            $recipes[] = $row;
        }
    }

    $conn->close();
    return $recipes;
}

$recipes = loadRecipes();

?>
