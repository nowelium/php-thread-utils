<?php

function hello(){
    echo "hello", PHP_EOL;
}

register_tick_function("hello");
declare(ticks = 1){
    echo "hoge1", PHP_EOL;
    echo "hoge2", PHP_EOL;
    echo "hoge3", PHP_EOL;
    echo "hoge4", PHP_EOL;
    echo "hoge5", PHP_EOL;
}

echo "---------------", PHP_EOL;
register_tick_function("hello");
declare(ticks = 2){
    echo "foo1", PHP_EOL;
    echo "foo2", PHP_EOL;
    echo "foo3", PHP_EOL;
    echo "foo4", PHP_EOL;
    echo "foo5", PHP_EOL;
}

echo "---------------", PHP_EOL;
register_tick_function("hello");
declare(ticks = 3){
    echo "bar1", PHP_EOL;
    echo "bar2", PHP_EOL;
    echo "bar3", PHP_EOL;
    echo "bar4", PHP_EOL;
    echo "bar5", PHP_EOL;
}
