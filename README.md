## About this repo

This is a simple implementation of the New York Times Books API.

### How to run the code
- Clone this repository
- Install composer packages with `composer install`
- Copy env file `cp .env.example .env`
- Generate encryption key `php artisan key:generate`
- Serve the site with `php artisan serve`

### Reach the API
- On http://127.0.0.1:8000/api/1/nyt/best-sellers

### Run the tests
`php artisan test`
