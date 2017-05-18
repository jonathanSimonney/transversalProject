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

    public function getEmailById($id, string $fields = '*')
    {
        return $this->DBManager->findOneSecure('SELECT '.$fields.' FROM mail WHERE id = :id', ['id' => $id]);
    }

    public function canSendMail()
    {
        return isset($_SESSION['currentUser']['data']['contact'][$_POST['dest']]);
    }

    public function userSendMail()
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
        $ret = $this->DBManager->findAllSecure('SELECT * FROM mail WHERE receptor_id = :id AND suppression_status != :id', ['id' => $id]);
        if ($ret !== false)
        {
            foreach ($ret as &$mail)
            {
                $mail['sender'] = $this->DBManager->findOne('SELECT `id`, `pseudo`, `location`, `type` FROM users WHERE id = '.$mail['sender_id']);
                $mail['receptor'] = $_SESSION['currentUser']['data'];
                $mail['content'] = $this->formatOutputFileContent(file_get_contents('mail/'.$mail['id'].'/content.txt'));
                $mail['shortObject'] = $this->shortenData($mail['object'], 50);
            }

            return $ret;
        }
        return [];
    }

    public function getAllSentEmail($id)
    {
        $ret = $this->DBManager->findAllSecure('SELECT * FROM mail WHERE sender_id = :id AND suppression_status != :id', ['id' => $id]);
        if ($ret !== false)
        {
            foreach ($ret as &$mail)
            {
                $mail['sender'] = $_SESSION['currentUser']['data'];
                $mail['receptor'] = $this->DBManager->findOne('SELECT `id`, `pseudo`, `location`, `type` FROM users WHERE id = '.$mail['receptor_id']);
                $mail['content'] = $this->formatOutputFileContent(file_get_contents('mail/'.$mail['id'].'/content.txt'));
                $mail['shortObject'] = $this->shortenData($mail['object'], 50);
            }

            return $ret;
        }
        return [];
    }

    public function downloadPj($idMail)
    {
        $pjName = $this->DBManager->findOneSecure('SELECT PJ FROM mail WHERE id = :id', ['id' => $idMail])['PJ'];
        $this->downloadFile('mail/'.$idMail.'/'.$pjName, $pjName);
        //var_dump('PJ downloaded!');
        $this->logManager->generateAccessMessage('downloaded pj '.$pjName.', in mail of id '.$idMail, 'access');
    }

    public function canActOnEmail($mailId)
    {
        $arrayEmail = $this->getAllEmail($_SESSION['currentUser']['data']['id']);
        if ($arrayEmail === null)
        {
            return false;
        }
        foreach ($arrayEmail as $mail)
        {
            if ($mail['id'] === $mailId)
            {
                return true;
            }
        }
        return false;
    }

    public function suppressMail($mailId)
    {
        $suppressionStatus = $this->DBManager->findOneSecure('SELECT suppression_status FROM mail WHERE id = :id', ['id' => $mailId])['suppression_status'];
        if ($suppressionStatus !== 'none')
        {
            $this->delTree('mail/'.$mailId);
            $this->DBManager->dbSuppress('mail', $mailId);
        }
        else
        {
            $this->DBManager->dbUpdate('mail', $mailId, ['suppression_status' => $_SESSION['currentUser']['data']['id']]);
        }
        $this->logManager->generateAccessMessage('suppressed his mail of id '.$mailId, 'access');
    }

    /*****************
     * end of public functions!
     * **************/

    protected function getAllEmail($userId)
    {
        $received = $this->getAllReceivedEmail($userId);
        $sent = $this->getAllSentEmail($userId);
        return array_merge($received, $sent);
    }

    public function formatOutputFileContent($fileContent)//todo move with public function, and REFACTORISE FOR BETTER ORGANISATION! SUPPRESS USELESS FUNCTIONS, AND SO ON!
    {
        $ret = preg_replace('/\n/','<br>',$fileContent);
        return preg_replace('/\s/','&nbsp;',$ret);
    }

    protected function formatInputFileContent($fileContent)
    {
        $ret = preg_replace('/&/','&amp;',$fileContent);
        return preg_replace('/</','&lt;',$ret);
    }

    protected function getFileType($filename)
    {
        $type = '';
        if (!empty($filename)){
            preg_match('/\.[0-9a-z]+$/', $filename, $cor);
            $type = $cor[0];
        }

        $type = str_replace('.', '', $type);
        return $type;
    }

    protected function downloadFile($filePath, $fileName)
    {
        // Specify file path.

        // Getting file extension.
        $ext = $this->getFileType($fileName);
        // For Gecko browsers
        header('Content-Transfer-Encoding: binary');
        header('Last-Modified: ' . gmdate('D, d M Y H:i:s', filemtime(str_replace($fileName, '', $filePath))) . ' GMT');
        // Supports for download resume
        header('Accept-Ranges: bytes');
        // Calculate File size
        header('Content-Length: ' . filesize($filePath));
        header('Content-Encoding: none');
        // Change the mime type if the file is not PDF
        header('Content-Type: application/' . $ext);
        // Make the browser display the Save As dialog
        header('Content-Disposition: attachment; filename=' . $fileName);
        readfile($filePath);
    }

    protected function shortenData(string $data, int $maxlength)
    {
        if (strlen($data) < $maxlength)
        {
            return $data;
        }
        return substr($data, 0, $maxlength - 3).'...';
    }
}