<?php

namespace PHPhotoSuit\PhotoSuite\Domain;

use PHPhotoSuit\PhotoSuite\Domain\Exception\OutOfBoundsException;

class PhotoCollection implements \ArrayAccess, \Iterator
{
    
    /** @var  Photo[] */
    private $collection = [];

    public function __construct(array $collection = null)
    {
        if (!is_null($collection)) {
            foreach ($collection as $photo) {
                $this->checkInstanceOf($photo);
            }
            $this->collection = $collection;
        }
    }

    public function offsetExists($offset)
    {
        return isset($this->collection[$offset]);
    }

    public function offsetGet($offset)
    {
        if (!isset($this->collection[$offset])) {
            throw new OutOfBoundsException($offset);
        }
        return $this->collection[$offset];
    }

    public function offsetSet($offset, $value)
    {
        $this->checkInstanceOf($value);
        if (is_null($offset)) {
            $this->collection[] = $value;
        } else {
            $this->collection[$offset] = $value;
        }
    }

    public function offsetUnset($offset)
    {
        unset($this->collection[$offset]);
    }

    private function checkInstanceOf($photo)
    {
        if (!$photo instanceof Photo::class) {
            throw new \InvalidArgumentException('Type does not match with ' . Photo::class);
        }
    }

    /**
     * Return the current element
     * @return Photo
     */
    public function current()
    {
        return current($this->collection);
    }

    /**
     * Move forward to next element
     * @return void Any returned value is ignored.
     */
    public function next()
    {
        next($this->collection);
    }

    /**
     * Return the key of the current element
     * @return mixed scalar on success, or null on failure.
     */
    public function key()
    {
        return key($this->collection);
    }

    /**
     * Checks if current position is valid
     * @link http://php.net/manual/en/iterator.valid.php
     * @return boolean The return value will be casted to boolean and then evaluated.
     */
    public function valid()
    {
        return key($this->collection) !== null;
    }

    /**
     * Rewind the Iterator to the first element
     * @return void Any returned value is ignored.
     */
    public function rewind()
    {
        reset($this->collection);
    }
}
