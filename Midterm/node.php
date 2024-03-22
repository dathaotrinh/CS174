<?php

class Node
{
    private $prev;
    private $next;
    private $val;
    private $key;

    public function __construct($key, $val)
    {
        $this->val = $val;
        $this->key = $key;
    }

    // public function __destruct() {
    //     print "Destroying " . __CLASS__ . "\n";
    // }

    public function getPrev()
    {
        return $this->prev;
    }

    public function setPrev(Node $prev)
    {
        $this->prev = $prev;
    }

    public function getNext()
    {
        return $this->next;
    }

    public function setNext(Node $next)
    {
        $this->next = $next;
    }

    public function getVal()
    {
        return $this->val;
    }

    public function setVal($val)
    {
        $this->val = $val;
    }

    public function getKey()
    {
        return $this->key;
    }

    public function setKey($key)
    {
        $this->key = $key;
    }

    public function __toString() {
        return "". $this->key .": ". $this->val ;
    }
}
?>