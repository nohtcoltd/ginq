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

use Ginq\Util\IteratorUtil;

/**
 * ReverseIterator
 * @package Ginq
 */
class ReverseIterator implements \Iterator
{
    /**
     * @var \Iterator
     */
    private $it;

    /**
     * @var array
     */
    private $items;

    /**
     * @var int
     */
    private $i;

    /**
     * @param array|\Traversable $xs
     */
    public function __construct($xs)
    {
        $this->it = IteratorUtil::iterator($xs);
    }

    public function current(): mixed
    {
        return $this->items[$this->i][1];
    }

    public function key(): mixed 
    {
        return $this->items[$this->i][0];
    }

    public function next(): void
    {
        $this->i--;
    }

    public function rewind(): void
    {
        $this->i = 0;
        $this->it->rewind();
        $this->items = array();
        $len = 0;
        foreach ($this->it as $k => $v) {
            array_push($this->items, array($k, $v));
            $len++;
        }
        $this->i = $len - 1;
    }

    public function valid(): bool
    {
        return 0 <= $this->i;
    }
}

