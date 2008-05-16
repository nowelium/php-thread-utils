<?php

require_once dirname(__FILE__) . "/event.inc.php";
require_once BASE_DIR . "/EventHandler.php";

class HogeHandler implements EventHandler {

    public function handle(Event $event){
        $event->execute();
    }
}
