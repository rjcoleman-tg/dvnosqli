<?php
/*
   //check whether server is running or not 
   echo "Server is running: ".$redis->ping(); 
   $redis->set($key, "Redis tutorial");
   $redis->set('1abcd', "Redis tutorial");
   $redis->set('1efgh', "Redis tutorial");
   $redis->set('1ijkl', "Redis tutorial");
   echo "getting tutorial-name " . $redis->get("tutorial-name");
   echo "getting keys " . print_r($redis->keys('1*'));
   $keys = $redis->keys('1*');
   echo 'getting all values' . $redis->get($keys[0]);
*/
class CacheBuilder {

  static function create(): CacheFactory {
    return new CacheFactory();
  }

}

class CacheFactory {

  function withLevel(int $level): Object {
     switch ($level) {
      case 1:
        return new Medium(1);
      case 2:
        return new Hard(2);
      case 3:
        return new Impossible(3);
      default:
        return new Easy(0);
    }
  }
}

class Cache {

  protected $redis;
  protected $value;
  protected $key;
  protected $level;

  function __construct($level) {
    //Connecting to Redis server on localhost 
    $this->redis = new Redis(); 
    $this->redis->connect('dvnosqli-redis-1', 6379); 
    // setting difficulty level
    $this->level = $level;
  }

  function printAllKeyValues() {
    $keys = $this->redis->keys('*');
    echo "<p>";
    foreach ($keys as $k) {
      echo $k . ': ' . $this->redis->get($k) . '<br>';
    }
    echo "</p>";
  }

  function testSet($key, $value): bool {
    echo "Setting test entry with " . print_r($key) . " => $value<br/>";
    $this->key = $key;
    $this->value = $value;
    return $this->redis->set($key, $value);
  }

  function testGet($key): mixed {
    echo "Getting test entry with $key<br/>";
    $this->value = $this->redis->get($key);
    return $this->value;
  }

  function testGetKeys(string $keyName = null): array {
    return $this->redis->keys($keyName);
  }

  function getSession(string $key): mixed {
    $this->key = $key;
    $this->value = $this->redis->get($key);
    return $this->value;
  }

  function printResults(): string {
    return print_r($this->value, 1);
 
  }

}

class Easy extends Cache {

  /**
   * @return list of all names with disabled set to true if not permitted
   */
  function getNames(): array {
    $results = $this->neo4j->run('MATCH (person:Person) RETURN person');
    $names = [];
    foreach ($results as $result) {
      $names[$result->get('person')->getProperty('name')]['disabled'] = 
        (!empty($this->include) &&
         !in_array($result->get('person')->getProperty('name'), $this->include)) ? " disabled": "";
    }
    return $names; 
  }

  // EASY: can get all records for all people and their relationships to all things with the right injection
  // bonus points if you send the data to an external server
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
}

class Medium extends Easy {
 
  /**
   * @return list of all names with disabled set to true if not permitted
   */
  function getNames(): array {
    return parent::getNames(); 
  }

  // medium can get all records for all people and their relationships to all things with the right injection
  // brute force name as 'bruteforced-name"})-[role]->(movie) return p, r, m //' 
  // use list from select drop down disabled or use list pulled from internet 
  // bonus points if you send the data to an external server
  // https://github.com/janester/mad_libs/blob/master/List%20of%20Male%20Celebrities.txt
  function getData(string $name, ?string $role = null): void {
    if (!$role) {
      echo "Role cannot be null";
      throw new Exception("Role cannot be null");
    }
    try {
      $query = 'MATCH (person:Person {name: "' . $name . '"})-[role:' . $role . ']->(movie) RETURN person,role,movie';
      $this->results = $this->neo4j->run($query );
    } catch (Exception $e) {
      echo ("There was an error with your query " . $e->getMessage());
      echo ("<br>");
      echo ($query);
      throw $e;
    }
  }

}

class Hard extends Medium {
  /**
   * @return only list of names permitted
   */
  function getNames(): array {
    $results = $this->neo4j->run('MATCH (person:Person) RETURN person');
    $names = [];
    foreach ($results as $result) {
      if (!in_array($result->get('person')->getProperty('name'), $this->include)) continue;
      $names[$result->get('person')->getProperty('name')]['disabled'] = false; 
    }
    return $names; 
  }

  // HARD: there is no injection here but user can brute force name and role
  // there is no check to make sure name is in the include list and 
  // there is no allow list to even check role against at all
  function getData(string $name, ?string $role = null): void {
    if (!$role) {
      throw new Exception("Role cannot be null");
    }
    try {
      $query = 'MATCH (person:Person {name: $name})-[role]->(movie) WHERE TYPE(role) = "'.$role.'" RETURN person,role,movie';
      $this->results = $this->neo4j->run($query, ['name' => $name, 'role' => $role]);
    } catch (Exception $e) {
      echo ("There was an error with your query " . $e->getMessage());
      throw $e;
    }
  }
}

class Impossible extends Hard {
  function getNames(): array {
    return parent::getNames(); 
  }
  
  // IMPOSSIBLE: there is no injection here and all user input is actually validated
  function getData(string $name, ?string $role = null): void {
    if (!$role) {
      throw new Exception("Role cannot be null");
    }
    $allowed_roles = ['ACTED_IN', 'DIRECTED', 'PRODUCED', 'WROTE'];
    if (!in_array($name, $this->include) || !in_array($role, $allowed_roles)) {
      throw new Exception("Invalid search attempt bad data");
    }
    try {
      $query = 'MATCH (person:Person {name: $name})-[role]->(movie:Movie) WHERE TYPE(role) = "' . $role . '" RETURN person,role,movie';
      $this->results = $this->neo4j->run($query, ['name' => $name, 'role' => $role]);
    } catch (Exception $e) {
      error_log("caught exception: ". $e->getMessage());
      throw new Exception("Invalid search attempt");
    }
  }
}
