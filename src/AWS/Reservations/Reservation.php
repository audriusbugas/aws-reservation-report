<?php

namespace AWS\Reservations;

/**
 * Class Reservation
 */
class Reservation extends Resource
{

    /**
     * @var \DateTime
     */
    private $expires;

    /**
     * @return \DateTime
     */
    public function getExpires(): \DateTime
    {
        return $this->expires;
    }

    /**
     * @param string $type
     * @param string $availabilityZone
     * @param int $count
     * @param \DateTime $expires
     */
    public function __construct(string $type, string $availabilityZone, int $count, \DateTime $expires)
    {
        parent::__construct($type, $availabilityZone, $count);
        $this->expires = $expires;
    }

    /**
     * @param array $data
     * @return Reservation
     */
    public static function parse(array $data)
    {
        return new self(
            $data['InstanceType'],
            isset($data['AvailabilityZone']) ? $data['AvailabilityZone'] : '*',
            $data['InstanceCount'],
            new \DateTime($data['End'])
        );
    }

    /**
     * @return string
     */
    public function getGroupId()
    {
        return $this->getAvailabilityZone() . '/' .
            $this->getType() . '/' . $this->getExpires()->format('Y-m-d');
    }
}
