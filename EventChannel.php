<?php

/**
 * event sender
 */
interface EventChannel {

    public function listen();
    
    public function addListener(EventListener $listener);

    public function removeListener(EventListener $listener);

    public function removeAllListeners();

    public function clearEventQueue();

    public function multicast(Event $event);
}

?>
