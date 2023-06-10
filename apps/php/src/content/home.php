<p>Welcome to the Damn Vulnerable NoSQL Injection application! As the name implies, this app is vulnerable to NoSQL Injection, and possibly other attacks. The databases included in this application are Neo4j, MongoDB, and Redis. This application is written in PHP and is very rudimentary, nothing fancy to see here.</p>

<p>Each time you accomplish injection the page will include the following banner:</p>

<?php require_once($_BASE_PATH . 'content/banner.html'); ?>

<p>Each section of this application has easy, medium, hard, and impossible levels. Details below will explain how to navigate them:
<ul>
<li>To change levels, click the desired level in the footer of the appliction.</li> 
<li>Impossible levels should have no vulnerabilities and will describe mitigations</li>
<li>When you have acheived NoSQL Injection, you will see the text "FLAG" in the response. The FLAGs will indicate their difficulty levels as below. Some FLAGs will have multiple difficulty levels as they are attainable at multiple levels using different attacks:
  <ul>
    <li>FLAG!!!EASY^MEDIUM^HARD
    <li>FLAG!!!BONUS^EASY^MEDIUM^HARD
  </ul></li>
<li>The BONUS flags are typically extra hard and may require an out of band / side channel resource like Burp Collaborator.</li>
<li>In some cases the injection vulnerability is not related to the query language used by the particular technology.</li>
</ul>
