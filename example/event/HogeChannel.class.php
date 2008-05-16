<?php

require_once dirname(__FILE__) . "/event.inc.php";
require_once BASE_DIR . "/EventChannel.php";
require_once BASE_DIR . "/EventListener.php";

class HogeChannel implements EventChannel {

    private $listeners = array();
    private $queue;

    public function __construct(EventQueue $queue){
        $this->queue = $queue;
    }

    public function listen(){
        foreach($this->listeners as $listener){
            $iter = $this->queue->iterator();
            foreach($iter as $event){
                $listener->handleEvent($event);
            }
            $listener->start();
        }
    }

    public function addListener(EventListener $listener){
        $this->listeners[$listener->getName()] = $listener;
    }

    public function removeListener(EventListener $listener){
        unset($this->listeners[$listener->getName()]);
    }
    
    public function clearEventQueue(){
        unset($this->queue);
    }
}

?>
