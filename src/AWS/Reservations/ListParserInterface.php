<?php

namespace AWS\Reservations;

interface ListParserInterface
{

    /**
     * @param array $data
     * @return ResourceList
     */
    public function parse(array $data);
}
