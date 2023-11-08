<?php
/**
 * Ginq: `LINQ to Object` inspired DSL for PHP
 * Copyright 2013, Atsushi Kanehara <akanehara@gmail.com>
 *
 * Licensed under The MIT License
 * Redistributions of files must retain the above copyright notice.
 *
 * PHP Version 5.3 or later
 *
 * @author     Atsushi Kanehara <akanehara@gmail.com>
 * @copyright  Copyright 2013, Atsushi Kanehara <akanehara@gmail.com>
 * @license    MIT License (http://www.opensource.org/licenses/mit-license.php)
 * @package    Ginq
 */

namespace Ginq\Iterator;

/**
 * RepeatIterator
 * @package Ginq
 */
class LazyRepeatIterator implements \Iterator
{
    /**
     * @var int
     */
    private $i;

    /**
     * @var mixed
     */
    private $x;

    /**
     * @var /Closure
     */
    private $valueFactory;

    /**
     * @var int|null
     */
    private $count;

    /**
     * @param \Closure $valueFactory
     * @param int      $count
     */
    public function __construct($valueFactory, $count)
    {
        $this->valueFactory = $valueFactory;
        $this->count = $count;
    }

    public function current(): mixed
    {
        return $this->x;
    }

    public function key(): mixed
    {
        return $this->i;
    }

    public function next(): void
    {
        $this->i++;
    }

    public function rewind(): void
    {
        $this->i = 0;
        $f = $this->valueFactory;
        $this->x = $f();
    }

    public function valid(): bool
    {
        if (is_null($this->count)) {
            return true;
        } else {
            return $this->i < $this->count;
        }
    }
}
