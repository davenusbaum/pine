# pine
PHP Inspired by Node Express

The world probably doesn't need another PHP framework, but I needed one.
The minimalist nature of [Express for Node.js](http://expressjs.com/) was my inspiration here, but this is not a port of Express *(which would really make no sense at all)*.

## Sample Application
The standard Hello World app.
```
<?php

use Pine\Application;

// load vendor/autoload.php etc
require '../bootstrap.php';

// create an application instance
$app = new Application();

// add just one simple route
$app->router->get('', function ($req, $res) {
    echo 'Hello World';
    yield;
});

// handle the request
$app->handle($_SERVER, $_GET, $_POST, $_COOKIE);
```
