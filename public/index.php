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

    $randomtest = <<<'randomtest'
CREATE TABLE `randomtest`
  (
     `id`   INT(11) NOT NULL auto_increment,
     `name` VARCHAR(50) NOT NULL DEFAULT '',
     `pub`  TINYINT(1) NOT NULL DEFAULT '1',
     PRIMARY KEY (`id`)
  )
randomtest;
    $tree = <<<'tree'
CREATE TABLE `tree`
  (
     `id`       INT(11) NOT NULL auto_increment,
     `parentID` INT(11) NOT NULL DEFAULT '0',
     `name`     VARCHAR(50) NOT NULL DEFAULT '',
     `pub`      TINYINT(1) NOT NULL DEFAULT '1',
     PRIMARY KEY (`id`),
     KEY `parentid` (`parentid`)
  )
tree;

    $app->db->query('drop table if exists `randomtest`');
    $app->db->query('drop table if exists `tree`');
    $app->db->query($randomtest);
    $app->db->query($tree);

    $nodes_tree = $nodes_randomtest = array();

    $random_ids = array();
    for ($i=1; $i<100; $i++) {
        $random_ids[] = $i + mt_rand(0, 21);
    }
    $random_ids = array_unique($random_ids);

    foreach ($random_ids as $id) {
        $name = '"node_' . '_' . $id . '"';
        $pub = 1;
        $node_random = array($id, $name, $pub);
        $nodes_randomtest[] = '(' . join(',', $node_random) . ')';
    }
    $app->db->query('insert into randomtest (id, name, pub) values ' . join(',', $nodes_randomtest));

    for ($i=1; $i<21; $i++) {
        $id = $i;
        $parentID = $i - mt_rand(1, 5);
        $parentID = $parentID > 0 ? $parentID : 0;
        $name = '"node_' . $parentID . '.' . $id . '"';
        $pub = 1;
        $node_tree = array($id, $parentID, $name, $pub);
        $nodes_tree[] = '(' . join(',', $node_tree) . ')';
    }
    $app->db->query('insert into tree (id, parentID, name, pub) values ' . join(',', $nodes_tree));
    $app->response()->redirect('/');
});

$app->get('/task/:num', function ($num) use ($app) {

    $db = $app->db;
    $data = array();
    switch ($num) {
        case 1:
            $sql_v1 = <<<'SELECT'
SELECT r1.id, name, pub FROM randomtest AS r1
JOIN (
    SELECT ( (SELECT MAX(id) FROM randomtest) * RAND() ) AS id
) AS r2 ON r1.id >= r2.id
ORDER BY r1.id ASC LIMIT 1;
SELECT;
            $res = $app->db->query($sql_v1);
            if ($res){
                $result = DB::fetchAll($res);
                $data['variants']['1'] = var_export($result, 1);
            }

            $sql_v2 = <<<'SELECT'
SELECT r1.id, name, pub FROM randomtest AS r1
JOIN (
    SELECT ( RAND() * (SELECT MAX(id) FROM randomtest) ) AS id
) AS r2
WHERE r1.id >= r2.id ORDER BY r1.id ASC LIMIT 1;
SELECT;
            $res = $app->db->query($sql_v2);
            if ($res){
                $result = DB::fetchAll($res);
                $data['variants']['2'] = var_export($result, 1);
            }

            break;
        case 2:
            $res = $db->query( 'select * from tree' );
            $nodes = DB::fetchAll($res);
            $tree = [];
            if (count($nodes)) {
                foreach ($nodes as $node) {
                    if (0 == $node['parentID']) {
                        $node = build_node($node, $nodes);
                        $tree[$node['id']] = $node;
                    }
                }
            }
            $data = array('tree' => $tree);
            break;
        case 3:
            break;
    }
    if ($num) {
        $app->render('tasks/' . $num . '.php', $data);
    }else{
        $app->render('404.php');
    }
});

$app->get('/ya.ru', function () use ($app) {
    $app->render('tasks/3_preview.php');
});

$app->run();