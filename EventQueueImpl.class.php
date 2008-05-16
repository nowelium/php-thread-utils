<?php

require_once dirname(__FILE__) . "/EventQueue.php";

class EventQueueImpl extends QueueImpl implements EventQueue {

    public function __construct(){
        parent::__construct();
    }

    public function add(Event $event){
        parent::push($event);
    }

}

?>
