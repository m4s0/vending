# Application

Application runs with PHP 7.4 and MySQL 8 at this url http://127.0.0.1

#### Docker

Copy `docker/.env.dist` to `docker/.env` and customise it with your parameters

Build container

```
make build
```

Run container

```
make up
```

Stop container

```
make down
```

Remove database container

```
make rm-db
```

Display container logs

```
make logs
```

Enter into php container

```
make bash
```

#### SSH Keys

Generate the SSL keys

```
make generate-keypair
```

#### Init Application

install dependencies

```
make install
```

initialize database

```
make init
``` 

Drop database

```
make drop
``` 

Remove database container

```
make rm-db
``` 

#### Tests

run all tests

```
make test
```

run unit tests

```
make unit
``` 

run behat tests

```
make behat
``` 

#### Coding Standards

```
make cs
``` 

#### Static Code Analysis

```
make stan
``` 
