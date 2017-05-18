<?php
/**
 * Created by PhpStorm.
 * User: user
 * Date: 19/04/2017
 * Time: 17:10
 */

namespace Model;


class FormManager extends BaseManager
{
    protected $DBManager;

    public function setup()
    {
        $this->DBManager = DBManager::getInstance();
    }

    protected function requiredField($name)
    {
        $noError = false;

        if (isset($_POST[$name]))
        {
            if ($_POST[$name] !== '')
            {
                $noError = true;
            }
        }

        if (!$noError)
        {
            if (preg_match('/[A-Z]{1}/', $name)===1)
            {
                $name = preg_replace('/([A-Z])/', ' $1', $name);
                $name = strtolower($name);
            }
            $_SESSION['errorMessage'][$name] = 'empty. ';
            return false;
        }
        return true;
    }

    protected function requiredLength($item, $length)
    {
        if (strlen($_POST[$item]) <= $length)
        {
            return true;
        }

        $_SESSION['errorMessage'][$item] = 'too long : max length is '.$length.' characters.';
        return false;
    }

    protected function exactLength($item, $length)
    {
        if (strlen($_POST[$item]) === $length)
        {
            return true;
        }

        $_SESSION['errorMessage'][$item] = 'length must be of exactly '.$length.' characters.';
        return false;
    }

    public function checkUpdate()
    {
        $_SESSION['errorMessage'] = '';

        $this->checkRequiredField(['pseudo', 'email', 'indic', 'location']);
        $this->checkMaxLengthField(['pseudo', 'email', 'newPassword', 'confirmationOfPassword', 'indic'], 255);

        if ($_POST['pseudo'] !== $_SESSION['currentUser']['data']['pseudo'])
        {
            $this->checkUniqField(['pseudo' => 'users']);
            $this->checkUniqField(['pseudo' => 'unregistered_users']);
        }
        if ($_POST['email'] !== $_SESSION['currentUser']['data']['email'])
        {
            $this->checkUniqField(['email' => 'users']);
            $this->checkUniqField(['email' => 'unregistered_users']);
        }

        $this->checkEmail($_POST['email']);
        $this->checkExactLength(['location'], 5);//todo change with much more precise check!

        $temp = $_SESSION['errorMessage'];

        if (!$this->checkEmptyField(['newPassword', 'confirmationOfPassword']))
        {
            $this->checkPassword($_POST['newPassword'], $_POST['confirmationOfPassword']);
        }
        else
        {
            $_POST['newPassword'] = $_POST['oldPassword'];
            $_SESSION['errorMessage'] = $temp;
        }

        if ($_SESSION['currentUser']['data']['type'] !== 'victime' )
        {
            if ($this->checkRequiredField(['freeSlot']) && $_POST['freeSlot'] < 0)
            {
                $_SESSION['errorMessage']['freeSlot'] = 'must be non negative number!';
            }
        }

        return $this->getArrayReturned($_SESSION['errorMessage'], 'Your change have been registered.');
    }

    public function checkEmptyField(array $arrayEmptyField)
    {
        $ret = true;
        foreach ($arrayEmptyField as $item)
        {
            if ($this->requiredField($item) === true && $ret === true)//DO NOT CHANGE!!!
            {
                $ret = false;
            }
        }

        return $ret;
    }

    public function checkRequiredField(array $arrayRequiredField)
    {
        $ret = true;
        foreach ($arrayRequiredField as $item)
        {
            if ($this->requiredField($item) === false && $ret === true)//DO NOT CHANGE!!!
            {
                $ret = false;
            }
        }

        return $ret;
    }

    public function checkMaxLengthField(array $arrayLengthField, $length)//todo factorise these 3 functions, too much similitudes (higher-order functions!)
    {
        $ret = true;
        foreach ($arrayLengthField as $item)
        {
            if ($this->requiredLength($item, $length) === false && $ret === true)
            {
                $ret = false;
            }
        }

        return $ret;
    }

    public function checkExactLength(array $arrayLengthField, $length)
    {
        $ret = true;
        foreach ($arrayLengthField as $item)
        {
            if ($this->exactLength($item, $length) === false && $ret === true)
            {
                $ret = false;
            }
        }

        return $ret;
    }

    private function isNotAlreadyInDb($needle, $column, $table)
    {
        if ($this->DBManager->getWhatHow($needle, $column, $table))
        {
            $_SESSION['errorMessage'][$column] = 'Already taken in '.$table.'. ';
            return false;
        }

        return true;
    }

    public function checkUniqField(array $objectUniqField)
    {
        $ret = '';
        foreach ($objectUniqField as $item => $table)
        {
            if ($this->isNotAlreadyInDb($_POST[$item], $item, $table) !== true)
            {
                $ret = $ret === '' ? [] : $ret;
                $ret[$item] = 'already in Db';
            }
        }
        return $ret;
    }

    public function checkEmail($potentialEmail)
    {
        $re = '/^(([^<>()\[\]\\.,;:\s@"]+(\.[^<>()\[\]\\.,;:\s@"]+)*)|(".+"))@((\[[0-9]{1,3}\.[0-9]{1,3}\.[0-9]{1,3}\.[0-9]{1,3}])|(([a-zA-Z\-0-9]+\.)+[a-zA-Z]{2,}))$/';
        if (preg_match($re, $potentialEmail) !== 1)
        {
            $_SESSION['errorMessage']['email'] = 'Invalid adress. ';
        }
    }

    public function checkGender($potentialGender)
    {
        if ($potentialGender !== 'man' && $potentialGender !== 'woman')
        {
            $_SESSION['errorMessage']['gender'] = 'Invalid.';
        }
    }

    public function checkPassword($password, $confirmationPassword)
    {
        if ($password !== $confirmationPassword)
        {
            $_SESSION['errorMessage']['password'] = 'You must write the same thing in the fields password and confirmation of password. ';//sorry for the non concatenation
            return $_SESSION['errorMessage']['password'];
        }//BONUS : add a security level of password

        if (strlen($password) < 8)
        {
            $_SESSION['errorMessage']['password'] = 'Your password must do at least 8 characters. ';//same as line before.
            return $_SESSION['errorMessage']['password'];
        }

        return '';
    }

    public function getArrayReturned($errorMessage, $successMessage)
    {
        $arrayReturned = [
            $errorMessage,
            'formOk' => false
        ];
        if ($errorMessage === '')
        {
            $arrayReturned = [
                $successMessage,
                'formOk' => true
            ];
        }

        return $arrayReturned;
    }
}