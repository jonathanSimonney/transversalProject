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
        $ret = $this->DBManager->findAll('SELECT `id`, `pseudo`, `location`, `type` FROM users WHERE id IN ('.$_SESSION['currentUser']['data']['lawyer_id'].', '.$_SESSION['currentUser']['data']['psy_id'].')');
        return $this->makeInferiorKeyIndex($ret, 'pseudo');
    }
}