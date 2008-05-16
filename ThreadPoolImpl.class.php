<?php

require_once dirname(__FILE__) . "/ThreadImpl.class.php";
require_once dirname(__FILE__) . "/MutexImpl.class.php";
require_once dirname(__FILE__) . "/ThreadPool.php";
require_once dirname(__FILE__) . "/ThreadCreator.php";

class ThreadPoolImpl extends ThreadImpl implements ThreadPool {

    private static $code = 0;

    private $maxThreads = self::MAX_THREADS;
    private $minThreads = self::MIN_THREADS;
    private $isRunning = false;
    protected $mutex;
    protected $creator;
    
    private $idle = array();
    private $used = array();

    public function __construct($maxThreads, $minThreads, ThreadCreator $creator){
        parent::__construct();
        $this->mutex = new MutexImpl($this);
        $this->maxThreads = $maxThreads;
        $this->minThreads = $minThreads;
        $this->creator = $creator;

        for($i = 0; $i < $minThreads; $i++){
            $thread = $creator->getWorkerThread();
            $this->setupThread($thread);
            $this->idle []= $thread;
        }

    }

    protected function setupThread(WorkerThread $thread){
        $thread->setPool($this);
        $thread->setName(__CLASS__ . '[' . (self::$code++) . ']');
        $thread->setDaemon(true);
        $thread->setPriority(Thread::MAX_PRIORITY);
        $thread->start();
        echo $thread->__toString(), "is starton [", $thread->getId(), "]", PHP_EOL;
    }

    /**
     * idle状態のthreadを返します。
     * idle状態のthreadが存在しない場合はnullを返します。
     * @return WorkerThread
     */
    public function getWorker(){
        return $this->mutex->synchronize('synchronizedgetWorker');
    }

    public function synchronizedgetWorker(){
        $worker = null;
        if(0 < count($this->idle)){
            $worker = $this->idle->pop();
        } else {
            if(count($this->idle) < $this->maxThreads){
                $worker = $this->creator->getWorkerThread();
                $this->setupThread($worker);
            }
        }
        return $worker;
    }

    public function returnWorker(WorkerThread $worker){
        if($this->isRunning){
            $this->mutex->synchronize('synchronizedreturnWorker', $worker);
        } else {
            $worker->setDoRun(false);
            //$worker->notify();
        }
    }

    public function synchronizedreturnWorker(WorkerThread $worker){
        unset($this->used[0]);
        if(count($this->idle) < $this->minThreads && in_array($this->idle, $worker)){
            $this->idle []= $worker;
        } else {
            $worker->setDoRun(false);
        }
        //$worker->notify();
    }

    public function getThreads(){
        return $this->idle;
    }

    public function getMaxThreads(){
        return $this->maxThreads;
    }

    public function setMaxThreads($maxThreads){
        $this->maxThreads = $maxThreads;
    }

    public function getMinThreads(){
        return $this->minThreads;
    }

    public function setMinThreads($minThreads){
        $this->minThreads = $minThreads;
    }

    public function stop(){
        $this->isRunning = false;
        $this->mutex->synchronize('synchronizedstop');
    }

    public function synchronizedstop(){
        foreach($this->idle as &$worker){
            $this->returnWorker($worker);
            unset($worker);
        }
    }
}

?>
