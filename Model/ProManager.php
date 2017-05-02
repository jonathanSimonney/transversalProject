<?php
/**
 * Created by PhpStorm.
 * User: user
 * Date: 02/05/2017
 * Time: 08:55
 */

namespace Model;


class ProManager extends UserManager
{
    public function setup()
    {
        parent::setup();
    }

    public function getContact()
    {
        $ret = [];

        $ret[] = $this->DBManager->findAll('SELECT `name`, `firstName`, `id`, `pseudo`, `location` 
        FROM users WHERE type != \'victime\'');

        $ret[] = $this->DBManager->findOne('SELECT `name`, `firstName`, `id`, `pseudo`, `location` 
        FROM users WHERE psy_id = '.$_SESSION['currentUser']['data']['id'].' 
        OR lawyer_id = '.$_SESSION['currentUser']['data']['id']);

        return $ret;
    }
}