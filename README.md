# Jagaad task

A simple CLI application that grabs a list of [Musement](https://musement.com) cities
and fetches weather forecasts for all of them using [WeatherAPI](https://weatherapi.com).

## Description

No framework has been used.
Just a few crucial libraries.

[PHPStan](https://github.com/phpstan/phpstan)
and [EasyCodingStandards](https://github.com/symplify/easy-coding-standard) are used to ensure
standards of the project.

[PHPStan](https://github.com/phpstan/phpstan) is running on the highest level `8`.

## Structure

`Components` namespace contains reusable code that can be extracted
to external libraries.

E.g. `Musement` or `WeatherAPI` SDK.

`Domain` namespace contains business logic unique to this project.

`Infrastructure` namespace contains code that links `Domain` and `CLI`

## Getting Started

### Dependencies

* The only dependency that you need is [Docker](https://www.docker.com/products/docker-desktop).

### Installing

* First thing that you need to is register at [WeatherAPI](https://www.weatherapi.com/signup.aspx)
  and grab your personal API key.
* `cp .env.example .env`
* Add your `WEATHER_API_KEY` key to `.env`

### Executing program

```
docker-compose up
```
The command installs Composer dependencies and runs the program

### Running tests

```
docker-compose run php ./vendor/bin/phpunit
```

### Running static analysis

```
docker-compose run php ./vendor/bin/ecs
docker-compose run php ./vendor/bin/phpstan
```

### Step 2 - API Design

`docs/openapi.yaml` contains OpenAPI specification for the
second part of the test.

`City` has many `Forecasts`.

Forecast is treated as a `Model`.

Four endpoints have been introduced for flexibility purposes.

## Author

Arnas Kazlauskas
