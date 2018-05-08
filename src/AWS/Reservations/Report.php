<?php

namespace AWS\Reservations;

class Report
{

    /**
     * @var array
     */
    private $instances;

    /**
     * @var array
     */
    private $reservations;

    /**
     * @var array
     */
    private $groups;

    /**
     * @param array $instances
     * @param array $reservations
     * @param array $groups
     */
    public function __construct(array $instances, array $reservations, array $groups)
    {
        $this->instances = $instances;
        $this->reservations = $reservations;
        $this->groups = $groups;
    }

    public function generate()
    {
        $out = [
            'header' => [
                'Group',
                'Type',
                'AZ',
                'Running Count',
                'Total Reserved'
            ],
            'body' => []
        ];

        $instances = (new InstancesParser($this->groups))->parse($this->instances);
        $instances->sort();

        $reservations = (new ReservationsParser())->parse($this->reservations);
        $reservations->sort();

        $instances->match($reservations);

        foreach ($instances as $instance) {
            if ($instance instanceof Resource) {
                $out['body'][] = [
                    $instance->getName(),
                    $instance->getType(),
                    $instance->getAvailabilityZone(),
                    $instance->getCount(),
                    $instance->getMatchedCount()
                ];
            }
        }

        foreach ($reservations as $reservation) {
            if ($reservation instanceof Reservation) {
                $out['body'][] = [
                    '',
                    $reservation->getType(),
                    $reservation->getAvailabilityZone(),
                    0,
                    $reservation->getCount()
                ];
            }
        }

        return $out;
    }
}
