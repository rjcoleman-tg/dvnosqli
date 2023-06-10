<?php 
$name = $passwd = $flag = $msg = false;

// confirm data in exists and is in correct format, in our case, both name and passwd should be strings
if (isset($_REQUEST['fields']['name'])) {
  if (!is_string($_REQUEST['fields']['name'])) {
    // do not print error to the screen, only to the logs
    error_log("ALERT name is being attacked.");
    $msg = "<br />Login Failed";
  } else {
    $name = $_REQUEST['fields']['name'];  
  }
}

if (isset($_REQUEST['fields']['passwd'])) {
  if (!is_string($_REQUEST['fields']['passwd'])) {
    // do not print error to the screen, only to the logs
    error_log("ALERT passwd is being attacked.");
    $msg = "<br />Login Failed";
  } else {
    $passwd = $_REQUEST['fields']['passwd'];  
  }
}

if ($name && $passwd) {
  $manager = new MongoDB\Driver\Manager("mongodb://root:example@dvnosqli-mongo-1:27017");

  // build query
  try {

    $query=new MongoDB\Driver\Query(array(
      "name" => strval($name),
      "passwd" => strval($passwd)
    ));

    $users = $manager->executeQuery("test.users",$query)->toArray();
    foreach ($users as $user) {
      $user = ((array)$user);
      echo '<br/>====Login Successful====</br>';
      echo 'name: ' . htmlentities($user['name']) . '<br />';
      echo 'passwd: ' . htmlentities($user['passwd']) . '<br />';
      if ($user['passwd'] != $passwd) {
        $flag = true;
      }
    }
    if ($flag) {
      echo "<br />";
      echo "<h3 style='color: red; font-weight: heavy;'>If you are here, you have used an unexpected vulnerability! Please submit <a href='https://github.com/RJColeman/tools/issues' target='_blank'>an issue</a> describing the steps you took.</h3>";
      require_once $_BASE_PATH . "content/banner.html";

    } else if (count($users) == 0) {
      echo "<br />Login Failed!";
      print_good_code();
    } else {
      print_good_code();
    }
  } catch (Exception $e) {
    error_log("ALERT " . $e->getMessage());
    $msg = "<br />Login Failed";
    print_good_code();
  }
}

if ($msg) echo $msg;

function print_good_code() {
  echo '
<br />
<h3>====== Mitigation Information Below ======</h3>
Below is the code mitigating NoSQLi vulnerabilities for this MongoDB instance. Three things to note:
<ul>
<li>This code rejects input that does not meet data requirements: 
  <ul><li>In this case, the data must be a string, ie. no arrays.</li>
  </ul></li>
<li>This code is Not using the $where operator, which allows JavaScript to be passed to the server.
  <ul><li>Serverside Javascript should be disabled in MongoDB if not needed.</li>
  </ul></li>
<li>This code logs when unexpected data types are passed in.</li>
<li>This code DOES NOT print unnecessary errors to the browser. 
  <ul>
    <li>"Login Failed" and "Please enter both username and password" are sufficient errors for users.</li>
  </ul></li>
</ul>
<pre class="good-code">
  // validate input data: name &amp; passwd should be strings
  if (isset($_REQUEST["fields"]["name"])) {

    if (!is_string($_REQUEST["fields"]["name"])) {

      // do not print error to the screen, only to the logs
      error_log("ALERT name is being attacked.");
      $msg = "Login Failed";

    } else {
      $name = $_REQUEST["fields"]["name"];
    }
  }

  if (isset($_REQUEST["fields"]["passwd"])) {

    if (!is_string($_REQUEST["fields"]["passwd"])) {

      // do not print error to the screen, only to the logs
      error_log("ALERT passwd is being attacked.");
      $msg = "Login Failed";

    } else {
      $passwd = $_REQUEST["fields"]["passwd"]
    }
  }

  ... code omitted ...


  // build query
  $query=new MongoDB\Driver\Query(array(
    "name" => strval($name),
    "passwd" => strval($passwd)
  ));

  ... code omitted ...
</pre>

';

}
