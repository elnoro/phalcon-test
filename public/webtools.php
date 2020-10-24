<?php

/**
@phalcon





*/

use Phalcon\DevTools\Bootstrap;

/**
@psalm
*/
include 'webtools.config.php';
include PTOOLSPATH . '/bootstrap/autoload.php';

/**
@psalm
*/
$bootstrap = new Bootstrap([
'ptools_path' => PTOOLSPATH,
'ptools_ip' => PTOOLS_IP,
'base_path' => BASE_PATH,
]);

if (APPLICATION_ENV === ENV_TESTING) {
return $bootstrap->run();
} else {
echo $bootstrap->run();
}
