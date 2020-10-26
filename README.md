### A sample application with Phalcon

This is a very simple REST API for a "phonebook". 
You can create, update and delete contacts. 
You cannot create a contact with a nonexistent timezone or country code.
Phone numbers must start with a "+" and have no more than 20 digits (e.g. +123456 is fine).

### Launching the app

```
$ docker-compose up -d
$ docker-compose exec php /bin/sh
# composer install
# vendor/bin/phalcon-migrations run
```

You should be able to access the app on http://localhost/api/contacts


### Running tests

Enter the container shell
```
$ docker-compose exec php /bin/sh
```

Run unit tests
```
# vendor/bin/phpunit --testdox
```

Run API tests
```
# vendor/bin/codeception
```
API tests do not depend on Phalcon, so you can run tests against any service, just change the url in codeception.yml.

#### Thanks

* [4xxi](https://github.com/4xxi/docker-nginx) - docker image with a preconfigured nginx
* [MilesChou](https://github.com/MilesChou/docker-phalcon) - docker image with phalcon preinstalled
* [Hostaway](http://api.hostaway.com) - free API for validation timezones/country codes
