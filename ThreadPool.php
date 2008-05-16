<?php

interface ThreadPool {

    const MAX_THREADS = 200;

    const MIN_THREADS = 0;

    /*
    public function dispatch(Runnable $job);

    public function join();
    */

    /**
     * 
     */
    public function getWorker();

    /**
     *
     */
    public function returnWorker(WorkerThread $worker);

    /**
     *
     */
    public function getThreads();

    /**
     * @return integer
     */
    public function getMaxThreads();

    /**
     * @return interger
     */
    public function getMinThreads();
}

?>
