[![Build Status](https://travis-ci.org/julienvolle/SensioLabs-Symfony-DevOps.svg?branch=master)](https://travis-ci.org/julienvolle/SensioLabs-Symfony-DevOps)
[![Coverage](https://codecov.io/gh/julienvolle/SensioLabs-Symfony-DevOps/branch/master/graph/badge.svg)](https://codecov.io/gh/julienvolle/SensioLabs-Symfony-DevOps)

# Open Agriculture Initiative

The MIT Media Lab Open Agriculture Initiative builds open resources to enable a global community to accelerate digital agricultural innovation.  

> See: [https://www.media.mit.edu/open-agriculture-openag/](https://www.media.mit.edu/groups/open-agriculture-openag/overview/)

## Install

- Require [Docker](https://docs.docker.com/) & [Docker Compose](https://docs.docker.com/compose/)
- Just run `make docker_start` to start the project
- The first time, wait for the database server to be installed and run `make db DOCKER=1`
- To see all available commands, run `make` or checked the `Makefile`

> **Account:**  
> `user: johndoe`  
> `pass: johndoe`  
> `mail: johndoe@gmail.com`  

## Domain

> [http://localhost:8080/](http://localhost:8080/) = Front Office  
> [http://localhost:8181/](http://localhost:8181/) = Database Managment System  
