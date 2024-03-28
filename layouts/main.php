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

<?php 

    if (isset($_SESSION)&& isset($_SESSION['lang']) && $_SESSION['lang'] == "ar") {
        ?>
            <html lang="ar" dir="rtl">
                <?php
            }else{
                ?>
                <html lang="en" dir="ltr">
                <?php
            }
?>