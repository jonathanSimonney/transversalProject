<?php
/**
 * Created by PhpStorm.
 * User: user
 * Date: 11/04/2017
 * Time: 14:52
 */

namespace Controller;


use Model\ProManager;
use Model\UserManager;
use Model\VictimManager;

class UserController extends BaseController
{
    private $userManager;

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

    public function changePasswordAction()
    {
        $errorMessage = '';
        if (password_verify($_POST['oldPassword'], $_SESSION['currentUser']['data']['password']))
        {
            $_SESSION['errorMessage']['password'] = '';
            $errorMessage = $this->formManager->checkPassword($_POST['newPassword'], $_POST['confirmPassword']);
        }
        else
        {
            $errorMessage = 'wrong password';
        }

        if ($errorMessage !== '')
        {
            echo json_encode(['invalid', 'reason' => $errorMessage]);
        }
        else
        {
            $this->userManager->updateData(['password' => $_POST['newPassword'], 'indic' => $_POST['indic']]);
            $this->logManager->generateAccessMessage('changed his password', 'access');
        }
    }

    public function changeUsernameAction()
    {
        if (password_verify($_POST['password'], $_SESSION['currentUser']['data']['password']))
        {
            $errorMessage = $this->formManager->checkUniqField(['pseudo' => 'users']);
        }
        else
        {
            $errorMessage = 'wrong password';
        }

        if ($errorMessage !== '')
        {
            echo json_encode(['invalid', 'reason' => $errorMessage]);
        }
        else{
            $this->userManager->updateData(['pseudo' => $_POST['pseudo']]);
            $this->logManager->generateAccessMessage('changed his username to '.$_POST['pseudo'], 'access');
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
}