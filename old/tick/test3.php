<?php
class my_class {
    function my_method(){
        var_dump(func_get_args());
    }
}

$object = new my_class();
register_tick_function(array($object, 'my_method'));


declare(ticks = 1){
    echo "hoge";
}
