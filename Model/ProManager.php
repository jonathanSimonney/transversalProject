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
        $ret = $this->DBManager->findAll('SELECT `id`, `pseudo`, `location`, `type`
        FROM users WHERE (type != \'victime\' AND id != '.$_SESSION['currentUser']['data']['id'].') OR psy_id = '.$_SESSION['currentUser']['data']['id'].' OR lawyer_id = '.$_SESSION['currentUser']['data']['id']);

        return $this->makeInferiorKeyIndex($ret, 'pseudo');
    }
}