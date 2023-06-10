<?php 
if (isset($_REQUEST['fields']) && isset($_REQUEST['fields'])) {
  $manager = new MongoDB\Driver\Manager("mongodb://root:example@dvnosqli-mongo-1:27017");
  $name = isset($_REQUEST['fields']['name']) ? $_REQUEST['fields']['name'] : '';
  $passwd = isset($_REQUEST['fields']['passwd']) ? $_REQUEST['fields']['passwd'] : '';

  $function = "
  function() {
    var name = '".$name."';
    var passwd = '".$passwd."';
    if(this.name == name && this.passwd == passwd) return true;
    else return false;
  }";
    

  $query = new MongoDB\Driver\Query(array(
    '$where' => $function
  ));

  try {
    $users = $manager->executeQuery("test.users",$query)->toArray();
    $content = '';
    foreach ($users as $user) {
      $user = ((array)$user);
      $user_row = '<br/>====Login Successful====</br>';
      foreach ($_REQUEST['fields'] as $lbl => $val) {
        $user_row .= $lbl . ': ' . (isset($user[$lbl]) ? $user[$lbl] : '') .'</br>';
      }
      $content .= $user_row;
    }
    if (count($users) > 0) {
      echo "<br />";
      require_once $_BASE_PATH . "content/banner.html";
      echo $content;
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

  \$function = \"
    function() {
      var name = '\".\$name.\"';
      var passwd = '\".\$passwd.\"';
      if(this.name == name && this.passwd == passwd) return true;
      else return false;
    }\";
    
  \$query = new MongoDB\Driver\Query(array(
    '\$where' => \$function
  ));


</pre>

</details>

";
}
/*
  // javascript sleep without using settimeout (settimeout does not exist on mongodb server)
      const date = Date.now();
      let currentDate = null;
      do {
        currentDate = Date.now();
      } while (currentDate - date < 100);
*/

