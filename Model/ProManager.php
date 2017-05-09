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
        FROM users WHERE psy_id = '.$_SESSION['currentUser']['data']['id'].' OR lawyer_id = '.$_SESSION['currentUser']['data']['id']);

        return $this->makeInferiorKeyIndex($ret, 'pseudo');
    }

    public function changeSlot($slotNumber)
    {
        $this->DBManager->dbUpdate('users', $_SESSION['currentUser']['data']['id'], ['free_slot' => $slotNumber]);
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