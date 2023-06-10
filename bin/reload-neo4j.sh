echo "Connecting to Neo4j container .... "
cid=$(docker ps --filter "name=dvnosqli-neo4j*" -aq)
echo "Executing Neo4j data reload .... "
docker exec --privileged -i $cid sh /data-load/reload.sh
echo "Completeed Neo4j data reload .... "
