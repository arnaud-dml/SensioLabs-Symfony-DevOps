SYMFONY 	= ./bin/console
PHPUNIT 	= ./bin/phpunit
VENDOR 		= ./vendor/bin
LOG 		= ./var/log

## 
## Bases
## -----
## 

composer.lock: composer.json
	composer update --lock --no-scripts --no-progress --no-interaction

vendor: composer.lock
	composer install --no-scripts --no-progress --no-interaction

install: ## composer install/update
install: vendor
.PHONY: install

server: ## run server
server: install
	$(SYMFONY) server:run
.PHONY: server

## 
## Cache
## -----
## 

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

phpcs: ## run PHP Code Sniffer (PSR1, PSR2, PSR12)
phpcs: install
	mkdir -p $(LOG)/phpcs/
	$(VENDOR)/phpcs --standard=PSR1  src --ignore=./src/Kernel.php --report-full=$(LOG)/phpcs/PSR1.txt
	$(VENDOR)/phpcs --standard=PSR2  src --ignore=./src/Kernel.php --report-full=$(LOG)/phpcs/PSR2.txt
	$(VENDOR)/phpcs --standard=PSR12 src --ignore=./src/Kernel.php --report-full=$(LOG)/phpcs/PSR12.txt
.PHONY: phpcs

phpcsfixer: ## run PHP Code Sniffer Fixer
phpcsfixer: install phpcs
	$(VENDOR)/php-cs-fixer fix --using-cache=no --verbose --diff
.PHONY: phpcsfixer

phpmd: ## run PHP Mess Detector
phpmd: install
	$(VENDOR)/phpmd src html ./phpmd.xml.dist > $(LOG)/phpmd.html
.PHONY: phpmd

phpcpd: ## PHP Copy/Paste Detector
phpcpd: install
	mkdir -p $(LOG)/phpcpd/
	$(VENDOR)/phpcpd src > $(LOG)/phpcpd/report.txt
.PHONY: phpcpd

phpunit: ## run PHPUnit
phpunit: install
	$(PHPUNIT)
.PHONY: phpunit

panther: ## run PHPUnit Panther
panther: install
	PANTHER_NO_HEADLESS=1 $(PHPUNIT)
.PHONY: panther

coverage: ## run PHPUnit Coverage
coverage: install
	mkdir -p $(LOG)/phpunit/coverage/
	$(PHPUNIT) --coverage-html=$(LOG)/phpunit/coverage
.PHONY: coverage

phpmetrics: ## run PHP Metrix
phpmetrics: install
	mkdir -p $(LOG)/phpmetrix/
	$(VENDOR)/phpmetrics --report-html=$(LOG)/phpmetrix/ ./src/
.PHONY: phpmetrics

tests: ## run all tests
tests: twig yaml phpcs phpmd phpcpd coverage phpmetrics
.PHONY: tests

## 
## Tools
## -----
## 

uml: ## generate diagram of class with PlantUML (https://packagist.org/packages/jawira/plantuml)
uml: install
	vendor/bin/plantuml /docs/uml/diagram-class.puml
.PHONY: uml

.DEFAULT_GOAL := help
help:
	@grep -E '(^[a-zA-Z0-9_-]+:.*?##.*$$)|(^##)' $(MAKEFILE_LIST) | awk 'BEGIN {FS = ":.*?## "}; {printf "\033[32m%-30s\033[0m %s\n", $$1, $$2}' | sed -e 's/\[32m##/[33m/'
.PHONY: help
