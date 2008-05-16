<?php

class Thread implements Runnable {

    const PID_STORE_DIR = "/tmp";
    const PID_STORE_EXT = ".pid";

    const LOCK = "1";
    const UNLOCK = "0";

    // instancee code
    private static $code = 0;

    // pid
    private $id;
    // class name
    private $name;
    // process status
    private $status;
    // shared semaphore
    private $sid;
    //
    private $pidFile;
    // child process
    private $isChild = false;
    // running status
    private $isRunning = false;
    
    // thread code
    private $threadCode = 0;

    public function __construct($name = null){
        $this->name = get_class($this);
        if($name !== null){
            $this->name = $name;
        }
        $this->threadCode = self::$code++;
        $this->createSegment();
    }

    public function __destuct(){
        // callee, exitting..exit/die
        $this->shutdown();
        $this->closeSegment();
    }

    private function createSegment(){
        $file = self::PID_STORE_DIR . '/' . $this->name . '$' . $this->threadCode . self::PID_STORE_EXT;
        $this->pidFile = $file;
        touch($file);
        $shm_key = ftok($file, "t");
        if ($shm_key === -1){
            throw new Exception ("Fatal exception creating SHM segment (ftok)");
        }
        $this->sid = shmop_open($shm_key, "c", 0644, 10);
        $this->unlock();
    }

    private function closeSegment(){
        shmop_delete($this->sid);
        shmop_close($this->sid);
    }

    protected function lock(){
        shmop_write($this->sid, self::LOCK, 0);
    }

    protected function unlock(){
        shmop_write($this->sid, self::UNLOCK, 0);
    }

    protected function isLocked(Thread $job = null){
        if($job === null){
            return $this->isLock($this);
        }
        return strcmp(shmop_read($job->sid, 0, 1), self::LOCK) === 0;
    }

    protected function wait(Thread $job){
        while(true){
            $data = shmop_read($job->sid, 0, 1);
            if(strcmp($data, self::UNLOCK) === 0){
                break;
            }
            $this->sleep(1);
        }
    }

    public function run(){
        throw new Exception(__CLASS__ , " cannot be run " . __METHOD__ . ", Please extend and override this method");
    }

    public function start(){
        if ($this->isRunning()){
            throw new Exception(__CLASS__ . " is already running");
        }
        $this->fork();
    }

    public function shutdown(){
        $this->isRunning = false;
        pcntl_waitpid($this->id, $this->status, WUNTRACED);
        return pcntl_wifexited($this->status);
    }
    
    public function join($wait = true){
        $option = WUNTRACED;
        if($wait === false){
            $option = WNOHANG;
        }
        if (0 === pcntl_waitpid($this->id, $this->status, $option)){
            return -1;
        }
        $this->isRunning = false;
        $this->id = -1;
        return $this->status;
    }
    
    protected function fork(){
        $pid = pcntl_fork();
        if ($pid === -1) {
            // error
            throw new Exception("could not fork");
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
            die;
        } else {
            // parent
            $this->isRunning = true;
            $this->isChild = false;
            $this->id = $pid;
        }
    }
    
    protected function handler($sigNo) {
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
            $this->resume();
        break;
        }
    }

    public function suspend(){
    }

    public function resume(){
    }

    protected function sleep($msecond){
        usleep($msecond * 1000);
    }
    
    protected function isRunning(){
        return $this->isRunning;
    }
    
    protected function isChild(){
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

    public static function code(){
        return self::$code;
    }

    public function __toString(){
        return $this->name . "#" . $this->threadCode;
    }

}

class SynchronizedThread extends Thread {

    private $synchronus = array();
    private $shutdownFunction = array();

    public function __construct(Runnable $job, $method){
        parent::__construct();
        $this->synchronus = array($job, $method);
    }

    protected function fork(){
        $pid = pcntl_fork();
        if ($pid === -1) {
            // error
            throw new Exception("could not fork");
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
            $this->doSynchronus();
            die;
        } else {
            // parent
            $this->isRunning = true;
            $this->isChild = false;
            $this->id = $pid;
        }
    }

    protected function doSynchronus(){
        if(count($this->synchronus)){
            $result = call_user_func_array($this->shutdownFunction, null);
            call_user_func_array($this->synchronus, array($result));
            $job = $this->synchronus[0];
            $job->start();
        }
    }

    protected function registerShutdown($method){
        $this->shutdownFunction = array($this, $method);
    }

}

class DelegateThread extends Thread {

    private $delegate;

    public function setDelegate($obj){
        $this->delegate = $obj;
    }

    public function __call($name, $args = null){
        if(method_exists($this->delegate, $name)){
            $this->wait($this);
            $this->lock();
            $result = call_user_func_array(array($this->delegate, $name), $args);
            $this->unlock();
            return $result;
        }
    }
}

?>
