<?php

namespace AWS\Reservations;

use Traversable;

class ResourceList implements \IteratorAggregate
{

    /** @var \AWS\Reservations\Resource[] */
    private $data = [];

    /**
     * @param \AWS\Reservations\Resource $item
     */
    public function add(\AWS\Reservations\Resource $item)
    {
        if (isset($this->data[$item->getGroupId()])) {
            $this->data[$item->getGroupId()]->attach($item->getCount());
        } else {
            $this->data[$item->getGroupId()] = $item;
        }
    }

    /**
     * {@inheritdoc}
     */
    public function getIterator()
    {
        return new NonEmptyIterator(new \ArrayIterator($this->data));
    }

    public function sort()
    {
        uasort(
            $this->data,
            function (\AWS\Reservations\Resource $left, \AWS\Reservations\Resource $right) {
                if ($left->getCount() == $right->getCount()) {
                    if ($left->getType() == $right->getType()) {
                        return 0;
                    }

                    return $left->getType() < $right->getType() ? -1 : 1;
                }

                return $left->getCount() < $right->getCount() ? 1 : -1;
            }
        );
    }

    /**
     * @param string $callable
     */
    public function filter($callable)
    {
        $this->data = array_filter($this->data, $callable);
    }

    /**
     * @param string $type
     */
    public function filterType($type)
    {
        $this->filter(
            function (\AWS\Reservations\Resource $item) use ($type) {
                return strpos($item->getType(), $type) === 0;
            }
        );
    }

    /**
     * @param bool $covered
     */
    public function filterCovered($covered = false)
    {
        $this->filter(
            function (\AWS\Reservations\Resource $item) use ($covered) {
                return $item->isCovered() === $covered;
            }
        );
    }

    /**
     * @param ResourceList $list
     */
    public function match(self $list)
    {
        foreach ($this->data as $item) {
            foreach ($list as $target) {
                $item->match($target);

                if ($item->isCovered()) {
                    break;
                }
            }
        }
    }
}
