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
    public function setup()
    {
        // TODO: Implement setup() method.
    }

    public function generateAccessMessage($action, $actionType){

        if (isset($_SESSION['currentUser']['data']['username'])){
            $begin = 'User '.$_SESSION['currentUser']['data']['username'].' of id '.$_SESSION['currentUser']['data']['id'];
        }else{
            $begin = 'Unknown user';
        }

        $this->writeToLog($begin.' '.$action.' at '.date('r'), $actionType);
        //return $begin.' '.$action.' at '.date('r');
    }

    private function writeToLog($newMessage, $file){
        if ($file === 'access'){
            $file = fopen('logs/access.log', 'ab');
        }else{
            $file = fopen('logs/security.log', 'ab');
        }
        fwrite($file, $newMessage."\n");

        fclose($file);
    }
}