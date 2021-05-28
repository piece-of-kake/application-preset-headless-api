<?php
use Slim\Factory\AppFactory;
use Slim\Logger;

require __DIR__ . '/../vendor/autoload.php';
require __DIR__ . '/../application/bootstrap.php';

$app = AppFactory::create();

$apiManager = new App\ApiControllerManager($app);

$apiManager->any('/{domain}/{endpoint}', PoK\Request\EndpointController::class);

// Define Custom Error Handler
$customErrorHandler = function (
    $request,
    $exception,
    bool $displayErrorDetails,
    bool $logErrors,
    bool $logErrorDetails
) use ($app) {
    $code = $exception->getCode() > 0
        ? $exception->getCode()
        : 500;

    $payload = (new \PoK\Exceptions\Formatter\ExceptionFormatter($exception))->toJSON(true);

    $response = $app->getResponseFactory()->createResponse();
    $response->getBody()->write($payload);

    // Log to the apache log file
    (new Logger())->error($payload);

    // Print out in response
    return $response
        ->withStatus($code)
        ->withHeader('Content-Type', 'application/json');
};

// Add Error Middleware
$errorMiddleware = $app->addErrorMiddleware(true, true, true);
$errorMiddleware->setDefaultErrorHandler($customErrorHandler);

$app->run();