<?php

require_once dirname(__FILE__) . "/event.inc.php";
require_once BASE_DIR . "/Event.php";

interface HogeEventObject implements Event {
    public function execute();
}

abstract class AbstractEvent implements HogeEventObject {
    public function execute(){
        echo get_class($this), PHP_EOL;
    }

    public function getName(){
        return __CLASS__;
    }
}

class HogeEvent extends AbstractEvent {
}

class FooEvent extends AbstractEvent {
}

class BarEvent extends AbstractEvent {
}

class BazEvent extends AbstractEvent {
}
