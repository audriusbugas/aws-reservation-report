<?php

namespace AWS\Reservations;

class ReservationsParser implements ListParserInterface
{

    /**
     * {@inheritdoc}
     */
    public function parse(array $data)
    {
        $out = new ResourceList();

        foreach ($data['ReservedInstances'] as $instance) {
            $out->add(Reservation::parse($instance));
        }

        return $out;
    }
}
