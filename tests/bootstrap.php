<?php
// @codingStandardsIgnoreFile
use Cake\Cache\Cache;
use Cake\Core\Configure;
use Cake\Core\Plugin;
use Cake\Datasource\ConnectionManager;
use Cake\I18n\I18n;

require dirname(__DIR__) . '/vendor/autoload.php';

define('ROOT', dirname(__DIR__) . DS);
define('CAKE_CORE_INCLUDE_PATH', ROOT . 'vendor' . DS . 'cakephp' . DS . 'cakephp');
define('CORE_PATH', ROOT . 'vendor' . DS . 'cakephp' . DS . 'cakephp' . DS);
define('CAKE', CORE_PATH . 'src' . DS);
define('TESTS', ROOT . 'tests');
define('APP', ROOT . 'tests' . DS . 'test_app' . DS);
define('APP_DIR', 'app');
define('WEBROOT_DIR', 'webroot');
define('WWW_ROOT', dirname(APP) . DS . 'webroot' . DS);
define('TMP', sys_get_temp_dir() . DS . 'tokens' . DS);
define('CONFIG', APP . 'config' . DS);
define('CACHE', TMP . 'cache' . DS);
define('SESSIONS', TMP . 'sessions' . DS);
define('LOGS', TMP . 'logs' . DS);

//@codingStandardsIgnoreStart
@mkdir(TMP);
@mkdir(LOGS);
@mkdir(SESSIONS);
@mkdir(CACHE);
@mkdir(CACHE . 'models');
@mkdir(CACHE . 'persistent');
@mkdir(CACHE . 'views');

require_once CORE_PATH . 'config/bootstrap.php';
date_default_timezone_set('UTC');
mb_internal_encoding('UTF-8');

Configure::write('App', [
    'namespace' => 'App',
    'encoding' => 'UTF-8',
    'base' => false,
    'baseUrl' => false,
    'dir' => APP_DIR,
    'webroot' => 'webroot',
    'wwwRoot' => WWW_ROOT
]);

Cache::config([
    'default' => [
        'className' => 'File',
        'path' => CACHE,
        'mask' => 0666,
        'serialize' => true,
        'duration' => 'now',
    ],
    '_cake_core_' => [
        'className' => 'File',
        'prefix' => 'core_',
        'path' => CACHE . 'persistent/',
        'mask' => 0666,
        'serialize' => true,
        'duration' => 'now',
    ],
    '_cake_model_' => [
        'className' => 'File',
        'prefix' => 'model_',
        'path' => CACHE . 'models/',
        'mask' => 0666,
        'serialize' => true,
        'duration' => 'now',
    ],
]);

Configure::write('debug', true);

ConnectionManager::config([
    'test' => [
        'className' => 'Cake\Database\Connection',
        'driver' => 'Cake\Database\Driver\Sqlite',
        'database' => TMP . 'test_token',
        'encoding' => 'utf8',
        'timezone' => 'UTC',
        'cacheMetadata' => true,
        'quoteIdentifiers' => false,
        'log' => false,
    ]
]);

ini_set('intl.default_locale', 'en_US');

$_SERVER['PHP_SELF'] = '/';
