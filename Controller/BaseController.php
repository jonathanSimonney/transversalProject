<?php

namespace Controller;

use Model\FormManager;
use Model\LogManager;

abstract class BaseController
{
    private $twig;
    protected $formManager;
    protected $logManager;

    public function __construct(\Twig_Environment $twig, $accessLevel)
    {
        $this->twig = $twig;
        $this->formManager = FormManager::getInstance();
        $this->logManager = LogManager::getInstance();
        if ($accessLevel !== 'both')
        {
            $connectionStatus = $this->getConnectionStatus();
            if ($accessLevel !== $connectionStatus)
            {
                $this->logManager->generateAccessMessage('tried to '.$_GET['action'].' while being '.$connectionStatus, 'security');
                die(json_encode(['error' => 'You must be '.$accessLevel.' to access to this page.']));
            }
        }

        if ($_SERVER['REQUEST_METHOD'] !== 'POST' && get_called_class() !== 'Controller\\DefaultController')
        {
            $this->logManager->generateAccessMessage('tried to '.$_GET['action'].' without using the POST method', 'security');
            http_response_code(405);
            die(json_encode(['error' => 'wrong method']));
        }
    }
    
    protected function getTwig()
    {
        return $this->twig;
    }

    protected function renderView($template, array $data = []){
        $template = $this->getTwig()->load($template);
        return $template->render($data);
    }

    protected function getConnectionStatus()
    {
        if (isset($_SESSION['currentUser']))
        {
            if ($_SESSION['currentUser']['loggedIn'])
            {
                return 'connected';
            }
        }

        return 'disconnected';
    }
}
