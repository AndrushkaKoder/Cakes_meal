<?php

namespace core\models;

abstract class Model
{

    protected \PDO $db;

    private bool $connected = false;

    protected string $pref = '';

    protected function connect()
    {

        if(!$this->connected){

            $this->pref = \App::DB('prefix') ?: '';

            try{

                $this->db = new \PDO('mysql:host=' . \App::DB('host') . ';dbname=' . \App::DB('dbName'), \App::DB('user'), \App::DB('password'));

            }catch (\PDOException $e){

                exit($e->getMessage());

            }

            $this->db->query("SET NAMES UTF8");

            $this->connected = true;

        }

    }

    public function query(string $query, ?array $parameters = []){

        try{

            if(!$parameters){

                $res = $this->db->query($query);

            }else{

                $res = $this->db->prepare($query);

                $res->execute($parameters);

            }

            if(!$res){

                return $res;

            }elseif (!empty($res->errorInfo()[1])){

                throw new \PDOException(implode("\n", $res->errorInfo()) . "\n" . $query);

            }

            if($res->rowCount()){

                if ($this->db->lastInsertId()){

                    return $this->db->lastInsertId();

                }

                if($result = $res->fetchAll(\PDO::FETCH_ASSOC)){

                    return $result;

                }

                return $res->rowCount();


            }

            return false;

        }catch (\PDOException $e){

            exit($e->getMessage());

        }


    }

    protected function prepareTable(string $table){

        if($this->pref && strpos($table, $this->pref) !== 0){

            $table = $this->pref . $table;

        }

        return !preg_match('/^\s*`[^`]+`\s*$/', $table) ? "`$table`" : $table;

    }

    public function getAll(string $table, ?string $order = ''){

        $order && $order = 'ORDER BY ' . $order;

        return $this->query("SELECT * FROM {$this->prepareTable($table)} $order");

    }



}