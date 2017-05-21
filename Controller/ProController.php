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
            $this->logManager->generateAccessMessage('tried to change his number of free slot(but he is a victime, not a pro!)', 'security');
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

    public function findAutoProfessionalAction()
    {
        if ($_SESSION['currentUser']['data']['type'] !== 'victime')
        {
            $this->logManager->generateAccessMessage('tried to contact a pro (but he is also a pro!)', 'security');
        }
        else
        {
            $_SESSION['errorMessage'] = [];

            if ($this->formManager->checkRequiredField(['professionalType']))
            {
                if ((int)$_SESSION['currentUser']['data'][$_POST['professionalType'].'_id'] !== 0)
                {
                    $_SESSION['errorMessage']['other'] = 'You already have a professional of this type. Please suppress it before you connect with a new professional.';
                }
                else
                {
                    $pro = $this->proManager->findAutoProfessional($_POST['professionalType']);
                }

                if (count($_SESSION['errorMessage']) === 0)
                {
                    $this->proManager->takeProfessional($pro);
                    $this->logManager->generateAccessMessage('took pro of pseudo '.$pro['pseudo'].' and of id '.$pro['id'], 'access');
                    echo json_encode(['pseudo' => $pro['pseudo'], 'gender' => $pro['gender'], 'location' => $pro['location']]);
                }
            }

            if (count($_SESSION['errorMessage']) !== 0)
            {
                echo json_encode(['error' => $_SESSION['errorMessage']]);
            }
        }
    }

    public function findUsernameProfessionalAction()
    {
        if ($_SESSION['currentUser']['data']['type'] !== 'victime')
        {
            $this->logManager->generateAccessMessage('tried to contact a pro (but he is also a pro!)', 'security');
        }
        else
        {
            $_SESSION['errorMessage'] = [];

            if ($this->formManager->checkRequiredField(['username']))
            {
                $potentialPro = $this->proManager->findProfessionalByName($_POST['username']);
                if ($potentialPro !== null && (int)$_SESSION['currentUser']['data'][$potentialPro['type'].'_id'] !== 0)
                {
                    $_SESSION['errorMessage']['other'] = 'You already have a professional of this type. Please suppress it before you connect with a new professional.';
                }
            }

            if (count($_SESSION['errorMessage']) === 0)
            {
                $this->proManager->takeProfessional($potentialPro);
                $this->logManager->generateAccessMessage('took pro of pseudo '.$potentialPro['pseudo'].' and of id '.$potentialPro['id'], 'access');
                echo json_encode(['pseudo' => $potentialPro['pseudo'], 'gender' => $potentialPro['gender'], 'location' => $potentialPro['location']]);
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
                http_response_code(403);
                echo json_encode(['error' => $_SESSION['errorMessage']]);
            }
            else
            {
                echo json_encode('this professional is no more in your contact!');
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