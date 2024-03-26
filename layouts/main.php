<?php
require_once ("lang.php");
?>
<?php
    if (!empty($_SESSION['lang'])) {
        $sessionLang = $_SESSION['lang'];
        require_once ('assets/lang/'.$sessionLang.'.php');
    } else {
        require_once ('assets/lang/ar.php');
    }
?>
<!doctype html>
<html lang="ar" dir="rtl">