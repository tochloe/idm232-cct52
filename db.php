<?php

define("DB_SERVER", "localhost");
define("DB_USERNAME", "root");
define("DB_PASSWORD", "root");  
define("DB_NAME", "cct52_db");

function db_connect() {
    $connection = mysqli_connect(DB_SERVER, DB_USERNAME, DB_PASSWORD, DB_NAME);

    if (!$connection) {
        die("Database connection failed: " . mysqli_connect_error());
    }
    return $connection;
}
?>
