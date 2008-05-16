<?php

require_once dirname(__FILE__) . "/Runnable.php";
require_once dirname(__FILE__) . "/Thread.php";

class ThreadImpl implements Thread {

    // instancee code
    private static $code = 0;

    // pid
    private $id;
    // class name
    private $name;
    // process status
    private $status;
    // process priority
    private $priority;
    // thread is running
    private $isRunning = false;
    // child process
    private $isChild = false;
    // deamon process
    private $isDaemon = false;
    // thread code
    private $threadCode = 0;

    public function __construct($name = null){
        $this->name = get_class($this);
        if($name !== null){
            $this->name = $name;
        }
        $this->threadCode = self::$code++;
    }

    public function __destuct(){
        // callee, exitting..exit/die
        // is parent
        if(0 < $this->id){
            $this->waitChildren();
        }
        $this->shutdown();
    }

    public function setPriority($priority){
    }

    public function setDaemon($isDaemon){
        $this->isDaemon = $isDaemon;
    }

    public function run(){
        throw new Exception(__CLASS__ . ' cannot be run ' . __METHOD__ . ', please extend and override this method');
    }

    public function start(){
        if ($this->isRunning()){
            throw new Exception(__CLASS__ . ' is already running');
        }
        $this->fork();
    }

    public function shutdown(){
        $this->isRunning = false;
        pcntl_waitpid($this->id, $this->status, WUNTRACED);
        return pcntl_wifexited($this->status);
    }
    
    /**
     * 指定したthreadの実行が終了するまで、現在のthreadを待機させます。
     * 引数にnullまたは、空の場合は現在のthreadの処理を待機させます。
     * @param $job 処理を待つThread
     */
    public function join(Thread $job = null){
        $target = $this;
        if($job !== null){
            $target = $job;
        }
        $target->wait();
        if($target !== $this){
            $this->wait();
        }
    }

    public function kill(Thread $job){
        posix_kill($job->id, SIGTERM);
    }

    public function wait(){
        pcntl_waitpid($this->id, $this->status, WUNTRACED);
    }

    private function waitpid($id){
        while(0 < pcntl_waitpid($id, $this->status, WNOHANG));
    }

    private function waitChildren(){
        $this->waitpid(0);
    }

    public function waitAll(){
        $this->waitpid(-1);
    }
    
    protected function fork(){
        $pid = pcntl_fork();
        if ($pid === -1) {
            // error
            throw new Exception('could not fork');
        } else if($pid === 0){
            // child
            $this->isRunning = true;
            $this->isChild = true;
            $this->id = $pid;

            declare(ticks = 1);

            pcntl_signal(SIGCHLD, array($this, 'handler'));
            pcntl_signal(SIGTERM, array($this, 'handler'));
            pcntl_signal(SIGHUP, array($this, 'handler'));

            // run it
            $this->run();
            if(!$this->isDaemon){
                die;
            }
        } else {
            // parent
            $this->isRunning = true;
            $this->isChild = false;
            $this->id = $pid;
        }
    }
    
    public function handler($sigNo) {
        switch ($sigNo) {
        case SIGTERM:
            // shutdown signal
            $this->shutdown();
            die;
        break;
        case SIGCHLD:
            // halt signal
            while (pcntl_waitpid($this->id, $this->status, WNOHANG) > 0);
        break;
        case SIGHUP:
            // release
        break;
        }
    }

    public function isRunning(){
        return $this->isRunning;
    }
    
    public function isChild(){
        return $this->isChild;
    }
    
    public function getId(){
        return $this->id;
    }

    public function getName(){
        return $this->name;
    }
    
    public function setName($name){
        $this->name = $name;
    }

    public function threadCode(){
        return $this->threadCode;
    }

    public function __toString(){
        return $this->name . '$' . $this->threadCode;
    }

    public static function code(){
        return self::$code;
    }

}

?>
