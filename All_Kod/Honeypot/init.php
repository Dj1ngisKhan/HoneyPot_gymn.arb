<?php

// Ladda om classes
require_once "autoloader.php";



// Starta Session
session_start();

// Include the main configuration file
require_once "config/config.php";

//
//// Ladda databas, behöver ej
//require_once "classes/Database.php";


// Inkludera helper functions
require_once "helpers.php";


// Definera global konstanter
define("APP_NAME", "CMS PDO System");
define("PROJECT_DIR", "cms-pdo");
?>

