<?php

require_once "node.php";
class LRUCache
{
    private $head;
    private $tail;
    private $map;
    private $cache_size;
    public function __construct($cache_size)
    {
        $this->map = array(); // Key - Node pair
        $this->cache_size = $cache_size;

        // Create doubly linked list
        $this->head = new Node(-1, -1);
        $this->tail = new Node(-1, -1);
        $this->head->setNext($this->tail);
        $this->tail->setPrev($this->head);
    }

    public function get_size() {
        return count($this->map);
    }

    public function get($key)
    {
        // if map already has key
        // remove the node associated with that key
        // and insert a new node with the same key but different value
        if(isset($this->map[$key])) {
            $temp_node = $this->map[$key];
            $this->remove_node($temp_node);
            $this->insert_node($temp_node);
            return $temp_node->getVal();
        } else {
            return -1;
        }
    }

    public function put($key, $val, $reset)
    {
        // check value form
        if (is_numeric($val)) {
            $val = (int) $val;
            if (is_int($val) === false || $val <= 0) {
                return self::print_negative_value_prompt();
            }
        } else {
            return self::print_wrong_type_value_prompt(gettype($val));
        }
        $result_str = "adding new key {$key}"; // order: reset > evict > update > adding new key
        $val = (int) $val; // convert the value from string to integer

        // if need to reset
        if ($reset === true) {
            $result_str = "reset cache";
            $this->reset();
        }

        // if the map already has key
        // print string to update the key with new value
        if (isset($this->map[$key])) {
            if($result_str === "adding new key {$key}") {
                $result_str = "update key " . $key . " value";
            }
            $temp_node = $this->map[$key];
            $this->remove_node($temp_node); // remove node out of doubly linked list
        }
        // evict the least recently used node
        if ($this->is_full()) {
            if($result_str === "adding new key {$key}") {
                $result_str = "evict key " . $this->tail->getPrev()->getKey();
            }
            $this->remove_node($this->tail->getPrev());
        }
        // create new node
        $new_node = new Node($key, $val);
        // add new node to doubly linked list
        $this->insert_node($new_node);
        // return result str
        // empty string if not fell into one of special cases (reset, evict,update)
        return $result_str;
    }

    // reset everything
    private function reset()
    {
        $this->head = new Node(-1, -1);
        $this->tail = new Node(-1, -1);
        $this->head->setNext($this->tail);
        $this->tail->setPrev($this->head);
        $this->map = array();
    }

    // remove node
    private function remove_node($node)
    {
        unset($this->map[$node->getKey()]); // remove the key out of map
        // ignore node in the doubly linked list
        $node->getPrev()->setNext($node->getNext());
        $node->getNext()->setPrev($node->getPrev());
    }

    // insert new node
    private function insert_node($node)
    {
        // add new node to map
        $this->map[$node->getKey()] = $node;
        // get heads next node
        $head_next = $this->head->getNext();
        // connect node with head
        $this->head->setNext($node);
        $node->setPrev($this->head);
        // make sure head next is not null
        if ($head_next !== null) {
            $head_next->setPrev($node);
            $node->setNext($head_next);
        }

    }

    // check if the cache is full
    private function is_full()
    {
        $current_map_size = count($this->map);
        return $current_map_size == $this->cache_size ? true : false;
    }

    // print current cache
    public function print_current_cache()
    {
        $current_cache_str = "";
        foreach ($this->map as $key => $value) {
            $current_cache_str = $this->map[$key]->__toString() . ", " . $current_cache_str;
        }
        $current_cache_str = substr($current_cache_str, 0, -2);
        return "{{$current_cache_str}}";
    }

    private static function print_negative_value_prompt()
    {
        return "Not accept negative value";
    }

    private static function print_wrong_type_value_prompt($type)
    {
        return "Not accept {$type} value";
    }
}
?>