<?php

/**
 * 継続可能なThreadの実装インタフェースです。
 */
interface ContinuableThread extends Thread {

    const STATUS_START = "start";

    const STATUS_STOP = "stop";

    /**
     * このthreadが一度も実行状態になっていない場合にtrueを返します。
     * @return boolean
     */
    public function isNew();

    /**
     * このスレッドが実行中であるかどうかを返します。
     * @return boolean 実行中の場合にtrue
     */
    public function isRunning();

    /**
     * このスレッドが停止中であるかどうかを返します。
     * @return boolean 停止中の場合にtrue
     */
    public function isStopping();

    /**
     * このthreadの実行状態を返します。
     */
    public function getStatus();
    
    /**
     * このthreadが実行可能な状態の場合にthreadを実行します。
     * 既に開始中の場合は例外を発生します。
     */
    public function start();

    /**
     * このthreadを停止状態にし、停止しますがthreadを終了しません。
     * 既に停止中の場合は例外を発生します。
     */
    public function stop();
    
    /**
     * もしこのthreadが停止状態の場合は、処理を再開します。
     */
    public function resume();
    
    /**
     * このthreadの処理を指定されたミリ秒だけ停止し、停止状態にします。
     */
    public function suspend($msecond);

    /**
     * このthreadの処理を指定されたミリ秒だけ停止し、その後処理を再開します。
     */
    public function sleep($msecond);
    
    /**
     * このthreadの状態を強制的にデフォルトの状態に設定します。
     */
    public function reset();
    
}

?>
