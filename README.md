# php-employment

## Installation

Copy the `.env` file

```
cp .env.example .env
```

Build docker image

```
docker-compose build
```

Run docker image

```
docker-compose up
```

Run the following command to install the package through Composer:

```bash
docker-compose exec composer install
```

## Migration and seed database

Run the following command to migration and seend database:

```bash
docker-compose exec app bash
```

Inside app bash run:

```bash
php artisan migrate:fresh --seed
```

## Usage

Stop docker

```
docker-compose stop
```
