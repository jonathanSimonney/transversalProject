<?php
/**
 * Created by PhpStorm.
 * User: user
 * Date: 02/05/2017
 * Time: 08:54
 */

namespace Model;


class VictimManager extends UserManager
{
    public function setup()
    {
        parent::setup();
    }

    public function getContact()
    {
        $ret['lawyer'] = $this->DBManager->findOne('SELECT `name`, `firstName`, `id`, `pseudo`, `location` FROM users WHERE id = '.$_SESSION['currentUser']['data']['lawyer_id']);
        $ret['psy'] = $this->DBManager->findOne('SELECT `name`, `firstName`, `id`, `pseudo`, `location` FROM users WHERE id = '.$_SESSION['currentUser']['data']['psy_id']);
        return $ret;
    }
}