<?php

require_once dirname(__FILE__) . "/ThreadImpl.class.php";
require_once dirname(__FILE__) . "/EventListener.php";
require_once dirname(__FILE__) . "/EventHandler.php";

class EventListenerImpl extends ThreadImpl implements EventListener {

    private $eventList = array();
    private $handler;
    private $runListener = true;
    private $sleepTime = 0;

    public function __construct(EventHandler $handler){
        parent::__construct();
        $this->handler = $handler;
    }

    public function run(){
        while(true){
            if($this->runListener){
                if(0 < count($this->eventList)){
                    $event = array_pop($this->eventList);
                    $this->handler->handle($event);
                } else {
                    $this->sleep();
                }
            }
        }
    }

    public function getName(){
        return __CLASS__;
    }

    public function handleEvent(Event $event){
        $this->eventList []= $event;
    }

    public function stopListener(){
        $this->runListener = false;
    }

    public function setSleep($msecond){
        $this->sleepTime = $msecond;
    }

    protected function sleep(){
        usleep($this->sleepTime * 1000);
    }

}
