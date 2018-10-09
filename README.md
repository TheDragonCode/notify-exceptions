# Notify Exceptions

Notify the site administrator of any errors through various channels of communication.

![notify-exceptions](https://user-images.githubusercontent.com/10347617/46622131-2424e080-cb32-11e8-8381-b98f2465191a.png)

<p align="center">
    <a href="https://styleci.io/repos/152111546"><img src="https://styleci.io/repos/152111546/shield" alt="StyleCI" /></a>
    <a href="https://packagist.org/packages/andrey-helldar/notify-exceptions"><img src="https://img.shields.io/packagist/dt/andrey-helldar/notify-exceptions.svg?style=flat-square" alt="Total Downloads" /></a>
    <a href="https://packagist.org/packages/andrey-helldar/notify-exceptions"><img src="https://poser.pugx.org/andrey-helldar/notify-exceptions/v/stable?format=flat-square" alt="Latest Stable Version" /></a>
    <a href="https://packagist.org/packages/andrey-helldar/notify-exceptions"><img src="https://poser.pugx.org/andrey-helldar/notify-exceptions/v/unstable?format=flat-square" alt="Latest Unstable Version" /></a>
    <a href="LICENSE"><img src="https://poser.pugx.org/andrey-helldar/notify-exceptions/license?format=flat-square" alt="License" /></a>
</p>


## Installation

To get the latest version of Laravel Notify Exception, simply require the project using [Composer](https://getcomposer.org):

```
composer require andrey-helldar/notify-exceptions
```

Instead, you may of course manually update your require block and run `composer update` if you so choose:

```json
{
    "require": {
        "andrey-helldar/notify-exceptions": "^1.0"
    }
}
```

If you don't use auto-discovery, add the `ServiceProvider` to the providers array in `config/app.php`:

```php
Helldar\NotifyExceptions\ServiceProvider::class,
```

You can also publish the config file to change implementations (ie. interface to specific class):

```
php artisan vendor:publish --provider="Helldar\NotifyExceptions\ServiceProvider"
php artisan vendor:publish --provider="SquareBoat\Sneaker\SneakerServiceProvider"
```

And call `php artisan migrate` command from console. 

Now you can use the `app('notifex')` method.


## Configuration

To configure the generation, you need go to `config/notifex.php` file for Slack and Jira settings and `config/sneaker.php` for Email notifications (we using a [squareboat/sneaker](https://github.com/squareboat/sneaker) package for that).


If you need to create applications in the Jira service, then you need to install the package [lesstif/php-jira-rest-client](https://github.com/lesstif/php-jira-rest-client):
```bash
composer require lesstif/php-jira-rest-client
```


## Using

Add exception capturing to `app/Exceptions/Handler.php`:

```php
public function report(Exception $exception)
{
    if ($this->shouldReport($exception)) {
        app('notifex')->send($exception);
    }

    parent::report($exception);
}
```

or just use in your code:
```php
try {
    $foo = $bar
} catch(\Exception $exception) {
    app('notifex')->send($exception);
}
```


## License

This package is released under the [MIT License](LICENSE).
