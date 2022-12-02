<?php

namespace core\models;

use core\exceptions\DbException;

class DbConnection
{

    //Подключения к базе данных через PDO и mysqli и их методы запроса к бд

    protected static $db;

    public static function PDOConnection()
    {
        try {

            return self::$db = new \PDO('mysql:host=' . \App::config()->DB('host') . ';dbname=' . \App::config()->DB('dbName'),
                            \App::config()->DB('user'), \App::config()->DB('password'),
                            [
                                \PDO::ATTR_ERRMODE => \PDO::ERRMODE_EXCEPTION,
                                \PDO::MYSQL_ATTR_INIT_COMMAND => "SET NAMES 'UTF8'"
                            ]);

        } catch (\PDOException $e) {

            throw new DbException($e->getMessage());

        }
    }

    public static function mysqliConnection()
    {
        try{

            self::$db = new \mysqli(\App::config()->DB('host'), \App::config()->DB('user'), \App::config()->DB('password'), \App::config()->DB('dbName'));

            if(self::$db->connect_error){

                throw new \Exception('Ошибка подключения к базе данных: '
                    . self::$db->connect_errno . ' '. self::$db->connect_error);

            }

            self::$db->query("SET NAMES 'UTF8'");

        }catch (\Exception $e){

            throw new DbException($e->getMessage());

        }

        return self::$db;


    }

    public static function PDOQuery(string $query, $crud = 'c', $return_id = false, ?array $parameters = [])
    {

        static $reConnect = false;

        try {

            if (!$parameters) {

                $res = self::$db->query($query);

            } else {

                $res = self::$db->prepare($query);

                $res->execute($parameters);

            }

            if (!$res) {

                if(!$reConnect && self::checkPDOTimeoutError()){

                    $reConnect = true;

                    return self::PDOQuery($query, $crud, $return_id, $parameters);

                }

                return $res;

            } elseif (!empty($res->errorInfo()[1])) {

                throw new \PDOException(implode("\n", $res->errorInfo()) . "\n" . $query);

            }

            $reConnect = false;

            if ($res->rowCount()) {

                if (self::$db->lastInsertId()) {

                    return self::$db->lastInsertId();

                }

                if ($result = $res->fetchAll(\PDO::FETCH_ASSOC)) {

                    return $result;

                }

                return $res->rowCount();

            }

            return !preg_match('/^\s*select\s/i', $query);

        } catch (\PDOException $e) {

            if(!$reConnect && self::checkPDOTimeoutError()){

                $reConnect = true;

                return self::PDOQuery($query, $crud, $return_id, $parameters);

            }

            throw new DbException($e->getMessage());

        }

    }

    protected static function checkPDOTimeoutError() : bool{

        $statusInfo = self::$db->errorInfo();

        if(!empty($statusInfo[1])){

            if($statusInfo[1] === 2006){

                self::$db = null;

                \App::model()->connect(true);

                return true;

            }

        }

        return false;

    }

    public static function mysqliQuery($query, $crud = 'c', $return_id = false, ?array $parameters = [])
    {

        static $reConnect = false;

        $result = self::$db->query($query);

        if (self::$db->affected_rows === -1) {

            if(self::$db->errno === 2006 && !$reConnect){

                self::$db->kill(self::$db->thread_id);

                $reConnect = true;

                self::$db->close();

                \App::model()->connect(true);

                return self::$db->query($query, $crud, $return_id);

            }

            throw new DbException('Ошибка в SQL запросе: '
                . $query . "\r\n" . self::$db->errno . ' ' . self::$db->error
            );

        }

        $reConnect = false;

        switch ($crud) {

            case 'r':

                if ($result->num_rows) {

                    $res = [];

                    for ($i = 0; $i < $result->num_rows; $i++) {
                        $res[] = $result->fetch_assoc();
                    }

                    return $res;
                }

                return false;

                break;

            case 'u':
            case 'c':

                if ($return_id) return self::$db->insert_id;

                return true;

                break;

            default:

                return true;

                break;

        }


    }

}