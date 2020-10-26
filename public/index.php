<?php
declare(strict_types=1);

use Phalcon\Di\FactoryDefault;

error_reporting(E_ALL);

define('BASE_PATH', dirname(__DIR__));
define('APP_PATH', BASE_PATH . '/app');

try {
    $di = new FactoryDefault();

    include '../vendor/autoload.php';
    include APP_PATH . '/config/router.php';
    include APP_PATH . '/config/services.php';

    $config = $di->getConfig();

    include APP_PATH . '/config/loader.php';

    $application = new \Phalcon\Mvc\Application($di);

    echo $application->handle($_SERVER['REQUEST_URI'])->getContent();
} catch (\Exception $e) {
    http_response_code(500);

    echo json_encode([
        'message' => $e->getMessage(),
        'trace' => $e->getTraceAsString(),
    ]);
}
