<?php

function loadRecipes() {
    $filepath = __DIR__ . "/recipes.csv";

    if (!file_exists($filepath)) {
        die("CSV file not found: " . $filepath);
    }

    $file = fopen($filepath, "r");
    $recipes = [];

    // header row
    $headers = fgetcsv($file);

    // data rows
    while (($row = fgetcsv($file)) !== false) {
        if (count($row) === count($headers)) {
            $recipes[] = array_combine($headers, $row);
        }
    }

    fclose($file);
    return $recipes;
}
