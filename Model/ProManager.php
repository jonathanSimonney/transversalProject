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
    protected $logManager;

    public function setup()
    {
        parent::setup();
        $this->logManager = LogManager::getInstance();
    }

    public function getContact()
    {
        $ret = $this->DBManager->findAll('SELECT `id`, `pseudo`, `location`, `type`
        FROM users WHERE psy_id = '.$_SESSION['currentUser']['data']['id'].' OR lawyer_id = '.$_SESSION['currentUser']['data']['id']);

        return $this->makeInferiorKeyIndex($ret, 'pseudo');
    }

    public function changeSlot($slotNumber)
    {
        if ($slotNumber >= 0)
        {
            $this->DBManager->dbUpdate('users', $_SESSION['currentUser']['data']['id'], ['free_slot' => $slotNumber]);
            $_SESSION['currentUser']['data']['free_slot'] = $slotNumber;
        }
        else
        {
            $_SESSION['errorMessage']['freeSlot'] = 'must be non negative number!';
        }
    }

    public function findAutoProfessional($type)
    {
        $data = $this->DBManager->findAllSecure('SELECT * FROM users WHERE type = :type AND free_slot != 0', ['type' => $type]);
        $locationCorresponds = false;
        $proArray = [];
        foreach ($data as $pro)
        {
            if ($pro['location'] === $_SESSION['currentUser']['data']['location'])
            {
                $locationCorresponds = true;
                $proArray[] = $pro;
            }
        }

        if (!$locationCorresponds)
        {
            $proArray = $data;
        }

        return $this->getProWithMinimumUserConnected($proArray);
    }

    public function findProfessionalByName($type, $name)
    {
        $pro = $this->DBManager->findOneSecure('SELECT * FROM users WHERE pseudo = :pseudo AND type = :type', ['pseudo' => $name, 'type' => $type]);
        if($pro === false)
        {
            $_SESSION['errorMessage']['other'] = 'No professional of this name and type exists. Sorry.';
            return;
        }

        if ((int)$pro['free_slot'] === 0)
        {
            $_SESSION['errorMessage']['other'] = 'Chosen professional does not have any free space. Sorry.';
            return;
        }

        return $pro;
    }

    public function takeProfessional($proData)
    {
        $this->DBManager->dbUpdate('users', $_SESSION['currentUser']['data']['id'], [$proData['type'].'_id' => $proData['id']]);
        $this->DBManager->dbUpdate('users', $proData['id'], ['free_slot' => $proData['free_slot'] - 1]);
        $_SESSION['currentUser']['data'][$proData['type'].'_id'] = $proData['id'];
        $_SESSION['currentUser']['data']['contact'][$proData['pseudo']] =[
            'id' => $proData['id'], 'pseudo' => $proData['pseudo'], 'location' => $proData['location'], 'type' => $proData['type']
        ];
    }

    public function abandonPro($proType, $victimId)
    {
        $pro = $this->getProPseudoFromType($proType);
        if ($pro === false)
        {
            $_SESSION['errorMessage']['proType'] = 'You don\'t have a pro of this type linked to you!';
            return false;
        }

        $this->DBManager->dbUpdate('users', $victimId, [$proType.'_id' => 0]);
        $_SESSION['currentUser']['data'][$proType.'_id'] = 0;
        unset($_SESSION['currentUser']['data']['contact'][$pro['pseudo']]);
        return true;
    }

    public function userRegister()
    {
        parent::userRegisterWithParams($_POST, ['pseudo', 'email', 'password', 'indic', 'type', 'location', 'free_slot']);

        $suAdress = $this->DBManager->findOne('SELECT email FROM users WHERE type = \'admin\'')['email'];

        $this->sendMail($suAdress, 'professional inscription',
            'A professional just created an account on bull.e. Please follow <a href="#">this link</a> to check his informations.', 'A professional just created an account on bull.e');
    }

    public function userCheckRegister()
    {
        $arrayReturned = parent::userCheckRegister();
        if (isset($_POST['free_slot']))
        {
            if ($_POST['free_slot'] < 0)
            {
                $arrayReturned['formOk'] = false;
                $arrayReturned[0]['free_slot'] = 'can\'t be negative';
            }
        }

        if ($_POST['type'] !== 'lawyer' && $_POST['type'] !== 'psy')
        {
            var_dump($_POST['type'], $_POST['type'] !== 'lawyer' && $_POST['type'] !== 'psy');
            $arrayReturned['formOk'] = false;
            $this->logManager->generateAccessMessage('tried to give the type '.$_POST['type'], 'security');
        }
        return $arrayReturned;
    }

    /********
     * protected functions begin here!
     */

    protected function getProPseudoFromType($proType)
    {
        foreach ($_SESSION['currentUser']['data']['contact'] as $contact)
        {
            if ($contact['type'] === $proType )
            {
                return $contact;
            }
        }

        return false;
    }

    protected function getProWithMinimumUserConnected($proArray)
    {
        if (count($proArray) === 0)
        {
            $_SESSION['errorMessage']['other'] = 'No professional of this type are available. Sorry.';
            return null;
        }

        $first = true;

        foreach ($proArray as $key => $pro)
        {
            $pro['numberLinkedUser'] = count($this->DBManager->findAllSecure('SELECT * FROM users WHERE '.$pro['type'].'_id = :id', ['id' => $_SESSION['currentUser']['data']['id']]));
            if ($first)
            {
                $first = false;
                $finalPro = $pro;
            }
            else
            {
                if ($pro['numberLinkedUser'] < $finalPro['numberLinkedUser'])
                {
                    $finalPro = $pro;
                }
            }
        }

        return $finalPro;
    }
}