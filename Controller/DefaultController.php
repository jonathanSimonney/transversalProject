<?php

namespace Controller;

use Model\DBManager;

class DefaultController extends BaseController
{
    public function homeAction()
    {
        if (array_key_exists('currentUser', $_SESSION)){
            $name = $_SESSION['currentUser']['pseudo'];
            echo $this->renderView('connected/home.html.twig', ['name' => $name]);
        }else{
            echo $this->renderView('disconnected/home.html.twig');
        }
    }
}
