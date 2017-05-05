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

    public function __construct(\Twig_Environment $twig, $accessLevel)
    {
        parent::__construct($twig, $accessLevel);
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
        if ($this->mailManager->canDownloadPj($_POST['mailId'])){
            $this->mailManager->downloadPj($_POST['mailId']);
        }
        else
        {
            $this->logManager->generateAccessMessage('tried to download PJ of mail '.$_POST['mailId'], 'security');//todo add owner of file in log.
        }
    }
}