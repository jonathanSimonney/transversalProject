<?php
/**
 * Created by PhpStorm.
 * User: user
 * Date: 19/04/2017
 * Time: 18:09
 */

namespace Model;


abstract class BaseManager
{
    protected static $_instances = [];
    final public static function getInstance() {
        $class = get_called_class();
        if (!isset(self::$_instances[$class])) {
            self::$_instances[$class] = new $class();
            self::$_instances[$class]->setup();
        }
        return self::$_instances[$class];
    }

    final protected function __construct(){}

    abstract public function setup();

    protected function makeInferiorKeyIndex(array $superArray, $inferior_key)
    {
        $new_array = [];
        foreach ($superArray as $key => $array){
            $new_array[$array[$inferior_key]] = $array;
        }
        return $new_array;
    }

    protected function delTree($dir)
    {
        $files = array_diff(scandir($dir), array('.','..'));
        foreach ($files as $file) {
            is_dir("$dir/$file") ? delTree("$dir/$file") : unlink("$dir/$file");
        }
        return rmdir($dir);
    }

    protected function initIfNotSet(&$varToInit, $initValue){
        if (!isset($varToInit))
        {
            $varToInit = $initValue;
        }
    }

    public function sendMail($to, $object, $content, $altContent = null)
    {
        global $privateConfig;
        $mail_config = $privateConfig['mail_config'];

        $mail = new \PHPMailer;
        $mail->isSMTP();                                      // Set mailer to use SMTP
        $mail->Host = $mail_config['host'];  // Specify main and backup SMTP servers
        $mail->SMTPAuth = true;                               // Enable SMTP authentication
        $mail->Username = $mail_config['adress'];                 // SMTP username
        $mail->Password = $mail_config['password'];                           // SMTP password
        $mail->SMTPSecure = 'tls';                            // Enable TLS encryption, `ssl` also accepted
        $mail->Port = 587;

        $mail->setFrom($mail_config['adress'], 'noreply');
        $mail->addAddress($to);
        $mail->isHTML(true);
        $mail->CharSet = 'UTF-8';

        $mail->Subject = $object;
        $mail->Body    = $content;
        if ($altContent === null)
        {
            $altContent = $content;
        }
        $mail->AltBody = $altContent;

        $mail->send();

        /*if(!$mail->send()) {    //for debugging!
            echo 'Message could not be sent.';
            echo 'Mailer Error: ' . $mail->ErrorInfo;
        } else {
            echo 'Message has been sent';
        }*/

        //var_dump($mail);
    }

    protected function isAdmin()
    {
        if (!isset($_SESSION['currentUser']))
        {
            return false;
        }
        return $_SESSION['currentUser']['data']['type'] === 'admin';
    }
}