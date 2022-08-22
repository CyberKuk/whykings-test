# Simple test project for WhyKings

## Installation
### Clone project
```
git clone https://github.com/CyberKuk/whykings-test.git && \
cd whykings-test/
```
### Install composer dependicies
```
docker run --rm \
    -u "$(id -u):$(id -g)" \
    -v $(pwd):/var/www/html \
    -w /var/www/html \
    laravelsail/php81-composer:latest \
    composer install --ignore-platform-reqs
```
### Start container and prepare DB
````
cp .env.example .env && \
./vendor/bin/sail up -d && \
./vendor/bin/sail artisan key:generate && \
./vendor/bin/sail php artisan migrate --force && \
````

## Usage
### Create person
```
curl -d "name=John&birthdate=1990-01-01&timezone=America/New_York"  -X POST http://localhost/person
```
### List Persons
```
curl -X GET http://localhost/person
```

## Run unit tests
```
./vendor/bin/sail test
```
