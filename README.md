[![Build Status](https://travis-ci.org/julienvolle/SensioLabs-OpenAG.svg?branch=master)](https://travis-ci.org/julienvolle/SensioLabs-OpenAG)
[![Coverage](https://codecov.io/gh/julienvolle/SensioLabs-OpenAG/branch/master/graph/badge.svg)](https://codecov.io/gh/julienvolle/SensioLabs-OpenAG)

# Open Agriculture Initiative

The MIT Media Lab Open Agriculture Initiative builds open resources to enable a global community to accelerate digital agricultural innovation.  

> See: [https://www.media.mit.edu/open-agriculture-openag/](https://www.media.mit.edu/groups/open-agriculture-openag/overview/)

## Install

Use [Docker](https://docs.docker.com/) & [Docker Compose](https://docs.docker.com/compose/) to start the project:
- Start server: `docker-compose up -d`  
- Create database: `docker exec -ti oai_php php bin/console d:d:c --if-not-exists`  
- Build schema database: `docker exec -ti oai_php php bin/console d:s:u --force`  
- Load fixures: `docker exec -ti oai_php php bin/console d:f:l --append`  
- Stop server: `docker-compose down`  

Local URL:
> [http://localhost:8080/](http://localhost:8080/) = Front Office  
> [http://localhost:8181/](http://localhost:8181/) = Database Managment System  

Test Account:
> `user: johndoe`  
> `pass: johndoe`  
> `mail: johndoe@gmail.com`  