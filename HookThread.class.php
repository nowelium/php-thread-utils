<?php

require_once "ThreadImpl.class.php";
require_once "CallMethod.class.php";

class HookThread extends ThreadImpl {

    private $job;

    private $before = array();
    private $after = array();

    public function __construt(Thread $job){
        $this->job = $job;
    }

    public function addBefore($target, $method, array $args = array()){
        $this->before []= new CallMethod($target, $method, $args);
    }

    public function addAfter($target, $method, array $args = array()){
        $this->after []= new CallMethod($target, $method, $args);
    }

    public function run(){
        foreach($this->before as $before){
            $before->call();
        }
        $this->job->run();
        $this->join($this->job);

        foreach($this->after as $after){
            $after->call();
        }
    }
}

?>
