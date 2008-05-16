<?php

require_once "Thread.class.php";

interface Printer {
    public function _print($s);
}

class RealPrinter extends Thread implements Printer {
    public function _print($s) {
        echo "\tRealPrinter prints '", $s, "'", PHP_EOL;
    }
}

class FuturePrinter extends Thread implements Printer {
    private $printer;
    public function _print($s) {
        while($this->printer == null) {
            echo "\tIn FuturePrinter: wait", PHP_EOL;
            $this->sleep(1);
        }
        echo "\tIn FuturePrinter: printing start.", PHP_EOL;
        $this->printer->_print($s);
    }
    public function setPrinter(Printer $printer) {
        $this->printer = $printer;
        $this->sleep(3);
    }
}

class Main extends Thread {
    private static $printer;
    public static function _main() {
        self::$printer = new FuturePrinter();
        $main = new Main();
        $main->start();

        echo Thread::code(), " is printing...", PHP_EOL;
        self::$printer->_print("Hello, world (1).");
        self::$printer->_print("Hello, world (2).");
        self::$printer->_print("Hello, world (3).");
    }
    public function run() {
        echo $this->threadCode(), " is sleeping.", PHP_EOL;
        $this->sleep(2);
        echo $this->threadCode(), " sets a RealPrinter.", PHP_EOL;
        self::$printer->setPrinter(new RealPrinter());
    }
}

Main::_main();
