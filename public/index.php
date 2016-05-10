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

// Initialize connection to the database
new \Pixie\Connection('sqlite', array(
    'driver'   => 'sqlite',
    'database' => 'healthcare.sqlite',
), 'DBConnection');

// Default routing conditions
\Slim\Route::setDefaultConditions(array(
    'id' => '\d+', // Ensure id parameter is an integer
));

// Content type for this application
$app->contentType('application/json');

// Define routes
$app->get('/providers', function () use ($app) {
    $providers = \HealthCare\ProviderModel::fetchAll();
    $app->response->write(json_encode($providers));
});
$app->get('/providers/:id', function ($id) use ($app) {
    $provider = new \HealthCare\Provider($id);

    if ($provider->isEmpty()) {
        $app->halt(404, "Provider not found");
    }

    $app->response->write(json_encode($provider->toArray()));
});
$app->post('/providers', function () use ($app) {
    $body = $app->request->getBody();
    if (empty($body)) {
        $app->halt(422, "Validation failed");
    }

    $params = json_decode($body, true);
    if (!\HealthCare\ProviderModel::isValidForCreate($params)) {
        $app->halt(422, "Validation failed");
    }

    $result = \HealthCare\ProviderModel::create($params);
    if (empty($result)) {
        $app->halt(422, "Validation failed");
    }

    $params['id'] = $result;

    $app->response->setStatus(201);
    $app->response->write(json_encode($params));
});
$app->put('/providers/:id', function ($id) use ($app) {
    $body = $app->request->getBody();
    if (empty($body)) {
        $app->halt(422, "Validation failed");
    }

    $params = json_decode($body, true);
    if (!\HealthCare\ProviderModel::isValidForUpdate($params)) {
        $app->halt(422, "Validation failed");
    }

    $provider = new \HealthCare\Provider($id);
    if ($provider->isEmpty()) {
        $app->halt(404, "Provider not found");
    }

    $provider->setFromRaw($params);
    if (!$provider->save()) {
        $app->halt(422, "Validation failed");
    }

    $app->response->setStatus(201);
    $app->response->write(json_encode($provider->toArray()));
});
$app->delete('/providers/:id', function ($id) use ($app) {
    $result = \HealthCare\ProviderModel::delete($id);

    if ($result->rowCount() < 1) {
        $app->halt(404, "Provider not found");
    }

    $app->response->setStatus(204);
    $app->response->write("Provider deleted");
});

// Run app
$app->run();
