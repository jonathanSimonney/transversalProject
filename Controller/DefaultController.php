<?php

namespace Controller;

use Model\AdminManager;
use Model\ProManager;
use Model\VictimManager;

class DefaultController extends BaseController
{
    private $userManager;

    public function __construct(\Twig_Environment $twig, $accessLevel, $requestMethod)
    {
        parent::__construct($twig, $accessLevel, $requestMethod);
    }

    public function homeAction()
    {
        $data = [];
        if (isset($_SESSION['currentUser']))
        {
            $data['currentUser'] = $_SESSION['currentUser']['data'];
            unset($data['currentUser']['password']);
            if ($_SESSION['currentUser']['loggedIn'])
            {
                if ($this->isAdmin())
                {
                    $this->userManager = AdminManager::getInstance();
                    $data['unregistered_user'] = $this->userManager->getUnregisteredUser();
                    $data['registered_user'] = $this->userManager->getRegisteredUser();
                    $data['logs'] = $this->userManager->getLogs();

                    echo $this->renderView('connected/admin.html.twig', $data);
                    return;
                }

                if ($_SESSION['currentUser']['data']['type'] === 'victime')
                {
                    $this->userManager = VictimManager::getInstance();
                }
                else
                {
                    $this->userManager = ProManager::getInstance();
                }

                $_SESSION['currentUser']['data']['contact'] = $this->userManager->getContact();
                $data['currentUser']['contact'] = $_SESSION['currentUser']['data']['contact'];
                echo $this->renderView('connected/home.html.twig', $data);
                return;
            }
        }
        echo $this->renderView('disconnected/home.html.twig', $data);
    }
}
