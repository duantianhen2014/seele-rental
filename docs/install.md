# Install

## Require

- PHP >= 7.0.0
- OpenSSL PHP Extension
- PDO PHP Extension
- Mbstring PHP Extension
- Tokenizer PHP Extension
- XML PHP Extension
- MySQL 5.6 or later
- Seele Client
- Seele RPC Service

## Steps

- download code:

```
git cloen https://github.com/Qsnh/seele-rental.git
```

- install dependencies:

```
cd seele-rental
composer install
```

- config

```
cp .env.example .env
php artisan key:generate
```

please edit `.env` to change database config:

```$xslt
DB_CONNECTION=mysql
DB_HOST=127.0.0.1
DB_PORT=3306
DB_DATABASE=homestead
DB_USERNAME=homestead
DB_PASSWORD=secret
```

- install database

> you has already input correct database config.

```
php artisan migrate
```

- running

```
php artisan serve
```

Now,open browser and input `http://127.0.0.1`