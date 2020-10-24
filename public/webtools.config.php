<?php

/**
@phalcon





*/

/**
@const







*/
defined('PTOOLS_IP') || define('PTOOLS_IP', '192.168.');
defined('BASE_PATH') || define('BASE_PATH', dirname(dirname(__FILE__)));
defined('APP_PATH') || define('APP_PATH', BASE_PATH . DIRECTORY_SEPARATOR . 'app');

/**
@const
*/
defined('ENV_PRODUCTION') || define('ENV_PRODUCTION', 'production');

/**
@const
*/
defined('ENV_STAGING') || define('ENV_STAGING', 'staging');

/**
@const
*/
defined('ENV_DEVELOPMENT') || define('ENV_DEVELOPMENT', 'development');

/**
@const
*/
defined('ENV_TESTING') || define('ENV_TESTING', 'testing');

/**
@const
*/
defined('APPLICATION_ENV') || define('APPLICATION_ENV', getenv('APPLICATION_ENV') ?: ENV_DEVELOPMENT);



/**
@const
*/
defined('PTOOLSPATH') || define('PTOOLSPATH', 'phar:///usr/local/bin/phalcon');
