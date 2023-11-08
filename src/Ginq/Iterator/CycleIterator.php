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
 * CycleIterator
 * @package Ginq
 */
class CycleIterator implements \Iterator
{
    /**
     * @var int
     */
    private $i;

    /**
     * @var \Iterator
     */
    private $it;

    /**
     * @param array|\Traversable $xs
     */
    public function __construct($xs)
    {
        $this->it = IteratorUtil::iterator($xs);
    }

    public function current(): mixed
    {
        return $this->it->current();
    }

    public function key(): mixed 
    {
        return $this->it->key();
    }

    public function next(): void
    {
        $this->i++;
        $this->it->next();
        $v = $this->it->valid();
        if (!$v) {
            $this->it->rewind();
        }
    }

    public function rewind(): void
    {
        $this->i = 0;
        $this->it->rewind();
    }

    public function valid(): bool
    {
        return $this->it->valid();
    }
}
