<?php
try {

  // to allow access to all people in db, remove the ->setInclude() line below
  $search = SearchBuilder::create()
          ->withLevel($_COOKIE['level']);

  $results = $search->testQuery('match( p:Person {name:"Tom Hanks"}) return p; match(n)-[r]-(m) return n,r,m // )-[r:ACTED_IN]-(m:Movie) return p, r, m');
  foreach ($results as $result) {
    echo $result->get('p')->getProperty('name');
  }

} catch (Exception $e) {
  error_log("caught exception: ". $e->getMessage());
}
?>
