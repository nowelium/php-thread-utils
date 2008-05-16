<?php

interface Queue {
    /**
     * 
     */
    public function clear();
    
    /**
     *
     */
    public function isEmpty();
    
    /**
     *
     */
    public function size();
    
    /**
     *
     */
    public function pop($nonBlock = false);
    
    /**
     *
     */
    public function shift($nonBlock = false);
    
    /**
     *
     */
    public function push($value);

    /**
     *
     */
    public function iterator();
}

?>
