!/bin/sh

echo "This script will remove all containers, images, volumes, and networks related to dvnosqli and will remove all unused build objects that have ever been cached by your docker engine."
echo ""
echo "Would you like to continue (y|n)?"

read cont

if [[ $cont != 'y' && $cont != 'Y' ]]
then
  echo ""
  echo "Quitting without removing anything.... "
  echo ""
  exit 1
fi

# 1. gets dvnosqli images for later removal
remove_images=$(docker inspect --format='{{.Image}}' $(docker ps --filter "name=dvnosqli.*" -aq)) 2> /dev/null
                                                           
# 2. gets dvnosqli volumes for later removal
volumes=$(docker volume ls  --format '{{.Name}}') 2> /dev/null 
remove_volumnes=""

for volume in $volumes
do
  container=$(docker ps -a --filter volume="$volume"  --format '{{.Names}}')
  if [[ $container == dvnosqli* ]]
  then
    remove_volumes="$remove_volumes $volume"
  fi
done

# 3. stops and removes dvnosqli containers
echo ""
echo "stopping all containers for dvnosqli......"
echo ""
docker-compose down

# 5. removes all dvnosqli images
echo ""
echo "removing all images for dvnosqli......"
echo ""
for rm_image in $remove_images
do
  echo "removing $rm_image"
  docker rmi --force $rm_image
done

# 6. removes all dvnosqli volumes
echo ""
echo "removing all volumes for dvnosqli......"
echo ""
for rm_volume in $remove_volumes
do
  echo "removing $rm_volume"
  docker volume rm $rm_volume
done


# 7. removes all dvnosqli networks 
echo ""
echo "removing all networks for dvnosqli......"
echo ""
docker network rm $(docker network ls --filter "name=dvnosqli.*" -q) 2> /dev/null 

# 8. removes all dvnosqli neo4j data
echo ""
echo "removing cached neo4j data......"
echo ""
rm -rf .data-neo4j/*

# 9. removes all unused builder cache objects
echo ""
echo "removing all unused builder cached objects....."
echo ""
docker builder prune -f

echo ""
echo ""
echo "To restart dvnosqli run"
echo ""
echo "    docker compose build"
echo "    docker compose --compatibility up -d"
echo "    sh bin/load-neo4j.sh" 
echo ""
echo ""
