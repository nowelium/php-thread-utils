<?php

require_once dirname(__FILE__) . "/Thread.class.php";
require_once dirname(__FILE__) . "/Mutex.class.php";

class NopMutexImpl implements Mutex {

    private $target;

    public function __construct(Thread $thread){
        $this->target = $thread;
    }

    public function lock(){
    }

    public function unlock(){
    }

    public function locked(){
        return false;
    }

    public function tryLock(){
        return true;
    }

    public function synchronize($method, array $args = array()){
        return call_user_method_array(array($this->target, $method), $args);
    }
}

?>
