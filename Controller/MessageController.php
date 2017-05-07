<?php
/**
 * Created by PhpStorm.
 * User: user
 * Date: 28/04/2017
 * Time: 12:47
 */

namespace Controller;


use Model\MailManager;

class MessageController extends BaseController
{
    protected $mailManager;

    public function __construct(\Twig_Environment $twig, $accessLevel, $requestMethod)
    {
        parent::__construct($twig, $accessLevel, $requestMethod);
        $this->mailManager = MailManager::getInstance();
    }

    public function sendMessageAction()
    {
        $_SESSION['errorMessage'] = [];
        if ($this->formManager->checkRequiredField(['object', 'dest', 'content']) && $this->formManager->checkLengthField(['object'], 255))
        {
            if ($this->mailManager->canSendMail())
            {
                $this->mailManager->sendMail();
            }
            else
            {
                $this->logManager->generateAccessMessage('tried to send a mail to '.$_POST['dest'], 'security');
            }
        }

        if (count($_SESSION['errorMessage']) !== 0)
        {
            echo json_encode($_SESSION['errorMessage']);
        }
    }

    public function downloadPjAction(){
        if ($this->mailManager->canActOnEmail($_POST['notForUser'])){
            $this->mailManager->downloadPj($_POST['notForUser']);
        }
        else
        {
            $this->logManager->generateAccessMessage('tried to download PJ of mail '.$_POST['notForUser'], 'security');//todo add owner of file in log.
        }
    }

    public function suppressMessageAction()
    {
        if ($this->mailManager->canActOnEmail($_POST['notForUser'])){
            $this->mailManager->suppressMail($_POST['notForUser']);
        }
        else
        {
            $this->logManager->generateAccessMessage('tried to suppress mail of id '.$_POST['notForUser'], 'security');//todo add owner of file in log.
        }
    }

    public function getReceivedMessageAction()
    {
        $data['currentUser'] = $_SESSION['currentUser']['data'];
        $data['currentUser']['message'] = $this->mailManager->getAllReceivedEmail($_SESSION['currentUser']['data']['id']);
        echo $this->renderView('connected/mailList.html.twig', $data);
    }

    public function getSentMessageAction()
    {
        $data['currentUser'] = $_SESSION['currentUser']['data'];
        $data['currentUser']['message'] = $this->mailManager->getAllSentEmail($_SESSION['currentUser']['data']['id']);
        echo $this->renderView('connected/mailList.html.twig', $data);
    }
}