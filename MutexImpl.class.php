<?php

require_once dirname(__FILE__) . "/ThreadImpl.class.php";
require_once dirname(__FILE__) . "/Mutex.php";
require_once dirname(__FILE__) . "/Segment.class.php";

/**
 * Mutexは指定されたThreadのロックを行います。
 */
class MutexImpl implements Mutex {

    /**
     * ロック中を示す定数
     */
    const LOCKED = '1';

    /**
     * ロック解除を示す定数
     */
    const UNLOCK = '0';

    /**
     * ロック対象となるThreadオブジェクト
     */
    private $target;

    /**
     * 共有メモリ
     */
    private $segment;

    /**
     * @param thread Threadオブジェクトを指定
     */
    public function __construct(Thread $thread){
        $this->target = $thread;
        $this->segment = new Segment($thread->__toString());
    }

    /**
     * ロックする.
     * すでにロックされている場合にはロックが解除されるまで待つ
     */
    public function lock(){
        if($this->locked()){
            $this->target->wait();
        }
        $this->segment->put(self::LOCKED);
    }

    /**
     * ロックを解除する．
     * ロックを待っている他のThreadがあればそちらを走らせる
     */
    public function unlock(){
        if($this->locked()){
            $this->target->wait();
        }
        $this->segment->put(self::UNLOCK);
    }

    /**
     * ロックされているとき、trueを返します。
     * @return boolean
     */
    public function locked(){
        $value = $this->segment->get();
        return $value === self::LOCKED;
    }

    /**
     * ロックしようとして、ロックが成功した場合trueを返します。
     * ロックできない場合はfalseを返します。
     */
    public function tryLock(){
        if($this->locked()){
            $this->segment->put(self::LOCKED);
        }
        return $this->locked();
    }

    /**
     * ロックを行い、<code>$method</code>を実行します。
     * 実行後にロックの解除を行います。
     * <code>$method</code>にはコンストラクタで指定したThreadのメソッドを指定します。
     * @param $method 設定したThreadクラスのMethodを指定します。
     * @param $args Methodに設定する引数を配列で指定します。
     * @return mixed Methodの実行結果を返します。
     */
    public function synchronize($method, array $args = array()){
        $this->lock();
        $cause = null;
        $return = null;
        try {
            $return = call_user_func_array(array($this->target, $method), $args);
        } catch(Exception $e){
            $cause = $e;
        }
        $this->unlock();
        if($cause !== null){
            throw $cause;
        }
        return $return;
    }
}

?>
