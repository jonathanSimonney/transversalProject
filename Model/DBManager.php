<?php

namespace Model;

use PDO;
use PDOException;

class DBManager extends BaseManager
{
    public function setup()
    {
        //$this->sessionManager = DataManager::getInstance();
    }

    private $dbh;
    //private $sessionManager;
    
    private function connectToDb()
    {
        global $privateConfig;
        $db_config = $privateConfig['db_config'];
        $dsn = 'mysql:dbname='.$db_config['name'].';host='.$db_config['host'];
        $user = $db_config['user'];
        $password = $db_config['pass'];
        
        try {
            $dbh = new \PDO($dsn, $user, $password);
        } catch (PDOException $e) {
            echo 'Connexion échouée : ' . $e->getMessage();
        }
        
        return $dbh;
    }
    
    protected function getDbh()
    {
        if ($this->dbh === null)
        {
            $this->dbh = $this->connectToDb();
        }
        return $this->dbh;
    }


    public function dbInsert($table, array $data = [], $keyCorrespond = false)
    {
        $dbh = $this->getDbh();
        $query = 'INSERT INTO `' . $table . '` VALUES (NULL,';
        $keyQuery = '(`id`';
        $first = true;
        foreach ($data AS $k => $value) {
            if (!$first){
                $query .= ', ';
            }else{
                $first = false;
            }
            $query .= ':'.$k;
            $keyQuery .= ',`'.$k.'`';
        }
        $query .= ')';
        $keyQuery .= ')';

        if ($keyCorrespond){
            $query = str_replace('INSERT INTO `' . $table . '` VALUES', 'INSERT INTO `' . $table . '`'.$keyQuery.' VALUES', $query);
        }
        $sth = $dbh->prepare($query);
        $sth->execute($data);

        $data['id'] = $this->getLastInsertedId();
        //$this->sessionManager->updateSession('insert', $table, $data);
        return true;
    }

    public function findOne($query)
    {
        $dbh = $this->getDbh();
        $data = $dbh->query($query, PDO::FETCH_ASSOC);
        return $data->fetch();
    }

    public function findOneSecure($query, array $data = [])
    {
        $dbh = $this->getDbh();
        $sth = $dbh->prepare($query);
        $sth->execute($data);
        return $sth->fetch(PDO::FETCH_ASSOC);
    }

    public function findAll($query)
    {
        $dbh = $this->getDbh();
        $data = $dbh->query($query, PDO::FETCH_ASSOC);
        return $data->fetchAll();
    }

    public function findAllSecure($query, array $data = [])
    {
        $dbh = $this->getDbh();
        $sth = $dbh->prepare($query);
        $sth->execute($data);
        return $sth->fetchAll(PDO::FETCH_ASSOC);
    }

    public function getLastInsertedId()
    {
        $dbh = $this->getDbh();
        return $dbh->lastInsertId();
    }

    public function getWhatHow($needle, $needleColumn, $needleTable)
    {
        $data = $this->findAllSecure('SELECT * FROM `'.$needleTable.'` WHERE `'.$needleColumn.'` = :needle',
            ['needle' => $needle]);

        return $data;
    }

    public function dbUpdate($table, $id, $fieldToUpdateData)
    {
        $dbh = $this->getDbh();
        $first = true;
        $query = 'UPDATE `' . $table . '` SET ';
        foreach ($fieldToUpdateData AS $key => $value){
            if (!$first){
                $query .= ', ';
            }else{
                $first = false;
            }

            $query .= '`'.$key.'` =:'.$key;
        }

        $query .= ' WHERE `'.$table.'`.`id` = '.$id;

        /*echo $query;
        var_dump($fieldToUpdateData);*/
        $sth = $dbh->prepare($query);
        $sth->execute($fieldToUpdateData);

        $fieldToUpdateData['id'] = $id;
        //$this->sessionManager->updateSession('update', $table, $fieldToUpdateData);
    }

    public function dbSuppress($table, $id)
    {
        $dbh = $this->getDbh();
        $query = 'DELETE FROM `'.$table.'` WHERE `'.$table.'`.`id` = '.$id.';';
        $sth = $dbh->prepare($query);
        $sth->execute();

        //$this->sessionManager->updateSession('suppress', $table, $id);
    }
}