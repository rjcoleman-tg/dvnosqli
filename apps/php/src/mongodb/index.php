<?php
$method = ($_COOKIE['level'] > 1 ? ' method="post"' : ' method="get"'); 
?>
<form action="/mongodb/load_mongodb.php"  method="POST">
<input class="button" name="load" value="Load / Reset MongoDB Data" type="submit" />
</form>
<br />
Please enter both username and password below:
<form action="/?db=mongodb"<?= $method ?>>
Username: <input type="text" id="name" name="fields[name]" value="<?= (isset($_REQUEST['fields']['name']) && !is_array($_REQUEST['fields']['name']) ? htmlentities($_REQUEST['fields']['name']) : ''); ?>" />
<br />
<br />
Password: <input type="text" id="passwd" name="fields[passwd]" value="<?= (isset($_REQUEST['fields']['passwd']) && !is_array($_REQUEST['fields']['passwd']) ? htmlentities($_REQUEST['fields']['passwd']) : ''); ?>" />
<br />
<br />
<input type="hidden" name="db" value="mongodb" />
<input class="button" type="submit" name="submit" value="submit" />
</form>

<?php 
if ($_COOKIE['level'] == 0) {
  require_once $_BASE_PATH . "mongodb/easy.php";
} else if ($_COOKIE['level'] == 1) {
  require_once $_BASE_PATH . "mongodb/medium.php";
} else if ($_COOKIE['level'] == 2) {
  require_once $_BASE_PATH . "mongodb/hard.php";
} else if ($_COOKIE['level'] > 2) {
  require_once $_BASE_PATH . "mongodb/impossible.php";
} 
