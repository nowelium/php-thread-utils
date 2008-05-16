<?php

require_once dirname(__FILE__) . "/ThreadImpl.class.php";

abstract class WorkerThread extends ThreadImpl {

    /**
     * @var ThreadPool
     */
    private $pool;

    /**
     * @var boolean
     */
    private $doRun = true;

    /**
     *
     */
    public function __construct(){
        parent::__construct();
    }

    /**
     *
     */
    public function setPool(ThreadPool $pool) {
        $this->pool = $pool;
    }

    /**
     *
     */
    public function setDoRun($doRun) {
        $this->doRun = $doRun;
    }

    /**
     *
     * @return ThreadPool
     */
    public function getPool() {
        return $this->pool;
    }

    /**
     *
     */
    public function isDoRun() {
        return $this->doRun;
    }

    /**
     *
     */
    public function close() {
        $this->doRun = false;
    }

}

?>
