<?php

namespace AWS\Reservations;

abstract class Resource
{

    /**
     * @var string
     */
    private $type;

    /**
     * @var string
     */
    private $availabilityZone;

    /**
     * @var integer
     */
    private $count;

    /**
     * @param string $type
     * @param string $availabilityZone
     * @param int $count
     */
    public function __construct(string $type, string $availabilityZone, int $count)
    {
        $this->type = $type;
        $this->availabilityZone = $availabilityZone;
        $this->count = $count;
    }

    /**
     * @return string
     */
    public function getType(): string
    {
        return $this->type;
    }

    /**
     * @return string
     */
    public function getAvailabilityZone(): string
    {
        return $this->availabilityZone;
    }

    /**
     * @return int
     */
    public function getCount(): int
    {
        return $this->count;
    }

    /**
     * @param int $count
     */
    public function attach($count = 1)
    {
        $this->count += $count;
    }

    /**
     * @param int $count
     * @return int
     */
    public function detach($count = 1)
    {
        $previous = $this->count;

        $this->count = max(0, $this->count - $count);

        return $previous >= $count ? $count : $count-$previous;
    }

    /**
     * @return string
     */
    abstract public function getGroupId();
}
