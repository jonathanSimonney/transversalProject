<?php

namespace Model;

class DBManager
{
    private $dbh;
    
    private static $instance = null;
    public static function getInstance()
    {
        if (self::$instance === null)
            self::$instance = new DBManager();
        return self::$instance;
    }
    
    private function __construct()
    {
        $this->dbh = null;
    }
    
    private function connectToDb()
    {
        global $privateConfig;
        $db_config = $privateConfig['db_config'];
        $dsn = 'mysql:dbname='.$db_config['name'].';host='.$db_config['host'];
        $user = $db_config['user'];
        $password = $db_config['pass'];
        
        try {
            $dbh = new \PDO($dsn, $user, $password);
        } catch (\PDOException $e) {
            echo 'Connexion échouée : ' . $e->getMessage();
        }
        
        return $dbh;
    }
    
    protected function getDbh()
    {
        if ($this->dbh === null)
            $this->dbh = $this->connectToDb();
        return $this->dbh;
    }
    
    public function insert($table, $data = [])
    {
        $dbh = $this->getDbh();
        $query = 'INSERT INTO `' . $table . '` VALUES ("",';
        $first = true;
        foreach ($data AS $k => $value)
        {
            if (!$first)
                $query .= ', ';
            else
                $first = false;
            $query .= ':'.$k;
        }
        $query .= ')';
        $sth = $dbh->prepare($query);
        $sth->execute($data);
        return true;
    }
    
    public function findOne($query)
    {
        $dbh = $this->getDbh();
        $data = $dbh->query($query, \PDO::FETCH_ASSOC);
        $result = $data->fetch();
        return $result;
    }
    
    public function findOneSecure($query, $data = [])
    {
        $dbh = $this->getDbh();
        $sth = $dbh->prepare($query);
        $sth->execute($data);
        $result = $sth->fetch(\PDO::FETCH_ASSOC);
        return $result;
    }
    
    public function findAll($query)
    {
        $dbh = $this->getDbh();
        $data = $dbh->query($query, \PDO::FETCH_ASSOC);
        $result = $data->fetchAll();
        return $result;
    }
    
    public function findAllSecure($query, $data = [])
    {
        $dbh = $this->getDbh();
        $sth = $dbh->prepare($query);
        $sth->execute($data);
        $result = $sth->fetchAll(\PDO::FETCH_ASSOC);
        return $result;
    }
}