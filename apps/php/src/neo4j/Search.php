<?php

use Laudis\Neo4j\Authentication\Authenticate;
use Laudis\Neo4j\ClientBuilder;
use Laudis\Neo4j\Databags\Statement;

class SearchBuilder {

  static function create(): SearchFactory {
    return new SearchFactory();
  }

}

class SearchFactory {

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

class Search {

  protected $neo4j;
  protected $include;
  protected $results;
  protected $level;

  function __construct($level) {
    $this->neo4j = ClientBuilder::create()
        ->withDriver('bolt', 'bolt://neo4j:protect-toga-hair-oberon-coral-2052@dvnosqli-neo4j-1:7687') // creates a bolt driver
        ->build();
    $this->level = $level;
  }

  function testQuery(string $data): Laudis\Neo4j\Databags\SummarizedResult {
    return $this->neo4j->run('MATCH (person)-[role]->(movie) WHERE person.name = "' . $data . '" RETURN person,role,movie');
  }

  function setInclude(?array $include = array()): bool {
    $this->include = $include;
    return true;
  }

  function printResults(): string {
 
    $output = "";
    if (isset($this->results) && !empty($this->results)) {
      foreach ($this->results as $result) { 
        $output .= "<tr>
          <td>" . $result->get('person')->getProperty('name') . "</td>
          <td>" . str_replace('_', ' ', strtolower($result->get('role')->getType())) . "</td>
          <td>";
          try { 
            $output .= $result->get('movie')->getProperty('title');
          } catch (Exception $e) { 
            $output .= "";
          } 
        $output .= "</td></tr>";
      }
    }
    if (strlen($output) > 0) {
      $output = "<table>" . $output . "</table>";
    }

    return $output;
  }

}

class Easy extends Search {

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
      $query = 'MATCH (person:Person {name: $name})-[role]->(movie:Movie) WHERE TYPE(role) = $role RETURN person,role,movie';
      $this->results = $this->neo4j->run($query, ['name' => $name, 'role' => $role]);
    } catch (Exception $e) {
      error_log("caught exception: ". $e->getMessage());
      throw new Exception("Invalid search attempt");
    }
  }
}
