<?php

require_once "Thread.class.php";

class Hoge extends Thread {

    private static $code = 0;

    private $sleepTime = 0;
    private $count = 0;
    private $max = 10;

    public function __construct($sleepTime, $max){
        parent::__construct();
        self::$code++;
        $this->sleepTime = $sleepTime;
        $this->max = $max;
    }

    public function run(){
        while($this->count < $this->max){
            $this->sleep($this->sleepTime);
            echo __CLASS__, self::$code, ":(", $this->count++, ") ", PHP_EOL;
        }
    }
}


