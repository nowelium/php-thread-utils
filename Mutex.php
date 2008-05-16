<?php

/**
 * Mutexは指定されたThreadのロックを行います。
 */
interface Mutex {

    /**
     * ロックする.
     * すでにロックされている場合にはロックが解除されるまで待つ
     */
    public function lock();

    /**
     * ロックを解除する．
     * ロックを待っている他のThreadがあればそちらを走らせる
     */
    public function unlock();

    /**
     * ロックされているとき、trueを返します。
     * @return boolean
     */
    public function locked();

    /**
     * ロックしようとして、ロックが成功した場合trueを返します。
     * ロックできない場合はfalseを返します。
     */
    public function tryLock();

    /**
     * ロックを行い、<code>$method</code>を実行します。
     * 実行後にロックの解除を行います。
     * <code>$method</code>にはコンストラクタで指定したThreadのメソッドを指定します。
     * @param $method 設定したThreadクラスのMethodを指定します。
     * @param $args Methodに設定する引数を配列で指定します。
     * @return mixed Methodの実行結果を返します。
     */
    public function synchronize($method, array $args = array());

}

?>
