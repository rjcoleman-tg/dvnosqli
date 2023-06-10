# What each script does

## load-neo4j.sh

Executes [../data-load/neo4j/load.sh](https://github.com/RJColeman/dvnosqli/blob/main/data-load/neo4j/load.sh) inside the dvnosqli neo4j container

## open-neo4j-container.sh

1. gets the id of the dvnosqli neo4j container
2. docker execs into the dvnosqli neo4j container

## start-over-clean.sh

1. gets the dvnosqli images for later removal
2. gets the dvnosqli volumes for later removal
3. stops and removes all dvnosqli containers
5. removes all dvnosqli images
6. removes all dvnosqli volumes
7. removes all dvnosqli networks 
8. removes all cached dvnosqli neo4j data
9. removes all unused builder cache objects
