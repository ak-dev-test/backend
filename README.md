## test api

### 1. Run docker containers

`docker-compose up -d`

### 2. Execute SQL manually

Open database-tool (MySql Workbench, DBeaver etc.), connect to the database (use connections parameters from
docker-compose.yml) and execute sql one by one

### 3. Install all packages

Enter into php container with command `docker-compose exec php-fpm bash` and execute `composer install` inside

#### Done. Application ready for use