<?php

/**
 * Threadのインタフェースです。
 * ThreadはRunnableを継承します。
 */
interface Thread extends Runnable {

    const MAX_PRIORITY = 20;

    public function setPriority($priority);

    /**
     * 新しいthreadを生成します。
     */
    public function start();

    /**
     * 現在実行しているthreadを終了します。
     */
    public function shutdown();
    
    /**
     * 指定したthreadの実行が終了するまで、現在のthreadを待機させます。
     * 引数にnullまたは、空の場合は現在のthreadの処理を待機させます。
     */
    public function join(Thread $thread = null);

    /**
     * 現在のthreadを待機させます。
     */
    public function wait();

    /**
     * 子プロセスの処理が終了するまで現在のthreadを待機させます。
     */
    public function waitAll();

    /**
     * 指定したthreadを終了させます。
     */
    public function kill(Thread $thread);

    /**
     * 現在のthreadが稼働中であるかを返します。
     */
    public function isRunning();

    /**
     * 現在のthreadが子プロセスであるかを返します。
     */
    public function isChild();
    
    /**
     * 現在のthreadのId(プロセスID)を返します。
     */
    public function getId();

    /**
     * 現在のthread名を返します。
     */
    public function getName();
    
    /**
     * 現在のthread名を設定します。
     */
    public function setName($name);

}
?>
