<?php

require_once dirname(__FILE__) . "/Thread.class.php";
require_once dirname(__FILE__) . "/Mutex.php";
require_once dirname(__FILE__) . "/MutexImpl.class.php";
require_once dirname(__FILE__) . "/NopMutexImpl.class.php";

class DelegateThread extends Thread {
 
    private $delegate;
    private $isLocked = true;
    private $mutex;
 
    public function __construct($delegate, $locked = true){
        parent::__construct();
        $this->delegate = $delegate;
        $mutex = 'MutexImpl';
        if($locked === false){
            $mutex = 'NopMutexImpl';
        }
        $this->mutex = new $mutex($this);
    }
 
    public function setDelegate($delegate){
        $this->delegate = $delegate;
    }
 
    public function __call($name, $args = null){
        if(method_exists($this->delegate, $name)){
            $this->mutex->lock();
            $result = call_user_func_array(array($this->delegate, $name), $args);
            $this->mutex->unlock();
            return $result;
        }
    }
}

?>
