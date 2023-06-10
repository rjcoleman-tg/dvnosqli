<form action="/?db=redis" method="POST">
<input name="load" value="Load / Reset Redis Cached Data" type="submit" />
</form>
<?php
require_once $_BASE_PATH . 'redis/Cache.php';
if (isset($_POST['load'])) {
  print '<p><a href="/?db=redis">Click to return to injection challenge</a></p>';
  require_once $_BASE_PATH . 'redis/load.php';
} 

if (isset($_GET['test'])) {
  try {

    $redis = CacheBuilder::create()
           ->withLevel($_COOKIE['level']);

  } catch (Exception $e) {
    echo ("caught exception: ". $e->getMessage());
  }
} else { 
  try {

    $redis = CacheBuilder::create()
            ->withLevel($_COOKIE['level']);
    $redis->getSession($_COOKIE['PHPSESSID']);

  } catch (Exception $e) {
    error_log("caught exception: ". $e->getMessage());
  }
  $output = $redis->printResults();
  if (strstr($output, 'FLAG')) {
    require_once($_BASE_PATH . 'content/banner.html');
  }
  echo $output;
}
?>
