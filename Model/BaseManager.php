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
}