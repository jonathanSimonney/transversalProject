<?php

namespace Controller;

use Model\MailManager;
use Model\ProManager;
use Model\VictimManager;

class DefaultController extends BaseController
{
    private $userManager;
    protected $mailManager;

    public function __construct(\Twig_Environment $twig, $accessLevel, $requestMethod)
    {
        parent::__construct($twig, $accessLevel, $requestMethod);
        $this->mailManager = MailManager::getInstance();
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

    public function getReceivedMessageAction()
    {
        $data['currentUser'] = $_SESSION['currentUser']['data'];
        $data['currentUser']['message'] = $this->mailManager->getAllReceivedEmail($_SESSION['currentUser']['data']['id']);
        echo $this->renderView('connected/mailList.html.twig', $data);
    }
}
