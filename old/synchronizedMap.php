<?php

require_once "Thread.class.php";
require_once "S2Container/S2Container.php";
require_once "S2Dao/S2Dao.php";

class Semaphore {

    public static function synchronizedMap(S2Dao_Map $map){
        $synchronizedMap = new SynchronizedMap($map);
        $synchronizedMap->start();
        return $synchronizedMap;
    }

    public static function synchronizedList(S2Dao_List $list){
        $synchronizedList = new SynchronizedList($list);
        $synchronizedList->start();
        return $synchronizedList;
    }
}

class SynchronizedList extends DelegateThread implements S2Dao_List {

    public function __construct(S2Dao_List $list){
        parent::__construct();
        parent::setDelegate($list);
    }

    public function run(){}

    public function size(){
        return $this->__call('size');
    }
    
    public function isEmpty(){
        return $this->__call('isEmpty');
    }
    
    public function contains($object){
        return $this->__call('contains', array($object));
    }
        
    public function get($index){
        return $this->__call('get', array($index));
    }
    
    public function set($index, $object){
        return $this->__call('set', array($index, $object));
    }
        
    public function add($indexOrObject, $object = null){
        return $this->__call('add', array($indexOrObject, $object));
    }
    
    public function addAll(ArrayObject $list){
        return $this->__call('addAll', array($list));
    }
    
    public function remove($index){
        return $this->__call('remove', array($index));
    }
    
    public function iterator(){
        return $this->__call('iterator');
    }
    
    public function toArray(){
        return $this->__call('toArray');
    }
}

class SynchronizedMap extends DelegateThread implements S2Dao_Map {
    
    public function __construct(S2Dao_Map $map){
        parent::__construct();
        parent::setDelegate($map);
    }

    public function run(){}

    public function size(){
        return $this->__call('size');
    }
    
    public function isEmpty(){
        return $this->__call('isEmpty');
    }
    
    public function get($key){
        return $this->__call('get', array($key));
    }
    
    public function put($key, $value){
        return $this->__call('put', array($key, $value));
    }
    
    public function remove($key){
        return $this->__call('remove', array($key));
    }
    
    public function clear(){
        return $this->__call('clear');
    }
    
    public function contains($key){
        return $this->__call('contains', array($key));
    }
    
    public function containsKey($key){
        return $this->__call('containsKey', array($key));
    }
    
    public function toArray(){
        return $this->__call('toArray');
    }
    
    public function iterator(){
        return $this->__call('iterator');
    }
        
    public function entrySet(){
        return $this->__call('entrySet');
    }
    
    public function keySet(){
        return $this->__call('keySet');
    }

}

class A extends Thread {

    private $index = 0;
    private $map;

    public function __construct(S2Dao_Map $map){
        parent::__construct(__CLASS__);
        $this->map = $map;
    }

    public function run(){
        for($i = 0; $i < 10; $i++){
            $this->map->put(__CLASS__ . (string)$this->index++, $i);
            $this->sleep(100);
        }
    }

    public function getMap(){
        return $this->map;
    }
}

class B extends Thread {

    private $index = 0;
    private $map;

    public function __construct(S2Dao_Map $map){
        parent::__construct(__CLASS__);
        $this->map = $map;
    }

    public function run(){
        for($i = 0; $i < 10; $i++){
            $this->map->put(__CLASS__ . (string)$this->index++, $i);
            $this->sleep(20);
        }
    }

    public function getMap(){
        return $this->map;
    }
}

//$map = new SynchronizedMap(new S2Dao_HashMap);
//$map = Semaphore::synchronizedMap(new S2Dao_HashMap);
/*
$map = new S2Dao_HashMap();
$map->put("hoge", "fua");
$a = new A($map);
$b = new B($map);

$a->start();
$b->start();

var_dump($map);
*/

/*
class A_Ex extends SynchronizedThread {
    private $index = 0;
    private $map;
    public function __construct($class, $method){
        parent::__construct($class, $method);
        $this->registerShutdown('getMap');
        $this->map = new S2Dao_HashMap();
    }
    public function run(){
        for($i = 0; $i < 10; $i++){
            $this->map->put(__CLASS__ . (string)$this->index++, $i);
            $this->sleep(100);
        }
    }

    public function getMap(){
        return $this->map;
    }
}
class B_Ex extends Thread {

    private $index = 0;
    private $map;

    public function __construct(){
        parent::__construct(__CLASS__);
    }

    public function run(){
        for($i = 0; $i < 10; $i++){
            $this->map->put(__CLASS__ . (string)$this->index++, $i);
            $this->sleep(20);
        }
        //
        // とりあえず、ここで出力してみる
        //
        var_dump($this->map);
    }

    public function setMap(S2Dao_Map $map){
        $this->map = $map;
    }

    public function getMap(){
        return $this->map;
    }
}

$a = new A_Ex(new B_Ex, 'setMap');
$a->start();
*/

/*
$map = Semaphore::synchronizedMap(new S2Dao_HashMap);
$map->put("hoge", "bar");
var_dump($map);
*/


$m = Semaphore::synchronizedMap(new S2Dao_HashMap);
$m->put("hoge", "bar");
var_dump($m);
