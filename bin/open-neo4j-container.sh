cid=$(docker ps --filter "name=dvnosqli-neo4j*" -aq)
docker exec -it $cid /bin/bash
