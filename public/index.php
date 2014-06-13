<?php
/**
 * Created by PhpStorm.
 * User: jasper
 * Date: 6/11/14
 * Time: 4:48 PM
 */

require '../vendor/autoload.php';
use \System\DB;

$app = new \Slim\Slim([
    "mode"=>"development"
]);

$app->base_dir = __DIR__ . '..';

$app->configureMode('development', function () use ($app) {
    $app->config([
        'log.enable' => true,
        'debug' => true,
        'templates.path' => '../application/views',

        'db.host' => 'localhost',
        'db.port' => 3306,
        'db.user' => 'root',
        'db.password' => 'bitnami',
        'db.name' => 'com.tachcard.jasper.app'
    ]);
});

// Define MySQL Connection resource
$app->container->singleton('db', function () use ($app) {
    $connection = mysqli_connect(
        $app->config('db.host'),
        $app->config('db.user'),
        $app->config('db.password'),
        $app->config('db.name'),
        $app->config('db.port')
    );

    $db = new DB($connection);
    return $db;
});

$app->view->setData('assets_path', $app->request->getScheme() . '://' . $app->request->getHost() . '/');
$app->view->setData('template_path', $app->config('templates.path'));
$app->view->setData('app', $app);

function build_node($node, $all_nodes) {
    $node['nodes'] = [];
    foreach ($all_nodes as $child_node){
        if ($child_node['parentID'] && ($node['id'] == $child_node['parentID'])) {
            $child_node = build_node($child_node, $all_nodes);
            array_push($node['nodes'], $child_node);
        }
    }
    return $node;
}

$app->get('/', function () use ($app) {
   $app->response()->redirect('/task/1');
});

$app->get('/install', function () use ($app) {
    \System\Controllers\InstallController::run($app);
});

$app->get('/task/:num', function ($num) use ($app) {
    \System\Controllers\TaskController::buildParams(array('num'=>$num));
    \System\Controllers\TaskController::run($app);
});

$app->get('/ya.ru', function () use ($app) {
    $app->render('tasks/3_preview.php');
});

$app->run();