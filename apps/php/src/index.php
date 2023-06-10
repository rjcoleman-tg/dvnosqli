<?php
$_BASE_PATH = '/var/www/html/';
require_once '../vendor/autoload.php';
require_once $_BASE_PATH . 'lib/dvnosqli.php';
require_once $_BASE_PATH . 'lib/setcookie.php';

$_PAGES = [
  'neo4j' => 'neo4j/index.php', 
  'mongodb' => 'mongodb/index.php', 
  'redis' => 'redis/index.php' 
];
?><!doctype html>
<html lang="en">
<head>
  <meta charset="utf-8">
  <meta name="viewport" content="width=device-width, initial-scale=1">
  <link rel="stylesheet" href="/css/styles.css">
  <title>DVNoSQLi</title>
</head>
<body>

<?php
require_once($_BASE_PATH . "content/nav.php");
?>

<div class="main">
<?php

if (isset($_GET['db']) && isset($_PAGES[$_GET['db']])) {
  require_once($_BASE_PATH . $_PAGES[$_GET['db']]);
} else {
  require_once($_BASE_PATH . 'content/home.php');
}

?>
</div>
<?php
require_once($_BASE_PATH . "content/level.php");
?>

<script>
function handleHamburger() {
  var x = document.getElementById("myTopnav");
  if (x.className === "topnav") {
    x.className += " responsive";
  } else {
    x.className = "topnav";
  }
}
</script>

</body>
</html>

