<?php
if (isset($_REQUEST['fields']) && isset($_REQUEST['fields'])) {
  $manager = new MongoDB\Driver\Manager("mongodb://root:example@dvnosqli-mongo-1:27017");
  $name = isset($_REQUEST['fields']['name']) ? $_REQUEST['fields']['name'] : '';
  $passwd = isset($_REQUEST['fields']['passwd']) ? $_REQUEST['fields']['passwd'] : '';

  $query=new MongoDB\Driver\Query($_REQUEST['fields']);

  try {
    $users = $manager->executeQuery("test.users",$query)->toArray();
    $content = '';
    foreach ($users as $user) {
      $user = ((array)$user);
      $user_row = '<br/>====Login Successful====</br>';
      foreach ($_REQUEST['fields'] as $lbl => $val) {
        $user_row .= $lbl . ': ' . $user[$lbl].'</br>';
      }
      $content .= $user_row;
    }
    if (count($users) > 0) {
      echo "<br />";
      if (strstr($content, '!!HARD')) { 
        require_once $_BASE_PATH . "content/banner.html";
      }

      echo $content;
    } else {
      echo "<br />Login Failed!";
    }
  } catch (Exception $e) {
    error_log("ALERT: in " . __FILE__ . " error occured " . $e->getMessage());
    echo "<br />Login Failed!";
  }
}

/*
      const date = Date.now();
      let currentDate = null;
      do {
        currentDate = Date.now();
      } while (currentDate - date < 100);
*/

