<?php

function loadRecipes() {
    $csvFile = __DIR__ . "/recipes.csv";

    if (!file_exists($csvFile)) {
        return [];  // IMPORTANT: prevents NULL
    }

    $rows = array_map('str_getcsv', file($csvFile));
    $header = array_shift($rows);

    $recipes = [];

    foreach ($rows as $row) {
        if (count($row) === count($header)) {
            $recipes[] = array_combine($header, $row);
        }
    }

    return $recipes;
}