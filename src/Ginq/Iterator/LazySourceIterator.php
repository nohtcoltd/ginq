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
 * SelectIterator
 * @package Ginq
 */
class LazySourceIterator implements \Iterator
{
    /**
     * @var callable
     */
    private $sourceFactory;

    /**
     * @var \Iterator
     */
    private $it;

    /**
     * @param callable $sourceFactory
     */
    public function __construct($sourceFactory)
    {
        $this->sourceFactory = $sourceFactory;
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
        $this->it->next();
    }

    public function rewind(): void
    {
        $f = $this->sourceFactory;
        $this->it = IteratorUtil::iterator($f());
        $this->it->rewind();
    }

    public function valid(): bool
    {
        return $this->it->valid();
    }
}
