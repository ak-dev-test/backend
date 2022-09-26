# test api
### 1. run docker containers
`docker-compose up -d`
### 2. Go into php-fpm container with
`docker-compose exec php-fpm bash`
###### and
###### 1. install all packages
`composer install`
###### 2. execute migrations