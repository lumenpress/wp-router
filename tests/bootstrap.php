<?php

use Illuminate\Database\Capsule\Manager as Capsule;

define('WP_TESTS_CONFIG_FILE_PATH', __DIR__.'/wp-tests-config.php');

require realpath(dirname(PHPUNIT_COMPOSER_INSTALL).'/lumenpress/testing/tests/includes/bootstrap.php');

putenv('APP_DEBUG='.(WP_DEBUG ? 'true' : 'false'));
putenv('DB_CONNECTION=mysql');
putenv('DB_HOST='.DB_HOST);
putenv('DB_DATABASE='.DB_NAME);
putenv('DB_USERNAME='.DB_USER);
putenv('DB_PASSWORD='.DB_PASSWORD);
putenv('DB_PREFIX='.$GLOBALS['table_prefix']);
putenv('APP_TIMEZONE='.(get_option('timezone_string') ?: 'UTC'));

date_default_timezone_set(getenv('APP_TIMEZONE'));

$capsule = new Capsule;

$capsule->addConnection([
    'driver'    => getenv('DB_DRIVER') ?: 'mysql',
    'host'      => getenv('DB_HOST') ?: 'mysql',
    'database'  => getenv('DB_NAME') ?: 'wordpress',
    'username'  => getenv('DB_USER') ?: 'wordpress',
    'password'  => getenv('DB_PASSWORD') === false ? 'wordpress' : getenv('DB_PASSWORD'),
    'charset'   => 'utf8',
    'collation' => 'utf8_unicode_ci',
    'prefix'    => getenv('DB_PREFIX') ?: 'wp_testing_',
    'timezone'  => env('DB_TIMEZONE', '+00:00'),
    'strict'    => env('DB_STRICT_MODE', false),
]);

// Set the event dispatcher used by Eloquent models... (optional)
// use Illuminate\Events\Dispatcher;
// use Illuminate\Container\Container;

// $capsule->setEventDispatcher(new Dispatcher(new Container));

// Make this Capsule instance available globally via static methods... (optional)
$capsule->setAsGlobal();

// Setup the Eloquent ORM... (optional; unless you've used setEventDispatcher())
$capsule->bootEloquent();
