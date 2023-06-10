<?php
require_once $_BASE_PATH . 'neo4j/Search.php';
if (isset($_GET['test'])) {
  try {

    $search = SearchBuilder::create()
           ->withLevel($_COOKIE['level']);
    $results = $search->testQuery('Tom Hanks" OR p.name =~ ".*');

    echo '<p class="notice">Test was a ssucess</p>';

  } catch (Exception $e) {
    echo ("caught exception: ". $e->getMessage());
  }
} 
try {

  $search = SearchBuilder::create()
          ->withLevel($_COOKIE['level']);

  // to allow access to all people in db, remove the ->setInclude() line below
  $search->setInclude(['Tom Hanks']);

  $names = $search->getNames();

  $data = [];
  if (isset($_POST['search'])) {
    if ($_COOKIE['level'] > 0) {
      $results = $search->getData($_POST['person'], $_POST['role']);
    } else {
      $results = $search->getData($_POST['person']);
    }
  }

} catch (Exception $e) {
  error_log("caught exception: ". $e->getMessage());
}
?>
<p class="err">Until further notice, we are only permitted to display data related to Tom Hanks.</p>
<form method="POST">
  Show all movie data for 
  <select name="person">
<?php
  foreach ($names as $name => $info) {
?>
  <option value="<?= $name ?>"<?= $info['disabled'] ?>><?= $name ?></option>
<?php 
  }
?>
  </select> 
<?php if ($_COOKIE['level'] > 0) { ?>
  as
  <select name="role">
    <option value="ACTED_IN"<?= (isset($_POST['role']) && $_POST['role'] == 'ACTED_IN') ? ' selected': '' ?>>actor</option>
    <option value="DIRECTED"<?= (isset($_POST['role']) && $_POST['role'] == 'DIRECTED') ? ' selected': '' ?>>director</option>
    <option value="PRODUCED"<?= (isset($_POST['role']) && $_POST['role'] == 'PRODUCED') ? ' selected': '' ?>>producer</option>
    <option value="WROTE"<?= (isset($_POST['role']) && $_POST['role'] == 'WROTE') ? ' selected': '' ?>>writer</option>
  </select>
<?php } ?>
  <input type="submit" value="go" name="search" />
</form>
<?php
$output = $search->printResults();
if (strstr($output, 'FLAG')) {
  require_once($_BASE_PATH . 'content/banner.html');
}
echo $output;

if (isset($_POST['search'])) {

echo "<br />
<br />";
  if ($_COOKIE['level'] == 0 && strstr($output, 'FLAG')) {
?>

<details>
  <summary>====== Problematic Code Below ======</summary>
<pre class="bad-code">
  function getData(string $name, ?string $role = null): void {
    try {
      $query = 'MATCH (person:Person)-[role]->(movie) WHERE person.name = "' . $name . '"';
      if ($role) {
        $query .= ' AND TYPE(role) = "' . $role . '"'; 
      }
      $query .= ' RETURN person,role,movie';
      $this->results = $this->neo4j->run($query );
    } catch (Exception $e) {
      echo ("There was an error with your query " . $e->getMessage());
      echo ("<br>");
      echo ($query);
      throw $e;
    }
  }
</pre>

</details>

<?php
  } else if ($_COOKIE['level'] == 1 && strstr($output, 'FLAG')) {
?>
<details>
  <summary>====== Problematic Code Below ======</summary>
<?php
  } else if ($_COOKIE['level'] == 2 && strstr($output, 'FLAG')) {
?>
<details>
  <summary>====== Problematic Code Below ======</summary>
<?php
  } else if ($_COOKIE['level'] > 2) {
?>
<details>
  <summary>====== Mitigation Information Below ======</summary>
Below is the code mitigating NoSQLi vulnerabilities for this Neo4j instance. Four things to note:
<ul>
<li>This code validates input:
  <ul>
    <li>The code checks that data exists when it is required.</li>
    <li>The code checks include and allow lists before putting data in query.</li>
    <li>The code rejects input that does not meet data requirements.</li>
  </ul>
</li>
<li>This code logs when unexpected data types are passed in.</li>
<li>This code DOES NOT print unnecessary errors to the browser: 
  <ul>
    <li>"No results found" gives no information about the database in use.</li>
    <li>"No results found" gives no information about any database errors.</li>
  </ul></li>
<li>This code uses parameterized queries:
  <ul>
    <li>Even if injection gets past validation and sanistization, it will not make it into the query.</li>
  </ul></li>
</ul>
<pre class="good-code">
// validate role exists
if (!$role) {
  error_log("ALERT: Unexpected data sent to Search->getData, missing role");
  throw new Exception("Role cannot be null");
}

// validate name in search is in the include list and 
// role is in allow list for roles
$allowed_roles = ['ACTED_IN', 'DIRECTED', 'PRODUCED', 'WROTE'];

if ((count($this->include) > 0 && !in_array($name, $this->include)) || 
    !in_array($role, $allowed_roles)) {
  error_log("ALERT: Unexpected data sent to Search->getData");
  throw new Exception("No results found");
}

// if something goes wrong with the query, 
// log the error message from neo4j 
// and send generic error to the client
try {
  $query = 'MATCH (person:Person {name: $name})-[role]->(movie:Movie) ' . 
           'WHERE TYPE(role) = "$role" RETURN person,role,movie';
  $this->results = $this->neo4j->run($query, ['name' => $name, 'role' => $role]);
} catch (Exception $e) {
  error_log("ALERT: Search->getData() caught exception: ". $e->getMessage());
  throw new Exception("No results found");
}
</pre>

</details>

<?php
  }
}
?>
