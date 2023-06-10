echo "Connecting to Neo4j container .... "
cid=$(docker ps --filter "name=.*neo4j*" -aq)
echo "Executing Neo4j data load in $cid.... "
docker exec --privileged -i $cid sh /data-load/load.sh
echo "Completeed Neo4j data load .... "
