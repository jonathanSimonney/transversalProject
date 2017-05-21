<?php
/**
 * Created by PhpStorm.
 * User: user
 * Date: 28/04/2017
 * Time: 12:47
 */

namespace Controller;


use Model\MailManager;
use Model\UserManager;

class MessageController extends DefaultController
{
    protected $mailManager;
    protected $userManager;

    public function __construct(\Twig_Environment $twig, $accessLevel, $requestMethod)
    {
        parent::__construct($twig, $accessLevel, $requestMethod);
        $this->mailManager = MailManager::getInstance();
        $this->userManager = UserManager::getInstance();
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
        if ($this->mailManager->canActOnEmail($_GET['notForUser'])){
            $this->mailManager->downloadPj($_GET['notForUser']);
        }
        else
        {
            $this->logManager->generateAccessMessage('tried to download PJ of mail '.$_GET['notForUser'], 'security');//todo add owner of file in log.
        }
    }

    public function suppressMessageAction()
    {
        if ($this->mailManager->canActOnEmail($_POST['notForUser'])){
            $this->mailManager->suppressMail($_POST['notForUser']);
            echo json_encode('suppression successful!');
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
        $data['message'] = $this->mailManager->getAllReceivedEmail($_SESSION['currentUser']['data']['id']);//todo modify, no need to ask that much data!
        $data['receptorOrSender'] = 'sender';
        $this->simplyShowPage('connected/inbox.html.twig', $data);
    }

    public function sendMessageFormAction()
    {
        $this->simplyShowPage('connected/sendMessageForm.html.twig', $_GET);
    }

    public function showEmailAction()
    {
        $mail = $this->mailManager->getEmailById($_GET['id']);
        if ($_SESSION['currentUser']['data']['id'] !== $mail['receptor_id'] && $_SESSION['currentUser']['data']['id'] !== $mail['sender_id'])
        {
            $this->logManager->generateAccessMessage('tried to read the mail of id '.$_GET['id'].', but he did not send it nor received it.', 'security');
        }
        else
        {
            $mail['content'] = $this->mailManager->formatOutputFileContent(file_get_contents('mail/'.$mail['id'].'/content.txt'));
            $mail['receptorOrSender'] = $_SESSION['currentUser']['data']['id'] === $mail['sender_id'] ? 'receptor' : 'sender';
            $mail['person'] = $this->userManager->getUserById($mail[$mail['receptorOrSender'].'_id'], '`id`, `pseudo`, `location`, `type`');
            echo $this->renderView('connected/singleMail.html.twig', $mail);
        }
    }

    public function showSentMessageAction()
    {
        $data = [];
        $data['currentUser']['contact'] = $this->getAndSetContact();
        $data['message'] = $this->mailManager->getAllSentEmail($_SESSION['currentUser']['data']['id']);
        $data['receptorOrSender'] = 'receptor';
        $this->simplyShowPage('connected/inbox.html.twig', $data);
    }
}