# What Running Where

[![Coverage Status](https://coveralls.io/repos/github/neilmillard/whatrunningwhere/badge.svg?branch=main)](https://coveralls.io/github/neilmillard/whatrunningwhere?branch=main)

## Install the Application

* Point your virtual host document root to your new application's `public/` directory.
* Ensure `logs/` is web writable.

Further setup steps for database
```bash
cd whatrunningwhere
composer install
touch db.sqlite
vendor/bin/phinx migrate -e development
```
To run the application in development, you can run these commands

```bash
cd whatrunningwhere
composer start
```

Or you can use `docker-compose` to run the app with `docker`, so you can run these commands:

```bash
cd whatrunningwhere
docker-compose up -d
```

After that, open `http://localhost:8080` in your browser.

Run this command in the application directory to run the test suite

```bash
composer test
```
