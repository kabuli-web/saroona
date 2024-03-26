<?php
// include language configuration file based on selected language
$langArray = ["en", "es", "de", "it", "ru", "ar"];
$lng = "ar";
if (isset($_GET['lang'])) {
    $lng = $_GET['lang'];
    if (in_array($lng, $langArray)) {
        $_SESSION['lang'] = $lng;
        
    } else {
        $_SESSION['lang'] = 'ar';
    }
}
if(isset($_SESSION['lang'])) {
    $lng = $_SESSION['lang'];
} else {
    $lng = "ar";
}
?>
