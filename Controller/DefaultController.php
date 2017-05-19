<?php

namespace Controller;

use Model\AdminManager;
use Model\ProManager;
use Model\UserManager;
use Model\VictimManager;

class DefaultController extends BaseController
{
    private $userManager;

    public function __construct(\Twig_Environment $twig, $accessLevel, $requestMethod)
    {
        parent::__construct($twig, $accessLevel, $requestMethod);
        $this->userManager = UserManager::getInstance();
    }

    public function homeAction()//will be changed!
    {
        $data = ['loggedIn' => false];
        if (isset($_SESSION['currentUser']))
        {
            $data['currentUser'] = $_SESSION['currentUser']['data'];
            unset($data['currentUser']['password']);
            if ($_SESSION['currentUser']['loggedIn'])
            {
                $data['loggedIn'] = true;
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
        echo $this->renderView('both/home.html.twig', $data);
    }

    public function showHomePageAction()
    {
        $this->simplyShowPage('both/home.html.twig');
    }

    public function showAboutPageAction()
    {
        $this->simplyShowPage('both/about.html.twig');
    }

    public function showForumPageAction()
    {
        $this->simplyShowPage('both/forum.html.twig');
    }

    public function showContactPageAction()
    {
        $this->simplyShowPage('both/contact.html.twig');
    }

    public function showSignInAction()
    {
        $this->simplyShowPage('disconnected/modal/signIn.html.twig');
    }

    public function showSignUpAction()
    {
        $this->simplyShowPage('disconnected/modal/signUp.html.twig');
    }

    public function showProSignUpAction()
    {
        $this->simplyShowPage('disconnected/modal/proSignUp.html.twig');
    }

    public function showVictimSignUpAction()
    {
        $this->simplyShowPage('disconnected/modal/victimSignUp.html.twig');
    }

    public function getLegalNoticePageAction()
    {
        $this->simplyShowPage('both/legal.html.twig');
    }

    public function getProfessionalListAction()
    {
        $proArray = $this->userManager->getAllProfessionalData();
        $this->simplyShowPage('both/proList.html.twig', ['lawyer' => $proArray['lawyer'], 'psy' => $proArray['psy']]);
    }

    public function showDocPageAction()
    {
        $article = $_GET['article'] ?? 'what';
        $data = json_decode(file_get_contents('views/both/data/documentationData.json'), true);
        //var_dump($data);
        $data = $data[$article] ?? ['title' => 'invalid link', 'content' => 'Sorry, but it seems you have clicked on an invalid link.
         Please contact us so we can fix it as quickly as possible, giving as many details as you can on how you came here.'];
        $this->simplyShowPage('both/documentation.html.twig', $data);
    }

    public function showAccountPageAction()
    {
        $data = [];
        $data['currentUser']['contact'] = $this->getAndSetContact();
        $this->simplyShowPage('connected/account.html.twig', $data);
    }

    public function showModifAccountPageAction()
    {
        $this->simplyShowPage('connected/accountModify.html.twig');
    }

    public function showConfirmSuppressionModalAction()
    {
        $this->simplyShowPage('both/confirmSuppression.html.twig');
    }

    public function showLogPageAction()
    {
        echo $this->renderView('connected/admin/logs.html.twig');
    }

    public function getLogsAction()
    {
        $this->userManager = AdminManager::getInstance();
        echo json_encode($this->userManager->getLogs());
    }

    protected function getAndSetContact()
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
        return $_SESSION['currentUser']['data']['contact'];
    }

    protected function simplyShowPage($pagePath, $data = [])
    {
        $data['loggedIn'] = false;
        if (isset($_SESSION['currentUser']))
        {
            $data['currentUser'] = $_SESSION['currentUser']['data'];
            unset($data['currentUser']['password']);
            if ($_SESSION['currentUser']['loggedIn'])
            {
                $data['loggedIn'] = true;
                if ($this->isAdmin())
                {
                    $this->userManager = AdminManager::getInstance();
                    /*$data['unregistered_user'] = $this->userManager->getUnregisteredUser();
                    $data['registered_user'] = $this->userManager->getRegisteredUser();
                    $data['logs'] = $this->userManager->getLogs();*/

                    echo $this->renderView('connected/admin/admin.html.twig', $data);
                    return;
                }
            }
        }
        echo $this->renderView($pagePath, $data);
    }
}
