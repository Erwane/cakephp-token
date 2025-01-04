<?php
declare(strict_types=1);

use Cake\Core\Configure;
use Cake\Datasource\ConnectionManager;
use Cake\Utility\Security;
use Migrations\TestSuite\Migrator;

require dirname(__DIR__) . '/vendor/autoload.php';

define('ROOT', dirname(__DIR__) . DS);
define('TMP', sys_get_temp_dir() . DS);
// const CORE_PATH = ROOT . 'vendor' . DS . 'cakephp' . DS . 'cakephp' . DS;
// const CAKE = CORE_PATH . 'src' . DS;
// require CAKE . 'Core/functions_global.php';

Configure::write('debug', true);
Configure::write('App.encoding', 'UTF-8');
Security::setSalt('a-long-but-not-random-value');

// Ensure default test connection is defined
if (!getenv('DB_URL')) {
    putenv('DB_URL=sqlite:///:memory:');
}
ConnectionManager::setConfig('test', ['url' => getenv('DB_URL')]);

// Configure::write('App', [
//     'namespace' => 'TestApp',
//     'paths' => [
//         'plugins' => [ROOT . 'Plugin' . DS],
//         'templates' => [ROOT . 'templates' . DS],
//     ],
// ]);
//
// if (!getenv('DB_URL')) {
//     putenv('DB_URL=sqlite:///:memory:');
// }
//
// ConnectionManager::setConfig('test', ['url' => getenv('DB_URL')]);
// Router::reload();
// Security::setSalt('oJt5xYtBOSCLtlra3s5xgs96USjPLNJ8np657QSI4zhksqOh');
//
// Plugin::getCollection()->add(new \Token\Plugin());
//
// $_SERVER['PHP_SELF'] = '/';

(new Migrator())->run();
