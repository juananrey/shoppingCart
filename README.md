Shopping cart
========================

Pick some products and add / delete them. Made with <3 using [Symfony3](https://symfony.com/) 
and [Bootstrap](http://getbootstrap.com/) 

Requirements
------------
 * PHP 5.4 or higher
 * PDO-SQLite PHP extension enabled
 * [Composer](https://getcomposer.org/download/)
 * And the [usual Symfony application requirements](http://symfony.com/doc/current/reference/requirements.html)

Installation
------------

```bash
$ git clone https://github.com/juananrey/shoppingCart.git
$ cd shoppingCart/
$ composer install --no-interaction
```

Usage
-----

There is no need to configure a virtual host in your web server to access the application.
Just use the built-in web server:

```bash
$ cd shoppingCart/
$ php bin/console server:start
```

This command will start a web server for the Symfony application. Now you can
access the application in your browser at <http://localhost:8000>.