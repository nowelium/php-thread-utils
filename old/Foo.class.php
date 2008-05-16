<?php

require_once "Thread.class.php";

class A extends Thread {

    private $activate = false;

    public function run(){}

    public function hoge(){
        if($this->activate){
            $this->lock();
        } else {
            $this->unlock();
        }
        echo __CLASS__, (string)$this->activate, PHP_EOL;
        $this->sleep(100);
    }
    public function getActive(){
        return $this->activate;
    }

    public function setActive($boolean){
        $this->activate = $boolean;
    }

}

class B extends Thread {

    private $activate = true;

    public function run(){}

    public function hoge(){
        if($this->activate){
            $this->lock();
        } else {
            $this->unlock();
        }
        echo __CLASS__, (string)$this->activate, PHP_EOL;
        $this->sleep(100);
    }
    public function getActive(){
        return $this->activate;
    }
    public function setActive($boolean){
        $this->activate = $boolean;
    }
}

class Foo extends Thread {

    private $a;
    private $b;

    public function __construct(){
        parent::__construct();
        $this->a = new A();
        $this->b = new B();
    }

    public function run(){
        $this->a->start();
        $this->b->start();
        while(1){
            $this->a->hoge();
            $this->b->hoge();
            if($this->a->getActive() === false){
                $this->b->setActive(false);
            }
            $this->synchronized($this->a);
            if($this->b->getActive() === false){
                $this->a->setActive(true);
            }
        }
    }
}

$foo = new Foo;
$foo->start();
