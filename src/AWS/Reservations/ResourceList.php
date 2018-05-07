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
}
