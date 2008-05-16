<?php

final class Segment {
 
    const PID_STORE_DIR = "/tmp";
    const PID_STORE_EXT = ".pid";
 
    private $id;
    private $name;
    private $file;
    private $sid;
 
    /**
     * 共有メモリの作成と開始
     * @param $name 共有メモリを作成する際にキーとする文字列
     */
    public function __construct($name){
        $this->name = $name;
        $this->setupFile();
        $this->setupSegment();
    }

    private function setupFile(){
        $file = self::PID_STORE_DIR . '/' . $this->name . '$' .self::PID_STORE_EXT;
        touch($file);
        if(!file_exists($file)){
            throw new Exception('could not touch file {' . $file . '}');
        }
        $this->file = $file;
    }

    private function setupSegment(){
        $id = ftok($this->file, 't');
        if($id === -1){
            throw new Exception('could not creating semaphore segment (ftok)');
        }
        $this->id = $id;
        $this->sid = shmop_open($id, 'c', 0644, 100);
    }

    /**
     * 共有メモリの終了
     */
    public function __destruct(){
        shmop_delete($this->sid);
        shmop_close($this->sid);
    }

    /**
     * 1バイトだけ読み込む
     */
    public function get(){
        return shmop_read($this->sid, 0, 1);
    }

    /**
     * 先頭に値を設定する
     */
    public function put($value){
        shmop_write($this->sid, $value, 0);
    }
 
    public function getId(){
        return $this->id;
    }
 
    public function getName(){
        return $this->name;
    }
 
    public function getFile(){
        return $this->file;
    }

    public function getSId(){
        return $this->sid;
    }
 
}

?>
