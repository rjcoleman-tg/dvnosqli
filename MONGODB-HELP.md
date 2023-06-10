## MongoDB 

In part, this application tests your knowledge of MongoDB and [its operators](https://www.mongodb.com/docs/manual/reference/operator/query/). Below you will find hints and steps for full walkthroughs for each level. There are no BONUS flags in the MongoDB section as of May 2022. The Walkthrough steps use BurpSuite Pro when an intercept proxy is needed,, but for Easy and Medium BurpSuite Community and Zap can be used as well. For the Hard level, you will want eitehr BurpSuite Pro or ZAP as BurpSuite Community has limitations that will make it hard to use on level Hard.

### EASY

<details>
  <summary>Hints</summary>

  1. Can you carft a request causing application to display an error that includes the query being sent to MongoDB? 
  2. Can you modify the request to use [an operator](https://www.mongodb.com/docs/manual/reference/operator/query/), causing MongoDB to respond in the way an SQL database might respond to ```x' or 'x'='x```?
</details>

<details>
  <summary>Walkthrough</summary>

  1. Browse to the [MongoDB page](http://localhost:8084/app/?db=MongoDB) of the DVNoSQLi app.
  2. Enter any username and password combo you like and hit submit.
  3. Observe the URL in your browser's location bar and see that the form data is in the querystring.
  4. Change the username to ```'"``` and hit submit.
  5. Observe no error is rendered to the screen.
  6. Now, in the location bar, edit the querystring by adding ```[$xx]``` between ```[name]``` and ```=``` and hit enter. The URL should look something like: ```http://localhost:8084/?fields[name][$xx]='"&fields[passwd]=asdf&db=mongodb&submit=submit```
  7. Observe the error rendered to the screen, and, if you are not familiar with [MongoDB operators](https://www.mongodb.com/docs/manual/reference/operator/query/), study them.
  8. Now modify the URL again, this time using valid operators with both ```[name]``` and ```[passwd]``` fields.
  <blockquote>
  <details>
    <summary>Exact URL to get flags</summary>

    http://localhost:8084/?fields[name][$gt]=0&fields[passwd][$gt]=0&db=mongodb&submit=submit

  </details>

  </blockquote>

</details>

### MEDIUM 

<details>
  <summary>Hints</summary>

  1. Can you carft a request causing application to display an error that includes the query being sent to MongoDB? 
  2. Can you figure out how to inject the right javascript to get all users?
</details>

<details>
  <summary>Walkthrough</summary>

  1. Browse to the [MongoDB page](http://localhost:8084/app/?db=mongodb) of the DVNoSQLi app.
  2. Enter ```'"``` into the username field and enter anything into the password field, then click submit.
  3. Observe the error rendered to the screen.
  4. Craft the payload you think will work, entering your payload into the username or password field, then click submit.
  <blockquote>
  <details>
    <summary>Exact payload to get flags</summary>

    '; return true; //

  </details>

  </blockquote>

  </details>

</details>

### HARD

This section is marked HARD because it requires a bit of knowledge about using an intercept proxy at a bit more than beginner level. The NoSQLi part is not new.

<details>
  <summary>Hints</summary>

  1. You will use a form of NoSQL Injection encountered in Easy or Medium level. 
  2. You should examine requests submitted carefully and attempt to discover new data using Burp Intruder or ZAP's "attack" -> "fuzz" functionality.
</details>

<details>
  <summary>Walkthrough</summary>

  1. Turn **Intercept** on in BurpSuite's **Proxy**.
  2. Back in your browser, browse to the [MongoDB page](http://localhost:8084/app/?db=MongoDB) of the DVNoSQLi app.
  3. Enter any username and password combo you like and hit submit.
  4. Back in Burp, examine the POST body of the request.
  5. What information might you attack in the POST request body other than the values of name and passwd?
  <blockquote>
    <details>
      <summary>Click for list of attack parameters</summary>

      name
      passwd
  
  </blockquote>

  6. Go ahead and forward the request on.
  7. In Burp, go into the **HTTP History** tab of the **Proxy** app and find the request you just observed.
  8. Right click the request and send it to **Intruder**.
  9. In **Intruder**, clear the default selections and select ```name``` and ```passwd``` in the POST body of the request as the data you want to attack.
  10. Additionally change the ```=whateverusernameyouused``` to ```[$gt]=0``` and do the same for the ```=whateverpasswordyouused```.
  11. At the top, in the **Choose an attack type** section, select "Cluster bomb".
  12. Switch to the **Payloads** tab of **Intruder**, 
  13. select **Runtime File** as your **Payload Type** for both Payload sets 1 and 2.
  14. In the **Payload Option** section, select the common-columns.txt file in the **payloads** directory of this repository for both payload 1 and payload 2.
  15. In the **Options** tab, scroll down to the **Grep Match** section
  16. Check the "Flag result items with responses matching these expressions." box
  17. Clear the section by pressing "Clear"
  18. Add "FLAG" or "HARD" to the **Grep - Match** section.
  19. Scroll back to the top and click "Start Attack".
  20. In the attack window, sort on FLAG.
  21. Wait for it.
</details>
