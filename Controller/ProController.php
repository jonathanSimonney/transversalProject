<?php
/**
 * Created by PhpStorm.
 * User: user
 * Date: 07/05/2017
 * Time: 12:09
 */

namespace Controller;


use Model\ProManager;

class ProController extends UserController
{
    protected $proManager;

    public function __construct(\Twig_Environment $twig, $accessLevel, $requestMethod)
    {
        UserController::__construct($twig, $accessLevel, $requestMethod);
        $this->proManager = ProManager::getInstance();
    }

    public function changeFreeSlotAction()
    {
        if ($_SESSION['currentUser']['data']['type'] === 'victime')
        {
            $this->logManager->generateAccessMessage('tried to change his number of free slot(but he is a victim, not a pro!)', 'security');
        }
        else
        {
            if ($this->formManager->checkRequiredField(['freeSlot']))
            {
                $this->proManager->changeSlot($_POST['freeSlot']);
                $this->logManager->generateAccessMessage('changed his number of slots to '.$_POST['freeSlot'], 'access');
            }
        }
    }
}