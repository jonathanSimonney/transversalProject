<?php
/**
 * Created by PhpStorm.
 * User: user
 * Date: 28/04/2017
 * Time: 13:44
 */

namespace Model;


class LogManager extends BaseManager
{
    protected $dbManager;

    public function setup()
    {
        $this->dbManager = DBManager::getInstance();
    }

    public function generateAccessMessage($action, $actionType){

        $begin = 'Unknown user';
        if (isset($_SESSION['currentUser']['data']['pseudo']))
        {
            $begin = 'User '.$_SESSION['currentUser']['data']['pseudo'].' of id '.$_SESSION['currentUser']['data']['id'];
        }

        $this->writeToLog($begin.' '.$action.' at '.date('r'), $actionType);
        //return $begin.' '.$action.' at '.date('r');
    }

    private function writeToLog($newMessage, $file){
        if ($file === 'access')
        {
            $file = fopen('logs/access.log', 'ab');
        }
        elseif($file === 'message')
        {
            $file = fopen('logs/message.log', 'ab');
        }
        else
        {
            $file = fopen('logs/security.log', 'ab');
            $suAdress = $this->dbManager->findOne('SELECT email FROM users WHERE type = \'admin\'')['email'];
            $this->sendMail($suAdress,'security problem!','A message has been writen to the security.log file : <br>'.$newMessage, "A message was written to the security.log file. \r\n".$newMessage);
        }
        fwrite($file, str_replace('<', '&lt;', str_replace('&', '&amp;', $newMessage))."\n");

        fclose($file);
    }
}