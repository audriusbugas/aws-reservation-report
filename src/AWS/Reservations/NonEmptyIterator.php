<?php

namespace AWS\Reservations;

class NonEmptyIterator extends \FilterIterator
{

    /**
     * {@inheritdoc}
     */
    public function accept()
    {
        $item = $this->current();

        if ($item instanceof Resource) {
            return $item->getCount() > 0;
        }

        return false;
    }
}
