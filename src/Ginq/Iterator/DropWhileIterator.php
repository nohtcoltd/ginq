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

use Ginq\Core\Predicate;
use Ginq\Util\IteratorUtil;

/**
 * DropWhileIterator
 * @package Ginq
 */
class DropWhileIterator implements \Iterator
{
    /**
     * @var \Iterator
     */
    private $it;

    /**
     * @var Predicate
     */
    private $predicate;

    /**
     * @var int
     */
    private $i;

    /**
     * @param array|\Traversable $xs
     * @param Predicate $predicate
     */
    public function __construct($xs, $predicate)
    {
        $this->it = IteratorUtil::iterator($xs);
        $this->predicate = $predicate;
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
        $this->i++;
    }

    public function rewind(): void
    {
        $this->i = 0;
        $this->it->rewind();
        while ($this->it->valid()) {
            if ($this->predicate->predicate(
                $this->it->current(), $this->it->key()
            )) {
                $this->it->next();
            } else {
                break;
            }
        }
    }

    public function valid(): bool
    {
        return $this->it->valid();
    }
}
