<?php
/**
 * Created by PhpStorm.
 * User: user
 * Date: 11/04/2017
 * Time: 14:52
 */

namespace Controller;


use Model\AdminManager;
use Model\ProManager;
use Model\UserManager;
use Model\VictimManager;

class UserController extends BaseController
{
    protected $userManager;

    public function __construct(\Twig_Environment $twig, $accessLevel, $requestMethod)
    {
        BaseController::__construct($twig, $accessLevel, $requestMethod);
        $this->userManager = UserManager::getInstance();
    }


    public function loginAction(){//todo allow login with email. Make forgotten password option. Prevent if caps lock enclenched.
        $state = $this->userManager->userCheckLogin($_POST);
        if ($state[0])
        {
            $this->userManager->userLogin($_POST['username']);
        }

        echo json_encode([
            'state'  => (bool)$state[0],
            'reason' => $state[1],
            'data'   => $state[2]
        ]);
        http_response_code(200);
    }

    public function logoutAction()
    {
        session_destroy();
    }

    //todo change two functions following : far too much similitudes!

    public function changeDataAction()
    {
        $errorMessage = [];
        if (password_verify($_POST['oldPassword'], $_SESSION['currentUser']['data']['password']))
        {
            $_SESSION['errorMessage']['password'] = '';
            $errorMessage = $this->formManager->checkUpdate();
        }
        else
        {
            $errorMessage[0]['oldPassword'] = 'wrong password. Indic is '.$_SESSION['currentUser']['data']['indic'];
            $errorMessage['formOk'] = false;
        }

        if (!$errorMessage['formOk'])
        {
            echo json_encode(['invalid', 'reason' => $errorMessage[0]]);
        }
        else
        {
            $this->userManager->updateData(['pseudo' => $_POST['pseudo'], 'password' => $_POST['newPassword'], 'indic' => $_POST['indic'], 'email' => $_POST['email'], 'location' => $_POST['location']]);
            $this->logManager->generateAccessMessage('changed his data', 'access');
        }
    }

    protected function registerAction(){
        $error = '';
        http_response_code(200);
        $arrayReturned = $this->userManager->userCheckRegister();
        if ($arrayReturned['formOk'])
        {
            $this->userManager->userRegister();
            $this->logManager->generateAccessMessage('created an account as '.$_POST['pseudo'], 'access');
        }
        echo json_encode($arrayReturned);
    }

    public function professionalInscriptionAction()
    {
        $this->userManager = ProManager::getInstance();
        $this->registerAction();
    }

    public function victimInscriptionAction()
    {
        $this->userManager = VictimManager::getInstance();
        $this->registerAction();
    }

    public function activateAccountAction()
    {
        if ($this->isAdmin())
        {
            $this->userManager = AdminManager::getInstance();
            $this->userManager->activateAccount($_POST['id']);
            $this->logManager->generateAccessMessage('activated account of professional ', 'access');
        }
        else
        {
            $this->logManager->generateAccessMessage('tried to activate an account, but he is not admin', 'security');
        }
    }

    public function suppressAccountAction()
    {
        if ($this->isAdmin()){
            $user = $this->userManager->getUserById($_POST['id']);
            if ($user['type'] === 'admin')
            {
                echo json_encode(['error' => 'you can\'t suppress another admin account']);
            }
            else
            {
                $temp = $_SESSION;
                $_SESSION['currentUser']['data'] = ['type' => $user['type'], 'pseudo' => $user['pseudo'], 'id' => $_POST['id']
                    , 'lawyer_id' => $user['lawyer_id'], 'psy_id' => $user['psy_id']];
                if ($user['type'] === 'victime')
                {
                    $this->userManager = VictimManager::getInstance();
                }
                else
                {
                    $this->userManager = ProManager::getInstance();
                }
                $_SESSION['currentUser']['data']['contact'] = $this->userManager->getContact();
                $this->userManager->suppressAccount($_POST['id']);
                $_SESSION = $temp;
                $this->logManager->generateAccessMessage('suppressed the account of '.$user['pseudo'].' of id '.$_POST['id'].' for the following reason : '.$_POST['message'], 'access');
                $this->userManager->sendMail($user['email'], 'suppression de votre compte', 'Votre compte a été supprimé pour la raison suivante :<br> '.$_POST['message'],
                    "Votre compte a été supprimé pour la raison suivante :\r\n ".$_POST['message']);

                echo json_encode('removal complete!');
            }
        }
        else
        {
            $this->userManager->suppressAccount($_SESSION['currentUser']['data']['id']);
            $this->logManager->generateAccessMessage('suppressed his account.', 'access');
            session_destroy();
        }
    }
}