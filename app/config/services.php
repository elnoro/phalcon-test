<?php
declare(strict_types=1);

use App\DataProviders\Hostaway\CachingClient;
use App\DataProviders\Hostaway\ExistenceChecker;
use App\DataProviders\Hostaway\HttpClient;
use App\DataProviders\Hostaway\LoggingClient;
use Phalcon\Cache\AdapterFactory;
use Phalcon\Cache\CacheFactory;
use Phalcon\Escaper;
use Phalcon\Flash\Direct as Flash;
use Phalcon\Logger;
use Phalcon\Logger\Adapter\Stream;
use Phalcon\Mvc\Model\Metadata\Memory as MetaDataAdapter;
use Phalcon\Mvc\View;
use Phalcon\Mvc\View\Engine\Php as PhpEngine;
use Phalcon\Mvc\View\Engine\Volt as VoltEngine;
use Phalcon\Session\Adapter\Stream as SessionAdapter;
use Phalcon\Session\Manager as SessionManager;
use Phalcon\Storage\SerializerFactory;
use Phalcon\Url as UrlResolver;

$di->setShared('config', function () {
    return include APP_PATH . "/config/config.php";
});


$di->setShared('url', function () {
    $config = $this->getConfig();

    $url = new UrlResolver();
    $url->setBaseUri($config->application->baseUri);

    return $url;
});


$di->setShared('view', function () {
    $config = $this->getConfig();

    $view = new View();
    $view->setDI($this);
    $view->setViewsDir($config->application->viewsDir);

    $view->registerEngines([
        '.volt' => function ($view) {
            $config = $this->getConfig();

            $volt = new VoltEngine($view, $this);

            $volt->setOptions([
                'path' => $config->application->cacheDir,
                'separator' => '_',
            ]);

            return $volt;
        },
        '.phtml' => PhpEngine::class,

    ]);

    return $view;
});


$di->setShared('db', function () {
    $config = $this->getConfig();

    $class = 'Phalcon\Db\Adapter\Pdo\\' . $config->database->adapter;
    $params = [
        'host' => $config->database->host,
        'username' => $config->database->username,
        'password' => $config->database->password,
        'dbname' => $config->database->dbname,
        'charset' => $config->database->charset,
    ];

    if ($config->database->adapter == 'Postgresql') {
        unset($params['charset']);
    }

    return new $class($params);
});


$di->setShared('modelsMetadata', function () {
    return new MetaDataAdapter();
});


$di->set('flash', function () {
    $escaper = new Escaper();
    $flash = new Flash($escaper);
    $flash->setImplicitFlush(false);
    $flash->setCssClasses([
        'error' => 'alert alert-danger',
        'success' => 'alert alert-success',
        'notice' => 'alert alert-info',
        'warning' => 'alert alert-warning',
    ]);

    return $flash;
});


$di->setShared('session', function () {
    $session = new SessionManager();
    $files = new SessionAdapter([
        'savePath' => sys_get_temp_dir(),
    ]);
    $session->setAdapter($files);
    $session->start();

    return $session;
});

$di->setShared('logger', function () {
    $config = $this->getConfig();
    $adapter = new Stream($config->application->logPath);

    return new Logger('messages', ['main' => $adapter]);
});

$di->setShared('cache', function () {
    $config = $this->getConfig();
    $options = ['defaultSerializer' => 'Json', 'lifetime' => 7200];

    $serializerFactory = new SerializerFactory();
    $adapterFactory = new AdapterFactory($serializerFactory, $options);
    $cacheFactory = new CacheFactory($adapterFactory);

    return $cacheFactory->newInstance('stream', ['storageDir' => $config->application->cacheDir]);
});

$di->setShared('hostaway_client.http', function () {
    $config = $this->getConfig();

    return HttpClient::createFromSettings(
        $config->hostaway->apiUrl,
        $this->get('logger')
    );
});

$di->setShared('hostaway_client.cache', function () {
    $httpClient = $this->get('hostaway_client.http');
    $cache = $this->get('cache');

    return new CachingClient($httpClient, $cache);
});

$di->setShared('hostaway_client', function () {
    $httpClient = $this->get('hostaway_client.cache');
    $logger = $this->get('logger');

    return new LoggingClient($httpClient, $logger);
});

$di->setShared('hostaway_existence_checker', function () {
    return new ExistenceChecker($this->get('hostaway_client'));
});
