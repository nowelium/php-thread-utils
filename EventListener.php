<?php

interface EventListener {

    public function getName();

    public function handleEvent(Event $event);

    public function stopListener();

    public function setSleep($msecond);

}

?>
