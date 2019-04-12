ifdef DOCKER
TARGET_DOCKER_CONTAINER = docker exec -ti oai_php
endif
SYMFONY = $(TARGET_DOCKER_CONTAINER) php bin/console
PHPUNIT = $(TARGET_DOCKER_CONTAINER) php bin/phpunit
VENDOR 	= $(TARGET_DOCKER_CONTAINER) ./vendor/bin
LOG 	= ./var/log
CACHE 	= ./var/cache

## 
## Bases
## -----
## 

composer.lock: composer.json
	composer update --lock --no-scripts --no-progress --no-interaction

vendor: composer.lock
	composer install --no-scripts --no-progress --no-interaction

package-lock.json: package.json
	npm update

node_modules: package-lock.json
	npm install

install: ## install/update PHP & JS dependencies (Composer & NPM)
install: vendor node_modules
.PHONY: install

build: ## build assets
build: install
	npm run build
.PHONY: build

server: ## run server
server: install
	$(SYMFONY) server:run 127.0.0.1:8080 -d public
.PHONY: server

docker_start: ## start docker
docker_start:
	docker-compose up -d --build
	@echo "-------------------------"
	@echo "Let's go!"
	@echo "-> http://localhost:8080/ (Frontend)"
	@echo "-> http://localhost:8181/ (Database Managment System)"
	@echo "-> http://localhost:1080/ (Mailer Client)"
	@echo "-------------------------"
.PHONY: docker_start

docker_stop: ## stop docker
docker_stop:
	docker-compose down
	docker system prune -f
.PHONY: docker_stop

## 
## Cache
## -----
## 

rml: ## remove log folder
rml:
	rm -rf $(LOG)
	mkdir -p $(LOG)
.PHONY: rmc

rmc: ## remove cache folder
rmc:
	rm -rf $(CACHE)
	mkdir -p $(CACHE)
.PHONY: rmc

ccd: ## clear cache DEV
ccd: install
	$(SYMFONY) cache:clear --env=dev
.PHONY: ccd

ccp: ## clear cache PROD
ccp: install
	$(SYMFONY) cache:clear --env=prod
.PHONY: ccp

cct: ## clear cache TEST
cct: install
	$(SYMFONY) cache:clear --env=test
.PHONY: cct

cc: ## clear cache
cc: install
	$(SYMFONY) cache:clear
.PHONY: cc

rca: ## remove and clear all
rca: rmc ccd ccp cct
.PHONY: rca

## 
## Doctrine
## --------
## 

ddd: ## drop database
ddd:
	$(SYMFONY) doctrine:database:drop --force --if-exists
.PHONY: ddd

ddc: ## create database, if not exists
ddc:
	$(SYMFONY) doctrine:database:create --if-not-exists
.PHONY: ddc

dsc: ## create schema 
dsc:
	$(SYMFONY) doctrine:schema:create
.PHONY: dsc

dsv: ## validate schema 
dsv:
	$(SYMFONY) doctrine:schema:validate
.PHONY: dsv

dsup: ## update schema preview
dsup:
	$(SYMFONY) doctrine:schema:update --dump-sql
.PHONY: dsup

dsu: ## update schema 
dsu:
	$(SYMFONY) doctrine:schema:update --force
.PHONY: dsu

dmm: ## migrate migration's file
dmm:
	$(SYMFONY) doctrine:migrations:migrate --no-interaction
.PHONY: dmm

dfl: ## load fixtures
dfl:
	$(SYMFONY) doctrine:fixtures:load --append
.PHONY: dfl

db: ## Create database and load fixtures
db: ddd ddc dsu dfl
.PHONY: db

## 
## Tests
## -----
## 

twig: ## Lints TWIG files
twig: install
	$(SYMFONY) lint:twig templates
.PHONY: twig

yaml: ## Lints YAML files
yaml: install
	$(SYMFONY) lint:yaml config
.PHONY: yaml

