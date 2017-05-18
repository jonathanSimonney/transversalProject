<?php
/**
 * Created by PhpStorm.
 * User: user
 * Date: 28/04/2017
 * Time: 12:47
 */

namespace Controller;


use Model\MailManager;

class MessageController extends DefaultController
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
        if ($this->formManager->checkRequiredField(['object', 'dest', 'content']) && $this->formManager->checkMaxLengthField(['object'], 255))
        {
            if ($this->mailManager->canSendMail())
            {
                $this->mailManager->userSendMail();
            }
            else
            {
                $this->logManager->generateAccessMessage('tried to send a mail to '.$_POST['dest'], 'security');
                $_SESSION['errorMessage']['dest'] = 'This person is not in your contact!';
            }
        }

        if (count($_SESSION['errorMessage']) !== 0)
        {
            echo json_encode(['formOk' => false, 'error' => $_SESSION['errorMessage']]);
        }
        else
        {
            echo json_encode(['formOk' => true]);
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

    public function showInboxAction()
    {
        $data = [];
        $data['currentUser']['contact'] = $this->getAndSetContact();
        $data['message'] = $this->mailManager->getAllReceivedEmail($_SESSION['currentUser']['data']['id']);
        $this->simplyShowPage('connected/inbox.html.twig', $data);
    }

    public function sendMessageFormAction()
    {
        $this->simplyShowPage('connected/sendMessageForm.html.twig');
    }

    /*public function getReceivedMessageAction()
    {
        $data['currentUser'] = $_SESSION['currentUser']['data'];
        $data['currentUser']['message'] = $this->mailManager->getAllReceivedEmail($_SESSION['currentUser']['data']['id']);
        $data['type'] = 'received';
        echo $this->renderView('connected/mailList.html.twig', $data);
    }*/

    public function getSentMessageAction()
    {
        $data['currentUser'] = $_SESSION['currentUser']['data'];
        $data['currentUser']['message'] = $this->mailManager->getAllSentEmail($_SESSION['currentUser']['data']['id']);
        $data['type'] = 'sent';
        echo $this->renderView('connected/mailList.html.twig', $data);
    }
}