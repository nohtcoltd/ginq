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
use Ginq\Core\Selector;
use Ginq\Core\JoinSelector;

/**
 * SelectManyWithJoinIterator
 * @package Ginq
 */
class SelectManyWithJoinIterator implements \Iterator
{
    /**
     * @var Selector
     */
    private $manySelector;

    /**
     * @var JoinSelector
     */
    private $valueJoinSelector;

    /**
     * @var JoinSelector
     */
    private $keyJoinSelector;

    /**
     * @var \Iterator
     */
    private $outer;

    /**
     * @var mixed
     */
    private $outerV;

    /**
     * @var mixed
     */
    private $outerK;

    /**
     * @var \Iterator
     */
    private $inner;

    /**
     * @var mixed
     */
    private $v;

    /**
     * @var mixed
     */
    private $k;

    /**
     * @param array|\Traversable $xs
     * @param Selector $manySelector
     * @param JoinSelector $valueJoinSelector
     * @param JoinSelector $keyJoinSelector
     */
    public function __construct($xs, $manySelector, $valueJoinSelector, $keyJoinSelector)
    {
        $this->outer = IteratorUtil::iterator($xs);
        $this->manySelector = $manySelector;
        $this->valueJoinSelector = $valueJoinSelector;
        $this->keyJoinSelector = $keyJoinSelector;
    }

    public function current(): mixed
    {
        return $this->v;
    }

    public function key(): mixed 
    {
        return $this->k;
    }

    public function next(): void
    {
        $this->inner->next();
        $this->skipEmpty();
        $this->fetchInner();
    }

    public function rewind(): void
    {
        $this->outer->rewind();
        $this->fetchOuter();
        $this->skipEmpty();
        $this->fetchInner();
    }

    public function valid(): bool
    {
        return !is_null($this->inner) && $this->inner->valid();
    }

    protected function fetchOuter()
    {
        if ($this->outer->valid()) {
            $this->outerV = $this->outer->current();
            $this->outerK = $this->outer->key();
            $this->inner = IteratorUtil::iterator(
                $this->manySelector->select(
                    $this->outerV,
                    $this->outerK
                )
            );
            $this->inner->rewind();
        } else {
            $this->inner = null;
        }
    }

    protected function fetchInner()
    {
        if ($this->valid()) {
            $innerV = $this->inner->current();
            $innerK = $this->inner->key();
            $this->v = $this->valueJoinSelector->select(
                $this->outerV, $innerV,
                $this->outerK, $innerK
            );
            $this->k = $this->keyJoinSelector->select(
                $this->outerV, $innerV,
                $this->outerK, $innerK
            );
        }
    }

    private function skipEmpty()
    {
        while (!is_null($this->inner) && !$this->inner->valid()) {
            $this->outer->next();
            $this->fetchOuter();
        }
    }
}

