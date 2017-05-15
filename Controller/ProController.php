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
            $_SESSION['errorMessage'] = [];

            if ($this->formManager->checkRequiredField(['freeSlot']))
            {
                $this->proManager->changeSlot($_POST['freeSlot']);
                $this->logManager->generateAccessMessage('changed his number of slots to '.$_POST['freeSlot'], 'access');
            }

            if (count($_SESSION['errorMessage']) !== 0)
            {
                echo json_encode(['error' => $_SESSION['errorMessage']]);
            }
        }
    }

    public function findProfessionalAction()
    {
        if ($_SESSION['currentUser']['data']['type'] !== 'victime')
        {
            $this->logManager->generateAccessMessage('tried to contact a pro (but he is also a pro!)', 'security');
        }
        else
        {
            $_SESSION['errorMessage'] = [];

            if ($this->formManager->checkRequiredField(['professionalType', 'autoMatch']))
            {
                if ((int)$_SESSION['currentUser']['data'][$_POST['professionalType'].'_id'] !== 0)
                {
                    $_SESSION['errorMessage']['other'] = 'You already have a professional of this type. Please suppress it before you connect with a new professional.';
                }
                else
                {
                    if ($_POST['autoMatch'] === 'true')
                    {
                        $pro = $this->proManager->findAutoProfessional($_POST['professionalType']);
                    }
                    elseif ($this->formManager->checkRequiredField(['username']))
                    {
                        $pro = $this->proManager->findProfessionalByName($_POST['professionalType'], $_POST['username']);
                    }
                }

                if (count($_SESSION['errorMessage']) === 0)
                {
                    $this->proManager->takeProfessional($pro);
                    $this->logManager->generateAccessMessage('took pro of pseudo '.$pro['pseudo'].' and of id '.$pro['id'], 'access');
                }
            }

            if (count($_SESSION['errorMessage']) !== 0)
            {
                echo json_encode(['error' => $_SESSION['errorMessage']]);
            }
        }
    }

    public function abandonProfessionalAction()
    {
        if ($_SESSION['currentUser']['data']['type'] !== 'victime')
        {
            $this->logManager->generateAccessMessage('tried to abandon a pro (but he is also a pro!)', 'security');
        }
        else
        {
            $_SESSION['errorMessage'] = [];
            if ($this->formManager->checkRequiredField(['professionalType']))
            {
                if ($this->proManager->abandonPro($_POST['professionalType'], $_SESSION['currentUser']['data']['id']) === true)
                {
                    if ($_POST['reason'] !== '')
                    {
                        $this->logManager->generateAccessMessage('gave the following reason for abandoning his '.$_POST['professionalType']." : \n".$_POST['reason'], 'message');
                    }
                    $this->logManager->generateAccessMessage('abandoned his '.$_POST['professionalType'], 'access');
                }
            }

            if (count($_SESSION['errorMessage']) !== 0)//too much repetitive, should do a function for it. No time though.
            {
                echo json_encode(['error' => $_SESSION['errorMessage']]);
            }
        }
    }

    public function suppressCandidateAction()
    {
        if (!$this->isAdmin())
        {
            $this->logManager->generateAccessMessage('tried to reject a candidature (but he is not admin).', 'security');
            die();
        }
        $user = $this->proManager->getUnregisteredUserById($_POST['id']);


        $this->proManager->suppressCandidature($_POST['id']);
        $this->logManager->generateAccessMessage('suppressed the account of '.$user['pseudo'].' of id '.$_POST['id'].' for the following reason : '.$_POST['message'], 'access');
        $this->userManager->sendMail($user['email'], 'suppression de votre compte', 'Votre compte a été supprimé pour la raison suivante :<br> '.$_POST['message'],
            "Votre compte a été supprimé pour la raison suivante :\r\n ".$_POST['message']);

        echo json_encode('removal complete!');
    }
}