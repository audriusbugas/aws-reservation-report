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
     * @var array
     */
    private $matchedCounts = [];

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
        $out = min($count, $this->count);
        $this->count = max(0, $this->count - $count);

        return $out;
    }

    /**
     * @return string
     */
    abstract public function getGroupId();

    /**
     * @return string
     */
    public function getMatchId()
    {
        return $this->getAvailabilityZone() . '/' .$this->getType();
    }

    /**
     * @return float|int
     */
    public function getMatchedCount()
    {
        return array_sum($this->matchedCounts);
    }

    /**
     * @return bool
     */
    public function isCovered()
    {
        return $this->getCount() == $this->getMatchedCount();
    }

    /**
     * @param self $resource
     */
    public function match(self $resource)
    {
        if (fnmatch($this->getMatchId(), $resource->getMatchId()) ||
            fnmatch($resource->getMatchId(), $this->getMatchId())) {
            if ($this->getMatchedCount() < $this->getCount()) {
                $this->matchedCounts[$resource->getGroupId()] =
                    $resource->detach($this->getCount() - $this->getMatchedCount());
            }
        }
    }

    /**
     * @return array
     */
    public function getMatchedCounts(): array
    {
        return $this->matchedCounts;
    }
}
