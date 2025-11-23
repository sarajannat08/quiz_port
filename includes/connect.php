// Source - https://stackoverflow.com/a
// Posted by DeclanFell
// Retrieved 2025-11-08, License - CC BY-SA 4.0

    Connect.php
<?php
/* Database connection settings */
$host = '';
$user = '';
$pass = '';
$db = '';
$mysqli = new mysqli($host,$user,$pass,$db) or die($mysqli->error);
?>
