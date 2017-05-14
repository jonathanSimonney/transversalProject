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
        $ret = $this->DBManager->findAll('SELECT `id`, `pseudo`, `gender`, `location`, `type` FROM users WHERE id IN ('.$_SESSION['currentUser']['data']['lawyer_id'].', '.$_SESSION['currentUser']['data']['psy_id'].')');
        return $this->makeInferiorKeyIndex($ret, 'pseudo');
    }

    public function userRegister()
    {
        parent::userRegisterWithParams($_POST, ['pseudo', 'email', 'gender', 'password', 'indic', 'location', 'birthdate'], true);
        //do additional things!
    }

    public function userCheckRegister()
    {
        $arrayReturned = parent::userCheckRegister();
        $dateArray = explode('-', $_POST['birthdate']);
        if (count($dateArray) !== 3)
        {
            $arrayReturned['formOk'] = false;
            $arrayReturned[0]['birthdate'] = 'invalid data';
        }
        elseif(!checkdate((int)$dateArray[1], (int)$dateArray[2], (int)$dateArray[0]))
        {
            $arrayReturned['formOk'] = false;
            $arrayReturned[0]['birthdate'] = 'invalid data';
        }
        return $arrayReturned;
    }
}