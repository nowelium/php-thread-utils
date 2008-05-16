<?php

require_once dirname(__FILE__) . "/ThreadImpl.class.php";
require_once dirname(__FILE__) . "/Queue.php";

/**
 * Queue実行のためのThreadです。
 * このThreadはIteratableです。
 * Queueを継承したクラスはexecuteを実装してください。
 * ::executeの実行はiterate/pop/shiftされた際に呼び出されます。
 */
abstract class QueueImpl extends ThreadImpl implements Queue, Iterator {

    private $maxSize = -1;

    public function __construct($size = -1){
        $this->maxSize = $size;
        $this->queue = new ArrayObject();
    }

    public final function run(){
        // no operation
    }

    protected abstract function execute($value);

    public final function clear(){
        $this->queue = array();
    }

    public final function isEmpty(){
        return empty($this->queue);
    }

    public final function size(){
        return count($this->queue);
    }

    public final function pop($nonBlock = false){
        return $this->execute(array_pop($this->queue));
    }

    public final function shift($nonBlock = false){
        return $this->execute(array_shift($this->queue));
    }

    public final function push($value){
        if(-1 < $this->maxSize){
            if($this->maxSize < $this->size()){
                $this->join();
                return $this->push($value);
            }
        }
        $this->queue []= $value;
    }

    public final function current(){
        return $this->execute(current($this->queue));
    }

    public final function next(){
        return next($this->queue);
    }

    public final function key(){
        return key($this->queue);
    }

    public final function rewind(){
        return reset($this->queue);
    }

    public final function valid(){
        return current($this->queue) !== false;
    }

    public final function iterator(){
        return $this->queue->getIterator();
    }
}

?>
