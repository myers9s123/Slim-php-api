<?php
define('BASE_DIR', dirname(__DIR__));
require BASE_DIR.'/vendor/autoload.php';

// Prepare app
$app = new \Slim\Slim(array(
    'name'           => 'API Test',
    'mode'           => 'development',
    'templates.path' => BASE_DIR.'/templates',
));

// Production specifc settings
$app->configureMode('production', function () use ($app) {
    $app->config(array(
        'debug' => false,
    ));
});
// Development settings
$app->configureMode('development', function () use ($app) {
    $app->config(array(
        'debug' => true,
    ));
});

// Create monolog logger and store logger in container as singleton 
// (Singleton resources retrieve the same log resource definition each time)
$app->container->singleton('log', function () {
    $log = new \Monolog\Logger('slim-skeleton');
    $log->pushHandler(new \Monolog\Handler\StreamHandler(BASE_DIR.'/logs/app.log', \Monolog\Logger::DEBUG));
    return $log;
});

// Define routes
$app->get('/', function () use ($app) {
    // Sample log message
    $app->log->info("Slim-Skeleton '/' route");
    // Render index view
    $app->render('index.html');
});

// Run app
$app->run();
