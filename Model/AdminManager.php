<?php
/**
 * Created by PhpStorm.
 * User: user
 * Date: 11/05/2017
 * Time: 16:01
 */

namespace Model;


class AdminManager extends UserManager
{
    public function getUnregisteredUser()
    {
        $ret = $this->DBManager->findAll('SELECT id, email, pseudo, type FROM unregistered_users');
        return $this->makeInferiorKeyIndex($ret, 'pseudo');
    }

    public function getRegisteredUser()
    {
        $ret = $this->DBManager->findAll('SELECT id, email, pseudo, type FROM users');
        return $this->makeInferiorKeyIndex($ret, 'pseudo');
    }

    public function activateAccount($id)
    {
        $data = $this->DBManager->findOneSecure('SELECT pseudo, email, gender, password, indic, location, type, free_slot FROM unregistered_users WHERE id = :id', ['id' => $id]);
        $_POST['pseudo'] = $data['pseudo'];
        $_POST['email'] = $data['email'];
        $_SESSION['errorMessage'] = [];
        $this->FormManager->checkUniqField(['email' => 'users', 'pseudo' => 'users']);
        if (count($_SESSION['errorMessage']) !== 0)
        {
            echo json_encode($_SESSION['errorMessage']);
        }
        else
        {
            $this->userRegisterWithParams($data, ['pseudo', 'email', 'gender', 'password', 'indic', 'type', 'location', 'free_slot'], true, false);
            $this->DBManager->dbSuppress('unregistered_users', $id);
            echo json_encode('ok');
        }
        //$this->userRegisterWithParams($data, ['pseudo', 'email', 'password', 'indic', 'type', 'location', 'free_slot'], true);
        //$this->DBManager->dbSuppress('unregistered_user', $id);
    }

    public function getLogs()
    {
        $arrayLogs = ['access', 'message', 'security'];
        $ret = [];
        foreach ($arrayLogs as $fileLogName)
        {
            $ret[$fileLogName] = str_replace("\n", '<br>', file_get_contents('logs/'.$fileLogName.'.log'));
        }

        return $ret;
    }
}