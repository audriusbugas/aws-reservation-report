<?php

namespace AWS\Reservations;

class InstancesParser implements ListParserInterface
{

    /**
     * @var array
     */
    private $tagSearch = [];

    /**
     * @param array $tagSearch
     */
    public function __construct(array $tagSearch)
    {
        $this->tagSearch = $tagSearch;
    }

    /**
     * @param array $data
     * @return ResourceList
     */
    public function parse(array $data)
    {
        $out = new ResourceList();

        foreach ($data['Reservations'] as $reservation) {
            foreach ($reservation['Instances'] as $instance) {
                $out->add(InstanceGroup::parse($instance, $this->tagSearch));
            }
        }

        return $out;
    }
}
