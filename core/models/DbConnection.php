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

            return self::$db = new \PDO('mysql:host=' . \App::config()->DB('host') . ';dbname=' . \App::config()->DB('dbName'), \App::config()->DB('user'), \App::config()->DB('password'));

        } catch (\PDOException $e) {

            exit($e->getMessage());

        }
    }

    public static function mysqliConnection()
    {
        return self::$db = new \mysqli(\App::config()->DB('host'), \App::config()->DB('user'), \App::config()->DB('password'), \App::config()->DB('dbName'));

    }

    public static function PDOQuery(string $query, $crud = 'c', $return_id = false, ?array $parameters = [])
    {
        try {

            if (!$parameters) {

                $res = self::$db->query($query);

            } else {

                $res = self::$db->prepare($query);

                $res->execute($parameters);

            }

            if (!$res) {

                return $res;

            } elseif (!empty($res->errorInfo()[1])) {

                throw new \PDOException(implode("\n", $res->errorInfo()) . "\n" . $query);

            }

            if ($res->rowCount()) {

                if (self::$db->lastInsertId()) {

                    return self::$db->lastInsertId();

                }

                if ($result = $res->fetchAll(\PDO::FETCH_ASSOC)) {

                    return $result;

                }

                return $res->rowCount();


            }

            return false;

        } catch (\PDOException $e) {

            throw new DbException('Ошибка в SQL запросе: '
                . $e->getMessage()
            );

        }
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