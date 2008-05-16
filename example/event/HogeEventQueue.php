<?php

require_once dirname(__FILE__) . "/event.inc.php";
require_once dirname(__FILE__) . "/HogeEvent.class.php";
require_once dirname(__FILE__) . "/HogeHandler.class.php";
require_once dirname(__FILE__) . "/HogeChannel.class.php";
require_once BASE_DIR . "/EventListenerImpl.class.php";
require_once BASE_DIR . "/EventQueueImpl.class.php";

$queue = new EventQueueImpl();
$queue->add(new HogeEvent);
$queue->add(new FooEvent);
$queue->add(new BarEvent);
$queue->add(new BazEvent);

$channel = new HogeChannel($queue);
$channel->addListener(new EventListenerImpl(new HogeHandler));
$channel->listen();
