<?php
/**
 * Created by PhpStorm.
 * User: jasper
 * Date: 6/13/14
 * Time: 12:50 PM
 */

namespace System\Controllers;
use \System\DB;

class TaskController implements IBaseController{

    public static $params;

    public static function buildParams($params){
        self::$params = $params;
    }

    public static function run($app) {
        $db = $app->db;
        $num = self::$params['num'];
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
    }

} 