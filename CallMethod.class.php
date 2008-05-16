<?php

class CallMethod {

    private $target;
    private $method;
    private $args = array();

    public function __construct($target, $method, array $args = array()){
        $this->target = $target;
        $this->method = $method;
        $this->args = $args;
    }

    public function call(){
        return call_user_func_array(array($this->target, $this->method), $this->args);
    }
}

?>
