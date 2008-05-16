<?php

function profile(){
    echo "hoge";
}

register_tick_function("profile");

declare(ticks = 1){
    for($i = 0; $i < 10; $i++){
        echo "piyo", $i, PHP_EOL;
    }
}

declare(ticks = 2){
    for($i = 0; $i < 10; $i++){
        echo "huga", $i, PHP_EOL;
    }
}

declare(ticks = 3){
    for($i = 0; $i < 10; $i++){
        echo "nara", $i, PHP_EOL;
    }
}
