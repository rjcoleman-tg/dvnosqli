Easy vulnerability is that an error can be forced that will reveal the query
The query can be manipulated in the where clause using cypher "like" functionality aka =~
Payload:

anyname" or p.name =~ "*

Finish building return array for Easy->getData()

On Easy, get full list of actors

Medium is the same as Easy, except the full list of names is not displayed and the user has to figure out that they can send empty data in "role"
On Medium, change to MATCH (p:Person {name: "' . $name . '"})-[r]-(m:Movie) - Vulnerability here is setting:

$name = 'anyone"})-[r]-(m) return r,m to list more than just movies

On Hard, require data to be sent to burp collaborator or attacker's server
