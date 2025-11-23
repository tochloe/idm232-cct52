<?php
echo in_array("mod_rewrite", apache_get_modules()) ? "rewrite ON" : "rewrite OFF";
?>