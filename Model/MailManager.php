<?php
/**
 * Created by PhpStorm.
 * User: user
 * Date: 28/04/2017
 * Time: 12:53
 */

namespace Model;


class MailManager extends BaseManager
{
    protected $DBManager;
    protected $formManager;
    protected $userManager;
    protected $logManager;

    public function setup()
    {
        $this->DBManager = DBManager::getInstance();
        $this->formManager = FormManager::getInstance();
        $this->userManager = UserManager::getInstance();
        $this->logManager = LogManager::getInstance();
    }

    public function canSendMail()
    {
        return isset($_SESSION['currentUser']['data']['contact'][$_POST['dest']]);
    }

    public function sendMail()
    {
        $filename = $_FILES['PJ']['name'];
        if (strlen($filename) > 255)
        {
            $_SESSION['errorMessage']['PJ'] = 'too long : max length for filename is 255 characters.';
        }else{
            $receptorId = $this->DBManager->findOneSecure('SELECT * FROM users WHERE pseudo = \''. $_POST['dest'].'\'')['id'];
            //var_dump($this->DBManager->findOneSecure('SELECT * FROM users WHERE pseudo = \''. $_POST['dest'].'\''), $_POST['dest']);
            $senderId = $_SESSION['currentUser']['data']['id'];
            $this->DBManager->dbInsert('mail', [
                'object'      => $_POST['object'],
                'sender_id'   => $senderId,
                'receptor_id' => $receptorId,
                'PJ'          => $filename
            ], true);
            mkdir('mail/'.$this->DBManager->getLastInsertedId());
            $content = $this->formatInputFileContent($_POST['content']);
            file_put_contents('mail/'.$this->DBManager->getLastInsertedId().'/content.txt', $content);
            if ($filename !== '')
            {
                move_uploaded_file($_FILES['PJ']['tmp_name'], 'mail/'.$this->DBManager->getLastInsertedId().'/'.$filename);//('articles/'.$this->DBManager->getLastInsertedId().$filename, $content);
            }
            $this->logManager->generateAccessMessage('sent a mail to user '.$_POST['dest'].' of id '.$receptorId, 'access');
        }
    }

    public function getAllReceivedEmail($id)
    {
        $ret = $this->DBManager->findAllSecure('SELECT * FROM mail WHERE receptor_id = '.$id);
        if ($ret !== false)
        {
            foreach ($ret as &$mail)
            {
                $mail['sender'] = $this->DBManager->findOne('SELECT `id`, `pseudo`, `location`, `type` FROM users WHERE id = '.$mail['sender_id']);
                $mail['content'] = $this->formatOutputFileContent(file_get_contents('mail/'.$mail['id'].'/content.txt'));
            }

            return $ret;
        }
        return null;
    }

    protected function formatOutputFileContent($fileContent)
    {
        $ret = preg_replace('/\n/','<br>',$fileContent);
        return preg_replace('/\s/','&nbsp;',$ret);
    }

    protected function formatInputFileContent($fileContent)
    {
        $ret = preg_replace('/&/','&amp;',$fileContent);
        return preg_replace('/</','&lt;',$ret);
    }
}