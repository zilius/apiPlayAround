app_name = supermetrics

export MYSQL_PORT = 3310
export HTTP_PORT = 8080
export COMPOSE_PROJECT_NAME = supermetrics

init:
	mkdir -p tmp
	mkdir -p tmp/nginx
	touch tmp/nginx/access.log
	touch tmp/nginx/error.log
	make up
	make composer-install

up:
	docker-compose up

down:
	docker-compose down

composer-install:
	docker-compose -f docker-compose-helpers.yml run composer install

composer-require:
	#example: make composer-require package="composer require doctrine/migrations"
	docker-compose -f docker-compose-helpers.yml run composer require ${package}

	#example: make composer cmd="dump-autoload -o"
composer:
	docker-compose -f docker-compose-helpers.yml run composer ${cmd}
