<?php
/**
 * Created by PhpStorm.
 * User: user
 * Date: 11/04/2017
 * Time: 14:52
 */

namespace Controller;


use Model\UserManager;

class UserController extends BaseController
{
    private $userManager;

    public function __construct(\Twig_Environment $twig, $accessLevel)
    {
        BaseController::__construct($twig, $accessLevel);
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

    public function registerAction(){
        $error = '';
        http_response_code(200);
        $arrayReturned = $this->userManager->userCheckRegister();
        if ($arrayReturned['formOk'])
        {
            $this->userManager->userRegister($_POST, ['username', 'email', 'password', 'indic']);
            //writeToLog(generateAccessMessage('created an account as '.$_POST['username']), 'access');
        }
        echo json_encode($arrayReturned);
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
        $errorMessage = '';
        if (password_verify($_POST['password'], $_SESSION['currentUser']['data']['password']))
        {
            $errorMessage = $this->formManager->checkUniqField(['username' => 'users']);
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
            $this->userManager->updateData(['username' => $_POST['username']]);
            $this->logManager->generateAccessMessage('changed his username', 'access');
        }
    }
}