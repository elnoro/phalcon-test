<?php


use Phalcon\Mvc\Router;
use Phalcon\Mvc\RouterInterface;

/** @var RouterInterface $router */
$router = $di->getRouter();

$router->addGet('/api/contacts', 'Contacts::list');
$router->addPost('/api/contacts', 'Contacts::create');

$router->addGet('/api/contacts/{contactId}', 'Contacts::get');
$router->addPut('/api/contacts/{contactId}', 'Contacts::update');
$router->addDelete('/api/contacts/{contactId}', 'Contacts::delete');

$router->handle($_SERVER['REQUEST_URI']);
