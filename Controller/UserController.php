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
    public function connectAction(){
        $this->userManager = UserManager::getInstance();
        if ($this->userManager->userCheckLogin($_POST)){
            $this->userManager->userLogin($_POST['username']);

            header('Location: ?action=home');
            exit();
        }

        $this->redirect('home');
    }

    public function logoutAction(){
        session_destroy();
        $this->redirect('home');
    }
}