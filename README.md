# Notifex

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

To get the latest version of `Notifex`, simply require the project using [Composer](https://getcomposer.org):

```
composer require andrey-helldar/notify-exceptions
```

Instead, you may of course manually update your `require` block and run `composer update`:

```json
{
    "require": {
        "andrey-helldar/notify-exceptions": "^3.0"
    }
}
```

For Laravel `5.5 - 6.x` run `composer require andrey-helldar/notify-exceptions:^2.0` in your project folder.

You can also publish the config file to change implementations (ie. interface to specific class):

```
php artisan vendor:publish --provider="Helldar\Notifex\ServiceProvider"
```

And call `php artisan migrate` command from console. 

Now you can use the `app('notifex')` method.


## Configuration

### Ingore bots

By default, the package does not respond to errors created in the process of search bots.

To enable error messages from bots, change the setting `ignore_bots` in [config/notifex.php](config/notifex.php) file.

By default, false.


### Email

See [configuration](config/notifex.php) file.

Example email message:

![email-message](https://user-images.githubusercontent.com/10347617/53572196-c003c700-3b7b-11e9-93e0-bff5aab01078.png)


### Jira

If you need to create issues in the Jira, then you need to install the package [lesstif/php-jira-rest-client](https://github.com/lesstif/php-jira-rest-client):
```bash
composer require lesstif/php-jira-rest-client
```

![2018-10-10_23-32-57](https://user-images.githubusercontent.com/10347617/46765597-187b1a80-cce8-11e8-91c4-ca2fffad88ff.png)


### Slack

If you need to send messages in the Slack channel, then you need to install the package [laravel/slack-notification-channel](https://packagist.org/packages/laravel/slack-notification-channel):
```bash
composer require laravel/slack-notification-channel
```


### Your notification services

You can easily connect your notification services. To do this, in block `jobs` of file `config/notifex.php`, add a call to its job:
```php
\Helldar\Notifex\Jobs\ExampleJob::class
```

If you need to pass any parameters to your job, you can use an associative entry, where the key is the link to the job class, and the values are the parameters:
```php
\Helldar\Notifex\Jobs\ExampleJob::class => [
    'host'      => env('EXAMPLE_HOST'), // http://127.0.0.1:8080
    'user'      => env('EXAMPLE_USER'), // 'foo'
    'password'  => env('EXAMPLE_PASS'), // 'bar'
    'other_key' => env('EXAMPLE_OTHER_KEY'), // 12345
],
```

Your job should inherit from the abstract class `Helldar\Notifex\Abstracts\JobAbstract`. This will help to correctly create a class for work.

To get the values of the settings you need to use the method `getConfig($class, $key)`:
```php
$host      = $this->getConfig(get_class(), 'host');
$user      = $this->getConfig(get_class(), 'user');
$password  = $this->getConfig(get_class(), 'password');
$other_key = $this->getConfig(get_class(), 'other_key');

// or add `config(string $key)` method:

private function config(string $key)
{
    return $this->getConfig(get_class(), $key);
}

$host      = $this->config('host');
$user      = $this->config('user');
$password  = $this->config('password');
$other_key = $this->config('other_key');
```

Examples of completed classes can be found here:
* [ExampleJob](src/Jobs/ExampleJob.php)
* [JiraJob](src/Jobs/JiraJob.php)

It is worth noting that standard jobs of Laravel are used for the call:
```bash
php artisan make:job <name>
```

They should remove the call interface `ShouldQueue` and extend the class:
```php
// before
use Illuminate\Contracts\Queue\ShouldQueue;

class ExampleJob implements ShouldQueue {}

// after
use Helldar\Notifex\Abstracts\JobAbstract;

class ExampleJob extends JobAbstract {}
```

As the abstract class includes a call of all necessary classes and interfaces.

It's all! Enjoy 😊


## Using

Add exception capturing to `app/Exceptions/Handler.php`:

```php
public function report(Throwable $exception)
{
    parent::report($exception);
    
    if ($this->shouldReport($exception)) {
        app('notifex')->send($exception);
    }
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

#### **IMPORTANT!**
To realize the possibility of saving an object to a database table, this object is processed before serialization.
Due to the peculiarities of linking objects in PHP, serialization does not support the `Throwable` interface, and therefore, if you call method `app('notifex')->send($exception)` before processing a variable, the application may cause an error `Expected array for frame 0`.

To avoid this, use method `parent::report($exception)` strictly **before** sending notifications.


## Test message

To verivy that Notifex is configured correctly and our integration is working, use `notifex:test` command:
```
php artisan notifex:test
```

A `Helldar\Notifex\Exceptions\NotifexTestException` class will be thrown and captured by Notifex. The captured exception will appear in your configured email immediately.


## Support

The package out of the box supports sending notifications to the following services:
* **Email** _(default, enabled)_
* **Slack** _(default, disabled)_
* **Jira** _(default, disabled)_


## License

This package is released under the [MIT License](LICENSE).
