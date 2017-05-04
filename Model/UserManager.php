<?php

namespace Model;

class UserManager extends BaseManager
{
    protected $DBManager;
    protected $FormManager;

    public function setup()
    {
        $this->DBManager = DBManager::getInstance();
        $this->FormManager = FormManager::getInstance();
    }

    public function getContact(){}

    public function getUserById($id)
    {
        $id = (int)$id;
        return $this->DBManager->findOne('SELECT * FROM users WHERE id = '.$id);
    }
    
    private function getUserByUsername($username)
    {
        $data = $this->DBManager->findOneSecure('SELECT * FROM users WHERE pseudo = :username',
                                ['username' => $username]);
        return $data;
    }
    
    public function userCheckRegister()
    {
        $_SESSION['errorMessage'] = '';

        $this->FormManager->checkRequiredField(['username', 'email', 'password', 'confirmationOfPassword', 'indic']);
        $this->FormManager->checkUniqField(['username' => 'users', 'email' => 'users']);

        $this->FormManager->checkEmail($_POST['email']);
        $this->FormManager->checkPassword($_POST['password'], $_POST['confirmationOfPassword']);

        return $this->FormManager->getArrayReturned($_SESSION['errorMessage'], 'Your inscription is successful! Welcome among us <i>'.htmlspecialchars($_POST['username']).'</i>. <br>You\'ll soon be redirected to home to confirm your inscription by logging in.');
    }

    private function transformData($data){
        if (isset($data['password']))
        {
            $data['password'] = $this->userHash($data['password']);
        }
        return $data;
    }
    
    private function userHash($pass)
    {
        return password_hash($pass, PASSWORD_BCRYPT);
    }

    public function userRegister($data, array $arrayFields)
    {
        $user = [];
        $data = $this->transformData($data);//currently useless (function with only one instruction... But allows easier improvement if in the future one want to add other
        // transformation to data before inscription in db.
        foreach ($arrayFields as $field)
        {
            $user[$field] = $data[$field];
        }
        $this->DBManager->dbInsert('users', $user, true);
        $user = $this->DBManager->getWhatHow($data['username'], 'username', 'users')[0];
        $_SESSION['currentUser']['data'] = $user;//currently useless, but could be used later to pre-fill login field or something else.
        $_SESSION['currentUser']['loggedIn'] = false;
    }
    
    public function userCheckLogin($data)
    {
        if (empty($data['username']) || empty($data['password']))
        {
            return [false, 'field empty', []];
        }
        $user = $this->getUserByUsername($data['username']);
        if ($user === false)
        {
            return [false, 'unregistered user', []];
        }

        return [password_verify($data['password'], $user['password']), 'password', $user['indic']];
    }
    
    public function userLogin($username)
    {
        $data = $this->getUserByUsername($username);
        if ($data === false)
        {
            return false;
        }
        $_SESSION['currentUser']['data'] = $data;
        $_SESSION['currentUser']['loggedIn'] = true;
        return true;
    }

    public function getAllUser()
    {
        return $this->DBManager->findAll('SELECT * FROM `users`');
    }

    public function updateData(array $data)
    {
        $data = $this->transformData($data);
        $this->DBManager->dbUpdate('users', $_SESSION['currentUser']['data']['id'], $data);
        foreach ($data as $key => $value)
        {
            $_SESSION['currentUser']['data'][$key] = $value;
        }
    }
}