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

    public function setup()
    {
        $this->DBManager = DBManager::getInstance();
        $this->formManager = FormManager::getInstance();
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
            file_put_contents('mail/'.$this->DBManager->getLastInsertedId().'/content.txt', $_POST['content']);
            if ($filename !== '')
            {
                move_uploaded_file($_FILES['PJ']['tmp_name'], 'mail/'.$this->DBManager->getLastInsertedId().'/'.$filename);//('articles/'.$this->DBManager->getLastInsertedId().$filename, $content);
            }
        }
    }
}