phpcs: ## run PHP Code Sniffer (PSR1, PSR2)
phpcs: install
	mkdir -p $(LOG)/phpcs/
	$(VENDOR)/phpcs --standard=PSR1  src --ignore=./src/Kernel.php --report-full=$(LOG)/phpcs/PSR1.txt
	$(VENDOR)/phpcs --standard=PSR1  tests --report-full=$(LOG)/phpcs/PSR1.txt
	$(VENDOR)/phpcs --standard=PSR2  src --ignore=./src/Kernel.php --report-full=$(LOG)/phpcs/PSR2.txt
	$(VENDOR)/phpcs --standard=PSR2  tests --report-full=$(LOG)/phpcs/PSR2.txt
.PHONY: phpcs

fixer: ## run PHP Code Sniffer Fixer
fixer: install
	$(VENDOR)/php-cs-fixer fix --using-cache=no --verbose --diff
.PHONY: fixer

phpmd: ## run PHP Mess Detector
phpmd: install
	$(VENDOR)/phpmd src html ./phpmd.xml.dist > $(LOG)/phpmd.html
	$(VENDOR)/phpmd tests html ./phpmd.xml.dist > $(LOG)/phpmd.html
.PHONY: phpmd

phpcpd: ## PHP Copy/Paste Detector
phpcpd: install
	mkdir -p $(LOG)/phpcpd/
	$(VENDOR)/phpcpd src > $(LOG)/phpcpd/report.txt
	$(VENDOR)/phpcpd tests > $(LOG)/phpcpd/report.txt
.PHONY: phpcpd

phpunit: ## run PHPUnit
phpunit: install
	$(PHPUNIT) --exclude-group panther
.PHONY: phpunit

ifdef PNH
MODE = PANTHER_NO_HEADLESS=$(PNH)
endif
panther: ## run PHPUnit with Panther
panther: install
	$(MODE) $(PHPUNIT) --group panther
.PHONY: panther

coverage: ## run PHPUnit with Coverage
coverage: install
	mkdir -p $(LOG)/phpunit/coverage/
	$(PHPUNIT) --coverage-html=$(LOG)/phpunit/coverage --exclude-group panther
.PHONY: coverage

phpmetrics: ## run PHP Metrix
phpmetrics: install
	mkdir -p $(LOG)/phpmetrix/
	$(VENDOR)/phpmetrics --report-html=$(LOG)/phpmetrix/ ./src/
.PHONY: phpmetrics

tests: ## run all tests
tests: twig yaml phpcs phpmd phpcpd coverage phpmetrics
.PHONY: tests

travis: ## run all tests for Travis
travis: install
	$(SYMFONY) lint:twig templates
	$(SYMFONY) lint:yaml config
	$(VENDOR)/phpcs --standard=PSR1  src --ignore=./src/Kernel.php
	$(VENDOR)/phpcs --standard=PSR2  src --ignore=./src/Kernel.php
	$(VENDOR)/phpmd src text ./phpmd.xml.dist
	$(VENDOR)/phpcpd src
	$(VENDOR)/phpcs --standard=PSR1  tests
	$(VENDOR)/phpcs --standard=PSR2  tests
	$(VENDOR)/phpmd tests text ./phpmd.xml.dist
	$(VENDOR)/phpcpd tests
	$(PHPUNIT) --coverage-clover=coverage.xml --exclude-group panther
.PHONY: travis

## 
## Tools
## -----
## 

uml: ## generate diagram of class with PlantUML (https://packagist.org/packages/jawira/plantuml)
uml: install
	$(VENDOR)/plantuml ./docs/uml/diagram-class.puml
.PHONY: uml

.DEFAULT_GOAL := help
help:
	@grep -E '(^[a-zA-Z0-9_-]+:.*?##.*$$)|(^##)' $(MAKEFILE_LIST) | awk 'BEGIN {FS = ":.*?## "}; {printf "\033[32m%-30s\033[0m %s\n", $$1, $$2}' | sed -e 's/\[32m##/[33m/'
.PHONY: help
