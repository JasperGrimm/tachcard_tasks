<?php
/**
 * Created by PhpStorm.
 * User: jasper
 * Date: 6/11/14
 * Time: 5:10 PM
 */

namespace System;


class DB {

    protected $connection;

    public function __construct($connection){
        $this->connection = $connection;
    }


    public function query($query) {

        return mysqli_query($this->connection, $query);

    }

    public static function fetchAll($result) {

        return mysqli_fetch_all($result, MYSQL_ASSOC);

    }

    public static function fetchOne($result) {
        return mysqli_fetch_object($result);
    }

    /**
     * @return resource
     */
    public function getConnection()
    {
        return $this->connection;
    }


} 