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

    /*protected function initOrConcatenate($var, $add)
    {
        if (!isset($var))
        {
            $var = '';
        }else{
            $var .= $add;
        }

        return $var;
    }*/

    protected function initIfNotSet(&$varToInit, $initValue){
        if (!isset($varToInit))
        {
            $varToInit = $initValue;
        }
    }
}