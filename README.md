# Why

I wanted to understand NoSQL Injection with Neo4j and MongoDB, so I built this vulnerable web application. It is still under construction, but for the most part, Neo4j and MongoDB work. 

The web interface renders the problematic code once injection has been accomplished in the easy, medium, and hard levels and renders mitigation code in the  impossible level.

# Requires docker compose v 2

Because this project was created using docker compose version 2 and because [docker compose v1 (written in python) and docker compose v2 (written in go) use _ or - respectively](https://stackoverflow.com/questions/69464001/docker-compose-container-name-use-dash-instead-of-underscore) when naming machines in docker networks, this project will not work if run using docker compose v 1.

To find out what version of docker compose your system is using, run:

```
docker compose version
```

# Getting up and running 

To run your first build, after cloning this repository, execute the following commands from the repository's root directory:

```
docker compose build
docker compose up -d
sh bin/load-neo4j.sh
```

At the very end you should see output as follows:

```
Connecting to Neo4j container ....
Executing Neo4j data load ....
Completed Neo4j data load ....
``` 

Next load the MongoDB data following instructions in [Just the data step 1](#just-the-data)

# Rebuilding 

## Just the data

1. Reset the MongoDB data by browsing to [http://localhost:8084/?db=mongodb](http://localhost:8084/?db=mongodb) and clicking the "reset/load" button.
2. Reset the neo4j data by executing `sh bin/reset-neo4j.sh`, from the root of this repository.

## The entire application

To rebuild the entire application from scratch you can either:

1. *Manually* remove all containers, images, volumes, networks, cached build objects, and cached data 

-or-

2. Use the `start-over-clean.sh` script in the bin directory of this repository by executing the following command from the root directory of this repository:

```
sh bin/start-over-clean.sh
```

# Solutions

For hints and walkthrough steps, see:

- [Neo4j Walkthrough hints and steps](https://github.com/RJColeman/dvnosqli/blob/main/NEO4J-HELP.md)
- [MongoDB Walkthrough hints and steps](https://github.com/RJColeman/dvnosqli/blob/main/MONGODB-HELP.md)


# Network Details

Once up and running, executing `docker inspect dvnosqli_network` should render output similar to the following:

```
[
    {
        "Name": "dvnosqli_network",
        "Id": "[network id]",
        "Created": "2022-06-12T17:58:14.189979722Z",
        "Scope": "local",
        "Driver": "bridge",
        "EnableIPv6": false,
        "IPAM": {
            "Driver": "default",
            "Options": null,
            "Config": [
                {
                    "Subnet": "172.20.0.0/16",
                    "Gateway": "172.20.0.1"
                }
            ]
        },
        "Internal": false,
        "Attachable": false,
        "Ingress": false,
        "ConfigFrom": {
            "Network": ""
        },
        "ConfigOnly": false,
        "Containers": {
            "[container id]": {
                "Name": "dvnosqli-neo4j-1",
                "EndpointID": "[endpoint id]",
                "MacAddress": "02:42:ac:14:00:05",
                "IPv4Address": "172.20.0.5/16",
                "IPv6Address": ""
            },
            "[container id]": {
                "Name": "dvnosqli-php-app-1",
                "EndpointID": "[endpoint id]",
                "MacAddress": "02:42:ac:14:00:04",
                "IPv4Address": "172.20.0.4/16",
                "IPv6Address": ""
            },
            "[container id]": {
                "Name": "dvnosqli-mongo-1",
                "EndpointID": "[endpoint id]",
                "MacAddress": "02:42:ac:14:00:02",
                "IPv4Address": "172.20.0.2/16",
                "IPv6Address": ""
            },
            "[container id]": {
                "Name": "dvnosqli-redis-1",
                "EndpointID": "[endpoint id]",
                "MacAddress": "02:42:ac:14:00:03",
                "IPv4Address": "172.20.0.3/16",
                "IPv6Address": ""
            },
            "[container id]": {
                "Name": "dvnosqli-mongo-frontend-1",
                "EndpointID": "[endpoint id]",
                "MacAddress": "02:42:ac:14:00:06",
                "IPv4Address": "172.20.0.6/16",
                "IPv6Address": ""
            }
        },
        "Options": {},
        "Labels": {
            "com.docker.compose.network": "network",
            "com.docker.compose.project": "dvnosqli",
            "com.docker.compose.version": "[your version]"
        }
    }
]

```


# Accessing the assets

## Web Frontend

To access the web front end browse to [http://localhost:8084/](http://localhost:8084/)

## Neo4j 

To confirm that the test data was loaded for neo4j:

1. Browse to [http://localhost:7474/browser/](http://localhost:7474/browser/)
2. Log in with username/password neo4j/protect-toga-hair-oberon-coral-2052
3. Execute the following query

 ```
 match (n) return n
 ```
4. You should see nodes and relationships

## Mongo

To access the mongodb shell:

```
$> docker exec -it [container id] bash
```

## Mongo Frontend 

This container depends on mongo being up and accessible. To enforce this in the docker-compose.yml file, the file must use 

* [depends_on: - mongo](https://docs.docker.com/compose/startup-order/). 
* [restart: unless-stopped](https://docs.docker.com/config/containers/start-containers-automatically/)

## References

* [setting environment variables in docker compose](https://docs.docker.com/compose/environment-variables/)
* [primer on docker networking](https://docs.docker.com/network/network-tutorial-standalone/)
* [mongodb shell commands](https://www.mongodb.com/docs/manual/reference/mongo-shell/)
