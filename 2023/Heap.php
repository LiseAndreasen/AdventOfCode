<?php declare(strict_types=1);

// https://github.com/affinity4/heap/blob/main/src/Heap.php
//namespace Affinity4\Heap;

/**
 * Class Heap
 * 
 * Implements a binary heap with a heap sort algorithm.
 * 
 * @package Heap
 */
class Heap
{
    const ASC = 'ascending';
    const DESC = 'descending';

    private array $heap;
    private int $count;

    /**
     * Constructor for the Heap class.
     *
     * @param array $array The initial array to initialize the heap with.
     */
    public function __construct()
    {
        $this->heap = [];
        $this->count = 0;
    }

    /**
     * Inserts a new value into the heap and performs heapifyUp operation.
     *
     * @param mixed $value The value to be inserted into the heap.
     *
     * @return void
     */
    public function insert($value): void
    {
        $this->heap[] = $value;
        $this->count++;
        $this->heapifyUp($this->count - 1);
    }
    
    // pop first element
    public function pop()
    {
        $this->swap(0, $this->count - 1);
        $e = array_pop($this->heap);
        $this->heapifyDown(0, $this->count - 1);
        $this->heapifyUp(0);
        $this->count--;
        return $e;
    }
    
    // get info on element
    public function get($name, $default)
    {
        $min_val = 1000000; // hack
        $min_index = false;
        foreach($this->heap as $a => $b) {
            if(strcmp($b[0], $name) == 0) {
                if($b[1] < $min_val) {
                    $min_val = $b[1];
                    $min_index = $a;
                }
            }
        }
        if ($min_index === false) {
            return [$name, $default];
        }
        
        return $this->heap[$min_index];
    }
    
    // return minimal value
    public function min() {
        return $this->heap[0];
    }
    
    // return size of heap
    public function size() {
        return $this->count;
    }
    
    public function print() {
        print("printing heap\n");
        for($i=0;$i<$this->count;$i++) {
            [$c, $d] = $this->heap[$i];
            print("$i => ($c, $d)\n");
        }
    }
    
    /**
     * Checks if the heap is empty.
     *
     * @return bool Returns true if the heap is empty, false otherwise.
     */
    protected function isEmpty() {
        return $this->count === 0;
    }

    /**
     * Swaps the elements at the specified indices in the heap array.
     *
     * @param int $index1 The index of the first element to swap.
     * @param int $index2 The index of the second element to swap.
     *
     * @return void
     */
    private function swap($index1, $index2): void
    {
        $temp = $this->heap[$index1];
        $this->heap[$index1] = $this->heap[$index2];
        $this->heap[$index2] = $temp;
    }

    /**
     * Heapify the heap upwards starting from a given index.
     *
     * @param int $index The index from which to heapify upwards.
     *
     * @return void
     */
    private function heapifyUp($index): void
    {
        $parentIndex = intval(($index - 1) / 2);
        while ($index > 0 && $this->heap[$index][1] < $this->heap[$parentIndex][1]) {
            $this->swap($index, $parentIndex);
            $index = $parentIndex;
            $parentIndex = intval(($index - 1) / 2);
        }
    }

    /**
     * Heapify down the heap starting from a specific index.
     *
     * @param int $index The index from which to heapify down.
     * @param int $heapSize The size of the heap.
     *
     * @return void
     */
    private function heapifyDown($index, $heapSize): void
    {
        $lastIndex = $heapSize - 1;
        while (true) {
            $leftChildIndex = 2 * $index + 1;
            $rightChildIndex = 2 * $index + 2;
            $largestIndex = $index;
            if ($leftChildIndex <= $lastIndex && $this->heap[$leftChildIndex][1] < $this->heap[$largestIndex][1]) {
                $largestIndex = $leftChildIndex;
            }

            if ($rightChildIndex <= $lastIndex && $this->heap[$rightChildIndex][1] < $this->heap[$largestIndex][1]) {
                $largestIndex = $rightChildIndex;
            }

            if ($largestIndex === $index) {
                break;
            }

            $this->swap($index, $largestIndex);
            $index = $largestIndex;
        }
    }
}
