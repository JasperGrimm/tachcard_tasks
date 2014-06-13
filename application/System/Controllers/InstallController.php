<?php
/**
 * Created by PhpStorm.
 * User: jasper
 * Date: 6/13/14
 * Time: 12:46 PM
 */
namespace System\Controllers;

class InstallController {

    public static function run($app) {

        $randomtest = array();
        $randomtest[] = <<<'randomtest'
CREATE TABLE `randomtest`
  (
     `id`   INT(11) NOT NULL auto_increment,
     `name` VARCHAR(50) NOT NULL DEFAULT '',
     `pub`  TINYINT(1) NOT NULL DEFAULT '1',
     PRIMARY KEY (`id`)
  );
randomtest;
        $randomtest[] = <<<'randomtest'
CREATE TABLE randomtest_keymapper (
    id SERIAL,
    row_id INT(11) unsigned NOT NULL UNIQUE
);
randomtest;

        $randomtest[] = <<<'randomtest'
DROP TRIGGER IF EXISTS randomtest_insert_trigger
randomtest;
        $randomtest[] = <<<'randomtest'
DROP TRIGGER IF EXISTS randomtest_update_trigger
randomtest;
        $randomtest[] = <<<'randomtest'
DROP TRIGGER IF EXISTS randomtest_delete_trigger
randomtest;

        $randomtest[] = <<<'randomtest'
CREATE TRIGGER randomtest_insert_trigger
AFTER INSERT ON randomtest
FOR EACH ROW
BEGIN
    DECLARE ai BIGINT UNSIGNED DEFAULT 1;
    SELECT MAX(id) + 1 FROM randomtest_keymapper INTO ai;
    SELECT IFNULL(ai, 1) INTO ai;
    INSERT INTO randomtest_keymapper(id, row_id) VALUES (ai, NEW.id);
END
randomtest;
        $randomtest[] = <<<'randomtest'
CREATE TRIGGER randomtest_update_trigger
AFTER DELETE ON randomtest
FOR EACH ROW
BEGIN
    DELETE FROM randomtest_keymapper WHERE row_id = OLD.id;
    UPDATE randomtest_keymapper SET id = id - 1 WHERE row_id > OLD.id;
END
randomtest;
        $randomtest[] = <<<'randomtest'
CREATE TRIGGER randomtest_delete_trigger
AFTER UPDATE ON randomtest
FOR EACH ROW
BEGIN
    UPDATE randomtest_keymapper SET row_id = NEW.id WHERE row_id = OLD.id;
END
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
        $app->db->query('drop table if exists `randomtest_keymapper`');
        $app->db->query('drop table if exists `tree`');
        $app->db->query($randomtest[0]);
        $app->db->query($randomtest[1]);
        $app->db->query($randomtest[2]);
        $app->db->query($randomtest[3]);
        $app->db->query($randomtest[4]);
        $app->db->query($randomtest[5]);
        $app->db->query($randomtest[6]);
        $app->db->query($randomtest[7]);
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
        $app->db->query('set @key = 0;');
        $app->db->query('INSERT INTO randomtest_keymapper SELECT @key := @key + 1, id FROM randomtest;');
        $app->response()->redirect('/');

    }

}