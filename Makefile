# On définit nos "label" de tâches (sinon, se sont des noms de dossiers/fichiers)
.PHONY: phpcs phpmd phpunit phpmetrics tests

# Tâche : "composer.lock"
# Dépendances : si le fichier "composer.json" existe et est plus vieux, sinon...
composer.lock: composer.json
	composer update --lock --no-scripts --no-progress --no-interaction

# Tâche : "vendor"
# Dépendances : si le fichier "composer.lock" existe et est plus vieux, sinon, execute la tâche du même nom
vendor: composer.lock
	composer install --no-scripts --no-progress --no-interaction

# Tâche : "phpcs"
# Dépendances : si le dossier "vendor" existe et est plus vieux, sinon, execute la tâche du même nom
phpcs: vendor
	php vendor/bin/phpcs --standard=PSR1 src
	php vendor/bin/phpcs --standard=PSR2 src
	php vendor/bin/phpcs --standard=PSR12 src --ignore=/src/Kernel.php

phpcsfixer: vendor phpcs
	vendor/bin/php-cs-fixer fix --using-cache=no --verbose --diff

# Tâche : "phpmd"
# Dépendances : si le dossier "vendor" existe et est plus vieux, sinon, execute la tâche du même nom
phpmd: vendor
	php vendor/bin/phpmd src html ./phpmd.xml.dist > ./var/log/phpmd.html

# Tâche : "phpunit"
# Dépendances : si le dossier "vendor" existe et est plus vieux, sinon, execute la tâche du même nom
phpunit: vendor
	php bin/phpunit --coverage-html=./var/log/phpunit/coverage

panther: vendor
	PANTHER_NO_HEADLESS=1 ./bin/phpunit

# Tâche : "phpmetrics"
# Dépendances : si le dossier "vendor" existe et est plus vieux, sinon, execute la tâche du même nom
phpmetrics: vendor
	php vendor/bin/phpmetrics --report-html=./var/log/phpmetrix/ ./src/

# Tâche : "tests"
# Dépendances : tâche "phpcs", tâche "phpcs", tâche "phpunit", puis tâche "phpmetrics"
tests: phpcs phpmd phpunit phpmetrics

# Tâche : "uml"
uml: plantuml.jar
	java -jar plantuml.jar plantuml.txt