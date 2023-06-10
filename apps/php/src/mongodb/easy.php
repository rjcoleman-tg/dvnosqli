<?php
if (isset($_REQUEST['fields'])) {
  $manager = new MongoDB\Driver\Manager("mongodb://root:example@dvnosqli-mongo-1:27017");
  $name = isset($_REQUEST['fields']['name']) ? $_REQUEST['fields']['name'] : '';
  $passwd = isset($_REQUEST['fields']['passwd']) ? $_REQUEST['fields']['passwd'] : '';

  $query=new MongoDB\Driver\Query(array(
    "name" => $name,
    "passwd" => $passwd
  ));

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
      require_once $_BASE_PATH . "content/banner.html";
      echo $content;
      echo '<br /><a href="https://www.mongodb.com/docs/manual/reference/operator/query/" target="_blank">Take a look at the query operators available with mongo and try some of them out</a>';
      print_code();
    } else {
      echo "<br />Login Failed!";
    }
  } catch (Exception $e) {
    echo "<br />Login Failed!";
    echo "<br><br>" . $e->getMessage();
    echo "<br>";
    echo printObj($query);
  }
}
function print_code() {
  echo " 
<br />
<br />
<details>
  <summary>====== Problematic Code Below ======</summary>
<pre class=\"bad-code\">

  \$name   = isset(\$_REQUEST['fields']['name']) ? \$_REQUEST['fields']['name'] : '';
  \$passwd = isset(\$_REQUEST['fields']['passwd']) ? \$_REQUEST['fields']['passwd'] : '';

  \$query=new MongoDB\Driver\Query(array(
    \"name\"   => \$name,
    \"passwd\" => \$passwd
  ));

</pre>

</details>

";
}
