<?php
declare(strict_types=1);

use Cake\Core\Configure;
use Cake\Core\Plugin;
use Cake\Datasource\ConnectionManager;
use Cake\Routing\Router;
use Cake\Utility\Security;
use Migrations\TestSuite\Migrator;

$findRoot = function ($root) {
    do {
        $lastRoot = $root;
        $root = dirname($root);
        if (is_dir($root . '/vendor/cakephp/cakephp')) {
            return $root;
        }
    } while ($root !== $lastRoot);

    /** @noinspection PhpUnhandledExceptionInspection */
    throw new Exception('Cannot find the root of the application, unable to run tests');
};
$root = $findRoot(__FILE__);
unset($findRoot);
chdir($root);

require_once 'vendor/cakephp/cakephp/src/basics.php';
require_once 'vendor/autoload.php';

$_SERVER['PHP_SELF'] = '/';

define("ROOT", dirname(__FILE__, 2));
define('TMP', sys_get_temp_dir() . DS);
const TESTS = ROOT . DS . 'tests' . DS;
const CAKE_CORE_INCLUDE_PATH = ROOT . DS . 'vendor' . DS . 'cakephp' . DS . 'cakephp';

if (!defined('TEST_APP')) {
    define('TEST_APP', TESTS . 'test_app' . DS);
}

const APP = ROOT . 'App' . DS;

Configure::write('debug', true);
Configure::write('App', [
    'namespace' => 'TestApp',
    'paths' => [
        'plugins' => [ROOT . 'Plugin' . DS],
        'templates' => [ROOT . 'templates' . DS],
    ],
]);

if (!getenv('DB_URL')) {
    putenv('DB_URL=sqlite:///:memory:');
}

ConnectionManager::setConfig('test', ['url' => getenv('DB_URL')]);
Router::reload();
Security::setSalt('oJt5xYtBOSCLtlra3s5xgs96USjPLNJ8np657QSI4zhksqOh');

Plugin::getCollection()->add(new \Token\Plugin());

$_SERVER['PHP_SELF'] = '/';

(new Migrator())->run();
