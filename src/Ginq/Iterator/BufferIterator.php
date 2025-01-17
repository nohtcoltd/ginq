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
 * BufferIterator
 * @package Ginq
 */
class BufferIterator implements \Iterator
{
    /**
     * @var \Iterator
     */
    private $it;

    /**
     * @var array
     */
    private $buffer;

    /**
     * @var int
     */
    private $i;

    /**
     * @var int
     */
    private $chunkSize;

    /**
     * @param array|\Traversable $xs
     * @param int                $chunkSize
     * @throws \InvalidArgumentException
     */
    public function __construct($xs, $chunkSize)
    {
        if ($chunkSize < 1) {
            throw new \InvalidArgumentException("chunkSize must be greater than 0");
        }
        $this->it = IteratorUtil::iterator($xs);
        $this->chunkSize = $chunkSize;
    }

    public function rewind(): void
    {
        $this->it->rewind();
        $this->buffer = array();
        $this->i = 0;
        $this->fetch();
    }

    public function valid(): bool
    {
        return !empty($this->buffer);
    }

    public function key(): mixed
    {
        return $this->i;
    }

    public function current(): mixed
    {
        return $this->buffer;
    }

    public function next(): void
    {
        $this->fetch();
        $this->i++;
    }

    private function fetch()
    {
        $this->buffer = array();
        for ($i = 0; $i < $this->chunkSize; $i++) {
            if (!$this->it->valid()) return;
            array_push($this->buffer, $this->it->current());
            $this->it->next();
        }
    }
}
